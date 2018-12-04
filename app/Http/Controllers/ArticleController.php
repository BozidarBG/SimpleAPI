<?php

namespace App\Http\Controllers;

use App\Article;
use App\Http\Resources\ArticleCollection;
use App\Http\Resources\ArticleResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Log;
use App\Tag;


class ArticleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except(['index', 'show']);
    }

    protected function log($msg){
        Log::channel('single')->info($msg);
    }

    public function index(Request $request)
    {

        $perPage=10;
        //if request contains ?per_page=integer, we will display that many results per page
        //if request is wrong, we will return error
        if($request->has('per_page')){

            $validation=Validator::make($request->all(), [
                'per_page'=>'integer|min:2|max:50'
            ]);

            if($validation->fails()){
                return response()->json(['error'=>'Request is wrong'], 403);

            }
            $perPage=$request->per_page;
        }
        $articles=Article::with('comments')->paginate($perPage);

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
        $this->checkRequest($request);

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

        return response()->json(['status'=>200, 'data'=>$article]);

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

        $this->checkRequest($request);

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

        return response()->json(['status'=>201, 'data'=>$article]);
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

    //method that is checking validity for store and update
    protected function checkRequest($request){
        $this->validate($request, [
            'title'=>'required|max:255',
            'body'=>'required',
            'image'=>'image',

        ]);

        //checking validity of tags ids
        if($request->has('tags')){
            $tagsAreValid=true;
            $tags=Tag::all()->pluck('id')->toArray();
            $requestTagsLength=count($request->tags);

            for($i=0; $i<$requestTagsLength; $i++){
                if(!in_array($request->tags[$i], $tags)){
                    $tagsAreValid=false;
                    break;
                }
            }
            //if there is one tag id that doesn't exist, we will reject entire request for store
            if(!$tagsAreValid){
                return response()->json(['error'=>'Given Tag id does not match'], 404);
            }
        }
    }
}
