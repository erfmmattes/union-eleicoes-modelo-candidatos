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
        <button class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">

    <!-- Título -->
    <div class="col-md-6 mb-3">
        <label class="form-label fw-semibold">Título</label>
        <input type="text"
               name="titulo"
               class="form-control"
               value="{{ old('titulo', $escolha->titulo ?? '') }}">
    </div>

    <!-- Nome -->
    <div class="col-md-6 mb-3">
        <label class="form-label fw-semibold">Nome</label>
        <input type="text"
               name="nome"
               class="form-control"
               value="{{ old('nome', $escolha->nome ?? '') }}">
    </div>

    <!-- Cargo -->
    <div class="col-md-6 mb-3">
        <label class="form-label fw-semibold">Cargo</label>
        <input type="text"
               name="cargo"
               class="form-control"
               value="{{ old('cargo', $escolha->cargo ?? '') }}">
    </div>

    <!-- Sequência -->
    <div class="col-md-6 mb-3">
        <label class="form-label fw-semibold">Sequência</label>
        <input type="text"
               name="sequencia"
               class="form-control"
               value="{{ old('sequencia', $escolha->sequencia ?? '') }}">
    </div>

    <!-- Upload Foto -->
    <div class="col-md-12 mb-3">
        <label class="form-label fw-semibold">Foto (opcional)</label>
        <input type="file"
               name="foto_upload"
               class="form-control"
               accept="image/*"
               onchange="previewImagem(event)">
    </div>

    <!-- Preview -->
    <div class="col-md-12 mb-3">
        <img id="preview-img"
            src="{{ isset($escolha) && $escolha->tem_foto ? asset('storage/'.$escolha->caminho) : '' }}"
            style="max-width: 180px; display: {{ isset($escolha) && $escolha->tem_foto ? 'block' : 'none' }};"
            class="rounded shadow-sm">
    </div>

    <!-- Status -->
    <div class="form-check form-switch mb-3">
        <input type="checkbox"
               name="status"
               value="1"
               class="form-check-input c-pointer"
               {{ old('status', $escolha->status ?? false) ? 'checked' : '' }}>
        <label class="form-check-label c-pointer fw-semibold">Ativar escolha</label>
    </div>

</div>