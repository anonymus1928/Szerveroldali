<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'filename',
        'filename_hash',
    ];

    public function comments() {
        return $this->hasMany(Comment::class);
    }
}
