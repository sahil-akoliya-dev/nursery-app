<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommunityController extends Controller
{
    public function index()
    {
        $posts = Post::with(['user', 'comments.user', 'likes'])
            ->withCount(['likes', 'comments'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Add is_liked attribute for current user
        $posts->getCollection()->transform(function ($post) {
            $post->is_liked = $post->likes->contains('user_id', Auth::id());
            return $post;
        });

        return response()->json([
            'success' => true,
            'data' => $posts
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
            'image_url' => 'nullable|url'
        ]);

        $post = Post::create([
            'user_id' => Auth::id(),
            'content' => $request->content,
            'image_url' => $request->image_url
        ]);

        return response()->json([
            'success' => true,
            'data' => $post->load('user'),
            'message' => 'Post created successfully'
        ], 201);
    }

    public function like($id)
    {
        $post = Post::findOrFail($id);
        $userId = Auth::id();

        $existingLike = Like::where('post_id', $id)->where('user_id', $userId)->first();

        if ($existingLike) {
            $existingLike->delete();
            $post->decrement('likes_count');
            $isLiked = false;
        } else {
            Like::create([
                'post_id' => $id,
                'user_id' => $userId
            ]);
            $post->increment('likes_count');
            $isLiked = true;
        }

        return response()->json([
            'success' => true,
            'data' => [
                'likes_count' => $post->likes_count,
                'is_liked' => $isLiked
            ]
        ]);
    }

    public function comment(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string|max:500'
        ]);

        $post = Post::findOrFail($id);

        $comment = Comment::create([
            'post_id' => $id,
            'user_id' => Auth::id(),
            'content' => $request->content
        ]);

        $post->increment('comments_count');

        return response()->json([
            'success' => true,
            'data' => $comment->load('user'),
            'message' => 'Comment added'
        ], 201);
    }
}
