<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Note extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'note_list_id',
        'user_id',
        'note',
        'description',
        'completed',
    ];

    public function noteList()
    {
        return $this->belongsTo('App\Models\NoteList');
    }
}
