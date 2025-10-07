<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Ordem;
use App\Models\Servico;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrdemController extends Controller
{
  
    public function index()
    {
        $user = Auth::user();
    
        $ordems = Ordem::where('id_user', $user->id)->get();

        return response()->json([
            'status'=>200,
            'ordens'=>$ordems
        ]);
    }

public function store(Request $request)
{
    $user = Auth::user();
    
    $validados = $request->validate([
        'id_servico'=> 'numeric|required',
        'id_cliente'=> 'numeric|required',
        'obs'=>'nullable|string',
        'data'=>'required|date_format:Y-m-d',
        'modelo'=>'required|string'
    ]);

  

    $servico = Servico::findOrFail($validados['id_servico']);


    
    $data_formatada = Carbon::createFromFormat('Y-m-d', $validados['data'])->format('Y-m-d');


    

    $ordem = Ordem::create([
    'id_user' => $user->id,
    'id_servico' => $validados['id_servico'],
    'id_cliente' => $validados['id_cliente'],
    'modelo' => $validados['modelo'],
    'obs' => $validados['obs'],
    'data' => $data_formatada,
    'preco' => $servico->preco,
    'preco_pago' => $servico->preco_pago,
    'descricao' => $servico->descricao,
    'dias_garantia' => $servico->dias_garantia,
    'servico' => $servico->servico, // 👈 pega o nome do serviço da tabela “servicos”
]);

    return response()->json([
        'status'=>200,
        'ordem'=>$ordem
    ]);
}

   
    public function show(string $id)
    {
       
        $ordem = Ordem::findOrFail($id);
        $servico = Servico::findOrFail($ordem->id_servico);
       

        return response()->json([
            'status'=>200,
            'ordem'=>$ordem,
            'servico'=>$servico, 
        ]);
    }

    public function ClienteOrdem(string $id){
     ;
       $cliente = Cliente::findOrFail($id);
       
       $ordens = Ordem::where('id_cliente', $id)->get();
       return response()->json([
        'cliente'=>$cliente,
        'ordens'=>$ordens
       ]);

    }


    
   
    public function update(Request $request, string $id)
    {
        $ordem = Ordem::findOrFail($id);

          
        $validados = $request->validate([
            'id_servico'=> 'numeric|required',
            'obs'=>'required|string',
            'data'=>'required|date_format:d/m/Y',
            'preco'=>'required|numeric',
            'modelo', 'required|string'
        ]); 

        $servico = Servico::findOrFail($validados['id_servico']);
            

        $data_formatada = Carbon::createFromFormat('d/m/Y', $validados['data'])->format('Y-m-d');


        $ordem->update([
    'id_servico' => $validados['id_servico'],
    'id_cliente' => $validados['id_cliente'],
    'modelo' => $validados['modelo'],
    'obs' => $validados['obs'],
    'data' => $data_formatada,
    'preco' => $validados['preco'],
    'preco_pago' => $validados['preco_pago'],
    'descricao' => $validados['descricao'],
    'dias_garantia' => $validados['dias_garantia'],
    'servico' => $validados['servico'], 
        ]);
        
        return response()->json([
            'status'=>200,
            'message'=>'Atualizado com sucesso',
            'ordem'=>$ordem
        ]);
    }

    
    public function destroy(string $id)
    {
        $ordem = Ordem::findOrFail($id);

        $ordem->delete();
        
        return response()->json([
            'status'=>200,
            'message'=>'Apagado com sucesso',
        ]);
    }
}
