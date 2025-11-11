@extends('layouts.appMasterAdmin')
@section('title', 'Union Eleições - Detalhes do Log')
@section('content')
<div class="container">
    <!-- Page Header -->
    <div class="mb-5">
        <h1 class="h2 fw-bolder text-dark">Detalhe do Log de Erro: {{ $log->nome_log }}</h1>
        <p class="text-muted mt-1">
            Acompanhe todo detalhe do log de erro registrado no sistema em tempo real.
        </p>
    </div>

    <div class="card shadow-lg border-0">
        <div class="card-body p-4">
            <p><strong>Nome do Log:</strong> {{ $log->nome_log }}</p>
            <p><strong>Mensagem:</strong> {{ $log->mensagem }}</p>
            <p><strong>Data:</strong> {{ $log->created_at->format('d/m/Y H:i') }}</p>
        </div>
        <div class="card-footer text-end px-4 py-3">
            <a href="{{ route('admin.adminLogs.index') }}" class="btn bot-voltar w-25"><i class="fa-solid fa-arrow-left me-1"></i> Voltar</a>
        </div>
    </div>
</div>
@endsection
<!---------------- Início - Estillos CSS -------------------->
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
</style>
<!---------------- Final - Estillos CSS -------------------->