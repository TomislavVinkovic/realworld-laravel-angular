<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{

    public static $wrap = 'article';

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'slug' => $this->slug,
            'title' => $this->title,
            'description' => $this->title,
            'body' => $this->body,
            'tagList' => $this->tag_list,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
            'favorited' => $this->favorited,
            'favoritesCount' => $this->favorited_count,
            'author' => new ProfileResource($this->whenLoaded('author'))
        ];
    }
}
