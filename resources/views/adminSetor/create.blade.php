@extends('layouts.appMasterAdmin')
@section('title', 'Unir Votações - Criar Setor')

@section('content')
<div class="container">

    <!-- Cabeçalho -->
    <div class="mb-5">
        <h1 class="h2 fw-bolder text-dark">Novo Setor</h1>
        <p class="text-muted mt-1">
            Cadastre um novo setor para organização dos candidatos e etapas.
        </p>
    </div>

    <!-- Card -->
    <div class="card shadow-lg border-0 rounded-3 stat-card">
        <div class="card-body p-4">

            <form action="{{ route('admin.adminSetor.store') }}" method="POST">
                @csrf

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
                <div class="row">

                    <div class="col-md-12 mb-3">
                        <label class="form-label fw-semibold">Nome do Setor</label>
                        <input type="text" name="nome" class="form-control"
                               value="{{ old('nome') }}" required>
                    </div>

                    <div class="form-check form-switch mb-4 ms-2">
                        <input type="checkbox"
                               class="form-check-input c-pointer"
                               name="status"
                               id="status"
                               value="1"
                               {{ old('status') == 1 ? 'checked' : '' }}
                               onclick="document.querySelector('select[name=status]').value = this.checked ? 1 : 0;">
                        <label class="form-check-label fw-semibold c-pointer" for="status">
                            Ativar setor
                        </label>
                    </div>

                </div>

                <!-- Botões -->
                <div class="d-flex justify-content-end gap-3 mt-4">
                    <a href="{{ route('admin.adminSetor.index') }}" class="btn bot-cancelar-ele px-4">
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

<!---------------- Estilos CSS -------------------->
<style>
    .bot-atualizar {
        background: linear-gradient(135deg, #183F77, #4A90E2);
        color: #fff !important;
        transition: all 0.2s ease;
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
        transition: all 0.2s ease;
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

<!---------------- Scripts JS -------------------->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert-temporaria');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });
});
</script>