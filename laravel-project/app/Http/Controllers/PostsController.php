<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostsController extends Controller
{
    public function index()
    {
        $posts = Post::select('id', 'name', 'date', 'description')->get();

        return response($posts);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'required|string|max:280',
        ]);

        try {
            $post = new Post();
    
            $post->name = $validated['name'];
            $post->date = now();
            $post->description = $validated['description'];
    
            $post->save();
        } catch (\Exception $e) {
            return response($e->getMessage(), 500);
        }

        $posts = Post::select('id', 'name', 'date', 'description')->get();

        return response($posts);
    }
}
