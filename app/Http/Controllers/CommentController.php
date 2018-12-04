<?php

namespace App\Http\Controllers;

use App\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'article_id'=>'required|integer',
            'body'=>'required|string'
        ]);

        $articlesIds=\App\Article::all()->pluck('id')->toArray();
        if(!in_array($request->article_id, $articlesIds)){
            return response()->json(['error'=>'This article does not exist'], 404);
        }

        $comment=new Comment();
        $comment->user_id=\Auth::id();
        $comment->article_id=$request->article_id;
        $comment->body=$request->body;
        $comment->save();

        return response()->json(['success'=>$comment], 200);
    }


    //comment can be deleted by person who commented or owner of commented article
    public function destroy(Comment $comment)
    {
        if($comment->user_id == \Auth::id() || $comment->article->user->id == \Auth::id()){

            $comment->delete();

            return response()->json(['success'=>'Comment deleted'], 200);
        }
        return response()->json(['error'=>'You can only delete your comments'], 403);
    }
}
