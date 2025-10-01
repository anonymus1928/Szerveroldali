<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    /** @use HasFactory<\Database\Factories\TicketFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'done',
        'priority',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'done' => 'boolean',
        ];
    }

    /**
     * Get the ticket's comments.
     */
    public function comments(): HasMany {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the ticket's users.
     */
    public function users(): BelongsToMany {
        return $this->belongsToMany(User::class);
    }

    /**
     * Get the ticket's owner.
     */
    public function owner(): BelongsToMany {
        return $this->belongsToMany(User::class)->wherePivot('owner', 1);
    }

    /**
     * Get the ticket's normal users.
     */
    public function notOwners(): BelongsToMany {
        return $this->belongsToMany(User::class)->wherePivot('owner', 0);
    }
}
