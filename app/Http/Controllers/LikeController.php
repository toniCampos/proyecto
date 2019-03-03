<?php

namespace App\Http\Controllers;

use App\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Laravel\Lumen\Routing\Controller as BaseController;

class LikeController extends BaseController
{
    public function index()
    {
        $like = new Like();
        $like -> user_id=11;
        $like -> post_id=3;
        $like -> comment_id=2;

        return response()->json($like, 200);
    }

    public function forTests(Request $request)
    {
    	$data = $request->json()->all();
    	$postLikes = count(Like::where(["post_id"=>$data["post_id"], "user_id"=>$data["user_id"]])->get());
        return response()->json($postLikes, 418);
    }

    public function createPostLike(Request $request)
    {
        $data = $request->json()->all();
        try
        {
        	$postLikes = count(Like::where(["post_id"=>$data["post_id"], "user_id"=>$data["user_id"]])->get());
        	if($postLikes==0)
        	{
	            $like = Like::create([
	                "user_id" => $data["user_id"],
	                "post_id" => $data["post_id"]
	            ]);
	            return response()->json($like, 201);
        	}
        	$respuesta = array("error" => "ya diste like amigo", "codigo" => 406);
            Return response()->json($respuesta, 406);
        }
        catch (\Illuminate\Database\QueryException $e)
        {
            $respuesta = array("error" => $e->errorInfo, "codigo" => 500);
            Return response()->json($respuesta, 500);
        }
    }

    public function createCommentLike(Request $request)
    {
        $data = $request->json()->all();
        try
        {

        	$commentLikes = count(Like::where(["comment_id"=>$data["comment_id"], "user_id"=>$data["user_id"]])->get());
        	if($commentLikes==0)
        	{
	            $like = Like::create([
	                "user_id" => $data["user_id"],
	                "comment_id" => $data["comment_id"]
	            ]);
	            return response()->json($like, 201);
        	}
        	$respuesta = array("error" => "ya diste like amigo", "codigo" => 406);
            Return response()->json($respuesta, 406);

            $like = Like::create([
                "user_id" => $data["user_id"],
                "comment_id" => $data["comment_id"]
            ]);
            return response()->json($like, 201);
        }
        catch (\Illuminate\Database\QueryException $e)
        {
            $respuesta = array("error" => $e->errorInfo, "codigo" => 500);
            Return response()->json($respuesta, 201);
        }
    }

    public function getLikes()
    {
        $likes = Like::all();
        return response()->json([$likes], 200);
    }

    public function getLike($id)
    {
        $like = Like::find($id);
        return response()->json([$like], 202);
    }

    public function getLikesFromPost($postId)
    {
        $likes = Like::where(["post_id"=>$postId])->get();
        return response()->json([$likes], 202);	
    }

    public function getLikesFromComment($commentId)
    {
        $likes = Like::where(["comment_id"=>$commentId])->get();
        return response()->json([$likes], 202);	
    }

    public function deleteLike($id)
    {
        $like = Like::find($id);

        if($like){
            $like->delete();
            return response()->json("Eliminaste ese comentario amigo", 204);
        }
        return response()->json("No existe ese comentario", 404);
        
    }

}