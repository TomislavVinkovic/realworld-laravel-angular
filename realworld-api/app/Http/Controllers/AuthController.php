<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\Image;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

// TODO: Refactor the controller using services and scopes to offload code

class AuthController extends Controller
{
    public function login(LoginRequest $request) {
        $credentials = $request->validated();

        $token = null;

        try {
            if(!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Invalid credentials'], 401);
            }
        } catch(JWTException $e) {
            return response()->json([
                'error' => 'Could not create token'
            ], 500);
        }

        $user = auth('api')->user();

        // add a dynamic property I can access inside the resource
        $user->token = $token;

        return new UserResource($user);
    }

    public function register(RegisterRequest $request) {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);
        
        $token = null;

        try {
            $token = JWTAuth::fromUser($user);
        } catch(JWTException $e) {
            return response()->json([
                'error' => 'Could not create token'
            ], 500);
        }

        $user->token = $token;

        return new UserResource($user);
    }

    public function logout() {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
        } catch (JWTException $e) {
            return response()->json(['error' => 'Failed to logout, please try again'], 500);
        }

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function show() {
        return new UserResource(auth('api')->user());
    }

    public function update(UserUpdateRequest $request) {
        $user = auth('api')->user();
        DB::beginTransaction();

        try {
            $userData = $request->safe()->except('image');
            $userData = array_filter($userData, fn($value) => !is_null($value));

            if(!empty($userData)) {
                $user->update($userData);
            }
            
            if($request->hasFile('image')) {
                if($user->image) {
                    Storage::disk('public')->delete($user->image->src);
                    $user->image->delete();
                }

                $newImagePath = Storage::disk('public')
                    ->putFile('avatars', $request->file('image'));
                $newImage = Image::create([
                    'src' => $newImagePath
                ]);
                $user->image()->associate($newImage);
                $user->save();
            }

            DB::commit();

        } catch(\Exception $e) {
            DB::rollBack();

            return response()->json(['error' => 'An error occurred while updating the user.'], 500);
        }
        
        return new UserResource($user);

    }
}
