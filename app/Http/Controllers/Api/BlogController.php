<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        $posts = Post::where('is_published', true)
            ->with('user:id,name')
            ->orderBy('published_at', 'desc')
            ->paginate(9);

        return response()->json([
            'success' => true,
            'data' => $posts
        ]);
    }

    public function show($slug)
    {
        $post = Post::where('slug', $slug)
            ->where('is_published', true)
            ->with('user:id,name')
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => $post
        ]);
    }

    public function latest()
    {
        $posts = Post::where('is_published', true)
            ->with('user:id,name')
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $posts
        ]);
    }
}
