<?php

namespace App\Http\Controllers;

use App\Http\Requests\Comment\CommentStoreRequest;
use App\Http\Resources\CommentResource;
use App\Http\Resources\CommentsCollection;
use App\Models\Article;
use App\Models\Comment;

class CommentController extends Controller
{
    // List all comments for a given article
    public function list(Article $article) {
        $comments = Comment::query()
            ->where('article_id', $article->id)
            ->with(['user' => function ($query) {
                $query->withExists(['followers as is_following' => function ($q) {  
                    $q->where('follower_id', auth()->id());
                }]);
            }])->get();
        return new CommentsCollection($comments);
    }

    public function store(CommentStoreRequest $request, Article $article)
    {
        $data = $request->safe();
        $comment = Comment::make([
            ...$data,
            'user_id' => $request->user()->id
        ]);
        $article->comments()->save($comment);
        $article->refresh();

        $comment->load('user');

        return new CommentResource($comment);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article, Comment $comment)
    {
        $comment->delete();
        return new CommentResource($comment);
    }
}
