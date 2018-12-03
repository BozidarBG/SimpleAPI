<?php

namespace App\Http\Controllers;

use App\Article;
use App\Http\Resources\ArticleCollection;
use App\Http\Resources\ArticleResource;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except(['index', 'show']);
    }

    public function index()
    {
        $articles=Article::with('comments')->paginate(10);

        return ArticleCollection::collection($articles);
    }


    public function myArticles()
    {
        $articles=Article::with('comments')->where('user_id', \Auth::id())->paginate(10);

        return ArticleCollection::collection($articles);
    }

    public function test(){
        return response()->json(['status'=>\Auth::user()->name]);
    }


    public function store(Request $request)
    {
        //\Log::info($request->all());
        $this->validate($request, [
            'title'=>'required|max:255',
            'body'=>'required',
            'image'=>'image',
            'tags[]'=>'array'
        ]);

        $article=new Article();
        $article->user_id=$request->user()->id;
        $article->title=$request->title;
        $article->body=$request->body;
        if($request->has('image')){
            $image = $request->file('image');
            $filename = time().'.'.$image->guessExtension();
            $image->move('uploads/images', $filename);
            $article->image=$filename;
        }

        $article->save();
        if($request->has('tags')){
            $article->tags()->attach($request->tags);


        }

        return response()->json(['status'=>200]);

    }


    public function show(Article $article)
    {
        return new ArticleResource($article);
    }


    public function update(Request $request, Article $article)
    {
        if($article->user_id != $request->user()->id){
            return response()->json(['error'=>'You can only update your articles'], 403);
        }
        //\Log::info($request->all());
        $this->validate($request, [
            'title'=>'required|max:255',
            'body'=>'required',
            'image'=>'image',
            'tags[]'=>'array'
        ]);

        $article->title=$request->title;
        $article->body=$request->body;
        if($request->has('image')){
            //first delete existing image, since article can have only one image
            if($article->image){
                $path = parse_url($article->folder.$article->image);
                \File::delete(public_path($path['path']));
            }
            $image = $request->file('image');
            $filename = time().'.'.$image->guessExtension();
            $image->move('uploads/images', $filename);
            $article->image=$filename;
        }

        $article->save();
        if($request->has('tags')){
            $article->tags()->sync($request->tags);


        }

        return response()->json(['status'=>200]);
    }


    public function destroy(Request $request, Article $article)
    {
        if($article->user_id != $request->user()->id){
            return response()->json(['error'=>'You can only delete your articles'], 403);
        }

        if($article->image){
            $path = parse_url($article->folder.$article->image);
            \File::delete(public_path($path['path']));
        }
        $article->delete();
        return response()->json(['status'=>200]);
    }
}
