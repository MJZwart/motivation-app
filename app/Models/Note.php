<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'note_list_id',
        'user_id',
        'note',
        'description',
    ];

    public function noteList()
    {
        return $this->belongsTo('App\Models\NoteList');
    }
}
