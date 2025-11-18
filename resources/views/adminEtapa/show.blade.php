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
                    <label class="form-label fw-semibold">Setor</label>
                    <div class="form-control bg-light">{{ $etapa->setor->nome }}</div>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Sequência</label>
                    <div class="form-control bg-light">{{ $etapa->sequencia }}</div>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Status</label>
                    <div>
                        @if($etapa->status)
                            <span class="badge bg-success px-3 py-2">Ativa</span>
                        @else
                            <span class="badge bg-secondary px-3 py-2">Inativa</span>
                        @endif
                    </div>
                </div>
            </div>

            <hr>

            <div class="title-center mt-2">
                <h1>Escolhas Relacionadas</h1>
            </div>

            <hr>

            <div class="table-responsive tbop mt-4">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light bod-tabled">
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Sequência</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($escolhasRelacionadasEtapas as $etapa)
                            <tr>
                                <td class="fw-semibold text-dark">{{ $etapa->id }}</td>
                                <td>{{ $etapa->nome }}</td>
                                <td>{{ $etapa->sequencia }}</td>
                                <td>
                                    <div class="btn cur-n btn-sm toggle-status-btn {{ $etapa->status ? 'btn-success' : 'btn-secondary' }}">
                                        {{ $etapa->status ? 'Ativo' : 'Inativo' }}
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    Nenhuma etapa encontrada.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
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
    .title-center {
        text-align: center;
    }
    .title-center h1 {
        font-size: 25px;
        font-weight: 600 !important;
    }
    .cur-n {
        cursor: auto !important;
    }
    .tbop {
        border: var(--bs-border-width) solid var(--bs-border-color);
        margin: auto !important;
        width: 85% !important;
    }
</style>
<!---------------- Final - Estilos CSS -------------------->