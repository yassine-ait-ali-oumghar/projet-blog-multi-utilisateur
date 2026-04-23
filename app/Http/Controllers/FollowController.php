<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use App\Models\User;
use App\Notifications\UserFollowedYou;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function toggle(Request $request, int $userId)
    {
        $actor = $request->user();
        $targetUser = User::findOrFail($userId);

        if ($actor->id === $targetUser->id) {
            return response()->json(['message' => 'Vous ne pouvez pas vous suivre vous-meme.'], 422);
        }

        $follow = Follow::where('follower_id', $actor->id)
            ->where('followed_id', $targetUser->id)
            ->first();

        if ($follow) {
            $follow->delete();
            $following = false;
        } else {
            Follow::create([
                'follower_id' => $actor->id,
                'followed_id' => $targetUser->id,
            ]);
            $following = true;
            $targetUser->notify(new UserFollowedYou($actor));
        }

        return response()->json([
            'following' => $following,
            'followed_user_id' => $targetUser->id,
        ]);
    }
}
