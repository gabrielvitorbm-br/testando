<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center print:hidden">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Relatório de Desempenho de Serviços') }}
            </h2>
            <button onclick="window.print()" class="bg-gray-800 text-white px-4 py-2 rounded text-sm hover:bg-gray-700">
                Imprimir / PDF
            </button>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <form method="GET" action="{{ route('relatorios.servicos') }}" class="bg-white p-4 rounded shadow print:hidden grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Data Inicial</label>
                    <input type="date" name="data_inicio" value="{{ $dataInicio }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Data Final</label>
                    <input type="date" name="data_fim" value="{{ $dataFim }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Filtrar</button>
                </div>
            </form>

            <div class="bg-white rounded shadow overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Serviço</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Preço Base</th>
                            <th class="px-4 py-3 text-center font-medium text-gray-500">Qtd. Executada (Período)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($servicos as $servico)
                        <tr>
                            <td class="px-4 py-3 font-semibold">{{ $servico->nome }}</td>
                            <td class="px-4 py-3">R$ {{ number_format($servico->preco, 2, ',', '.') }}</td>
                            <td class="px-4 py-3 text-center font-bold">{{ $servico->ordens_servico_count }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-4 py-6 text-center text-gray-500">Nenhum serviço cadastrado.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>