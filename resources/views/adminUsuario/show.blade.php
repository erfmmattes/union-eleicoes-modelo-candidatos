@extends('layouts.appMasterAdmin')
@section('title', 'Unir Votações - Visualizar Usuário')

@section('content')
<div class="container">
    <div class="mb-5">
        <h1 class="h2 fw-bolder text-dark">Visualizar Usuário</h1>
        <p class="text-muted">Confira as informações e permissões do usuário selecionado.</p>
    </div>

    <div class="card shadow-lg border-0 rounded-3 stat-card">
        <div class="card-body p-4">

            <!-- Dados do Usuário -->
            <div class="mb-4">
                <h5 class="fw-bold">Informações Básicas</h5>
                <div class="card-body">
                    <div class="row g-4">
                        <!-- Nome -->
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Nome:</strong></p>
                            <p class="text-dark">{{ $usuario->name }}</p>
                        </div>

                        <!-- E-mail -->
                        <div class="col-md-6">
                            <p class="mb-1"><strong>E-mail:</strong></p>
                            <p class="text-dark">{{ $usuario->email }}</p>
                        </div>

                        <!-- Tipo de Usuário -->
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Tipo de Usuário:</strong></p>
                            <p class="text-dark">
                                @if($usuario->tipo_usuario === 'admin')
                                    <span class="badge bg-success">Administrador</span>
                                @else
                                    <span class="badge bg-secondary text-dark">Convidado</span>
                                @endif
                            </p>
                        </div>

                        <!-- Ativou Conta -->
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Ativou Conta:</strong></p>
                            <p class="text-dark">
                                <span class="badge {{ $usuario->conta_ativa ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $usuario->conta_ativa ? 'Sim' : 'Não' }}
                                </span>
                            </p>
                        </div>

                        <!-- Status -->
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Status:</strong></p>
                            <p class="text-dark">
                                <span class="badge {{ $usuario->status ? 'bg-success' : 'bg-secondary text-dark' }}">
                                    {{ $usuario->status ? 'Ativo' : 'Inativo' }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Permissões do Usuário -->
            <div>
                <h5 class="fw-bold mb-3">Permissões</h5>
                <div class="row g-3">
                    @php
                        $telas = collect(
                            $listTelas->map(fn($t) => (object)[
                                'slug' => $t->slug,
                                'nome' => $t->nome,
                            ])
                        );
                    @endphp

                    @foreach($telas as $tela)
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="card p-3 border rounded">
                                <h6 class="fw-bold">{{ $tela->nome }}</h6>

                                @php
                                    // Define permissões salvas ou padrão
                                    $permissoesUsuario = $usuarioPermissoes[$tela->slug] ?? [];

                                    // Define as ações conforme a tela
                                    $acoes = $tela->slug === 'eleitores'
                                        ? ['criar', 'importar_eleitores', 'enviar_senha', 'ver', 'editar', 'deletar']
                                        : ['criar', 'ver', 'editar', 'deletar'];
                                @endphp

                                <ul class="list-unstyled mb-0">
                                    @foreach($acoes as $acao)
                                        <li>
                                            <i class="fa-solid {{ ($permissoesUsuario[$acao] ?? false) ? 'fa-check text-success' : 'fa-xmark text-danger' }}"></i>
                                            {{ ucfirst(str_replace('_', ' ', $acao)) }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Botão Voltar -->
            <div class="d-flex justify-content-end mt-4">
                <a href="{{ route('admin.adminUsuario.index') }}" class="btn bot-cancelar-u px-4">
                    <i class="fa-solid fa-arrow-left me-1"></i> Voltar
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
<!---------------- Início - Estilos CSS -------------------->
<style>
    .bot-cancelar-u {
        background: linear-gradient(135deg, #6c757d, #6c757d);
        color: #fff !important;
    }
    .bot-cancelar-u:hover {
        background: linear-gradient(135deg, #5c636a, #5c636a);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        font-weight: 600 !important;
    }
</style>
<!---------------- Final - Estilos CSS -------------------->