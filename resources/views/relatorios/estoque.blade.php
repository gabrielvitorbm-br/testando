<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center print:hidden">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Relatório de Posição de Estoque') }}
            </h2>
            <button onclick="window.print()" class="bg-gray-800 text-white px-4 py-2 rounded text-sm hover:bg-gray-700">
                Imprimir / PDF
            </button>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="flex justify-between items-center bg-white p-4 rounded shadow print:hidden">
                <form method="GET" action="{{ route('relatorios.estoque') }}" class="flex items-center space-x-2">
                    <input type="checkbox" name="baixo_estoque" value="1" id="baixo_estoque" {{ $apenasBaixoEstoque ? 'checked' : '' }} onchange="this.form.submit()" class="rounded border-gray-300">
                    <label for="baixo_estoque" class="text-sm font-medium text-gray-700">Exibir apenas produtos com estoque baixo</label>
                </form>

                <div class="text-right">
                    <span class="text-xs text-gray-500 uppercase block">Valor Total do Estoque</span>
                    <span class="text-xl font-bold text-gray-800">R$ {{ number_format($valorTotalEstoque, 2, ',', '.') }}</span>
                </div>
            </div>

            <div class="bg-white rounded shadow overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Produto / Peça</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Preço de Custo</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500">Preço de Venda</th>
                            <th class="px-4 py-3 text-center font-medium text-gray-500">Qtd. Atual</th>
                            <th class="px-4 py-3 text-center font-medium text-gray-500">Qtd. Mínima</th>
                            <th class="px-4 py-3 text-center font-medium text-gray-500">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($produtos as $produto)
                        @php $critico = $produto->quantidade <= $produto->quantidade_minima; @endphp
                        <tr class="{{ $critico ? 'bg-red-50' : '' }}">
                            <td class="px-4 py-3 font-semibold">{{ $produto->nome }}</td>
                            <td class="px-4 py-3">R$ {{ number_format($produto->preco_custo, 2, ',', '.') }}</td>
                            <td class="px-4 py-3">R$ {{ number_format($produto->preco_venda, 2, ',', '.') }}</td>
                            <td class="px-4 py-3 text-center font-bold">{{ $produto->quantidade }}</td>
                            <td class="px-4 py-3 text-center text-gray-500">{{ $produto->quantidade_minima }}</td>
                            <td class="px-4 py-3 text-center">
                                @if($critico)
                                    <span class="px-2 py-1 text-xs bg-red-200 text-red-800 rounded-full font-semibold">Reposição Necessária</span>
                                @else
                                    <span class="px-2 py-1 text-xs bg-green-200 text-green-800 rounded-full font-semibold">OK</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-gray-500">Nenhum produto encontrado.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>