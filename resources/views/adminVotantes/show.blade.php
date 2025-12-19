@extends('layouts.appMasterAdmin')
@section('title', 'Unir Votações - Detalhes do Votante')

@section('content')
<div class="container">
    <!-- Page Header -->
    <div class="mb-5">
        <h1 class="h2 fw-bolder text-dark">Detalhes do Votante: {{ $votante->nome }} - {{ formatarEtapa($votante->etapa) }}ª - Etapa</h1>
        <p class="text-muted mt-1">
            Visualize as informações completas do votante armazenado no sistema.
        </p>
    </div>

    <!-- Card de Detalhes -->
    <div class="card shadow-lg border-0 rounded-3 stat-card">
        <div class="card-body p-4">
            <!-- ID -->
            <div class="mb-3">
                <p class="mb-1"><strong>ID:</strong></p>
                <p class="text-dark">{{ $votante->id }}</p>
            </div>

            <!-- Nome -->
            <div class="mb-3">
                <p class="mb-1"><strong>Nome:</strong></p>
                <p class="text-dark">{{ $votante->nome}}</p>
            </div>

            <!-- CPF/CNPJ -->
            <div class="mb-3">
                <p class="mb-1"><strong>CPF/CNPJ:</strong></p>
                <p class="text-dark">{{ formatarCpfCnpj($votante->cpf_cnpj) ?? '—' }}</p>
            </div>

            <!-- Data do Hora do Voto -->
            <div class="mb-3">
                <p class="mb-1"><strong>Data do Hora do Voto:</strong></p>
                <p class="text-dark">
                    {{ $votante->votado_em ? \Carbon\Carbon::parse($votante->votado_em)->format('d/m/Y H:i') : '—' }}
                </p>
            </div>

            <!-- Votou -->
            <div class="mb-3">
                <p class="mb-1"><strong>Votou:</strong></p>
                @if($votante->votou == '1')
                    <span class="badge bg-success px-3 py-2">Sim</span>
                @else
                    <span class="badge bg-secondary px-3 py-2">Não</span>
                @endif
            </div>

            <!-- Etapa -->
            <div class="mb-3">
                <p class="mb-1"><strong>Etapa:</strong></p>
                <p class="text-dark">{{ formatarEtapa($votante->etapa) }}</p>
            </div>

            <!-- IP -->
            <div class="mb-3">
                <p class="mb-1"><strong>IP:</strong></p>
                <p class="text-dark">{{ $votante->ip}}</p>
            </div>
        </div>

        <!-- Rodapé -->
        <div class="card-footer text-end px-4 py-3 d-flex justify-content-end gap-3">
            <a href="{{ route('admin.adminVotantes.index') }}" class="btn botao-voltar w-25">
                <i class="fa-solid fa-arrow-left me-1"></i> Voltar
            </a>
        </div>
    </div>
</div>
@endsection
<!---------------- Início - Estilos CSS -------------------->
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
<!---------------- Final - Estilos CSS -------------------->