@extends('layouts.appMasterAdmin')
@section('title', 'Union Eleições - Nova Escolha')

@section('content')
<div class="container">

    <!-- Cabeçalho -->
    <div class="mb-5">
        <h1 class="h2 fw-bolder text-dark">Nova Escolha</h1>
        <p class="text-muted mt-1">
            Cadastre uma nova escolha para ser exibida aos eleitores durante a votação.
        </p>
    </div>

    <!-- Card -->
    <div class="card shadow-lg border-0 rounded-3 stat-card">
        <div class="card-body p-4">

            <form action="{{ route('admin.adminEscolhas.store') }}"
                  method="POST"
                  enctype="multipart/form-data">
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


                <!-- 🔵 Campo Branco / Nulo / Abstenção -->
                <div class="form-check form-switch mb-4">
                    <input type="checkbox"
                           class="form-check-input c-pointer"
                           name="branco_nulo_abstencao"
                           id="branco_nulo_abstencao"
                           value="1"
                           onchange="toggleCampos()">
                    <label class="form-check-label fw-semibold c-pointer" for="branco_nulo_abstencao">
                        É Branco / Nulo / Abstenção?
                    </label>
                </div>


                <!-- Campos Sempre Visíveis -->
                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Nome</label>
                        <input type="text" name="nome" class="form-control"
                               value="{{ old('nome') }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Etapa Candidato</label>
                        <select name="etapas_candidatos_id" class="form-select" required>
                            <option value="">Selecione...</option>

                            @foreach($listaEtapas as $listaEtapa)
                                <option value="{{ $listaEtapa->id }}"
                                    {{ old('nome') == $listaEtapa->nome ? 'selected' : '' }}>
                                    {{ $listaEtapa->nome }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Sequência</label>
                        <input type="text" name="sequencia" class="form-control"
                               value="{{ old('sequencia') }}">
                    </div>

                </div>


                <!-- 🔴 Campos que somem quando Branco/Nulo/Abstenção -->
                <div id="campos-normais">

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Cargo</label>
                        <input type="text" name="cargo" class="form-control"
                               value="{{ old('cargo') }}">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label fw-semibold">Foto (opcional)</label>
                        <input type="file" name="foto_upload" class="form-control"
                               accept="image/*" onchange="previewImagem(event)">
                    </div>

                    <div class="col-md-12 mb-3">
                        <img id="preview-img" style="max-width: 180px; display: none;"
                             class="rounded shadow-sm">
                    </div>

                </div>


                <!-- Ativar Escolha -->
                <div class="form-check form-switch mb-4">
                    <input type="checkbox"
                           class="form-check-input c-pointer"
                           name="status" id="status" value="1">
                    <label class="form-check-label fw-semibold c-pointer" for="status">
                        Ativar escolha
                    </label>
                </div>


                <!-- Botões -->
                <div class="d-flex justify-content-end gap-3 mt-4">
                    <a href="{{ route('admin.adminEscolhas.index') }}" class="btn bot-cancelar-ele px-4">
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
function previewImagem(event) {
    const img = document.getElementById('preview-img');
    img.src = URL.createObjectURL(event.target.files[0]);
    img.style.display = 'block';
}

function toggleCampos() {
    const isBrancoNulo = document.getElementById('branco_nulo_abstencao').checked;
    const blocosNormais = document.getElementById('campos-normais');

    blocosNormais.style.display = isBrancoNulo ? 'none' : 'block';
}

document.addEventListener('DOMContentLoaded', function() {
    toggleCampos(); // aplica ao carregar

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
<!---------------- Final - Scripts JS -------------------->