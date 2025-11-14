@extends('layouts.appMasterAdmin')
@section('title', 'Union Eleições - Detalhes da Etapa')

@section('content')
<div class="container">

    <!-- Cabeçalho -->
    <div class="mb-5">
        <h1 class="h2 fw-bolder text-dark">Detalhes da Etapa</h1>
        <p class="text-muted mt-1">
            Visualize as informações completas da etapa selecionada.
        </p>
    </div>

    <!-- Card de Visualização -->
    <div class="card shadow-lg border-0 rounded-3 stat-card">
        <div class="card-body p-4">

            <!-- Detalhes -->
            <div class="row mb-3">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Nome da Etapa</label>
                    <div class="form-control bg-light">{{ $etapa->nome }}</div>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Sequência</label>
                    <div class="form-control bg-light">{{ $etapa->sequencia }}</div>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Status</label>
                <div>
                    @if($etapa->status)
                        <span class="badge bg-success px-3 py-2">Ativa</span>
                    @else
                        <span class="badge bg-secondary px-3 py-2">Inativa</span>
                    @endif
                </div>
            </div>

            <!-- Botões -->
            <div class="d-flex justify-content-end gap-3 mt-4">

                <a href="{{ route('admin.adminEtapa.index') }}" class="btn bot-cancelar-ele px-4">
                    <i class="fa-solid fa-arrow-left me-1"></i> Voltar
                </a>

            </div>

        </div>
    </div>

</div>
@endsection
<!---------------- Início - Estilos CSS -------------------->
<style>
    .bot-atualizar {
        background: linear-gradient(135deg, #183F77, #4A90E2);
        color: #fff !important;
    }
    .bot-atualizar:hover {
        background: linear-gradient(135deg, #122b55, #3570c2);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        font-weight: 600 !important;
    }
    .bot-cancelar-ele {
        background: linear-gradient(135deg, #6c757d, #6c757d);
        color: #fff !important;
    }
    .bot-cancelar-ele:hover {
        background: linear-gradient(135deg, #5c636a, #5c636a);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        font-weight: 600 !important;
    }
</style>
<!---------------- Final - Estilos CSS -------------------->