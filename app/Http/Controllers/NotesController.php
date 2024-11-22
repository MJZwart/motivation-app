<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNoteListRequest;
use App\Http\Requests\StoreNoteRequest;
use App\Models\Note;
use App\Models\NoteList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotesController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return ['notes' => $user->notes, 'noteLists' => $user->noteLists];
    }

    public function storeNote(StoreNoteRequest $request)
    {
        $validated = $request->validated();

        Note::create($validated);
    }

    public function updateNote(StoreNoteRequest $request, Note $note)
    {
        $validated = $request->validated();

        $note->update($validated);
    }

    public function toggleComplete(Note $note)
    {
        $note->update(['complete' => !$note->complete]);
    }

    public function storeNoteList(StoreNoteListRequest $request)
    {
        $validated = $request->validated();

        NoteList::create($validated);
    }

    public function updateNoteList(StoreNoteListRequest $request, NoteList $noteList)
    {
        $validated = $request->validated();

        $noteList->update($validated);
    }
}
