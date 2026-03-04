<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Weather extends Model
{
    /** @use HasFactory<\Database\Factories\WeatherFactory> */
    use HasFactory;

    protected $fillable = ['type', 'location_id', 'temp', 'logged_at'];

    public function location(): BelongsTo {
        return $this -> belongsTo(Location::class);
    }

    public function warnings(): BelongsToMany {
        return $this -> belongsToMany(Warning::class);
    }
}
