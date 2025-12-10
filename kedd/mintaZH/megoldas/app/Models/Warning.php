<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Warning extends Model
{
    /** @use HasFactory<\Database\Factories\WarningFactory> */
    use HasFactory;

    protected $fillable = ['level', 'message'];

    public function weather(): BelongsToMany{
        return $this -> belongsToMany(Weather::class);
    }
}
