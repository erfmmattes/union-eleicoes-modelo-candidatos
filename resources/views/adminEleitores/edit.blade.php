@extends('layouts.appMasterAdmin')
@section('title', 'Unir Votações - Editar Eleitor')

@section('content')
<div class="container">
    <!-- Cabeçalho -->
    <div class="mb-5">
        <h1 class="h2 fw-bolder text-dark">Editar Eleitor: {{ $eleitor->nome }}</h1>
        <p class="text-muted mt-1">
            Atualize as informações do eleitor selecionado.
        </p>
    </div>

    <!-- Card de Edição -->
    <div class="card shadow-lg border-0 rounded-3 stat-card">
        <div class="card-body p-4">
            <form action="{{ route('admin.adminEleitores.update', $eleitor->id) }}" method="POST">
                @csrf
                @method('PUT')

                @include('adminEleitores.form')

                <div class="d-flex justify-content-end gap-3 mt-4">
                    <a href="{{ route('admin.adminEleitores.index') }}" class="btn bot-cancelar-ele px-4">
                        <i class="fa-solid fa-arrow-left me-1"></i> Cancelar
                    </a>
                    <button type="submit" class="btn bot-atualizar px-4">
                        <i class="fa-solid fa-save me-1"></i> Atualizar
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
    .bot-cancelar-ele {
        background: linear-gradient(135deg, #6c757d, #6c757d);
        color: #fff !important;
    }
    .bot-cancelar-ele:hover {
        background: linear-gradient(135deg, #6c757d, #6c757d);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        font-weight: 600 !important;
    }
</style>
<!---------------- Final - Estilos CSS -------------------->