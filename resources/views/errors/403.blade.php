@extends('layouts.appMasterAdmin')
@section('title', 'Unir Votações - Acesso Negado!')

@section('content')
<div class="container text-center py-5">
    <div class="d-flex flex-column align-items-center justify-content-center" style="min-height: 70vh;">
        <i class="fa-solid fa-ban text-danger mb-4" style="font-size: 80px;"></i>
        <h1 class="display-5 fw-bold text-dark mb-3">Acesso Negado!</h1>
        <p class="text-muted mb-4">
            Você não tem permissão para visualizar esta página.
        </p>

        <div class="d-flex">
            <a href="{{ url()->previous() }}" class="btn bot-voltar me-2">
                <i class="fa-solid fa-arrow-left me-1"></i> Voltar
            </a>

            <a href="{{ route('admin.home') }}" class="btn botao-pad">
                <i class="fas fa-tachometer-alt me-1"></i> Ir para o Dashboard
            </a>
        </div>
    </div>
</div>
@endsection
<!-- -------------- Início - Estillos CSS ------------------ -->
 <style>
.bot-voltar {
    background: linear-gradient(135deg, #6c757d, #6c757d);
    color: #fff !important;
}
.bot-voltar:hover {
    background: linear-gradient(135deg, #6c757d, #6c757d);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    font-weight: 600 !important;
}
.botao-pad {
    background: linear-gradient(135deg, #183F77, #4A90E2);
    color: #ffffff!important;
}
.botao-pad:hover {
    background: linear-gradient(135deg, #122b55, #3570c2);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    font-weight: 600 !important;
}
 </style>
 <!-- -------------- Final - Estillos CSS ------------------ -->