<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function postIndex($postId) {
        $post = Post::findOrFail($postId);
        // $post = Post::find($postId);
        // if(is_null($post)) {
        //     return abort(404);
        // }
        
        return view('post', compact('post'));
    }

    public function newPostIndex() {
        return view('new-post');
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

        $console_output = new \Symfony\Component\Console\Output\ConsoleOutput();
        $console_output->writeln('Validated data: ' . json_encode($data));

        $request->session()->flash('post-added', $data['title']);
        return redirect()->route('new-post');
    }
}
