<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Repost;
use App\Notifications\PostLiked;
use App\Notifications\PostReposted;

class PostController extends Controller
{
    public function index()
    {
        $currentUser = request()->user('sanctum');
        $sortByLikes = request()->boolean('sort_by_likes');
        $followingOnly = request()->boolean('following');

        // 1. Base query for original posts
        $postsQuery = Post::with(['user'])
            ->withCount(['comments', 'likes', 'reposts']);

        if ($followingOnly && $currentUser) {
            $followedUserIds = $currentUser->following()->pluck('users.id')->all();
            if (empty($followedUserIds)) {
                return response()->json([]);
            }
            $postsQuery->whereIn('user_id', $followedUserIds);
        }

        $posts = $postsQuery->get()->map(function($post) {
            $post->is_repost = false;
            $post->reposted_by = null;
            $post->sort_date = $post->created_at;
            return $post;
        });

        // 2. Fetch reposts if not sorting by likes (standard feed)
        if (!$sortByLikes) {
            $repostsQuery = Repost::with(['user', 'post.user'])
                ->with(['post' => function($query) {
                    $query->withCount(['comments', 'likes', 'reposts']);
                }]);

            if ($followingOnly && $currentUser) {
                $followedUserIds = $currentUser->following()->pluck('users.id')->all();
                $repostsQuery->whereIn('user_id', $followedUserIds);
            }

            $reposts = $repostsQuery->get()->map(function($repost) {
                if (!$repost->post) return null;
                $post = $repost->post;
                $post->is_repost = true;
                $post->reposted_by = $repost->user;
                $post->sort_date = $repost->created_at;
                return $post;
            })->filter();

            $posts = $posts->concat($reposts);
        }

        // 3. Sort the combined collection
        if ($sortByLikes) {
            $posts = $posts->sortByDesc('likes_count')->values();
        } else {
            $posts = $posts->sortByDesc('sort_date')->values();
        }

        // 4. Add user-specific interaction data
        if ($currentUser) {
            $likedPostIds = Like::where('user_id', $currentUser->id)->pluck('post_id')->all();
            $repostedPostIds = Repost::where('user_id', $currentUser->id)->pluck('post_id')->all();
            $followedUserIds = $currentUser->following()->pluck('users.id')->all();

            $posts->each(function ($post) use ($likedPostIds, $repostedPostIds, $followedUserIds) {
                $post->liked_by_current_user = in_array($post->id, $likedPostIds, true);
                $post->reposted_by_current_user = in_array($post->id, $repostedPostIds, true);
                $post->is_following_author = in_array($post->user_id, $followedUserIds, true);
            });
        } else {
            $posts->each(function ($post) {
                $post->liked_by_current_user = false;
                $post->reposted_by_current_user = false;
                $post->is_following_author = false;
            });
        }

        return response()->json($posts);
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
        $user = $request->user();

        // Check if user is owner or admin
        $isAdmin = $user->email === 'admin@blog.com';
        if (!$isAdmin && $user->id !== $singlePostFromDB->user_id) {
            return response()->json(['message' => 'Action non autorisée.'], 403);
        }

        $rules = [
            'title' => ['required', 'min:3'],
            'description' => ['required', 'min:5'],
            'post_creator' => ['sometimes', 'exists:users,id'],
        ];

        $validatedData = $request->validate($rules);

        $updateData = [
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
        ];

        if ($isAdmin && isset($validatedData['post_creator'])) {
            $updateData['user_id'] = $validatedData['post_creator'];
        }

        $singlePostFromDB->update($updateData);

        return response()->json($singlePostFromDB->fresh('user'));
    }

    public function destroy(Request $request, $postId)
    {
        $post = Post::findOrFail($postId);
        $user = $request->user();

        // Check if user is owner or admin
        $isAdmin = $user->email === 'admin@blog.com';
        if (!$isAdmin && $user->id !== $post->user_id) {
            return response()->json(['message' => 'Action non autorisée.'], 403);
        }

        $post->delete();

        return response()->json(null, 204);
    }

    public function toggleLike(Request $request, $postId)
    {
        $post = Post::with('user')->findOrFail($postId);
        $user = $request->user();

        $like = Like::where('user_id', $user->id)
            ->where('post_id', $post->id)
            ->first();

        if ($like) {
            $like->delete();
            $liked = false;
        } else {
            Like::create([
                'user_id' => $user->id,
                'post_id' => $post->id,
            ]);
            $liked = true;

            if ($post->user_id !== $user->id) {
                $post->user->notify(new PostLiked($user, $post));
            }
        }

        return response()->json([
            'liked' => $liked,
            'likes_count' => $post->likes()->count(),
        ]);
    }

    public function toggleRepost(Request $request, $postId)
    {
        $post = Post::with('user')->findOrFail($postId);
        $user = $request->user();

        $repost = Repost::where('user_id', $user->id)
            ->where('post_id', $post->id)
            ->first();

        if ($repost) {
            $repost->delete();
            $reposted = false;
        } else {
            Repost::create([
                'user_id' => $user->id,
                'post_id' => $post->id,
            ]);
            $reposted = true;

            if ($post->user_id !== $user->id) {
                $post->user->notify(new PostReposted($user, $post));
            }
        }

        return response()->json([
            'reposted' => $reposted,
            'reposts_count' => $post->reposts()->count(),
        ]);
    }
}
