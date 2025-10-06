<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Garantia;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GarantiaController extends Controller
{
   
    public function index()
    {
        $user = Auth::user();
        $garantias = Garantia::where('id_user', $user->id)->get();

        return response()->json([
            'status'=>200,
            'ordens'=>$garantias
        ]);
    }

    
    public function store(Request $request)
    {
        $user = Auth::user();


        $validados = $request->validate([
            'data_garantia'=>'required|date',
            'id_orcamento'=>'required|numeric'
        ]);

        $data_formatada = Carbon::createFromFormat('d/m/Y', $validados['data_garantia'])->format('Y-m-d');

        $garantia = Garantia::create([
            'data_garantia'=>$data_formatada,
            'id_cliente'=>$user->id,
            'id_orcamento'=>$validados['id_orcamento']
        ]);
        
    }

    
    public function show(string $id)
    {
        //
    }


    public function update(Request $request, string $id)
    {
        //
    }

    
    public function destroy(string $id)
    {
        //
    }
}
