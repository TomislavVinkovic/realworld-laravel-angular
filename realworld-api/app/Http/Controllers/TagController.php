<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * Get all tags
     */
    public function index()
    {
        return response()->json(
            [
                'tags' => Tag::limit(5)->pluck('tag')
            ]
            
        );
    }

}
