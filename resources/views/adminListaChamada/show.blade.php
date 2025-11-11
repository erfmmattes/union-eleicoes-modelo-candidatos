@extends('layouts.appMasterAdmin')
@section('title', 'Union Eleições - Detalhes da Chamada')

@section('content')
<div class="container">
    <!-- Page Header -->
    <div class="mb-5">
        <h1 class="h2 fw-bolder text-dark">Detalhes da Chamada: {{ $listaDaChamada->nome }}</h1>
        <p class="text-muted mt-1">
            Visualize as informações completas do eleitor armazenado no sistema.
        </p>
    </div>

    <!-- Card de Detalhes -->
    <div class="card shadow-lg border-0 rounded-3 stat-card">
        <div class="card-body p-4">
            <!-- ID -->
            <div class="mb-3">
                <p class="mb-1"><strong>ID:</strong></p>
                <p class="text-dark">{{ $listaDaChamada->lista_chamada_id }}</p>
            </div>

            <!-- Nome -->
            <div class="mb-3">
                <p class="mb-1"><strong>Nome:</strong></p>
                <p class="text-dark">{{ $listaDaChamada->nome}}</p>
            </div>

            <!-- CPF/CNPJ -->
            <div class="mb-3">
                <p class="mb-1"><strong>CPF/CNPJ:</strong></p>
                <p class="text-dark">{{ formatarCpfCnpj($listaDaChamada->cpf_cnpj) ?? '—' }}</p>
            </div>

            <!-- E-mail -->
            <div class="mb-3">
                <p class="mb-1"><strong>E-mail:</strong></p>
                <p class="text-dark">{{ $listaDaChamada->email}}</p>
            </div>

            <!-- Celular -->
            <div class="mb-3">
                <p class="mb-1"><strong>Celular:</strong></p>
                <p class="text-dark">{{ formatarTelefone($listaDaChamada->celular) }}</p>
            </div>

            <!-- Data de Criação -->
            <div class="mb-3">
                <p class="mb-1"><strong>Data de Criação:</strong></p>
                <p class="text-dark">
                    {{ $listaDaChamada->created_at ? \Carbon\Carbon::parse($listaDaChamada->created_at)->format('d/m/Y H:i') : '—' }}
                </p>
            </div>
        </div>

        <!-- Rodapé -->
        <div class="card-footer text-end px-4 py-3 d-flex justify-content-end gap-3">
            <a href="{{ route('admin.adminListaChamada.index') }}" class="btn botao-voltar w-25">
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