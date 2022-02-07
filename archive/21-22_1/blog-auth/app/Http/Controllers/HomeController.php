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
        $posts = Post::orderByDesc('created_at')->paginate(9); // a helyes megjelenéshez módosítani kell az App\Providers\AppServiceProvider class-t!
        $users_count = User::count();
        $posts_count = $posts->count();
        return view('home', compact('categories', 'posts', 'users_count', 'posts_count'));
    }
}
