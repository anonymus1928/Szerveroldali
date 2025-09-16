<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    /** @use HasFactory<\Database\Factories\CommentFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'text',
        'filename',
        'filename_hash',
        'user_id',
        'ticket_id',
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function ticket(): BelongsTo {
        return $this->belongsTo(Ticket::class);
    }
}
