<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'text',
        'attachment_hash_name',
        'attachment_original_name',
        'comments_enabled',
        'published',
        'user_id',
    ];

    public function author() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function categories() {
        return $this->belongsToMany(Category::class);
    }

    public function comments() {
        return $this->hasMany(Comment::class, 'post_id', 'id');
    }
}
