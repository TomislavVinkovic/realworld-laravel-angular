<?php

namespace App\Http\Controllers;

use App\Http\Requests\Article\ArticleStoreRequest;
use App\Http\Requests\Article\ArticleUpdateRequest;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\ArticlesCollection;
use App\Models\Article;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// TODO: Refactor the controller using services and scopes to offload code

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
            $article->favorited = $isFavorited = $authenticatedUser->favorites()
                ->where('article_id', $article->id)
                ->exists();
            return $article;
        });

        return new ArticlesCollection($articles);
    }

    public function feed(Request $request) {
        $authenticatedUser = auth('api')->user();
        $authenticatedUser->load('following', 'favorites');

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

        // The key code block that differentiates the feed endpoint from the index endpoint
        {
            $followingIds = $authenticatedUser->following()->pluck('id');
            $articles = $articles->whereIn('author_id', $followingIds);
        }

        $articles = $articles->paginate(page:$page, perPage:$perPage);

        $articles->through(function ($article) use ($authenticatedUser) {
            $article->favorited = $isFavorited = $authenticatedUser->favorites()
                ->where('article_id', $article->id)
                ->exists();
            return $article;
        });

        return new ArticlesCollection($articles);
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
        $authenticatedUser = auth('api')->user();
        DB::beginTransaction();

        try {
            $data = $request->safe();
            $slug = str_replace(" ", "-", strtolower($data['title']));

            $article = Article::make([
                ...$data,
                'slug' => $slug,
            ]);

            $authenticatedUser->articles()->save($article);
            $authenticatedUser->favorites()->attach($article);
            $authenticatedUser->refresh();

            // We know this since the article is not immediately favorited
            $article->favorited = false;

            return new ArticleResource($article);
            
        } catch(\Exception $e) {
            DB::rollBack();

            return response()->json(['error' => 'An error occurred while creating the article.'], 500);
        }
    }

    public function update(ArticleUpdateRequest $request, Article $article)
    {
        $authenticatedUser = auth('api')->user();
        DB::beginTransaction();

        try {
            $data = $request->safe();
            $data = array_filter($data, fn($value) => !is_null($value));

            if(array_key_exists('title', $data)) {
                $data['slug'] = str_replace(" ", "-", strtolower($data['title']));
            }
            $article->update($data);

            // We know this since the article is not immediately favoited
            $article->favorited = $authenticatedUser->favorites()
                ->where('article_id', $article->id)
                ->exists();

            return new ArticleResource($article);
            
        } catch(\Exception $e) {
            DB::rollBack();

            return response()->json(['error' => 'An error occurred while updating the article.'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        DB::beginTransaction();

        try {
            $article->delete();

            return response()->json(['message' => 'Article deleted successfully']);
        } catch(\Exception $e) {
            DB::rollBack();

            return response()->json(['error' => 'An error occurred while deleting the article.'], 500);
        }
        
    }
}
