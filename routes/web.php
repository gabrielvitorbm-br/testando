<?php

namespace App\Http\Controllers;

use App\Models\Agendamento;
use App\Models\Pedido;
use App\Models\Produto;
use App\Models\Servico;
use App\Models\Veiculo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RelatorioController extends Controller
{
    /**
     * Dashboard / Painel Central de Relatórios
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
        $dataInicio = $request->input('data_inicio', Carbon::now('America/Sao_Paulo')->startOfMonth()->format('Y-m-d'));
        $dataFim = $request->input('data_fim', Carbon::now('America/Sao_Paulo')->endOfMonth()->format('Y-m-d'));
        $status = $request->input('status');

        $query = Pedido::with(['cliente', 'veiculo'])
            ->whereBetween('created_at', [$dataInicio . ' 00:00:00', $dataFim . ' 23:59:59']);

        if ($status) {
            $query->where('status', $status);
        }

        $pedidos = $query->latest()->get();

        // Considera o campo de valor total do pedido se existente ou a soma dos itens
        $totalFaturado = $pedidos->whereIn('status', ['concluido', 'finalizado', 'entregue'])->sum('valor_total');
        $quantidadePedidos = $pedidos->count();
        $ticketMedio = $quantidadePedidos > 0 ? $totalFaturado / $quantidadePedidos : 0;

        return view('relatorios.faturamento', compact(
            'pedidos', 'dataInicio', 'dataFim', 'status', 'totalFaturado', 'quantidadePedidos', 'ticketMedio'
        ));
    }

    /**
     * Relatório de Desempenho de Serviços
     */
    public function servicos(Request $request)
    {
        $dataInicio = $request->input('data_inicio', Carbon::now('America/Sao_Paulo')->startOfMonth()->format('Y-m-d'));
        $dataFim = $request->input('data_fim', Carbon::now('America/Sao_Paulo')->endOfMonth()->format('Y-m-d'));

        $servicos = Servico::withCount(['pedidos' => function ($q) use ($dataInicio, $dataFim) {
            $q->whereBetween('pedidos.created_at', [$dataInicio . ' 00:00:00', $dataFim . ' 23:59:59']);
        }])->get();

        return view('relatorios.servicos', compact('servicos', 'dataInicio', 'dataFim'));
    }

    /**
     * Relatório de Posição e Alertas de Estoque
     */
    public function estoque(Request $request)
    {
        $apenasBaixoEstoque = $request->boolean('baixo_estoque');

        $query = Produto::query();

        if ($apenasBaixoEstoque) {
            $query->whereColumn('estoque', '<=', 'estoque_minimo');
        }

        $produtos = $query->orderBy('estoque', 'asc')->get();
        $valorTotalEstoque = $produtos->sum(function ($p) {
            return $p->estoque * ($p->preco_custo ?? $p->preco ?? 0);
        });

        return view('relatorios.estoque', compact('produtos', 'apenasBaixoEstoque', 'valorTotalEstoque'));
    }

    /**
     * Histórico de Manutenções do Veículo
     */
    public function historicoVeiculo(Request $request)
    {
        $user = Auth::user();

        // Se o perfil for de cliente (CLIENTE), restringe apenas aos seus veículos
        $veiculosQuery = Veiculo::query();
        if ($user->role === 'CLIENTE') {
            $veiculosQuery->where('cliente_id', $user->id);
        }
        $veiculos = $veiculosQuery->orderBy('placa')->get();

        $veiculoId = $request->input('veiculo_id');
        $historico = null;

        if ($veiculoId) {
            $veiculo = $veiculos->firstWhere('id', (int) $veiculoId);
            if ($veiculo) {
                $historico = Pedido::with(['servicos', 'produtos'])
                    ->where('veiculo_id', $veiculoId)
                    ->latest()
                    ->get();
            }
        }

        return view('relatorios.historico-veiculo', compact('veiculos', 'veiculoId', 'historico'));
    }
}