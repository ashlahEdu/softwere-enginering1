<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Income;
use App\Models\Expense;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalIncome = Income::sum('amount');
        $totalExpenses = Expense::sum('amount');
        $totalBalance = $totalIncome - $totalExpenses;

        
        
        $recentTransactions = collect()
            ->merge(Income::latest()->take(5)->get()->map(fn($i) => [
                'type' => 'income',
                'date' => $i->date,
                'source' => $i->source,
                'amount' => $i->amount,
                'created_at' => $i->created_at,
            ]))
            ->merge(Expense::latest()->take(5)->get()->map(fn($e) => [
                'type' => 'expense',
                'date' => $e->date,
                'source' => $e->category,
                'amount' => $e->amount,
                'created_at' => $e->created_at,
            ]))
            ->sortByDesc('created_at')
            ->take(5)
            ->values();

        return view('dashboard', compact('totalIncome', 'totalExpenses', 'totalBalance', 'recentTransactions'));
    }
}
