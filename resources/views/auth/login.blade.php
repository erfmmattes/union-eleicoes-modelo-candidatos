@extends('layouts.app')
@section('title', 'Unir Votações - Login')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="card-header text-center mt-5 tx-adm">Admin da Eleição</div>
        <div class="largura-colt">
            <div class="card mt-4">
                <div class="card-header text-center fundo-log">Login</div>

                <div class="card-body mt-3">
                    <form method="POST" action="{{ route('admin.login') }}">
                        @csrf

                        <div class="row mb-3">
                            <div>
                                <input id="email" type="email" placeholder="E-mail" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col position-relative">
                                <div class="input-group">
                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        name="password"
                                        placeholder="Senha"
                                        required autocomplete="current-password">
                                    <span class="input-group-text" id="togglePassword" style="cursor:pointer;">
                                        <i class="fas fa-eye" aria-hidden="true"></i>
                                    </span>
                                </div>

                                @error('password')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div>
                                <button type="submit" class="btn botao-login">
                                    Login
                                </button>

                                @if (Route::has('admin.password.request'))
                                    <a class="btn btn-link botao-esqueceu-senha" href="{{ route('admin.password.request') }}">
                                        Esqueceu sua senha?
                                    </a>
                                @endif
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
    .tx-adm {
        font-size: 25px !important;
        font-weight: 600 !important;
    }
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
    .botao-esqueceu-senha {
        width: 100% !important;
    }
    /* ---------- MOBILE ---------- */
    @media (max-width: 992px) {
        .botao-login {
            min-width: 373px !important;
        }
        .botao-esqueceu-senha {
            min-width: 373px !important;
        }
    }
 </style>
 <!-- -------------- Final - Estillos CSS ------------------ -->
<!---------------- Início - Scripts JavaScript e Jquery ------------------ -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('password');

    togglePassword.addEventListener('click', function () {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);

        // Alterna o ícone
        this.querySelector('i').classList.toggle('fa-eye');
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });
});
</script>
<!---------------- Final - Scripts JavaScript e Jquery ------------------ -->