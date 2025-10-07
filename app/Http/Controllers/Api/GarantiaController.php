<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Garantia;
use App\Models\Ordem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GarantiaController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $garantias = Garantia::where('id_cliente', $user->id)->get();

        $garantiasFormatadas = $garantias->map(function($garantia) {
            $ordem = Ordem::find($garantia->id_orcamento);
            $dias_garantia = $ordem->dias_garantia ?? 0;

            $data_garantia = Carbon::parse($garantia->data_garantia);
            $data_final = $data_garantia->copy()->addDays($dias_garantia);
            $dias_restantes = Carbon::now()->diffInDays($data_final, false);
            $expirada = $dias_restantes < 0;

            return [
                'id' => $garantia->id,
                'id_orcamento' => $garantia->id_orcamento,
                'id_cliente' => $garantia->id_cliente,
                'id_user' => $garantia->id_user,
                'data_garantia' => $data_garantia->format('Y-m-d'),
                'data_final' => $data_final->format('Y-m-d'),
                'dias_restantes' => $dias_restantes,
                'expirada' => $expirada
            ];
        });

        return response()->json([
            'status' => 200,
            'garantias' => $garantiasFormatadas
        ]);
    }

    public function show(string $id)
    {
        $garantia = Garantia::findOrFail($id);
        $ordem = Ordem::find($garantia->id_orcamento);
        $dias_garantia = $ordem->dias_garantia ?? 0;

        $data_garantia = Carbon::parse($garantia->data_garantia);
        $data_final = $data_garantia->copy()->addDays($dias_garantia);
        $dias_restantes = Carbon::now()->diffInDays($data_final, false);
        $expirada = $dias_restantes < 0;

        return response()->json([
            'status' => 200,
            'garantia' => [
                'id' => $garantia->id,
                'id_orcamento' => $garantia->id_orcamento,
                'id_cliente' => $garantia->id_cliente,
                'id_user' => $garantia->id_user,
                'data_garantia' => $data_garantia->format('Y-m-d'),
                'data_final' => $data_final->format('Y-m-d'),
                'dias_restantes' => $dias_restantes,
                'expirada' => $expirada
            ]
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $validados = $request->validate([
            'data_garantia'=>'required|date',
            'id_orcamento'=>'required|numeric'
        ]);

        $data_formatada = Carbon::parse($validados['data_garantia'])->format('Y-m-d');

        $garantia = Garantia::create([
            'data_garantia' => $data_formatada,
            'id_cliente' => $user->id,
            'id_user' => $user->id,
            'id_orcamento' => $validados['id_orcamento']
        ]);

        $ordem = Ordem::find($garantia->id_orcamento);
        $dias_garantia = $ordem->dias_garantia ?? 0;

        $data_garantia = Carbon::parse($garantia->data_garantia);
        $data_final = $data_garantia->copy()->addDays($dias_garantia);
        $dias_restantes = Carbon::now()->diffInDays($data_final, false);
        $expirada = $dias_restantes < 0;

        return response()->json([
            'status' => 201,
            'mensagem' => 'Garantia cadastrada com sucesso',
            'garantia' => [
                'id' => $garantia->id,
                'id_orcamento' => $garantia->id_orcamento,
                'id_cliente' => $garantia->id_cliente,
                'id_user' => $garantia->id_user,
                'data_garantia' => $data_garantia->format('Y-m-d'),
                'data_final' => $data_final->format('Y-m-d'),
                'dias_restantes' => $dias_restantes,
                'expirada' => $expirada
            ]
        ]);
    }

    public function update(Request $request, string $id)
    {
        $garantia = Garantia::findOrFail($id);

        $validados = $request->validate([
            'data_garantia'=>'sometimes|date',
            'id_orcamento'=>'sometimes|numeric',
            'id_cliente'=>'sometimes|numeric',
            'id_user'=>'sometimes|numeric'
        ]);

        if(isset($validados['data_garantia'])){
            $garantia->data_garantia = Carbon::parse($validados['data_garantia'])->format('Y-m-d');
        }
        if(isset($validados['id_orcamento'])){
            $garantia->id_orcamento = $validados['id_orcamento'];
        }
        if(isset($validados['id_cliente'])){
            $garantia->id_cliente = $validados['id_cliente'];
        }
        if(isset($validados['id_user'])){
            $garantia->id_user = $validados['id_user'];
        }

        $garantia->save();

        $ordem = Ordem::find($garantia->id_orcamento);
        $dias_garantia = $ordem->dias_garantia ?? 0;

        $data_garantia = Carbon::parse($garantia->data_garantia);
        $data_final = $data_garantia->copy()->addDays($dias_garantia);
        $dias_restantes = Carbon::now()->diffInDays($data_final, false);
        $expirada = $dias_restantes < 0;

        return response()->json([
            'status' => 200,
            'mensagem' => 'Garantia atualizada com sucesso',
            'garantia' => [
                'id' => $garantia->id,
                'id_orcamento' => $garantia->id_orcamento,
                'id_cliente' => $garantia->id_cliente,
                'id_user' => $garantia->id_user,
                'data_garantia' => $data_garantia->format('Y-m-d'),
                'data_final' => $data_final->format('Y-m-d'),
                'dias_restantes' => $dias_restantes,
                'expirada' => $expirada
            ]
        ]);
    }

    public function destroy(string $id)
    {
        $garantia = Garantia::findOrFail($id);
        $garantia->delete();

        return response()->json([
            'status' => 200,
            'mensagem' => 'Garantia deletada com sucesso'
        ]);
    }
}
