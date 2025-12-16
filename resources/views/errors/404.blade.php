@extends('layouts.appTrocaDeSenhaUser')

@section('title', 'Unir Votações - Página não encontrada')

@section('content')
<div class="container py-5 d-flex align-items-center justify-content-center" style="min-height: 80vh;">
    <div class="text-center">
        <div class="mb-4">
            <i class="fa-solid fa-triangle-exclamation text-warning" style="font-size: 80px;"></i>
        </div>

        <h1 class="display-5 fw-bold text-dark mb-3">404 - Página não encontrada</h1>
        <p class="text-muted mb-4" style="max-width: 500px; margin: 0 auto;">
            A página que você tentou acessar não existe ou foi movida. 
            Verifique o endereço ou retorne ao painel principal.
        </p>

        <a href="{{ route('admin.home') }}" class="btn botao-pad btn-lg">
            <i class="fas fa-tachometer-alt me-1"></i> Ir para o Dashboard
        </a>
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