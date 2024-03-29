<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'priority',
        'done',
    ];

    public function comments(): HasMany {
        return $this->hasMany(Comment::class);
    }

    public function users(): BelongsToMany {
        return $this->belongsToMany(User::class)->withPivot('is_author', 'is_responsible');
    }

    public function author(): BelongsToMany {
        return $this->belongsToMany(User::class)->withPivot('is_author', 'is_responsible')->wherePivot('is_author', 1);
    }

    public function notAuthor(): BelongsToMany {
        return $this->belongsToMany(User::class)->withPivot('is_author', 'is_responsible')->wherePivot('is_author', 0);
    }
}
