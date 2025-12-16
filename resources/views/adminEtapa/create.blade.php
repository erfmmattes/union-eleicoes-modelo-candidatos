@extends('layouts.appMasterAdmin')
@section('title', 'Unir Votações - Nova Etapa')

@section('content')
<div class="container">

    <!-- Cabeçalho -->
    <div class="mb-5">
        <h1 class="h2 fw-bolder text-dark">Nova Etapa</h1>
        <p class="text-muted mt-1">
            Crie uma nova etapa para organização das escolhas de candidatos.
        </p>
    </div>

    <!-- Card -->
    <div class="card shadow-lg border-0 rounded-3 stat-card">
        <div class="card-body p-4">

            <form action="{{ route('admin.adminEtapa.store') }}" method="POST">
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
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Etapa</label>
                        <input 
                            type="text" 
                            name="nome" 
                            class="form-control" 
                            value="{{ old('nome') }}" 
                            required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Setor</label>
                        <select name="setores_id" class="form-select" required>
                            <option value="">Selecione...</option>

                            @foreach($listaSetores as $listaSetor)
                                <option value="{{ $listaSetor->id }}"
                                    {{ old('nome') == $listaSetor->nome ? 'selected' : '' }}>
                                    {{ $listaSetor->nome }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Sequência</label>
                        <input 
                            type="text" 
                            name="sequencia" 
                            class="form-control" 
                            value="{{ old('sequencia') }}">
                    </div>

                    <div class="col-md-6 mt-4">
                        <div class="form-check form-switch mb-4">
                            <input 
                                class="form-check-input c-pointer" 
                                type="checkbox" 
                                role="switch" 
                                id="multipla_escolha" 
                                name="multipla_escolha"
                                value="1"
                                {{ old('multipla_escolha') ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold c-pointer" for="multipla_escolha">
                                Esta etapa é de múltipla escolha?
                            </label>
                        </div>
                    </div>

                    <!-- CAMPOS QUE APARECEM SOMENTE SE MARCAR MULTIPLA ESCOLHA -->
                    <div id="bloco-multipla" style="display: none;">

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Quantidade mínima de escolhas</label>
                            <input 
                                type="text" 
                                name="quantidade_minima_escolhas" 
                                class="form-control"
                                value="{{ old('quantidade_minima_escolhas') }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Quantidade máxima de escolhas</label>
                            <input 
                                type="text" 
                                name="quantidade_maxima_escolhas" 
                                class="form-control"
                                value="{{ old('quantidade_maxima_escolhas') }}">
                        </div>

                    </div>

                    <div class="col-md-6 mt-4">
                        <div class="form-check form-switch mb-4">
                            <input 
                                class="form-check-input c-pointer" 
                                type="checkbox" 
                                role="switch" 
                                name="status" 
                                id="status" 
                                value="1" 
                            >
                            <label class="form-check-label fw-semibold c-pointer" for="status">
                                Ativar etapa
                            </label>
                        </div>
                    </div>

                </div>

                <!-- Botões -->
                <div class="d-flex justify-content-end gap-3 mt-4">
                    <a href="{{ route('admin.adminEtapa.index') }}" class="btn bot-cancelar-ele px-4">
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
<!---------------- Final - Estilos CSS -------------------->
<!---------------- Início - Scripts JS -------------------->
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
<script>
document.addEventListener('DOMContentLoaded', function() {
    const multipla = document.getElementById('multipla_escolha');
    const bloco = document.getElementById('bloco-multipla');

    function toggleCampos() {
        if (multipla.checked) {
            bloco.style.display = 'flex';
            bloco.classList.add('row');
        } else {
            bloco.style.display = 'none';
            bloco.querySelectorAll('input').forEach(input => input.value = '');
        }
    }

    multipla.addEventListener('change', toggleCampos);

    // Executa ao carregar (para old() em caso de erro)
    toggleCampos();
});
</script>
<!---------------- Final - Scripts JS -------------------->