<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center print:hidden">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Relatório de Faturamento') }}
            </h2>
            <button onclick="window.print()" class="bg-gray-800 text-white px-4 py-2 rounded text-sm hover:bg-gray-700">
                Imprimir / PDF
            </button>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Form de Filtros (Oculto na Impressão) -->
            <form method="GET" action="{{ route('relatorios.faturamento') }}" class="bg-white p-4 rounded shadow print:hidden grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Data Inicial</label>
                    <input type="date" name="data_inicio" value="{{ $dataInicio }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Data Final</label>
                    <input type="date" name="data_fim" value="{{ $dataFim }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">Todos</option>
                        <option value="concluida" {{ $status == 'concluida' ? 'selected' : '' }}>Concluída</option>
                        <option value="aberta" {{ $status == 'aberta' ? 'selected' : '' }}>Em Aberto</option>
                        <option value="cancelada" {{ $status == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Filtrar</button>
                </div>
            </form>

            <!-- Cards Resumo -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white p-4 rounded shadow border-l-4 border-green-500">
                    <span class="text-xs text-gray-500 uppercase">Total Faturado</span>
                    <p class="text-2xl font-bold text-gray-800">R$ {{ number_format($totalFaturado, 2, ',', '.') }}</p>
                </div>
                <div class="bg-white p-4 rounded shadow border-l-4 border-blue-500">
                    <span class="text-xs text-gray-500 uppercase">Total de OSs</span>
                    <p class="text-2xl font-bold text-gray-800">{{ $quantidadeOs }}</p>
                </div>
                <div class="bg-white p-4 rounded shadow border-l-4 border-purple-500">
                    <span class="text-xs text-gray-500 uppercase">Ticket Médio</span>
                    <p class="text-2xl font-bold text-gray-800">R$ {{ number_format($ticketMedio, 2, ',', '.') }}</p>
                </div>
            </div>

            <!-- Tabela de Dados -->
            <div class="bg-white rounded shadow overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-gray-500"># OS</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Data</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Cliente</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Veículo</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Status</th>
                            <th class="px-4 py-3 text-right font-medium text-gray-500">Valor Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($ordens as $os)
                        <tr>
                            <td class="px-4 py-3 font-semibold">#{{ $os->id }}</td>
                            <td class="px-4 py-3">{{ $os->created_at->format('d/m/Y') }}</td>
                            <td class="px-4 py-3">{{ $os->cliente->name ?? 'N/A' }}</td>
                            <td class="px-4 py-3">{{ $os->veiculo->placa ?? 'N/A' }}</td>
                            <td class="px-4 py-3 capitalize">{{ $os->status }}</td>
                            <td class="px-4 py-3 text-right font-bold">R$ {{ number_format($os->valor_total, 2, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-gray-500">Nenhum registro encontrado para os filtros selecionados.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>