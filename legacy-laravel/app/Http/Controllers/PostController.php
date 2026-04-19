<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;

class PostController extends Controller
{
    public function index()
    {
        $postsFromDB = Post::with('user')->withCount('comments')->orderBy('created_at', 'desc')->get();
        return response()->json($postsFromDB);
    }

    public function show($postId)
    {
        $singlePostFromDB = Post::with('user')->findOrFail($postId);
        return response()->json($singlePostFromDB);
    }

    public function store(Request $request)
    {
        $rules = [
            'title' => ['required', 'min:3'],
            'description' => ['required', 'min:5'],
            'post_creator' => ['required', 'exists:users,id'],
        ];

        $validatedData = $request->validate($rules);

        $post = Post::create([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'user_id' => $validatedData['post_creator'],
        ]);

        return response()->json($post, 201);
    }

    public function update(Request $request, $postId)
    {
        $singlePostFromDB = Post::with('user')->findOrFail($postId);

        if ($request->user()->id !== $singlePostFromDB->user_id) {
            return response()->json(['message' => 'Action non autorisée.'], 403);
        }

        $rules = [
            'title' => ['required', 'min:3'],
            'description' => ['required', 'min:5'],
        ];

        $validatedData = $request->validate($rules);

        $singlePostFromDB->update([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
        ]);

        return response()->json($singlePostFromDB->fresh('user'));
    }

    public function destroy(Request $request, $postId)
    {
        $post = Post::findOrFail($postId);

        if ($request->user()->id !== $post->user_id) {
            return response()->json(['message' => 'Action non autorisée.'], 403);
        }

        $post->delete();

        return response()->json(null, 204);
    }
}
