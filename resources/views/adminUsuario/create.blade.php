@extends('layouts.appMasterAdmin')
@section('title', 'Union Eleições - Criar Usuário')

@section('content')
<div class="container">
    <div class="mb-5">
        <h1 class="h2 fw-bolder text-dark">Criar Usuário</h1>
        <p class="text-muted">Crie um novo usuário do sistema.</p>
    </div>

    <div class="card shadow-lg border-0 rounded-3 stat-card">
        <div class="card-body p-4">
            <form action="{{ route('admin.adminUsuario.store') }}" method="POST">
                @csrf

                <!-- Mensagem de Sucesso -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show alert-temporaria" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Mensagens de Erro -->
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show alert-temporaria" role="alert">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Campos do Formulário -->
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nome</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">E-mail</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Tipo de Usuário</label>
                        <select name="tipo_usuario" class="form-select @error('tipo_usuario') is-invalid @enderror" required>
                            <option value="">Selecione</option>
                            @foreach ($listTiposUsuarios as $listTiposUsuario)
                                <option value="{{ $listTiposUsuario->slug }}" {{ old('tipo_usuario') === 'convidado' ? 'selected' : '' }}>{{ $listTiposUsuario->nome }}</option>
                            @endforeach
                        </select>
                        @error('tipo_usuario')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold d-block">Status</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input cp-tr" type="checkbox" role="switch"
                                   id="status" name="status"
                                   value="1" {{ old('status', $configuracao->status ?? false) ? 'checked' : '' }}>
                        </div>
                    </div>
                </div>

                @php
                    $telas = collect(
                        $listTelas->map(fn($t) => (object)[
                            'slug' => $t->slug,
                            'nome' => $t->nome,
                        ])
                    );
                @endphp

                <!-- Permissões do Usuário -->
                <div class="mt-4">
                    <label class="form-label fw-semibold">Permissões</label>
                    <div class="row g-3">
                        @foreach($telas as $tela)
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="card p-3 border rounded">
                                    <h6 class="fw-bold">{{ $tela->nome }}</h6>
                                    <div class="form-check">
                                        <input class="form-check-input cp-tr" type="checkbox" 
                                            name="permissoes[{{ $tela->slug }}][]" 
                                            value="criar"
                                            id="permissao-{{ $tela->slug }}-criar"
                                            {{ in_array('criar', old("permissoes.$tela->slug", [])) ? 'checked' : '' }}>
                                        <label class="form-check-label cp-tr" for="permissao-{{ $tela->slug }}-criar">Criar</label>
                                    </div>
                                    @if($tela->slug === 'eleitores')
                                    <div class="form-check">
                                        <input class="form-check-input cp-tr" type="checkbox" 
                                            name="permissoes[{{ $tela->slug }}][]" 
                                            value="importar_eleitores"
                                            id="permissao-{{ $tela->slug }}-importar_eleitores"
                                            {{ in_array('importar_eleitores', old("permissoes.$tela->slug", [])) ? 'checked' : '' }}>
                                        <label class="form-check-label cp-tr" for="permissao-{{ $tela->slug }}-importar_eleitores">Importar Eleitores</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input cp-tr" type="checkbox" 
                                            name="permissoes[{{ $tela->slug }}][]" 
                                            value="enviar_senha"
                                            id="permissao-{{ $tela->slug }}-enviar_senha"
                                            {{ in_array('enviar_senha', old("permissoes.$tela->slug", [])) ? 'checked' : '' }}>
                                        <label class="form-check-label cp-tr" for="permissao-{{ $tela->slug }}-enviar_senha">Enviar Senha</label>
                                    </div>
                                    @endif
                                    <div class="form-check">
                                        <input class="form-check-input cp-tr" type="checkbox" 
                                            name="permissoes[{{ $tela->slug }}][]" 
                                            value="ver"
                                            id="permissao-{{ $tela->slug }}-ver"
                                            {{ in_array('ver', old("permissoes.$tela->slug", [])) ? 'checked' : '' }}>
                                        <label class="form-check-label cp-tr" for="permissao-{{ $tela->slug }}-ver">Ver</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input cp-tr" type="checkbox" 
                                            name="permissoes[{{ $tela->slug }}][]" 
                                            value="editar"
                                            id="permissao-{{ $tela->slug }}-editar"
                                            {{ in_array('editar', old("permissoes.$tela->slug", [])) ? 'checked' : '' }}>
                                        <label class="form-check-label cp-tr" for="permissao-{{ $tela->slug }}-editar">Editar</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input cp-tr" type="checkbox" 
                                            name="permissoes[{{ $tela->slug }}][]" 
                                            value="deletar"
                                            id="permissao-{{ $tela->slug }}-deletar"
                                            {{ in_array('deletar', old("permissoes.$tela->slug", [])) ? 'checked' : '' }}>
                                        <label class="form-check-label cp-tr" for="permissao-{{ $tela->slug }}-deletar">Deletar</label>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Botões -->
                <div class="d-flex justify-content-end gap-3 mt-4">
                    <a href="{{ route('admin.adminUsuario.index') }}" class="btn bot-cancelar-u px-4">
                        <i class="fa-solid fa-arrow-left me-1"></i> Cancelar
                    </a>
                    <button type="submit" class="btn bot-atualizar px-4">
                        <i class="fa-solid fa-save me-1"></i> Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

<!---------------- Início - Scripts JavaScript e Jquery ------------------ -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert-temporaria');

    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });
});
</script>
<!---------------- Final - Scripts JavaScript e Jquery ------------------ -->

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
    .cp-tr {
        cursor: pointer;
    }
</style>
<!---------------- Final - Estilos CSS -------------------->