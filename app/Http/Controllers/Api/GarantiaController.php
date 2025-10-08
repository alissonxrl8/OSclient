<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Garantia;
use App\Models\Ordem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class GarantiaController extends Controller
{
    // Lista garantias de um cliente
    public function index(Request $request)
    {
        $clienteId = $request->query('cliente'); // pega id_cliente da query
        if (!$clienteId) {
            return response()->json(['status'=>400,'mensagem'=>'ID do cliente não informado']);
        }

        $garantias = Garantia::where('id_cliente', $clienteId)->get();

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

    // Detalhes de uma garantia
    public function show(string $id)
    {
        $garantia = Garantia::findOrFail($id);
        $ordem = Ordem::find($garantia->id_orcamento);
        $dias_garantia = $ordem ? (int) ($ordem->dias_garantia ?? 0) : 0;

        $data_garantia = Carbon::parse($garantia->data_garantia)->startOfDay();
        $data_final = $data_garantia->copy()->addDays($dias_garantia)->startOfDay();
        $dias_restantes = Carbon::now()->startOfDay()->diffInDays($data_final, false);
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

    // Criar nova garantia
    public function store(Request $request)
    {
        $validados = $request->validate([
            'data_garantia' => 'required|date',
            'id_orcamento' => 'required|numeric'
        ]);

        $ordem = Ordem::find($validados['id_orcamento']);
        if (!$ordem) {
            return response()->json(['status'=>404,'mensagem'=>'Ordem não encontrada']);
        }

        $data_formatada = Carbon::parse($validados['data_garantia'])->format('Y-m-d');

        $garantia = Garantia::create([
            'data_garantia' => $data_formatada,
            'id_cliente' => $ordem->id_cliente,    // <- pega o cliente da ordem
            'id_user' => Auth::id(),               // quem cadastrou
            'id_orcamento' => $ordem->id
        ]);

        return response()->json([
            'status'=>201,
            'mensagem'=>'Garantia cadastrada com sucesso',
            'garantia' => $garantia
        ]);
    }

    // Atualiza garantia
    public function update(Request $request, string $id)
    {
        $garantia = Garantia::findOrFail($id);
        $validados = $request->validate([
            'data_garantia' => 'sometimes|date',
            'id_orcamento' => 'sometimes|numeric',
            'id_cliente' => 'sometimes|numeric',
            'id_user' => 'sometimes|numeric'
        ]);
        $garantia->update($validados);
        return response()->json(['status'=>200,'mensagem'=>'Garantia atualizada com sucesso']);
    }

    // Deleta garantia
    public function destroy(string $id)
    {
        $garantia = Garantia::findOrFail($id);
        $garantia->delete();
        return response()->json(['status'=>200,'mensagem'=>'Garantia deletada com sucesso']);
    }

    // Baixar garantia em PDF
    public function baixarGarantia($id)
    {
        $garantia = Garantia::findOrFail($id);
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
