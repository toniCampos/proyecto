<?php

namespace App\Http\Controllers;

use App\User;
use App\Persona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function index()
    {
        return response()->json('{"hola mundo guapote":"nose", "juan":"pepe"}', 418);
    }

    public function comoQuieran(Request $request)
    {
        $data = $request->json()->all();
        $results = DB::select('SELECT * FROM users where nickname =:nickname', ["nickname" => $data["nickname"]]);
        return response()->json($results, 200);
    }

    public function login(Request $request)
    {   
        $data = $request->json()->all();
        $user = User::where(["nickname"=>$data["nickname"]])->first();
        if($user)
        {
            if(Hash::check($data["password"], $user->password))
            {
                return response()->json($user, 200);
            }
            $respuesta = array('error'=>"El password es incorrecto", 'codigo'=>404);
            return response()->json($respuesta, 404);
        }
        $respuesta = array('error'=>"El usuario no existe", 'codigo'=>404);
        return response()->json($respuesta, 404);

    }

    public function usuarios()
    {
        $user = User::all();
        return response()->json([$user], 418);
    }

    public function getUser($id)
    {
        $user = User::find($id);
        if($user){
            return response()->json($user, 200);    
        }
        return response()->json("No existe ese usuario", 404);
    }

    public function deleteUser($id)
    {
        $user = User::find($id);
    
        if($user){
            $user->delete();
            return response()->json("Eliminaste al compa, vato", 204);
        }
        return response()->json("No existe ese usuario", 404);
        
    }

    public function updateUser(Request $request, $id)
    {
        $data = $request->json()->all();
        $user = User::find($id);
    
        $user->name = $data["name"];
        $user->nickname = $data["nickname"];
        $user->email = $data["email"];

        $user->save();

        return response()->json($user, 200);
    }

    public function crearUsuario(Request $request)
    {
        $data = $request->json()->all();
        try
        {
            $user = User::create([
                "name"=>$data["name"],
                "nickname"=>$data["nickname"],
                "email"=>$data["email"],
                "password"=> Hash::make($data["password"]),
                "token"=> str_random(60)
            ]);
            return response()->json($user, 418);
        }
        catch (\Illuminate\Database\QueryException $e)
        {
            $respuesta = array("error"=>$e->errorInfo, "codigo"=>500);
            return response()->json($respuesta, 500);   
        }
            

        
    }

    public function pepa()
    {
        return "saque el agua de coco";
    }

    public function createName()
    {
        $user = new User();

        $user->name = "Antonio Campos";
        $user->email = "email@gmail.com";
        return response()->json([$user], 201);
    }

    public function createManchego()
    {
        $user = new User();

        $user->name = "Manchego";
        $user->email = "manchego@lala.com";
        return response()->json([$user], 201);
    }

    public function gente()
    {
        $chantalita = new Persona();
        
        $chantalita->peso = "Manchego";
        $chantalita->altura = " poco";
        $chantalita->piel = "dubalin";
        $chantalita->cabello = "gay";
        $chantalita->ojos = "dos";
        return response()->json([$chantalita], 418);
    }

    //        }
}
