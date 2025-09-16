<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    /** @use HasFactory<\Database\Factories\TicketFactory> */
    use HasFactory, SoftDeletes;

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

    public function users(): BelongsToMany {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function owner(): BelongsToMany {
        return $this->belongsToMany(User::class)->withTimestamps()->wherePivot('owner', true);
    }

    public function notOwner(): BelongsToMany {
        return $this->belongsToMany(User::class)->withTimestamps()->wherePivot('owner', false);
    }

    public function comments(): HasMany {
        return $this->hasMany(Comment::class);
    }
}
