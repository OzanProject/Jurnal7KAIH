<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HabitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $habits = \App\Models\Habit::all();
        return view('backend.superadmin.habits.index', compact('habits'));
    }

    public function create()
    {
        return view('backend.superadmin.habits.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        \App\Models\Habit::create($request->all());

        return redirect()->route('habits.index')->with('success', 'Master kebiasaan berhasil ditambahkan.');
    }

    public function edit(\App\Models\Habit $habit)
    {
        return view('backend.superadmin.habits.edit', compact('habit'));
    }

    public function update(Request $request, \App\Models\Habit $habit)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $habit->update($request->all());

        return redirect()->route('habits.index')->with('success', 'Master kebiasaan berhasil diperbarui.');
    }

    public function destroy(\App\Models\Habit $habit)
    {
        $habit->delete();
        return redirect()->route('habits.index')->with('success', 'Master kebiasaan berhasil dihapus.');
    }
}
