@extends('layouts.appMasterAdmin')
@section('title', 'Unir Votações - Detalhes do Não Votante')

@section('content')
<div class="container">
    <!-- Page Header -->
    <div class="mb-5">
        <h1 class="h2 fw-bolder text-dark">Detalhes do Não Votante: {{ $naoVotante->nome }}</h1>
        <p class="text-muted mt-1">
            Visualize as informações completas do não votante armazenado no sistema.
        </p>
    </div>

    <!-- Card de Detalhes -->
    <div class="card shadow-lg border-0 rounded-3 stat-card">
        <div class="card-body p-4">
            <!-- ID -->
            <div class="mb-3">
                <p class="mb-1"><strong>ID:</strong></p>
                <p class="text-dark">{{ $naoVotante->id }}</p>
            </div>

            <!-- Nome -->
            <div class="mb-3">
                <p class="mb-1"><strong>Nome:</strong></p>
                <p class="text-dark">{{ $naoVotante->nome}}</p>
            </div>

            <!-- CPF/CNPJ -->
            <div class="mb-3">
                <p class="mb-1"><strong>CPF/CNPJ:</strong></p>
                <p class="text-dark">{{ formatarCpfCnpj($naoVotante->cpf_cnpj) ?? '—' }}</p>
            </div>

            <!-- Celular -->
            <div class="mb-3">
                <p class="mb-1"><strong>Celular:</strong></p>
                <p class="text-dark">{{ formatarTelefone($naoVotante->celular) ?? '—' }}</p>
            </div>

            <!-- E-mail -->
            <div class="mb-3">
                <p class="mb-1"><strong>E-mail:</strong></p>
                <p class="text-dark">{{ $naoVotante->email ?? '—' }}</p>
            </div>

            <!-- Votou -->
            <div class="mb-3">
                <p class="mb-1"><strong>Votou:</strong></p>
                @if($naoVotante->votou == '1')
                    <span class="badge bg-success px-3 py-2">Sim</span>
                @else
                    <span class="badge bg-secondary px-3 py-2">Não</span>
                @endif
            </div>
        </div>

        <!-- Rodapé -->
        <div class="card-footer text-end px-4 py-3 d-flex justify-content-end gap-3">
            <a href="{{ route('admin.adminNaoVotantes.index') }}" class="btn botao-voltar w-25">
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