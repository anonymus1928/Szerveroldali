<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index() {
        $categories = Category::all();
        $posts = Post::paginate(9);
        $users_count = User::count();
        $posts_count = $posts->count();
        return view('home', compact('categories', 'posts', 'users_count', 'posts_count'));
    }
}
