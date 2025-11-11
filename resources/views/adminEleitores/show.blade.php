@extends('layouts.appMasterAdmin')
@section('title', 'Union Eleições - Detalhes do Eleitor')

@section('content')
<div class="container">
    <!-- Page Header -->
    <div class="mb-5">
        <h1 class="h2 fw-bolder text-dark">Detalhes do Eleitor: {{ $eleitor->nome }}</h1>
        <p class="text-muted mt-1">
            Visualize as informações completas do eleitor armazenado no sistema.
        </p>
    </div>

    <!-- Card de Detalhes -->
    <div class="card shadow-lg border-0 rounded-3 stat-card">
        <div class="card-body p-4">
            <!-- Nome -->
            <div class="mb-3">
                <p class="mb-1"><strong>Nome:</strong></p>
                <p class="text-dark">{{ $eleitor->nome}}</p>
            </div>

            <!-- Razão Social -->
            <div class="mb-3">
                <p class="mb-1"><strong>Razão Social:</strong></p>
                <p class="text-dark">{{ $eleitor->razao_social ?? '—'}}</p>
            </div>

            <!-- CPF -->
            <div class="mb-3">
                <p class="mb-1"><strong>CPF/CNPJ:</strong></p>
                <p class="text-dark">{{ formatarCpfCnpj($eleitor->cpf_cnpj) ?? '—' }}</p>
            </div>

            <!-- E-mail -->
            <div class="mb-3">
                <p class="mb-1"><strong>E-mail:</strong></p>
                <p class="text-dark">{{ $eleitor->email ?? '—' }}</p>
            </div>

            <!-- Celular -->
            <div class="mb-3">
                <p class="mb-1"><strong>Celular:</strong></p>
                <p class="text-dark">{{ formatarTelefone($eleitor->celular) ?? '—' }}</p>
            </div>

            <!-- Setor -->
            <div class="mb-3">
                <p class="mb-1"><strong>Setor:</strong></p>
                <p class="text-dark">{{ $eleitor->setor ?? '—' }}</p>
            </div>

            <!-- Peso do Voto -->
            <div class="mb-3">
                <p class="mb-1"><strong>Peso do Voto:</strong></p>
                <p class="text-dark">{{ $eleitor->peso_do_voto ?? '—' }}</p>
            </div>

            <!-- Data de Nascimento -->
            <div class="mb-3">
                <p class="mb-1"><strong>Data de Nascimento:</strong></p>
                <p class="text-dark">{{ $eleitor->data_nascimento ?? '—' }}</p>
            </div>

            <!-- Nome do Representante -->
            <div class="mb-3">
                <p class="mb-1"><strong>Nome do Representante:</strong></p>
                <p class="text-dark">{{ $eleitor->nome_do_representante ?? '—' }}</p>
            </div>

            <!-- Status -->
            <div class="mb-3">
                <p class="mb-1"><strong>Status:</strong></p>
                @if($eleitor->status)
                    <span class="badge bg-success px-3 py-2">Ativo</span>
                @else
                    <span class="badge bg-secondary px-3 py-2">Inativo</span>
                @endif
            </div>

            <!-- Enviou Senha E-mail -->
            <div class="mb-3">
                <p class="mb-1"><strong>Enviou Senha E-mail:</strong></p>
                @if($eleitor->enviou_senha_email)
                    <span class="badge bg-success px-3 py-2">Sim</span>
                @else
                    <span class="badge bg-secondary px-3 py-2">Não</span>
                @endif
            </div>

            <!-- Enviou Senha SMS -->
            <div class="mb-3">
                <p class="mb-1"><strong>Enviou Senha SMS:</strong></p>
                @if($eleitor->enviou_senha_sms)
                    <span class="badge bg-success px-3 py-2">Sim</span>
                @else
                    <span class="badge bg-secondary px-3 py-2">Não</span>
                @endif
            </div>

            <!-- Recuperou Senha -->
            <div class="mb-3">
                <p class="mb-1"><strong>Recuperou Senha:</strong></p>
                @if($eleitor->quantidade_recuperacao_senha > 0)
                    <span class="badge bg-success px-3 py-2">Sim</span> =>
                    <span class="badge bg-success px-3 py-2">{{ $eleitor->quantidade_recuperacao_senha }}</span>
                @else
                    <span class="badge bg-secondary px-3 py-2">Não</span>
                @endif
            </div>

            <!-- Troca Senha -->
            <div class="mb-3">
                <p class="mb-1"><strong>Troca de Senha:</strong></p>
                @if($eleitor->quantidade_troca_senha > 0)
                    <span class="badge bg-success px-3 py-2">Sim</span> =>
                    <span class="badge bg-success px-3 py-2">{{ $eleitor->quantidade_troca_senha }}</span>
                @else
                    <span class="badge bg-secondary px-3 py-2">Não</span>
                @endif
            </div>

            <!-- Data de Cadastro -->
            <div class="mb-3">
                <p class="mb-1"><strong>Data de Cadastro:</strong></p>
                <p class="text-dark">
                    {{ $eleitor->created_at ? $eleitor->created_at->format('d/m/Y H:i') : '—' }}
                </p>
            </div>

            <!-- Última Atualização -->
            @if($eleitor->updated_at)
                <div class="mb-3">
                    <p class="mb-1"><strong>Última Atualização:</strong></p>
                    <p class="text-dark">{{ $eleitor->updated_at->format('d/m/Y H:i') }}</p>
                </div>
            @endif
        </div>

        <!-- Rodapé -->
        <div class="card-footer text-end px-4 py-3 d-flex justify-content-end gap-3">
            <a href="{{ route('admin.adminEleitores.index') }}" class="btn botao-voltar w-25">
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
.botao-editar {
    background: linear-gradient(135deg, #183F77, #4A90E2);
    color: #ffffff!important;
}
.botao-editar:hover {
    background: linear-gradient(135deg, #122b55, #3570c2);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    font-weight: 600 !important;
}
</style>
<!---------------- Final - Estilos CSS -------------------->