<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CommentController extends Controller
{
    /**
     * Display a listing of the comments for a specific post.
     */
    public function index($postId)
    {
        $comments = Comment::with('user')
            ->where('post_id', $postId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($comments);
    }

    /**
     * Store a newly created comment in storage.
     */
    public function store(Request $request, $postId)
    {
        $request->validate([
            'content' => 'required|min:2|max:1000',
        ]);

        $comment = Comment::create([
            'content' => $request->content,
            'user_id' => $request->user()->id,
            'post_id' => $postId,
        ]);

        return response()->json($comment->load('user'), 201);
    }

    /**
     * Remove the specified comment from storage.
     */
    public function destroy(Request $request, $postId, $commentId)
    {
        $comment = Comment::findOrFail($commentId);

        // Authorization check: only the owner can delete their comment
        if ($request->user()->id !== $comment->user_id) {
            return response()->json(['message' => 'Action non autorisée.'], 403);
        }

        $comment->delete();

        return response()->json(null, 204);
    }
}
