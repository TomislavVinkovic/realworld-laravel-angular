<?php

namespace App\Http\Controllers;

use App\Http\Requests\Article\ArticleStoreRequest;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\ArticlesCollection;
use App\Models\Article;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $authenticatedUser = auth('api')->user();
        $authenticatedUser->load('favorites');

        $tag = $request->input('tag', null);
        $author = $request->input('author', null);
        $favorited = $request->input('favorited', null);

        $perPage = $request->input('limit', 10);
        $offset = $request->input('offset', 0);

        $page = intdiv($offset, $perPage);

        $articles = Article::query()
            ->orderBy('created_at', 'desc');

        if(!is_null($tag)) {
            $articles = $articles->whereHas('tags', function(Builder $query) use ($tag) {
                $query->where('tag', 'LIKE', "%$tag%");
            });
        }
        if(!is_null($author)) {
            $articles = $articles->whereHas('author', function(Builder $query) use ($author) {
                $query->where('name', 'LIKE', "%$author%");
            });
        }
        if(!is_null($favorited)) {
            $articles = $articles->whereHas('favorited', function(Builder $query) use ($favorited) {
                $query->where('name', $favorited);
            });
        }

        $articles = $articles->paginate(page:$page, perPage:$perPage);

        $articles->through(function ($article) use ($authenticatedUser) {
            $article->favorited = $authenticatedUser->favorites->contains($article->id);
            return $article;
        });
        
        return new ArticlesCollection($articles);
    }

    public function feed(Request $request) {

    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        $authenticatedUser = auth('api')->user();
        $article->loadCount('favorited');

        $isFavorited = false;
        $isFavorited = $authenticatedUser->favorites()
            ->where('article_id', $article->id)
            ->exists();
        

        $article->favorited = $isFavorited;

        return new ArticleResource($article);
    }


    public function store(ArticleStoreRequest $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Article $article)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        //
    }
}
