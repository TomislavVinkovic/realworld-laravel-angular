<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProfileResource;
use App\Models\User;

class ProfileController extends Controller
{
    public function show(User $user) {

        // Check if the authenticated user is following the requested user
        $authenticatedUser = auth('api')->user();
        $isFollowing = false;

        if($authenticatedUser) {
            $isFollowing = $authenticatedUser->following()
                ->where('following_id', $user->id)
                ->exists();
        }
        $user->following = $isFollowing;

        return new ProfileResource($user);
    }

    public function follow(User $user) {
        $authenticatedUser = auth('api')->user();
        $isFollowing = false;

        if($authenticatedUser) {
            $isFollowing = $authenticatedUser->following()
                ->where('following_id', $user->id)
                ->exists();
        }
        if($isFollowing) {
            $authenticatedUser->following()->detach($user->id);
            $isFollowing = false;
        }
        else {
            $authenticatedUser->following()->attach($user->id);
            $isFollowing = true;
        }
        $authenticatedUser->save();

        $user->following = $isFollowing;

        return new ProfileResource($user);
    }
}
