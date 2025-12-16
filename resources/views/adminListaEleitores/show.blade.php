@extends('layouts.appMasterAdmin')
@section('title', 'Unir Votações - Lista de Eleitores')

@section('content')
<div class="container">
    <!-- Page Header -->
    <div class="mb-5">
        <h1 class="h2 fw-bolder text-dark">Detalhes do Eleitor: {{ $listaDeEleitor->nome }}</h1>
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
                <p class="text-dark">{{ $listaDeEleitor->id }}</p>
            </div>

            <!-- Nome -->
            <div class="mb-3">
                <p class="mb-1"><strong>Nome:</strong></p>
                <p class="text-dark">{{ $listaDeEleitor->nome}}</p>
            </div>

            <!-- CPF/CNPJ -->
            <div class="mb-3">
                <p class="mb-1"><strong>CPF/CNPJ:</strong></p>
                <p class="text-dark">{{ formatarCpfCnpj($listaDeEleitor->cpf_cnpj) ?? '—' }}</p>
            </div>

            <!-- E-mail -->
            <div class="mb-3">
                <p class="mb-1"><strong>E-mail:</strong></p>
                <p class="text-dark">{{ $listaDeEleitor->email}}</p>
            </div>

            <!-- Celular -->
            <div class="mb-3">
                <p class="mb-1"><strong>Celular:</strong></p>
                <p class="text-dark">{{ formatarTelefone($listaDeEleitor->celular) }}</p>
            </div>

            <!-- Atualizou -->
            <div class="mb-3">
                <p class="mb-1"><strong>Atualizou:</strong></p>
                @if($listaDeEleitor->passou_por_ajuste == '1')
                    <span class="badge bg-success px-3 py-2">Sim</span>
                @else
                    <span class="badge bg-secondary px-3 py-2">Não</span>
                @endif
            </div>

            <!-- Status -->
            <div class="mb-3">
                <p class="mb-1"><strong>Status:</strong></p>
                @if($listaDeEleitor->status == '1')
                    <span class="badge bg-success px-3 py-2">Ativo</span>
                @else
                    <span class="badge bg-secondary px-3 py-2">Inativo</span>
                @endif
            </div>

            <!-- Recuperou Senha -->
            <div class="mb-3">
                <p class="mb-1"><strong>Recuperou Senha:</strong></p>
                @if($listaDeEleitor->quantidade_recuperacao_senha > 0)
                    <span class="badge bg-success px-3 py-2">Sim</span> =>
                    <span class="badge bg-success px-3 py-2">{{ $listaDeEleitor->quantidade_recuperacao_senha }}</span>
                @else
                    <span class="badge bg-secondary px-3 py-2">Não</span>
                @endif
            </div>

            <!-- Troca Senha -->
            <div class="mb-3">
                <p class="mb-1"><strong>Troca de Senha:</strong></p>
                @if($listaDeEleitor->quantidade_troca_senha > 0)
                    <span class="badge bg-success px-3 py-2">Sim</span> =>
                    <span class="badge bg-success px-3 py-2">{{ $listaDeEleitor->quantidade_troca_senha }}</span>
                @else
                    <span class="badge bg-secondary px-3 py-2">Não</span>
                @endif
            </div>

            <!-- Data de Criação -->
            <div class="mb-3">
                <p class="mb-1"><strong>Data de Criação:</strong></p>
                <p class="text-dark">
                    {{ $listaDeEleitor->created_at ? \Carbon\Carbon::parse($listaDeEleitor->created_at)->format('d/m/Y H:i') : '—' }}
                </p>
            </div>

            <!-- Data de Atualização -->
            <div class="mb-3">
                <p class="mb-1"><strong>Data de Atualização:</strong></p>
                <p class="text-dark">
                    {{ $listaDeEleitor->updated_at ? \Carbon\Carbon::parse($listaDeEleitor->updated_at)->format('d/m/Y H:i') : '—' }}
                </p>
            </div>
        </div>

        <!-- Rodapé -->
        <div class="card-footer text-end px-4 py-3 d-flex justify-content-end gap-3">
            <a href="{{ route('admin.adminListaEleitores.index') }}" class="btn botao-voltar w-25">
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