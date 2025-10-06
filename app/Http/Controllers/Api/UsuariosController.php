<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuariosController extends Controller
{
    public function index(){
        return response()->json([
        'message'=>'entrou com token'
    ]);
    }

    public function criarUser(Request $request){
        $validados = $request->validate([
            'name' => 'required|string',
            'email' => 'required|unique:users,email|email',
            'password' => 'required|min:6',
            'level' => 'numeric',
            'id_loja'=>'required'
        ]);

        $user = User::create([
            'name' => $validados['name'],
            'email' => $validados['email'],
            'password' => Hash::make($validados['password']),
            'level' => $validados['level'],
            'id_loja' => $validados['id_loja'],
        ]);

        return response()->json([

            'status'=>'ok',
            $user
        ]);
    }

    public function teste(){
        return response()->json([
            'status'=>200,
            'message'=>'funcionando'
        ]);
    }
}
