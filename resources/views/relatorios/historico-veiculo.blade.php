<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center print:hidden">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Histórico de Manutenções por Veículo') }}
            </h2>
            <button onclick="window.print()" class="bg-gray-800 text-white px-4 py-2 rounded text-sm hover:bg-gray-700">
                Imprimir / PDF
            </button>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <form method="GET" action="{{ route('relatorios.historico-veiculo') }}" class="bg-white p-4 rounded shadow print:hidden flex gap-4 items-end">
                <div class="grow">
                    <label class="block text-sm font-medium text-gray-700">Selecione o Veículo</label>
                    <select name="veiculo_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        <option value="">-- Escolha um veículo --</option>
                        @foreach($veiculos as $veiculo)
                            <option value="{{ $veiculo->id }}" {{ $veiculoId == $veiculo->id ? 'selected' : '' }}>
                                {{ $veiculo->placa }} - {{ $veiculo->modelo }} ({{ $veiculo->marca }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Buscar Histórico</button>
            </form>

            @if($historico)
                <div class="space-y-4">
                    @forelse($historico as $os)
                    <div class="bg-white p-6 rounded shadow border-l-4 border-blue-600">
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">Ordem de Serviço #{{ $os->id }}</h3>
                                <p class="text-sm text-gray-500">Data: {{ $os->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <span class="px-3 py-1 text-xs rounded-full bg-gray-100 font-semibold uppercase">{{ $os->status }}</span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <h4 class="font-semibold text-gray-700 mb-1">Serviços Realizados:</h4>
                                <ul class="list-disc list-inside text-gray-600">
                                    @forelse($os->servicos as $servico)
                                        <li>{{ $servico->nome }} (R$ {{ number_format($servico->pivot->valor ?? $servico->preco, 2, ',', '.') }})</li>
                                    @empty
                                        <li class="text-gray-400">Nenhum serviço listado.</li>
                                    @endforelse
                                </ul>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-700 mb-1">Peças / Produtos Aplicados:</h4>
                                <ul class="list-disc list-inside text-gray-600">
                                    @forelse($os->produtos as $produto)
                                        <li>{{ $produto->nome }} x{{ $produto->pivot->quantidade }}</li>
                                    @empty
                                        <li class="text-gray-400">Nenhuma peça aplicada.</li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-t text-right">
                            <span class="text-sm text-gray-500">Total da OS: </span>
                            <span class="text-lg font-bold text-gray-800">R$ {{ number_format($os->valor_total, 2, ',', '.') }}</span>
                        </div>
                    </div>
                    @empty
                    <div class="bg-white p-6 rounded text-center text-gray-500">
                        Nenhuma ordem de serviço registrada para este veículo.
                    </div>
                    @endforelse
                </div>
            @endif

        </div>
    </div>
</x-app-layout>