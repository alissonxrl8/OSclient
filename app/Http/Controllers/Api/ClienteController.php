<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class ClienteController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        $clientes = Cliente::where('id_user', $user->id)->get();
        return response()->json([
            'status'=>200,
            'clientes'=>$clientes
        ]);
    }


 
    
    public function store(Request $request)
    {
 
        $user = Auth::user();
        $validados = $request->validate([
            'nome'=>'required|string',
            'cpf'=>'required|string',
            'endereco'=>'required|string',
            'contato'=>'required|string',
        ]);
        $cliente = Cliente::create([
            'nome'=>$validados['nome'],
            'cpf'=>$validados['cpf'],
            'endereco'=>$validados['endereco'],
            'contato'=>$validados['contato'],
            'id_user'=>$user->id,
        ]);
        return response()->json([
            'status'=>200,
            'cliente'=>$cliente
        ]);
    }

 
    public function show(string $id)
    {
        $cliente = Cliente::findOrFail($id);
        return response()->json([
            'status'=>200,
            'cliente'=>$cliente
        ]);

    }


    public function update(Request $request, string $id)
    {
        $cliente = Cliente::findOrFail($id);

          $validados = $request->validate([
            'nome'=>'required|string',
            'cpf'=>'required|string',
            'endereco'=>'required|string',
            'contato'=>'required|string',
        ]);

        $cliente->update($validados);

        return response()->json([
            'status'=>200,
            'message'=>'cliente atualizado',
            'cliente'=>$cliente
        ]);
        
    }

    
    public function destroy(string $id)
    {
        $cliente = Cliente::findOrFail($id);

        $cliente->delete();

        return response()->json([
            'status'=>200,
            'message'=>'Cliente deletado com sucesso'
        ]);
    }
}
