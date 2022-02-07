<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function postIndex($postId) {
        $post = Post::findOrFail($postId);
        // $post = Post::find($postId);
        // if(is_null($post)) {
        //     return abort(404);
        // }

        if(isset($post->attachment_hash_name)) {
            return view('post', [
                'post' => $post,
                'url' => Storage::url($post->attachment_hash_name),
            ]);
        }

        return view('post', compact('post'));
    }

    public function newPostIndex() {
        $categories = Category::all();
        return view('new-post', ['categories' => $categories]);
    }

    public function storeNewPost(Request $request) {
        // dd($request);
        $data = $request->validate([
            'title' => 'required|min:2|max:48',
            'text' => 'required|max:2000',
            'categories' => 'nullable',
            // 'categories.*' => 'integer|distinct|exists:categories,id',
            'disable-comments' => 'nullable|boolean',
            'hide-post' => 'nullable|boolean',
            'attachment' => 'nullable|file|mimes:txt,pdf,jpg,png|max:4096',
        ],
        [
            'required' => 'A mező megadása kötelező.',
            'min' => 'A(z) :attribute mező legalább :min karakterből álljon.',
            'max' => 'A(z) :attribute mező legfeljebb :max karakterből álljon.',
            'attachment.file' => 'A csatolmány egy fájl kell, hogy legyen.',
            'attachment.mimes' => 'A csatolmány kiterjesztése csak :values lehet.',
            'attachment.max' => 'A csatolmány mérete lefgeljebb :max kilobájt lehet.',
        ]);

        $post = User::findOrFail(Auth::id())->posts()->create($data);

        if($request->hasFile('attachment')) {
            $filename = $request->file('attachment')->getClientOriginalName();
            $path = $request->file('attachment')->store('public');

            // dd($path, $filename);
            $post->attachment_hash_name = $path;
            $post->attachment_original_name = $filename;
            $post->save();
        }


        // $post = new Post($data);
        // $author = User::findOrFail(Auth::id());
        // $post->author()->associate($author);
        // $post->save();

        $request->session()->flash('post-added', $data['title']);
        return redirect()->route('new-post');
    }

    public function editPostIndex($postId) {
        $post = Post::findOrFail($postId);
        if($post->author->id === Auth::id() || Auth::user()->is_admin) {
            $categories = Category::all();

            $url = null;
            if(isset($post->attachment_hash_name)) {
                $url = Storage::url($post->attachment_hash_name);
            }
            return view('new-post', ['post' => $post, 'categories' => $categories, 'url' => $url]);
        } else {
            abort(401);
        }
    }

    public function storeEditedPost(Request $request, $postId) {
        $data = $request->validate([
            'title' => 'required|min:2|max:48',
            'text' => 'required|max:2000',
            'categories' => 'nullable',
            // 'categories.*' => 'integer|distinct|exists:categories,id',
            'disable-comments' => 'nullable|boolean',
            'hide-post' => 'nullable|boolean',
            'attachment' => 'nullable|file|mimes:txt,pdf,jpg,png|max:4096',
        ],
        [
            'required' => 'A mező megadása kötelező.',
            'min' => 'A(z) :attribute mező legalább :min karakterből álljon.',
            'max' => 'A(z) :attribute mező legfeljebb :max karakterből álljon.',
            'attachment.file' => 'A csatolmány egy fájl kell, hogy legyen.',
            'attachment.mimes' => 'A csatolmány kiterjesztése csak :values lehet.',
            'attachment.max' => 'A csatolmány mérete lefgeljebb :max kilobájt lehet.',
        ]);

        $post = Post::findOrFail($postId);

        if($post->author->id !== Auth::id() && !Auth::user()->is_admin) {
            abort(401);
        }

        $post->update($data);

        $post->hide_post = isset($data['hide-post']) ? $data['hide-post'] : false;
        $post->disabled_comments = isset($data['disable-comments']) ? $data['disable-comments'] : false;
        
        $post->categories()->sync(isset($data['categories']) ? $data['categories'] : []);

        if($request->hasFile('attachment')) {
            if(isset($post->attachment_hash_name)) {
                Storage::delete($post->attachment_hash_name);
            }

            $filename = $request->file('attachment')->getClientOriginalName();
            $path = $request->file('attachment')->store('public');

            // dd($path, $filename);
            $post->attachment_hash_name = $path;
            $post->attachment_original_name = $filename;
        }
        
        $post->save();

        $request->session()->flash('post-edited', $data['title']);
        return redirect()->route('edit-post', ['postId' => $post->id]);
    }

    public function deletePost($postId) {
        $post = Post::findOrFail($postId);
        if($post->author->id !== Auth::id() && !Auth::user()->is_admin) {
            abort(401);
        }

        $post->delete();
        return redirect()->route('home');
    }

    public function deleteFile($postId) {
        $post = Post::findOrFail($postId);

        if($post->author->id !== Auth::id() && !Auth::user()->is_admin) {
            abort(401);
        }

        Storage::delete($post->attachment_hash_name);

        $post->attachment_hash_name = null;
        $post->attachment_original_name = null;
        $post->save();

        return redirect()->route('edit-post', ['postId' => $post->id]);
    }
}
