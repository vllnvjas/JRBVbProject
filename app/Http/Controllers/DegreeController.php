<?php

namespace App\Http\Controllers;

use App\Models\Degree;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DegreeController extends Controller
{
    public function index()
    {
        $degrees = Degree::orderBy('name')->paginate(10);

        return view('degrees', compact('degrees'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:255', 'unique:degrees,name'],
        ], [
            'name.min' => 'Degree name must be at least 2 letters.',
        ]);

        $degree = Degree::create($validated);

        Log::info('Degree created', [
            'degree_id' => $degree->id,
            'name' => $degree->name,
        ]);

        return redirect()->route('degrees.index')->with('success', 'Degree added successfully');
    }

    public function edit(Degree $degree)
    {
        return view('editDegree', compact('degree'));
    }

    public function update(Request $request, Degree $degree): RedirectResponse
    {
        $before = $degree->only(['name']);

        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:255', 'unique:degrees,name,' . $degree->id],
        ], [
            'name.min' => 'Degree name must be at least 2 letters.',
        ]);

        $degree->update($validated);

        $after = $degree->fresh()->only(['name']);
        $changedFields = [];

        foreach ($after as $field => $value) {
            if (($before[$field] ?? null) !== $value) {
                $changedFields[$field] = [
                    'from' => $before[$field] ?? null,
                    'to' => $value,
                ];
            }
        }

        Log::info('Degree updated', [
            'degree_id' => $degree->id,
            'changes' => $changedFields,
            'before' => $before,
            'after' => $after,
        ]);

        return redirect()->route('degrees.index')->with('success', 'Degree updated successfully');
    }

    public function destroy(Degree $degree): RedirectResponse
    {
        if ($degree->students()->exists()) {
            Log::warning('Degree delete blocked', [
                'degree_id' => $degree->id,
                'name' => $degree->name,
                'assigned_students_count' => $degree->students()->count(),
            ]);

            return redirect()
                ->route('degrees.index')
                ->with('error', 'This degree cannot be deleted because students are assigned to it.');
        }

        Log::info('Degree deleted', [
            'degree_id' => $degree->id,
            'name' => $degree->name,
        ]);

        $degree->delete();

        return redirect()->route('degrees.index')->with('success', 'Degree deleted successfully');
    }
}
