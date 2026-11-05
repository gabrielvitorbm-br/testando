<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Módulo de Relatórios') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            
            @if(in_array(Auth::user()->role, ['ADM', 'FUNC']))
            <!-- Card Faturamento -->
            <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
                <h3 class="text-lg font-bold text-gray-800 mb-2">Relatório de Faturamento</h3>
                <p class="text-sm text-gray-600 mb-4">Consulte o faturamento por período, status das OSs e ticket médio.</p>
                <a href="{{ route('relatorios.faturamento') }}" class="inline-block bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700">Acessar</a>
            </div>

            <!-- Card Serviços -->
            <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
                <h3 class="text-lg font-bold text-gray-800 mb-2">Desempenho de Serviços</h3>
                <p class="text-sm text-gray-600 mb-4">Veja quais serviços são mais solicitados na oficina.</p>
                <a href="{{ route('relatorios.servicos') }}" class="inline-block bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700">Acessar</a>
            </div>

            <!-- Card Estoque -->
            <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
                <h3 class="text-lg font-bold text-gray-800 mb-2">Posição de Estoque</h3>
                <p class="text-sm text-gray-600 mb-4">Controle de peças em estoque e alertas de reposição mínima.</p>
                <a href="{{ route('relatorios.estoque') }}" class="inline-block bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700">Acessar</a>
            </div>
            @endif

            <!-- Card Histórico por Veículo -->
            <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
                <h3 class="text-lg font-bold text-gray-800 mb-2">Histórico do Veículo</h3>
                <p class="text-sm text-gray-600 mb-4">Consulte todas as manutenções e peças aplicadas por veículo.</p>
                <a href="{{ route('relatorios.historico-veiculo') }}" class="inline-block bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700">Acessar</a>
            </div>

        </div>
    </div>
</x-app-layout>