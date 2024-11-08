<?php

namespace App\Http\Controllers;

use App\Models\Challenge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChallengeController extends Controller
{
    // Front Office Methods
    public function index()
    {
        // Fetch ongoing and upcoming challenges
        $challenges = Challenge::where('end_date', '>=', now())->get();
        return view('challenges.index', compact('challenges'));
    }

    public function participate($id)
    {
        $challenge = Challenge::findOrFail($id);
        // Logic to add the user to participants
        $challenge->increment('participants_count');
        return redirect()->route('challenges.index')->with('success', 'You are now participating in the challenge.');
    }

    public function leave($id)
    {
        $challenge = Challenge::findOrFail($id);
        // Logic to remove the user from participants
        $challenge->decrement('participants_count');
        return redirect()->route('challenges.index')->with('success', 'You have left the challenge.');
    }

    public function suggest(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        Challenge::create([
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('challenges.index')->with('success', 'Challenge suggested successfully.');
    }

    // Admin Methods
    public function adminIndex()
    {
        // Fetch all challenges
        $challenges = Challenge::all();
        return view('admin.challenges.index', compact('challenges'));
    }

    public function create()
    {
        return view('admin.challenges.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        Challenge::create([
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'created_by' => Auth::id(),
            'participants_count' => 0,
            'status' => 'Ongoing',
        ]);

        return redirect()->route('admin.challenges.index')->with('success', 'Challenge created successfully.');
    }

    public function edit($id)
    {
        $challenge = Challenge::findOrFail($id);
        return view('admin.challenges.edit', compact('challenge'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $challenge = Challenge::findOrFail($id);
        $challenge->update($request->all());

        return redirect()->route('admin.challenges.index')->with('success', 'Challenge updated successfully.');
    }

    public function destroy($id)
    {
        $challenge = Challenge::findOrFail($id);
        $challenge->delete();

        return redirect()->route('admin.challenges.index')->with('success', 'Challenge deleted successfully.');
    }
}