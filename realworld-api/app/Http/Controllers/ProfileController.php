<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProfileResource;
use App\Models\User;

// TODO: Refactor the controller using services and scopes to offload code

class ProfileController extends Controller
{
    public function show(User $user) {

        // Check if the authenticated user is following the requested user
        $authenticatedUser = auth('api')->user();
        $isFollowing = false;

        $isFollowing = $authenticatedUser->following()
            ->where('follower_id', $user->id)
            ->exists();
        
        return new ProfileResource($user);
    }

    public function follow(User $user) {
        if (auth('api')->user()->id == $user->id) {
        return response()->json([
            'errors' => [
                'message' => ['You cannot follow yourself.']
            ]
        ], 422);
    }
        auth('api')->user()->following()->attach($user->id);
        return new ProfileResource($user->refresh());
    }

    public function unfollow(User $user) {
        auth('api')->user()->following()->detach($user->id);
        return new ProfileResource($user->refresh());
    }
}
