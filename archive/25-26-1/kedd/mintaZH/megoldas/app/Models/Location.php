<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;

class Location extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\LocationFactory> */
    use HasFactory, HasApiTokens;

    protected $fillable = ['name', 'email', 'password', 'lat', 'lon', 'public'];

    protected $hidden = ['password'];

    public function weather(): HasMany
    {
        return $this->hasMany(Weather::class);
    }

    protected function casts(): array
    {
        return [
            'public' => 'boolean',
            'logged_at' => 'datetime'
        ];
    }
}
