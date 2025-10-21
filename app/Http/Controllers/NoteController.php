<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\NoteRequest;  // added

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $notesQuery = auth()->user()->notes()
                     ->where('is_archived', false)
                     ->where('is_deleted', false);

        // user type sesuatu di search box
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            
            // filterkan query
            $notesQuery->where(function ($query) use ($searchTerm) {
                $query->where('title', 'like', "%{$searchTerm}%")      // search title
                    ->orWhere('content', 'like', "%{$searchTerm}%"); // search content
            });
        }
        
        // list of notes atau filter search result
        $notes = $notesQuery->with('category')->latest()->get();

        $categories = \App\Models\Category::all();
        return view('notes.index', compact('notes', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('notes.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(NoteRequest $request)
    {
        $validatedData = $request->validated();
        $validatedData['users_id'] = auth()->id();
        
        Note::create($validatedData); 

        return redirect()->route('notes.index')->with('success', 'Catatan berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Note $note)
    {
        // memastikan user login dulu, untuk acess note
        if ($note->users_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('notes.show', compact('note'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Note $note)
    {
        if ($note->users_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $categories = Category::all();
        return view('notes.edit', compact('note', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(NoteRequest $request, Note $note)
    {
        if ($note->users_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        
        // update data dengan data tervalidated
        $note->update($request->validated());

        return redirect()->route('notes.index')->with('success', 'Catatan berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Note $note)
    {
        if ($note->users_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        
        // update note ke Trash
        $note->update(['is_deleted' => true]); 
        
        return redirect()->route('notes.index')->with('success', 'Catatan dipindahkan ke TRASH.');
    }

    public function archive(Note $note)
    {
        // authorization: making sure bahwa user memiliki note
        if ($note->users_id !== auth()->id()) {
            abort(403);
        }

        // update status note menjadi archived
        $note->update(['is_archived' => true]);

        return redirect()->route('notes.index')->with('success', 'Note sukses archived');
    }
}
