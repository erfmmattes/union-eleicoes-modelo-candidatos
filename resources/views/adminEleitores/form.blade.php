@csrf
<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Nome <strong>*</strong></label>
        <input type="text" name="nome" class="form-control" value="{{ old('nome', $eleitor->nome ?? '') }}">
    </div>

    <div class="col-md-6">
        <label class="form-label">Razão Social</label>
        <input type="text" name="razao_social" class="form-control" value="{{ old('razao_social', $eleitor->razao_social ?? '') }}">
    </div>

    <div class="col-md-4">
        <label class="form-label">CPF/CNPJ <strong>*</strong></label>
        <input
            type="text"
            name="cpf_cnpj"
            id="cpf_cnpj"
            class="form-control"
            value="{{ old('cpf_cnpj', $eleitor->cpf_cnpj ?? '') }}"
            maxlength="18"
            placeholder="000.000.000-00 ou 00.000.000/0000-00"
            autocomplete="off"
        >
    </div>

    <div class="col-md-4">
        <label class="form-label">E-mail <strong>*</strong></label>
        <input type="email" name="email" class="form-control" value="{{ old('email', $eleitor->email ?? '') }}">
    </div>

    <div class="col-md-4">
        <label class="form-label">Celular <strong>*</strong></label>
        <input type="text" name="celular" id="celular" class="form-control" value="{{ old('celular', formatarTelefone($eleitor->celular) ?? '') }}" placeholder="(00) 00000-0000">
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold">Setor</label>

        <select name="setor" class="form-select" required>
            @if($eleitor->setor === null)
                <option value="">Selecione um setor</option>
            @endif

            @foreach($setoresEleitores as $setor)
                <option value="{{ $setor->nome }}"
                    {{ old('setor', $eleitor->setor ?? null) == $setor->nome ? 'selected' : '' }}>
                    {{ $setor->nome }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-6">
        <label class="form-label">Peso do Voto</label>
        <input type="text" name="peso_do_voto" class="form-control" value="{{ old('peso_do_voto', $eleitor->peso_do_voto ?? '') }}">
    </div>

    <div class="col-md-6">
        <label class="form-label">Data de Nascimento</label>
        <input type="date" name="data_nascimento" class="form-control" value="{{ old('data_nascimento', $eleitor->data_nascimento ?? '') }}">
    </div>

    <div class="col-md-6">
        <label class="form-label">Nome do Representante</label>
        <input type="text" name="nome_do_representante" class="form-control" value="{{ old('nome_do_representante', $eleitor->nome_do_representante ?? '') }}">
    </div>

    <div class="form-check form-switch mb-4">
        <input 
            class="form-check-input c-pointer" 
            type="checkbox" 
            role="switch" 
            name="status" 
            id="status" 
            value="1" 
            {{ old('status', $eleitor->status ?? false) ? 'checked' : '' }}
        >
        <label class="form-check-label fw-semibold" for="status">
            Status
        </label>
    </div>
</div>
<!---------------- Início - Estillos CSS -------------------->
<style>
    .c-pointer {
        cursor: pointer;
    }
</style>
<!---------------- Final - Estillos CSS -------------------->
<!---------------- Início - Scripts JavaScript e Jquery ------------------ -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  const input = document.getElementById('cpf_cnpj');
  if (!input) return;

  function formatCpfCnpj(value) {
    const digits = value.replace(/\D/g, '');
    if (digits.length <= 11) {
      // CPF: 000.000.000-00
      return digits
        .replace(/^(\d{3})(\d{3})(\d{3})(\d{0,2}).*/, '$1.$2.$3-$4')
        .replace(/\.$/, '') // evita trailing dot quando incompleto
        .replace(/-$/, ''); // evita trailing dash
    } else {
      // CNPJ: 00.000.000/0000-00
      return digits
        .replace(/^(\d{2})(\d{3})(\d{3})(\d{4})(\d{0,2}).*/, '$1.$2.$3/$4-$5')
        .replace(/\.$/, '')
        .replace(/\/$/, '')
        .replace(/-$/, '');
    }
  }

  // tenta preservar caret de maneira razoável ao formatar
  function applyMaskAndKeepCaret(e) {
    const start = input.selectionStart;
    const oldValue = input.value;
    const oldLen = oldValue.length;

    const newValue = formatCpfCnpj(oldValue);
    input.value = newValue;

    const newLen = newValue.length;
    // calcula deslocamento simples do caret
    let newPos = start + (newLen - oldLen);

    // garante faixa válida
    if (newPos < 0) newPos = 0;
    if (newPos > newLen) newPos = newLen;

    try {
      input.setSelectionRange(newPos, newPos);
    } catch (err) {
      // alguns navegadores podem lançar se input não suportar seleção
    }
  }

  // eventos
  input.addEventListener('input', applyMaskAndKeepCaret);
  input.addEventListener('paste', function () {
    // espera o paste acontecer
    setTimeout(() => applyMaskAndKeepCaret(), 0);
  });

  // formata valor inicial (edição)
  if (input.value) {
    input.value = formatCpfCnpj(input.value);
  }
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const celularInput = document.getElementById('celular');

    celularInput.addEventListener('input', function(e) {
        let value = this.value.replace(/\D/g, ''); // remove tudo que não é número

        // Formata o número como (99) 99999-9999
        if(value.length > 10) {
            value = value.replace(/^(\d{2})(\d{5})(\d{4}).*/, '($1) $2-$3');
        } else if(value.length > 5) {
            value = value.replace(/^(\d{2})(\d{4})(\d{0,4}).*/, '($1) $2-$3');
        } else if(value.length > 2) {
            value = value.replace(/^(\d{2})(\d{0,5})/, '($1) $2');
        } else {
            value = value.replace(/^(\d*)/, '($1');
        }

        this.value = value;
    });
});
</script>
<!---------------- Final - Scripts JavaScript e Jquery ------------------ -->