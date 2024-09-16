<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'priority',
        'done',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'done' => 'boolean',
    ];

    public function comments() : HasMany {
        return $this->hasMany(Comment::class);
    }

    public function users() : BelongsToMany {
        return $this->belongsToMany(User::class);
    }

    public function owner() : BelongsToMany {
        return  $this->belongsToMany(User::class)->wherePivot('owner', 1);
    }

    public function notOwner() : BelongsToMany {
        return  $this->belongsToMany(User::class)->wherePivot('owner', 0);
    }
}
