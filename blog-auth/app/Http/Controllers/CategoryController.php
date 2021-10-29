<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function categoryIndex($categoryId, $postId = null) {
        //dd($categoryId, $postId);
        return view('category', compact('categoryId', 'postId'));
    }

    public function newCategoryIndex() {
        return view('new-category');
    }

    public function storeNewCategory(Request $request) {
        $data = $request->validate([
            'name' => 'required|string|min:2|max:48',
            'color' => 'string|in:primary,secondary,success,danger,warning,info,light,dark',
        ]);

        $category = new Category;
        $category->name = $request->name;
        $category->color = $request->color;
        $category->save();

        $request->session()->flash('category-added', $data['name']);
        return redirect()->route('new-category');
    }
}
