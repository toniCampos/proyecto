<?php

namespace App\Http\Controllers;

use App\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

use Laravel\Lumen\Routing\Controller as BaseController;

class CommentController extends BaseController
{
	public function index()
    {
        $comment = new Comment();
        $comment -> body="Contenido de comentario perron";
        $comment -> imagen_url="http://google.com";
        $comment -> user_id=2;
        $comment -> post_id=2;

        return response()->json($comment, 200);
    }
    
    public function createComment(Request $request)
    {
        $data = $request->json()->all();
        try
        {
            if($request->hasFile('imagen'))
            {
                if($request->file('imagen')->isValid())
                {
                    $destinationPath= "/Users/SNTE/Documents/UNI/Quinto/clienteSevidor/proyecto/storage/img";
                    $fileName = str_random(10);
                    $extension = $request->file('imagen')->getClientOriginalExtension();
                    $fileComplete = $fileName.".".$extension;
                    $comment = Comment::create([
                        "body" => $request["body"],
                        "imagen_url" => $fileComplete,
                        "user_id" => $request["user_id"],
                        "post_id" => $request["post_id"]
                    ]);                
                    $request->file('imagen')->move($destinationPath, $fileComplete);
                    return response()->json([$comment], 201);
                }
                return response()->json(['algo mal'], 404);
            }
            $comment = Comment::create([
                "body" => $data["body"],
                "user_id" => $data["user_id"],
                "post_id" => $data["post_id"]
            ]);
            return response()->json($comment, 201);
        }
        catch (\Illuminate\Database\QueryException $e)
        {
            $respuesta = array("error" => $e->errorInfo, "codigo" => 500);
            Return response()->json($respuesta, 201);
        }
        // $destinationPath= "/Users/SNTE/Documents/UNI/Quinto/clienteSevidor/proyecto/storage/img";
        // $fileName = "imagen11.jpg";
        // $request->file('imagen')->move($destinationPath, $fileName);
    
        // $post = Post::create([
        //         "title" => $data["title"],
        //         "body" => $data["body"],
        //         "imagen_url" => $data["imagen_url"],
        //         "user_id" => $data["user_id"]
        // ]);
        // return response()->json([$post], 201);
    }
    
    public function getComments()
    {
        $comments = Comment::all();
        return response()->json([$comments], 200);
    }

    public function getComment($id)
    {
        $comment = Comment::find($id);
        return response()->json([$comment], 202);
    }

    public function getCommentsFromPost($postId)
    {
        $comment = Comment::where(["post_id"=>$postId])->get();
        if($comment)
        {
        	return response()->json([$comment], 202);	
        }
        $respuesta = array('error'=>"Este post no tiene comentarios", 'codigo'=>404);
        return response()->json($respuesta, 404);//checar
    }
    
    public function getCommentsByUser($userId)
    {
        $comments = Comment::where(["user_id"=>$userId])->get();
        return response()->json([$comments], 202);
    }
    
    public function updateComment(Request $request, $id)
    {
        $data = $request->json()->all();
        $comment = Comment::find($id);
    
        $comment->body = $data["body"];

        $comment->save();

        return response()->json($comment, 200);
    }
    //
    public function deleteComment($id)
    {
        $comment = Comment::find($id);

        if($comment){
            $comment->delete();
            return response()->json("Eliminaste ese comentario amigo", 204);
        }
        return response()->json("No existe ese comentario", 404);
        
    }

}