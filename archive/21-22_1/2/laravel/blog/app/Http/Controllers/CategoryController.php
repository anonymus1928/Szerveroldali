<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function categoryIndex($categoryId, $postId = null)
    {
        //dd($categoryId, $postId);
        return view('category', compact('categoryId', 'postId'));
    }

    public function newCategoryIndex()
    {
        return view('new-category');
    }
}
