@extends('layouts.app')
@section('title', 'Unir Votações - Registrar')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="largura-colt">
            <div class="card mt-5">
                <div class="card-header text-center fundo-log">Registrar</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('admin.register') }}">
                        @csrf

                        <div class="row mb-3">
                            <div>
                                <input id="name" type="text" placeholder="Nome" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback alert-temporaria" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div>
                                <input id="email" type="email" placeholder="E-mail" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback alert-temporaria" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div>
                                <select id="tipo_usuario" name="tipo_usuario" 
                                        class="form-select @error('tipo_usuario') is-invalid @enderror" required>
                                    <option value="">Selecione o tipo de usuário</option>
                                    <option value="admin-master" {{ old('tipo_usuario') == 'admin-master' ? 'selected' : '' }}>Administrador Master</option>
                                    <option value="convidado" {{ old('tipo_usuario') == 'convidado' ? 'selected' : '' }}>Convidado</option>
                                </select>

                                @error('tipo_usuario')
                                    <span class="invalid-feedback alert-temporaria" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div>
                                <input id="password" type="password" placeholder="Senha" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback alert-temporaria" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div>
                                <input id="password-confirm" type="password" placeholder="Confirmar Senha" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div>
                                <button type="submit" class="btn botao-login">
                                    Registrar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<!-- -------------- Início - Estillos CSS ------------------ -->
 <style>
    .fundo-log {
        background: linear-gradient(135deg, #183F77, #4A90E2);
        color: #ffffff !important;
        font-weight: 600 !important;
        font-size: 18px !important;
    }
    .largura-colt {
        width: 500px !important;
    }
    .botao-login {
        background: linear-gradient(135deg, #183F77, #4A90E2);
        color: #ffffff !important;
        font-weight: 500 !important;
        width: 100% !important;
        font-weight: 600 !important;
    }
    .botao-login:hover {
        background: linear-gradient(135deg, #122b55, #3570c2);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        font-weight: 700 !important;
    }
 </style>
 <!-- -------------- Final - Estillos CSS ------------------ -->
<!---------------- Início - Scripts JavaScript e Jquery ------------------ -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert-temporaria');

    alerts.forEach(alert => {
        // Define o tempo em milissegundos (ex: 5 segundos)
        setTimeout(() => {
            // Anima o fade out
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            // Remove do DOM depois da animação
            setTimeout(() => alert.remove(), 500);
        }, 5000); // 5000ms = 5 segundos
    });
});
</script>
<!---------------- Final - Scripts JavaScript e Jquery ------------------ -->