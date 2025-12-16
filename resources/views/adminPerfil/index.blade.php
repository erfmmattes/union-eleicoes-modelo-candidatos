@extends('layouts.appMasterAdmin')
@section('title', 'Unir Votações - Perfil')
@section('content')
<div class="container-fluid">

    <div class="mb-4">
        <h1 class="h2 fw-bolder text-dark">Perfil</h1>
        <p class="text-muted">Gerencie suas informações pessoais e de acesso.</p>
    </div>

    <!-- Mensagens -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show alert-temporaria" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show alert-temporaria" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Formulário -->
    <div class="card shadow-lg border-0 rounded-3 stat-card">
        <div class="card-body p-4 p-md-5">
            <form action="{{ route('admin.adminPerfil.atualizar') }}" method="POST">
                @csrf
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nome</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                               name="name" value="{{ old('name', $perfil->name) }}">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">E-mail</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                               name="email" value="{{ old('email', $perfil->email) }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Tipo de Usuário</label>
                        @if($perfil->tipo_usuario === "admin-master")
                            <div class="form-control">Administrador Master</div>
                        @endif
                        @if($perfil->tipo_usuario === "convidado")
                            <div class="form-control">Convidado</div>
                        @endif
                    </div>

                    @if(Auth::user()->tipo_usuario == "admin-master")
                        <div class="col-md-6">
                            <label class="form-label fw-semibold"></label>
                            <a href="{{ route('admin.adminUsuario.index') }}" class="btn botao-salvar-alteracoes px-4 form-control mt-2">
                                <i class="fas fa-users me-1"></i> Usuários Adicionais
                            </a>
                        </div>
                    @endif
                </div>

                <div class="mt-4 text-end">
                    <button type="submit" class="btn botao-salvar-alteracoes px-4">
                        <i class="fa-solid fa-save me-1"></i> Salvar Alterações
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert-temporaria');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });
});
</script>

<style>
.alert-temporaria {
    transition: opacity 0.5s ease;
}
.botao-salvar-alteracoes {
    background: linear-gradient(135deg, #183F77, #4A90E2);
    color: #ffffff !important;
    font-weight: 500 !important;
    font-weight: 600 !important;
}
.botao-salvar-alteracoes:hover {
    background: linear-gradient(135deg, #122b55, #3570c2);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    font-weight: 700 !important;
}

</style>
@endsection