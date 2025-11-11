@extends('layouts.appMasterAdmin')
@section('title', 'Union Eleições - Novo Documento')

@section('content')
<div class="container">
    <!-- Cabeçalho -->
    <div class="mb-5">
        <h1 class="h2 fw-bolder text-dark">Novo Documento</h1>
        <p class="text-muted mt-1">
            Crie um novo documento e faça o upload do arquivo desejado.
        </p>
    </div>

    <!-- Card de Criação -->
    <div class="card shadow-lg border-0 rounded-3 stat-card">
        <div class="card-body p-4">
            <form action="{{ route('admin.adminDocumentos.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                @include('adminDocumentos.form')

                <div class="d-flex justify-content-end gap-3 mt-4">
                    <a href="{{ route('admin.adminDocumentos.index') }}" class="btn bot-cancelar-p px-4">
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
    .bot-cancelar-p {
        background: linear-gradient(135deg, #6c757d, #6c757d);
        color: #fff !important;
    }
    .bot-cancelar-p:hover {
        background: linear-gradient(135deg, #6c757d, #6c757d);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        font-weight: 600 !important;
    }
</style>
<!---------------- Final - Estilos CSS -------------------->
