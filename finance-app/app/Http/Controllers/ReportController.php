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
        $totalIncome   = Income::sum('amount');
        $totalExpenses = Expense::sum('amount');
        $totalBalance  = $totalIncome - $totalExpenses;

        $expenseByCategory = Expense::selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->orderByDesc('total')
            ->get();

        return view('reports.index', compact('totalIncome', 'totalExpenses', 'totalBalance', 'expenseByCategory'));
    }

    public function askAI(Request $request)
    {
        $request->validate(['question' => 'required|string|max:1000']);

        $apiKey = config('services.gemini.api_key');
        if (!$apiKey) {
            return response()->json([
                'error' => 'Gemini API key tidak dikonfigurasi. Tambahkan GEMINI_API_KEY ke file .env'
            ], 400);
        }

        // ====== LOAD ALL DATA (PHP collections, no raw SQL - SQLite compatible) ======
        $allMembers  = Member::all();
        $allIncomes  = Income::with('member')->orderBy('date')->get();
        $allExpenses = Expense::with('member')->orderBy('date')->get();

        // Totals
        $totalIncome   = $allIncomes->sum('amount');
        $totalExpenses = $allExpenses->sum('amount');
        $totalBalance  = $totalIncome - $totalExpenses;
        $totalMembers  = $allMembers->count();
        $activeMembers = $allMembers->where('status', 'active')->count();

        // Member lines with per-member income & expense totals
        $memberLines = $allMembers->isEmpty()
            ? '  (Belum ada anggota)'
            : $allMembers->map(function ($m) use ($allIncomes, $allExpenses) {
                $incTotal = $allIncomes->where('member_id', $m->id)->sum('amount');
                $expTotal = $allExpenses->where('member_id', $m->id)->sum('amount');
                return "  - {$m->name} | Role: " . ($m->role ?? 'N/A') .
                    " | Status: {$m->status}" .
                    " | Income: Rp" . number_format($incTotal, 0, ',', '.') .
                    " | Pengeluaran: Rp" . number_format($expTotal, 0, ',', '.');
            })->join("\n");

        // Income lines
        $incomeLines = $allIncomes->isEmpty()
            ? '  (Belum ada data pemasukan)'
            : $allIncomes->map(fn($i) =>
                "  - [{$i->date->format('d M Y')}] {$i->source}" .
                " | Rp" . number_format($i->amount, 0, ',', '.') .
                " | Anggota: " . ($i->member?->name ?? 'Tidak ada') .
                ($i->description ? " | Ket: {$i->description}" : '')
            )->join("\n");

        // Expense lines
        $expenseLines = $allExpenses->isEmpty()
            ? '  (Belum ada data pengeluaran)'
            : $allExpenses->map(fn($e) =>
                "  - [{$e->date->format('d M Y')}] [{$e->category}]" .
                " Rp" . number_format($e->amount, 0, ',', '.') .
                " | PIC: " . ($e->member?->name ?? '-') .
                " | No: {$e->invoice_number}" .
                ($e->description ? " | Ket: {$e->description}" : '')
            )->join("\n");

        // Expense by category (PHP groupBy)
        $categoryLines = $allExpenses->isEmpty()
            ? '  (Tidak ada data)'
            : $allExpenses->groupBy('category')->map(fn($g, $cat) =>
                "  - {$cat}: Rp" . number_format($g->sum('amount'), 0, ',', '.') . " ({$g->count()} transaksi)"
            )->join("\n");

        // Monthly breakdown (PHP, no SQL strftime needed)
        $allTx = collect()
            ->merge($allIncomes->map(fn($i) => ['bulan' => $i->date->format('Y-m'), 'type' => 'income',  'amount' => $i->amount]))
            ->merge($allExpenses->map(fn($e) => ['bulan' => $e->date->format('Y-m'), 'type' => 'expense', 'amount' => $e->amount]));

        $months = $allTx->pluck('bulan')->unique()->sort()->values();
        $monthlyLines = $months->isEmpty()
            ? '  (Tidak ada data bulanan)'
            : $months->map(function ($bulan) use ($allTx) {
                $inc = $allTx->where('bulan', $bulan)->where('type', 'income')->sum('amount');
                $exp = $allTx->where('bulan', $bulan)->where('type', 'expense')->sum('amount');
                return "  - {$bulan}: Pemasukan Rp" . number_format($inc, 0, ',', '.') .
                    " | Pengeluaran Rp" . number_format($exp, 0, ',', '.') .
                    " | Saldo Rp" . number_format($inc - $exp, 0, ',', '.');
            })->join("\n");

        // ====== BUILD CONTEXT STRING ======
        $context =
            "=== DATA KEUANGAN ORGANISASI (Real-time dari database) ===\n\n" .
            "RINGKASAN:\n" .
            "  - Total Pemasukan : Rp" . number_format($totalIncome, 0, ',', '.') . "\n" .
            "  - Total Pengeluaran: Rp" . number_format($totalExpenses, 0, ',', '.') . "\n" .
            "  - Saldo Bersih    : Rp" . number_format($totalBalance, 0, ',', '.') . "\n" .
            "  - Jumlah Anggota  : {$totalMembers} total, {$activeMembers} aktif\n\n" .
            "ANGGOTA:\n{$memberLines}\n\n" .
            "CATATAN PEMASUKAN ({$allIncomes->count()} transaksi):\n{$incomeLines}\n\n" .
            "CATATAN PENGELUARAN ({$allExpenses->count()} transaksi):\n{$expenseLines}\n\n" .
            "PENGELUARAN PER KATEGORI:\n{$categoryLines}\n\n" .
            "RINGKASAN BULANAN:\n{$monthlyLines}";

        // ====== CALL GEMINI API ======
        try {
            $response = Http::timeout(30)->post(
                "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}",
                [
                    'contents' => [[
                        'parts' => [[
                            'text' =>
                                "Kamu adalah asisten keuangan yang cerdas untuk sebuah organisasi. " .
                                "Kamu memiliki akses ke seluruh data keuangan organisasi di bawah ini.\n\n" .
                                $context .
                                "\n\n=== PERTANYAAN ===\n" . $request->question .
                                "\n\n=== INSTRUKSI ===\n" .
                                "- Jawab dengan akurat berdasarkan data di atas.\n" .
                                "- Jika data tidak ada atau kosong, katakan dengan jelas.\n" .
                                "- Format angka dalam Rupiah (contoh: Rp 1.500.000).\n" .
                                "- Jawab dalam Bahasa Indonesia yang natural dan informatif.\n" .
                                "- Jangan mengarang data yang tidak ada."
                        ]]
                    ]],
                    'generationConfig' => [
                        'temperature'     => 0.2,
                        'maxOutputTokens' => 1024,
                    ]
                ]
            );

            if (!$response->successful()) {
                return response()->json([
                    'error' => 'Gemini API error ' . $response->status() . ': ' . $response->body()
                ], 500);
            }

            $result = $response->json();
            $text   = $result['candidates'][0]['content']['parts'][0]['text']
                ?? 'Tidak dapat mendapatkan respons dari AI.';

            return response()->json(['answer' => $text]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menghubungi AI: ' . $e->getMessage()], 500);
        }
    }
}
