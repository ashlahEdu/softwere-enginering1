<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->get('category', 'all');
        
        $expenses = Expense::with('member')
            ->when($category !== 'all', fn($q) => $q->where('category', $category))
            ->latest('date')
            ->get();
            
        $members = Member::active()->get();
        $categories = ['Logistics', 'Events', 'Maintenance', 'Honorarium', 'Utilities', 'Other'];

        return view('expenses.index', compact('expenses', 'members', 'categories', 'category'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'category' => 'required|string|max:255',
            'amount' => 'required|numeric|min:1',
            'description' => 'nullable|string',
            'member_id' => 'required|exists:members,id',
            'proof_image' => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('proof_image')) {
            $validated['proof_image'] = $request->file('proof_image')->store('proofs', 'public');
        }

        $expense = Expense::create($validated);

        // Create invoice automatically
        Invoice::create([
            'invoice_number' => $expense->invoice_number,
            'expense_id' => $expense->id,
        ]);

        return redirect()->route('expenses.index')->with('success', 'Expense recorded successfully!');
    }

    public function update(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'category' => 'required|string|max:255',
            'amount' => 'required|numeric|min:1',
            'description' => 'nullable|string',
            'member_id' => 'required|exists:members,id',
        ]);

        $expense->update($validated);

        return redirect()->route('expenses.index')->with('success', 'Expense updated successfully!');
    }

    public function destroy(Expense $expense)
    {
        if ($expense->proof_image) {
            Storage::disk('public')->delete($expense->proof_image);
        }
        $expense->delete();
        return redirect()->route('expenses.index')->with('success', 'Expense deleted!');
    }

    public function analyzeImage(Request $request)
    {
        $request->validate(['image' => 'required|image|max:5120']);
        
        $apiKey = config('services.gemini.api_key');
        if (!$apiKey) {
            return response()->json(['error' => 'Gemini API key not configured'], 400);
        }

        $image = $request->file('image');
        $base64 = base64_encode(file_get_contents($image->getRealPath()));
        $mimeType = $image->getMimeType();

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}", [
                'contents' => [[
                    'parts' => [
                        ['inline_data' => ['mime_type' => $mimeType, 'data' => $base64]],
                        ['text' => 'Analyze this receipt/invoice image. Extract: 1) What was purchased 2) Visible amount (DO NOT guess if unclear) 3) Date if visible 4) Suggest category from: Logistics, Events, Maintenance, Honorarium, Utilities, Other. Be factual. Format: Description: [text], Visible Amount: [amount or "Not visible"], Category Suggestion: [category]']
                    ]
                ]]
            ]);

            $result = $response->json();
            $text = $result['candidates'][0]['content']['parts'][0]['text'] ?? 'Unable to analyze image';

            return response()->json(['suggestion' => $text]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'AI analysis failed: ' . $e->getMessage()], 500);
        }
    }
}
