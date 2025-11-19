<?php

namespace App\Http\Controllers;

use App\Models\Diary;
use Illuminate\Http\Request;

class DiaryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $month = $request->query('month', now()->format('Y-m'));

        $diaries = Diary::whereYear('date', substr($month, 0, 4))
            ->whereMonth('date', substr($month, 5, 2))
            ->orderBy('date', 'desc')
            ->get();

        // 月選択用のオプションを生成（過去12ヶ月）
        $months = [];
        for ($i = 0; $i < 12; $i++) {
            $date = now()->subMonths($i);
            $months[$date->format('Y-m')] = $date->format('Y年n月');
        }

        return view('diaries.index', compact('diaries', 'months', 'month'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('diaries.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'content' => 'required|string',
        ]);

        Diary::create($validated);

        return redirect()->route('diaries.index')->with('success', '日記を保存しました');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Diary $diary)
    {
        return view('diaries.edit', compact('diary'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Diary $diary)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'content' => 'required|string',
        ]);

        $diary->update($validated);

        return redirect()->route('diaries.index')->with('success', '日記を更新しました');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Diary $diary)
    {
        $diary->delete();

        return redirect()->route('diaries.index')->with('success', '日記を削除しました');
    }
}
