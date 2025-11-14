@extends('layouts.appMasterAdmin')
@section('title', 'Union Eleições - Nova Ajuda')

@section('content')
<div class="container">
    <div class="mb-5">
        <h1 class="h2 fw-bolder text-dark">Nova Ajuda</h1>
        <p class="text-muted">Crie uma nova ajuda do sistema.</p>
    </div>

    <div class="card shadow-lg border-0 rounded-3 stat-card">
        <div class="card-body p-4">
            <form action="{{ route('admin.adminAjuda.store') }}" method="POST">
                @csrf

                <!-- Mensagens de Sucesso -->
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

                @include('adminAjuda.form')

                <div class="d-flex justify-content-end gap-3 mt-4">
                    <a href="{{ route('admin.adminAjuda.index') }}" class="btn bot-cancela px-4">
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
    .bot-cancela {
        background: linear-gradient(135deg, #6c757d, #6c757d);
        color: #fff !important;
    }
    .bot-cancela:hover {
        background: linear-gradient(135deg, #6c757d, #6c757d);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        font-weight: 600 !important;
    }
</style>
<!---------------- Final - Estilos CSS -------------------->