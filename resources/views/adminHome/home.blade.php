@extends('layouts.appMasterAdmin')
@section('title', 'Unir Votações - Home')
@section('content')
<div class="container">
    <div class="container-fluid">

        <!-- Cabeçalho -->
        <div class="mb-5">
            <h1 class="h2 fw-bolder text-dark">Visão Geral do Dashboard</h1>
            <p class="text-muted mt-1">Aqui você acompanha o desempenho da eleição em tempo real.</p>
        </div>

        <!-- Gráficos principais -->
        <div class="row g-4 mb-5">
            <!-- Stat Card 1 -->
            <div class="col-12 col-md-6 col-xl-3">
                <div class="card shadow-lg border-0 stat-card p-3">
                    <div class="card-body">
                        <p class="card-title text-muted text-center mb-1 small fw-semibold">Total geral</p>
                        <h3 class="card-text fw-bolder text-primary text-center">{{ $totalUsuariosAtivos }}</h3>
                    </div>
                </div>
            </div>

            <!-- Stat Card 2 -->
            <div class="col-12 col-md-6 col-xl-3">
                <div class="card shadow-lg border-0 stat-card p-3">
                    <div class="card-body">
                        <p class="card-title text-muted text-center mb-1 small fw-semibold">Total de Votantes</p>
                        <h3 class="card-text fw-bolder text-warning text-center">{{ $totalVotantes }}</h3>
                    </div>
                </div>
            </div>

            <!-- Stat Card 3 -->
            <div class="col-12 col-md-6 col-xl-3">
                <div class="card shadow-lg border-0 stat-card p-3">
                    <div class="card-body">
                        <p class="card-title text-muted text-center mb-1 small fw-semibold">Total de Não Votantes</p>
                        <h3 class="card-text fw-bolder text-primary text-center">{{ $totalNaoVotantes }}</h3>
                    </div>
                </div>
            </div>

            <!-- Stat Card 4 -->
            <div class="col-12 col-md-6 col-xl-3">
                <div class="card shadow-lg border-0">
                    <div class="card-body text-center">
                        <h6 class="fw-bold text-muted mb-2">Percentual de Votantes</h6>
                        <div style="position: relative;">
                            <canvas id="graficoPercentual" height="140"></canvas>
                            <div id="percentualCentro"
                                style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-weight: bold; font-size: 22px; color: #ff9800;">
                                {{ $percentualVotantes }}%<br />
                                Votantes
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @php
            use Carbon\Carbon;

            $dataInicio = Carbon::parse($configuracaoData->data_hora_inicio_eleicao);
            $dataFinal  = Carbon::parse($configuracaoData->data_hora_final_eleicao);
        @endphp
        @if (!$dataInicio->isSameDay($dataFinal))
            <!-- Gráfico de Votos por Dia -->
            <div class="row mb-5">
                <div class="col-12">
                    <div class="card shadow-lg border-0">
                        <div class="card-body">
                            <h5 class="fw-bold mb-3 text-dark">Votos por Dia</h5>
                            <canvas id="graficoVotosPorDia" height="120"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Gráfico comparativo -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="card shadow-lg border-0">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3 text-dark">Comparativo Geral</h5>
                        <canvas id="graficoComparativo" height="120"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
<!---------------- Início - Scripts JavaScript -------------------->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    // ==== Dados do Laravel ====
    const totalUsuariosAtivos = parseInt("{{ $totalUsuariosAtivos ?? 0 }}");
    const totalVotantes = parseInt("{{ $totalVotantes ?? 0 }}");
    const totalNaoVotantes = parseInt("{{ $totalNaoVotantes ?? 0 }}");
    const percentualVotantes = parseFloat("{{ $percentualVotantes ?? 0 }}");
    const percentualNaoVotantes = 100 - percentualVotantes;

    // === 1. Gráfico Percentual (Donut central com %) ===
    new Chart(document.getElementById('graficoPercentual'), {
        type: 'doughnut',
        data: {
            labels: ['Votantes (%)', 'Não votantes (%)'],
            datasets: [{
                data: [percentualVotantes, percentualNaoVotantes],
                backgroundColor: ['#ff9800', '#007bff'],
                borderWidth: 2,
                hoverOffset: 8
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            cutout: '70%',
            animation: { duration: 1200 }
        }
    });

    // === 2. Gráfico Comparativo (Linha) ===
    new Chart(document.getElementById('graficoComparativo'), {
        type: 'line',
        data: {
            labels: ['Usuários Ativos', 'Votantes', 'Não Votantes'],
            datasets: [{
                label: 'Distribuição Geral (%)',
                data: [
                    100,
                    percentualVotantes,
                    percentualNaoVotantes
                ],
                borderColor: '#007bff',
                backgroundColor: 'rgba(0,123,255,0.2)',
                fill: true,
                tension: 0.4,
                pointRadius: 6,
                pointBackgroundColor: '#ff9800',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: { callback: value => value + '%' }
                }
            },
            plugins: {
                legend: { position: 'top' },
                tooltip: {
                    callbacks: {
                        label: ctx => `${ctx.parsed.y.toFixed(1)}%`
                    }
                }
            },
            animation: { duration: 1500, easing: 'easeOutQuart' }
        }
    });

    // === 3. Gráfico de Votos por Dia (Barra + Linha + Total) ===
    const votosPorDia = @json($totalVotantesPorDia ?? []);
    const dias = Object.keys(votosPorDia);
    const votos = Object.values(votosPorDia);

    // Calcula o total geral de votos
    const totalGeral = votos.reduce((acc, val) => acc + val, 0);

    // Exibe o total geral acima do gráfico
    const totalDiv = document.createElement('div');
    totalDiv.classList.add('text-center', 'fw-bold', 'mb-2');
    totalDiv.innerHTML = `Total geral de votos: <span style="color:#ff9800;">${totalGeral}</span>`;
    document.getElementById('graficoVotosPorDia').parentNode.prepend(totalDiv);

    new Chart(document.getElementById('graficoVotosPorDia'), {
        type: 'bar',
        data: {
            labels: dias,
            datasets: [
                {
                    type: 'bar',
                    label: 'Votos Diários',
                    data: votos,
                    backgroundColor: '#007bff88',
                    borderColor: '#007bff',
                    borderWidth: 1,
                    borderRadius: 6
                },
                {
                    type: 'line',
                    label: 'Tendência',
                    data: votos,
                    borderColor: '#ff9800',
                    borderWidth: 2,
                    pointRadius: 5,
                    pointBackgroundColor: '#ff9800',
                    tension: 0.3,
                    fill: false
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' },
                tooltip: {
                    callbacks: {
                        label: ctx => `${ctx.dataset.label}: ${ctx.parsed.y} votos`
                    }
                },
                // Mostra os números sobre as barras
                datalabels: {
                    color: '#333',
                    anchor: 'end',
                    align: 'top',
                    font: { weight: 'bold' },
                    formatter: value => value,
                    display: ctx => ctx.dataset.type === 'bar'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: { display: true, text: 'Quantidade de Votos' }
                },
                x: {
                    title: { display: true, text: 'Data' }
                }
            },
            animation: { duration: 1200, easing: 'easeOutQuart' }
        },
        plugins: [ChartDataLabels]
    });
});
</script>
<!---------------- Final - Scripts JavaScript -------------------->