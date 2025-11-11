<div class="mb-3">
    <label for="titulo" class="form-label fw-semibold">Título</label>
    <input type="text" name="titulo" id="titulo" class="form-control" value="{{ old('titulo', $ajuda->titulo ?? '') }}" required>
</div>

<div class="mb-3">
    <label for="descricao" class="form-label fw-semibold">Descrição</label>
    <textarea name="descricao" id="descricao" class="form-control" rows="4">{{ old('descricao', $ajuda->descricao ?? '') }}</textarea>
</div>

<div class="mb-3">
    <label for="sequencia" class="form-label fw-semibold">Sequência</label>
    <input type="number" name="sequencia" id="sequencia" class="form-control" value="{{ old('sequencia', $ajuda->sequencia ?? '') }}" required>
</div>

<div class="form-check form-switch mb-4">
    <input 
        class="form-check-input c-pointer" 
        type="checkbox" 
        role="switch" 
        name="ativo" 
        id="ativo" 
        value="1" 
        {{ old('ativo', $ajuda->ativo ?? false) ? 'checked' : '' }}
    >
    <label class="form-check-label fw-semibold" for="ativo">
        Ativo
    </label>
</div>