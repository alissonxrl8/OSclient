<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Servico;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServicoController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        $servicos = Servico::Where('id_user',$user->id)->get();
        return response()->json([
            'status'=>200,
            'servicos'=>$servicos
        ]);
    }


    public function store(Request $request)
    {

        $user = Auth::user();
        $validados = $request->validate([
        'servico'=>'required|string',
        'descricao'=>'required|string',
        'dias_garantia'=>'required|integer',
        'preco'=>'required|numeric',
        'preco_pago'=>'required|numeric'
        ]);

        $servico = Servico::create([
            'id_user'=>$user->id,
            'servico'=>$validados['servico'],
            'descricao'=>$validados['descricao'],
            'dias_garantia'=>$validados['dias_garantia'],
            'preco'=>$validados['preco'],
            'preco_pago'=>$validados['preco_pago']
        ]);


        return response()->json([
            'status'=>200,
            $servico
        ]);
    }

 
    public function show(string $id)
    {
          $servico = Servico::findOrFail($id);
        return response()->json([
            'status'=>200,
            'servico'=>$servico
        ]);
    }


    public function update(Request $request, string $id)
    {
        $servico = Servico::findOrFail($id);
        $validados = $request->validate([
        'servico'=>'required|string',
        'descricao'=>'required|string',
        'dias_garantia'=>'required|integer',
        'preco'=>'required|numeric',
        'preco_pago'=>'required|numeric'
        ]);

        $servico->update($validados);

        return response()->json([
            'status'=>200,
            'message'=>'atualizado com sucesso',
            'servico'=>$servico
        ]);
    }

 
    public function destroy(string $id)
    {
        $servico = Servico::findOrFail($id);

        $servico->delete();

        return response()->json([
            'status'=>200,
            'message'=>'deletado com sucesso'
        ]);
    }
}
