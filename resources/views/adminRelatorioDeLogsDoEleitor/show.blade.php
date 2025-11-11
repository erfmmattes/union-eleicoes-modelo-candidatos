@extends('layouts.appMasterAdmin')
@section('title', 'Union Eleições - Detalhes do Log do Eleitor')

@section('content')
<div class="container">
    <!-- Cabeçalho da Página -->
    <div class="mb-5">
        <h1 class="h2 fw-bolder text-dark">Detalhes do Log do Eleitor: {{ $log->eleitor_nome ?? 'N/A' }}</h1>
        <p class="text-muted mt-1">
            Visualize todos os detalhes da ação registrada para este eleitor no sistema.
        </p>
    </div>

    <!-- Card de Detalhes -->
    <div class="card shadow-lg border-0">
        <div class="card-body p-4">
            <p><strong>Eleitor ID:</strong> {{ $log->eleitor_id ?? 'N/A' }}</p>
            <p><strong>Eleitor Nome:</strong> {{ $log->eleitor_nome ?? 'N/A' }}</p>
            <p><strong>Ação:</strong> {{ $log->acao ?? 'N/A' }}</p>
            
            <p><strong>Mensagem:</strong></p>
            <div class="border rounded p-3 bg-light">
                {{ $log->mensagem }}
            </div>

            <p class="mt-3"><strong>IP:</strong> {{ $log->ip ?? 'N/A' }}</p>
            <p><strong>Página:</strong> {{ $log->pagina ?? 'N/A' }}</p>
            <p><strong>Data de Criação:</strong> {{ $log->created_at->format('d/m/Y H:i') }}</p>
        </div>

        <div class="card-footer text-end px-4 py-3">
            <a href="{{ route('admin.adminRelatorioDeLogsDoEleitor.index') }}" class="btn bot-voltar w-25">
                <i class="fa-solid fa-arrow-left me-1"></i> Voltar
            </a>
        </div>
    </div>
</div>
@endsection

<!---------------- Início - Estilos CSS -------------------->
<style>
    .bot-voltar {
        background: linear-gradient(135deg, #6c757d, #6c757d);
        color: #fff !important;
        transition: all 0.3s ease-in-out;
    }

    .bot-voltar:hover {
        background: linear-gradient(135deg, #5a6268, #5a6268);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        font-weight: 600 !important;
    }

    .card {
        border-radius: 12px !important;
    }

    .card-body p {
        margin-bottom: 10px;
        font-size: 15px;
    }

    .border.rounded.p-3.bg-light {
        font-size: 15px;
        color: #212529;
        background-color: #f8f9fa !important;
        border-color: #e9ecef !important;
    }
</style>
<!---------------- Final - Estilos CSS -------------------->