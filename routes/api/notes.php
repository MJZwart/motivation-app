<?php

use App\Http\Controllers\NotesController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Notes API Routes
|--------------------------------------------------------------------------
|
| All API calls that have the valid-auth middleware. Only logged in users can
| reach these api calls. These calls are all about notes and note lists.
|
*/

Route::group(['middleware' => ['valid-auth', 'not-guest']], function () {
    Route::get('/', [NotesController::class, 'index']);
    Route::post('/', [NotesController::class, 'storeNote']);
    Route::put('/note/{note}', [NotesController::class, 'updateNote']);
    Route::put('/complete/{note}', [NotesController::class, 'toggleComplete']);

    Route::post('/note-list', [NotesController::class, 'storeNoteList']);
    Route::put('/note-list/{noteList}', [NotesController::class, 'updateNoteList']);
    Route::put('/complete-list/{noteList}', [NotesController::class, 'toggleListComplete']);
});
