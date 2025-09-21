<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{


    public static $wrap = 'profile';

    public function toArray(Request $request): array
    {
        return [
            'username' => $this->name,
            'bio' => $this->bio,
            'image' => $this->image->publicUrl,
            'following' => false // TODO: Implment following relationship
        ];
    }
}
