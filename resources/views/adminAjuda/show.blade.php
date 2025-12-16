@extends('layouts.appMasterAdmin')
@section('title', 'Unir Votações - Detalhes da Ajuda')

@section('content')
<div class="container">
    <div class="mb-5">
        <h1 class="h2 fw-bolder text-dark">Detalhe da Ajuda: {{ $ajuda->titulo }}</h1>
        <p class="text-muted">Visualize as informações completas da ajuda armazenado no sistema.</p>
    </div>

    <div class="card shadow-lg border-0 rounded-3 stat-card">
        <div class="card-body p-4">
            <p><strong>Título:</strong> {{ $ajuda->titulo }}</p>
            <p><strong>Descrição:</strong> {!! $ajuda->descricao !!}</p>
            <p><strong>Sequência:</strong> {{ $ajuda->sequencia }}</p>
            <p><strong>Status:</strong>
                @if($ajuda->ativo)
                    <span class="badge bg-success">Ativo</span>
                @else
                    <span class="badge bg-secondary">Inativo</span>
                @endif
            </p>
        </div>
        <div class="card-footer text-end px-4 py-3">
            <a href="{{ route('admin.adminAjuda.index') }}" class="btn botao-voltar w-25"><i class="fa-solid fa-arrow-left me-1"></i> Voltar</a>
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