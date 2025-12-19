@extends('layouts.appMasterAdmin')
@section('title', 'Unir Votações - Apuração ' . $etapa->nome)

@section('content')
<div class="container">

    <!-- Cabeçalho -->
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h1 class="h2 fw-bolder text-dark mb-0">{{ $etapa->nome }}</h1>
            <p class="text-muted mt-1 mb-0">Detalhes da apuração da etapa.</p>
        </div>
        <a href="{{ route('admin.adminApuracao.index') }}" class="btn botao-voltar btn-sm">
            <i class="fa-solid fa-arrow-left me-1"></i> Voltar
        </a>
    </div>

    <div class="row g-4">

        <!-- COLUNA ESQUERDA -->
        <div class="col-lg-6">

            <!-- Tabela -->
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title mb-3">Votos por candidato</h5>
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Candidato</th>
                                <th class="text-center">Votos</th>
                                <th class="text-center">%</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($apuracao as $item)
                                <tr>
                                    <td>{{ $item['candidato'] }}</td>
                                    <td class="text-center">{{ $item['quantidade'] }}</td>
                                    <td class="text-center">{{ $item['percentual'] }}%</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Total de votos -->
            <div class="card shadow-sm border-0 mt-4 bg-light">
                <div class="card-body d-flex align-items-center">
                    <i class="fa-solid fa-box-archive fa-2x icon-total me-3"></i>
                    <div>
                        <div class="text-muted small">Total de votos computados</div>
                        <div class="fs-3 fw-bold">{{ $apuracao->sum('quantidade') }}</div>
                    </div>
                </div>
            </div>

        </div>

        <!-- COLUNA DIREITA -->
        <div class="col-lg-6">

            <!-- Gráfico de Barras -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">Votos por Candidato</h5>
                    <canvas id="graficoBarras"></canvas>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
<!---------------- Início - Estillos CSS -------------------->
<style>
.botao-voltar {
    background: linear-gradient(135deg, #6c757d, #6c757d);
    color: #ffffff!important;
}
.botao-voltar:hover {
    background: linear-gradient(135deg, #6c757d, #6c757d);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    font-weight: 600 !important;
}
.icon-total {
    color: #183F77 !important;
}
</style>
<!---------------- Final - Estillos CSS -------------------->
<!---------------- Início - Scripts JavaScript e Jquery ------------------ -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const candidatos = @json($apuracao->pluck('candidato'));
    const votos = @json($apuracao->pluck('quantidade'));
    const percentuais = @json($apuracao->pluck('percentual'));

    // Gráfico de Barras
    new Chart(document.getElementById('graficoBarras'), {
        type: 'bar',
        data: {
            labels: candidatos,
            datasets: [{
                label: 'Quantidade de Votos',
                data: votos,
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const idx = context.dataIndex;
                            return `${context.dataset.label}: ${context.raw} (${percentuais[idx]}%)`;
                        }
                    }
                }
            },
            scales: { x: { beginAtZero: true } }
        }
    });
});
</script>
<!---------------- Final - Scripts JavaScript e Jquery ------------------ -->