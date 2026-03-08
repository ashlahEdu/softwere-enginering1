<?php

namespace App\Http\Controllers;

use App\Models\Income;
use App\Models\Expense;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ReportController extends Controller
{
    public function index()
    {
        $totalIncome = Income::sum('amount');
        $totalExpenses = Expense::sum('amount');
        $totalBalance = $totalIncome - $totalExpenses;

        $expenseByCategory = Expense::selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->orderByDesc('total')
            ->get();

        return view('reports.index', compact('totalIncome', 'totalExpenses', 'totalBalance', 'expenseByCategory'));
    }

    public function askAI(Request $request)
    {
        $request->validate(['question' => 'required|string']);
        
        $apiKey = config('services.gemini.api_key');
        if (!$apiKey) {
            return response()->json(['error' => 'Gemini API key not configured. Add GEMINI_API_KEY to .env file.'], 400);
        }

        // Build context from database
        $totalIncome = Income::sum('amount');
        $totalExpenses = Expense::sum('amount');
        $totalBalance = $totalIncome - $totalExpenses;
        $memberCount = Member::count();
        $activeMemberCount = Member::active()->count();
        
        $incomes = Income::with('member')->get()->map(fn($i) => 
            "- {$i->date->format('Y-m-d')}: {$i->source} - Rp" . number_format($i->amount, 0, ',', '.')
        )->join("\n");

        $expenses = Expense::with('member')->get()->map(fn($e) => 
            "- {$e->date->format('Y-m-d')}: {$e->category} - Rp" . number_format($e->amount, 0, ',', '.') . " ({$e->description})"
        )->join("\n");

        $expenseByCategory = Expense::selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->get()
            ->map(fn($e) => "- {$e->category}: Rp" . number_format($e->total, 0, ',', '.'))
            ->join("\n");

        $context = "Current Financial Data:
- Total Income: Rp" . number_format($totalIncome, 0, ',', '.') . "
- Total Expenses: Rp" . number_format($totalExpenses, 0, ',', '.') . "
- Net Balance: Rp" . number_format($totalBalance, 0, ',', '.') . "
- Total Members: {$memberCount} ({$activeMemberCount} active)

Income Records:
{$incomes}

Expense Records:
{$expenses}

Expense by Category:
{$expenseByCategory}";

        try {
            $response = Http::post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}", [
                'contents' => [[
                    'parts' => [[
                        'text' => "You are a financial assistant. Answer ONLY based on this data:\n\n{$context}\n\nQuestion: {$request->question}\n\nRules: Be factual, no assumptions, format currency properly."
                    ]]
                ]]
            ]);

            $result = $response->json();
            $text = $result['candidates'][0]['content']['parts'][0]['text'] ?? 'Unable to get response';

            return response()->json(['answer' => $text]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'AI query failed: ' . $e->getMessage()], 500);
        }
    }
}
