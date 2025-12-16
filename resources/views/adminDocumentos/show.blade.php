@extends('layouts.appMasterAdmin')
@section('title', 'Unir Votações - Detalhes do Documento')

@section('content')
<div class="container">
    <!-- Page Header -->
    <div class="mb-5">
        <h1 class="h2 fw-bolder text-dark">Detalhes do Documento: {{ $documento->titulo }}</h1>
        <p class="text-muted mt-1">
            Visualize as informações completas do documento armazenado no sistema.
        </p>
    </div>

    <!-- Card de Detalhes -->
    <div class="card shadow-lg border-0 rounded-3 stat-card">
        <div class="card-body p-4">
            <div class="mb-3">
                <p class="mb-1"><strong>Título:</strong></p>
                <p class="text-dark">{{ $documento->titulo }}</p>
            </div>

            <div class="mb-3">
                <p class="mb-1"><strong>Descrição:</strong></p>
                <p class="text-dark">{{ $documento->descricao ?? '—' }}</p>
            </div>

            <div class="mb-3">
                <p class="mb-1"><strong>Tipo:</strong></p>
                <p class="text-dark">{{ $documento->tipo ?? '—' }}</p>
            </div>

            <div class="mb-3">
                <p class="mb-1"><strong>Arquivo:</strong></p>
                @if($documento->arquivo && $documento->caminho)
                    <a href="{{ asset('storage/' . $documento->caminho) }}" target="_blank" class="text-decoration-none">
                        <i class="fas fa-download me-1"></i> {{ $documento->arquivo }}
                    </a>
                @else
                    <span class="text-muted">Nenhum arquivo anexado</span>
                @endif
            </div>

            <div class="mb-3">
                <p class="mb-1"><strong>Status:</strong></p>
                @if($documento->ativo)
                    <span class="badge bg-success px-3 py-2">Ativo</span>
                @else
                    <span class="badge bg-secondary px-3 py-2">Inativo</span>
                @endif
            </div>

            <div class="mb-3">
                <p class="mb-1"><strong>Sequência:</strong></p>
                <p class="text-dark">{{ $documento->sequencia ?? '—' }}</p>
            </div>
        </div>

        <!-- Rodapé -->
         <div class="card-footer text-end px-4 py-3">
            <a href="{{ route('admin.adminDocumentos.index') }}" class="btn botao-voltar w-25"><i class="fa-solid fa-arrow-left me-1"></i> Voltar</a>
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
</style>
<!---------------- Final - Estillos CSS -------------------->