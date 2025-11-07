<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'notes',
        'type',
        'amount',
        'occurred_at',
        'cover_path',
        'category',
    ];

    protected $dates = ['occurred_at'];

    // relation to user
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    // helper to get cover url
    public function getCoverUrlAttribute()
    {
        return $this->cover_path ? Storage::url($this->cover_path) : null;
    }
}
