@extends('layouts.appMasterAdmin')
@section('title', 'Unir Votações - Editar Setor')

@section('content')
<div class="container">

    <!-- Cabeçalho -->
    <div class="mb-5">
        <h1 class="h2 fw-bolder text-dark">Editar Setor: {{ $setor->nome }}</h1>
        <p class="text-muted mt-1">
            Atualize as informações do setor selecionado.
        </p>
    </div>

    <!-- Card de Edição -->
    <div class="card shadow-lg border-0 rounded-3 stat-card">
        <div class="card-body p-4">

            <form action="{{ route('admin.adminSetor.update', $setor->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Sucesso -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show alert-temporaria" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Erros -->
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

                <!-- Formulário -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nome do Setor</label>
                    <input 
                        type="text" 
                        name="nome" 
                        class="form-control" 
                        value="{{ old('nome', $setor->nome) }}" 
                        required>
                </div>

                <div class="form-check form-switch mb-4">
                    <input 
                        class="form-check-input c-pointer" 
                        type="checkbox" 
                        role="switch" 
                        name="status" 
                        id="status" 
                        value="1" 
                        {{ old('status', $setor->status) ? 'checked' : '' }}
                    >
                    <label class="form-check-label fw-semibold c-pointer" for="status">
                        Ativar setor
                    </label>
                </div>

                <!-- Botões -->
                <div class="d-flex justify-content-end gap-3 mt-4">
                    <a href="{{ route('admin.adminSetor.index') }}" class="btn bot-cancelar-ele px-4">
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
        background: linear-gradient(135deg, #5c636a, #5c636a);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        font-weight: 600 !important;
    }

    .c-pointer {
        cursor: pointer;
    }
</style>
<!---------------- Final - Estilos CSS -------------------->