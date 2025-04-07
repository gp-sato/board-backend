<?php

namespace App\Http\Controllers;

use App\Events\MessageEvent;
use App\Http\Requests\PostsRequest;
use App\Models\Post;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PostsController extends Controller
{
    public function index()
    {
        $posts = Post::select('id', 'name', 'date', 'description')
            ->orderBy('date', 'DESC')
            ->get();

        return response($posts);
    }

    public function store(PostsRequest $request)
    {
        try {
            $post = new Post();

            $post->name = $request->name;
            $post->date = now();
            $post->description = $request->description;

            $post->save();

            event(new MessageEvent($post));

            return response()->json([
                'status' => true,
                'message' => '登録されました。',
                'errors' => null,
                'data' => null
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => '問題が発生しました。',
                'errors' => null,
                'data' => null
            ] , Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
