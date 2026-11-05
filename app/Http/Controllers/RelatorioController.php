<?php

namespace App\Http\Controllers;

use App\Models\OrdemServico;
use App\Models\Produto;
use App\Models\Servico;
use App\Models\Veiculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RelatorioController extends Controller
{
    /**
     * Dashboard / Menu Central de Relatórios
     */
    public function index()
    {
        return view('relatorios.index');
    }

    /**
     * Relatório de Faturamento por Período
     */
    public function faturamento(Request $request)
    {
        $dataInicio = $request->input('data_inicio', now()->startOfMonth()->toDateString());
        $dataFim = $request->input('data_fim', now()->endOfMonth()->toDateString());
        $status = $request->input('status');

        $query = OrdemServico::with(['cliente', 'veiculo'])
            ->whereBetween('created_at', [$dataInicio . ' 00:00:00', $dataFim . ' 23:59:59']);

        if ($status) {
            $query->where('status', $status);
        }

        $ordens = $query->latest()->get();
        
        $totalFaturado = $ordens->where('status', 'concluida')->sum('valor_total');
        $quantidadeOs = $ordens->count();
        $ticketMedio = $quantidadeOs > 0 ? $totalFaturado / $quantidadeOs : 0;

        return view('relatorios.faturamento', compact(
            'ordens', 'dataInicio', 'dataFim', 'status', 'totalFaturado', 'quantidadeOs', 'ticketMedio'
        ));
    }

    /**
     * Relatório de Desempenho de Serviços
     */
    public function servicos(Request $request)
    {
        $dataInicio = $request->input('data_inicio', now()->startOfMonth()->toDateString());
        $dataFim = $request->input('data_fim', now()->endOfMonth()->toDateString());

        $servicos = Servico::withCount(['ordensServico' => function ($q) use ($dataInicio, $dataFim) {
            $q->whereBetween('ordem_servicos.created_at', [$dataInicio . ' 00:00:00', $dataFim . ' 23:59:59']);
        }])->get();

        return view('relatorios.servicos', compact('servicos', 'dataInicio', 'dataFim'));
    }

    /**
     * Relatório de Estoque e Consumo de Peças
     */
    public function estoque(Request $request)
    {
        $apenasBaixoEstoque = $request->boolean('baixo_estoque');

        $query = Produto::query();

        if ($apenasBaixoEstoque) {
            $query->whereColumn('quantidade', '<=', 'quantidade_minima');
        }

        $produtos = $query->orderBy('quantidade', 'asc')->get();
        
        // Multiplicação corrigida
        $valorTotalEstoque = $produtos->sum(fn($p) => $p->quantidade * ($p->preco_custo ?? $p->preco ?? 0));

        return view('relatorios.estoque', compact('produtos', 'apenasBaixoEstoque', 'valorTotalEstoque'));
    }

    /**
     * Histórico de Manutenções do Veículo
     */
    public function historicoVeiculo(Request $request)
    {
        $user = Auth::user();
        
        // Trata a verificação de perfil de forma flexível (role ou tipo)
        $isCliente = ($user->role ?? $user->tipo ?? '') === 'CLIENTE' || ($user->tipo ?? '') === 'cliente';

        $veiculosQuery = Veiculo::query();
        if ($isCliente) {
            $veiculosQuery->where('user_id', $user->id);
        }
        $veiculos = $veiculosQuery->get();

        $veiculoId = $request->input('veiculo_id');
        $historico = null;

        if ($veiculoId) {
            $veiculo = $veiculos->firstWhere('id', $veiculoId);
            if ($veiculo) {
                $historico = OrdemServico::with(['servicos', 'produtos'])
                    ->where('veiculo_id', $veiculoId)
                    ->latest()
                    ->get();
            }
        }

        return view('relatorios.historico-veiculo', compact('veiculos', 'veiculoId', 'historico'));
    }
}