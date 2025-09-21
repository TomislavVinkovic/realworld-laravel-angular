<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProfileResource;
use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show(User $user) {
        return new ProfileResource($user);
    }

    public function follow(User $user) {
        // TODO: Implement following relationship
    }
}
