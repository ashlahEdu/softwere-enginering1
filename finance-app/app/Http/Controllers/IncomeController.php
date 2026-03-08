<?php

namespace App\Http\Controllers;

use App\Models\Income;
use App\Models\Member;
use Illuminate\Http\Request;

class IncomeController extends Controller
{
    public function index()
    {
        $incomes = Income::with('member')->latest('date')->get();
        $members = Member::active()->get();

        return view('income.index', compact('incomes', 'members'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'amount' => 'required|numeric|min:1',
            'source' => 'required|string|max:255',
            'description' => 'nullable|string',
            'member_id' => 'nullable|exists:members,id',
        ]);
             
        Income::create($validated);

        return redirect()->route('income.index')->with('success', 'Income recorded successfully!');
    }

    public function update(Request $request, Income $income)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'amount' => 'required|numeric|min:1',
            'source' => 'required|string|max:255',
            'description' => 'nullable|string',
            'member_id' => 'nullable|exists:members,id',
        ]);

        $income->update($validated);

        return redirect()->route('income.index')->with('success', 'Income updated successfully!');
    }

    public function destroy(Income $income)
    {
        $income->delete();
        return redirect()->route('income.index')->with('success', 'Income deleted!');
    }
}
