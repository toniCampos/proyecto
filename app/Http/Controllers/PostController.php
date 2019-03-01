<?php

namespace App\Http\Controllers;

use App\Post;
use App\Like;
use App\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{

    public function index()
    {
        $post = new Post();
        $post -> title="Hola mundo";
        $post -> body="Contenido perron";
        $post -> imagen_url="http://google.com";
        $post -> user_id=2;

        return response()->json($post, 200);
    }

    public function createPost(Request $request)
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
                    $post = Post::create([
                        "title" => $request["title"],
                        "body" => $request["body"],
                        "imagen_url" => $fileComplete,
                        "user_id" => $request["user_id"]
                    ]);                
                    $request->file('imagen')->move($destinationPath, $fileComplete);
                    return response()->json([$post], 201);
                }
                return response()->json(['algo mal'], 404);
            }
            $post = Post::create([
                "title" => $data["title"],
                "body" => $data["body"],
                "user_id" => $data["user_id"]
            ]);
            return response()->json($post, 201);
        }
        catch (\Illuminate\Database\QueryException $e)
        {
            $respuesta = array("error" => $e->errorInfo, "codigo" => 500);
            Return response()->json($respuesta, 500);
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

    public function getPosts()
    {
        $posts = Post::all();
        return response()->json([$posts], 200);
    }


    public function getPost($id)
    {
        $posts = Post::find($id);
        return response()->json([$posts], 202);
    }

    public function getPostByUser($id)
    {
        $posts = Post::where(["user_id"=>$id])->get();
        return response()->json([$posts], 202);
    }

    public function updatePost(Request $request, $id)
    {
        $data = $request->json()->all();
        $post = Post::find($id);
    
        $post->title = $data["title"];
        $post->body = $data["body"];

        $post->save();

        return response()->json($post, 200);
    }

    public function deletePost($id)
    {
        $post = Post::find($id);

        if($post){
            $post->delete();
            return response()->json("Eliminaste al compa, vato", 204);
        }
        return response()->json("No existe ese post", 404);
        
    }

    public function postCommentsLikes($id)
    {
        $post = Post::find($id);
        $postLikes = count(Like::where(["post_id"=>$id])->get());
        $post->likes=$postLikes;
        $comments = Comment::where(["post_id"=>$id])->get();
        foreach ($comments as $comment) {
            $commentLikes = count(Like::where(["comment_id"=>$comment["id"]])->get());
            $comment->likes=$commentLikes;
        }
        
        return response()->json([$post, $comments], 201);
    }

}
