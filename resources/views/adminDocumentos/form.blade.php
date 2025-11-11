<div class="mb-3">
    <label for="titulo" class="form-label fw-semibold">Título</label>
    <input type="text" name="titulo" id="titulo"
           class="form-control @error('titulo') is-invalid @enderror"
           value="{{ old('titulo', $documento->titulo ?? '') }}" required>
    @error('titulo')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="descricao" class="form-label fw-semibold">Descrição</label>
    <textarea name="descricao" id="descricao" rows="4"
              class="form-control @error('descricao') is-invalid @enderror">{{ old('descricao', $documento->descricao ?? '') }}</textarea>
    @error('descricao')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="arquivo" class="form-label fw-semibold">Arquivo</label>
    <input type="file" name="arquivo" id="arquivo"
           class="form-control @error('arquivo') is-invalid @enderror">
           <small class="text-muted mt-2">Tipos de arquivo aceitos: PDF, DOC, DOCX, JPG, PNG</small>
    @error('arquivo')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror

    @if(isset($documento) && $documento->arquivo)
        <div class="mt-2">
            <a href="{{ asset('storage/' . $documento->caminho) }}" target="_blank" class="text-decoration-none">
                <i class="fas fa-download me-1"></i> {{ asset('storage/' . $documento->caminho) }}
            </a>
        </div>
    @endif
</div>

<div class="mb-3">
    <label for="sequencia" class="form-label fw-semibold">Sequência</label>
    <input type="number" name="sequencia" id="sequencia" class="form-control" value="{{ old('sequencia', $documento->sequencia ?? '') }}" required>
</div>

<div class="mb-3">
    <label for="tipo" class="form-label fw-semibold">Tipo - (Regulamento, Edital, Ata, Relatório)</label>
    <input type="text" name="tipo" id="tipo"
           class="form-control @error('tipo') is-invalid @enderror"
           value="{{ old('tipo', $documento->tipo ?? '') }}">
    @error('tipo')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="form-check form-switch mb-4">
    <input 
        class="form-check-input c-pointer" 
        type="checkbox" 
        role="switch" 
        name="ativo" 
        id="ativo" 
        value="1" 
        {{ old('ativo', $documento->ativo ?? false) ? 'checked' : '' }}
    >
    <label class="form-check-label fw-semibold" for="ativo">
        Documento ativo
    </label>
</div>
<!---------------- Início - Estillos CSS -------------------->
<style>
    .c-pointer {
        cursor: pointer;
    }
</style>
<!---------------- Final - Estillos CSS -------------------->