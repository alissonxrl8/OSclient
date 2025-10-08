<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Garantia;
use App\Models\Ordem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Laravel\Sanctum\PersonalAccessToken; // para validar token via query string

class GarantiaController extends Controller
{
    // GET /api/garantias?cliente=ID
    public function index(Request $request)
    {
        $token = $request->bearerToken();
        if (!$token) return response()->json(['status'=>false,'message'=>'token não fornecido'],401);

        $user = auth('sanctum')->user();
        if(!$user) return response()->json(['status'=>false,'message'=>'token inválido'],401);

        $clienteId = $request->query('cliente');

        $garantias = Garantia::where('id_user', $user->id)
                             ->when($clienteId, fn($q) => $q->where('id_cliente', $clienteId))
                             ->get();

        $garantiasFormatadas = $garantias->map(function($garantia) {
            $ordem = Ordem::find($garantia->id_orcamento);
            $dias_garantia = $ordem ? (int) ($ordem->dias_garantia ?? 0) : 0;

            $data_garantia = Carbon::parse($garantia->data_garantia)->startOfDay();
            $data_final = $data_garantia->copy()->addDays($dias_garantia)->startOfDay();
            $dias_restantes = Carbon::now()->startOfDay()->diffInDays($data_final, false);
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

    // GET /api/baixar-garantia/{id}?token=xxxx
    public function baixarGarantia(Request $request, $id)
    {
        // Pega token via query string
        $token = $request->query('token');
        if (!$token) return response()->json(['status'=>false,'message'=>'token não fornecido'],401);

        // Valida token com Sanctum
        $tokenModel = PersonalAccessToken::findToken($token);
        if (!$tokenModel) return response()->json(['status'=>false,'message'=>'token inválido'],401);

        $user = $tokenModel->tokenable; // usuário autenticado

        $garantia = Garantia::findOrFail($id);
        if ($garantia->id_user != $user->id) return response()->json(['status'=>false,'message'=>'Acesso negado'],403);

        $ordem = Ordem::findOrFail($garantia->id_orcamento);
        $cliente = Cliente::findOrFail($garantia->id_cliente);

        $dias_garantia = (int) ($ordem->dias_garantia ?? 0);
        $data_inicio = Carbon::parse($garantia->data_garantia);
        $data_final = $data_inicio->copy()->addDays($dias_garantia);

        $dados = [
            'cliente' => $cliente,
            'ordem' => $ordem,
            'garantia' => $garantia,
            'data_inicio' => $data_inicio->format('d/m/Y'),
            'data_final' => $data_final->format('d/m/Y'),
            'dias_garantia' => $dias_garantia,
        ];

        $pdf = Pdf::loadView('pdf.garantia', $dados);
        return $pdf->download('garantia_'.$cliente->nome.'.pdf');
    }
}
