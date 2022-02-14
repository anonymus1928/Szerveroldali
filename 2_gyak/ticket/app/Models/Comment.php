<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'text',
        'filename',
        'filename_hash',
    ];

    public function ticket() {
        return $this->belongsTo(Ticket::class);
    }
}
