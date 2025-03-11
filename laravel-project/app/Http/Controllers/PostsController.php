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
}
