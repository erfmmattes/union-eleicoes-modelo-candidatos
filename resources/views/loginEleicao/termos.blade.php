@extends('layouts.appMasterFront')
@section('title', 'Union Eleições - Termos')

@section('content')
<section class="hero d-flex align-items-center justify-content-center mt-5">
    <div class="card shadow-lg border-0 rounded-4 p-4 lar-mobile" style="max-width: 500px;">
        <div class="text-center mb-3">
            <h1 class="h4 fw-bold text-dark">Termos de Uso</h1>
            <p class="text-muted">Leia atentamente os termos abaixo e aceite para continuar.</p>
        </div>

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('loginEleicao.aceitarTermos') }}">
            @csrf

            <div class="mb-3">
                <div class="termos-box p-3 border rounded" style="height: 200px; overflow-y: auto; background-color: #f8f9fa;">
                    <p>{!! $dados['configuracoes']->termos !!}</p>
                </div>
            </div>

            <div class="form-check mb-4 lad">
                <input type="hidden" name="eleitor_id" value="{{ session('eleitor_id') }}">
                <input class="form-check-input cur-p" type="checkbox" value="1" id="aceitarTermos" name="aceitarTermos">
                <label class="form-check-label cur-p" for="aceitarTermos">
                    Eu li e aceito os termos de uso
                </label>
            </div>

            <div class="d-flex gap-2">
                <!-- Botão de aceitar -->
                <button type="submit" class="btn btn-hero btn-lg w-100" id="btnContinuar" disabled>
                    Concordo
                </button>

                <!-- Botão de não concordar -->
                <button type="button" class="btn btn-outline-danger btn-lg w-100" id="btnNaoConcordo" data-bs-toggle="modal" data-bs-target="#modalNaoConcordo">
                    Não concordo, sair
                </button>
            </div>
        </form>
    </div>
</section>

<!-- Modal de confirmação -->
<div class="modal fade" id="modalNaoConcordo" tabindex="-1" aria-labelledby="modalNaoConcordoLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-3">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title m-auto" id="modalNaoConcordoLabel">Confirmar saída</h5>
      </div>
      <div class="modal-body text-center">
        <p class="mb-0 fw-semibold text-dark">
          Você realmente <strong>não concorda</strong> com os termos de uso?<br>
          Caso confirme, será desconectado da eleição.
        </p>
      </div>
      <div class="modal-footer justify-content-center">
        <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancelar</button>
        <a href="{{ route('loginEleicao.logout') }}" class="btn btn-danger px-4">Sim, sair</a>
      </div>
    </div>
  </div>
</div>
@endsection
<!-- -------------- Início - Estilos CSS ------------------ -->
<style>
.hero {
  justify-content: center;
  align-items: center;
  text-align: center;
  padding: 0 2rem;
}
.hero h1 {
  color: #000000;
  font-size: 30px;
  font-weight: 700;
}
.hero .btn-hero {
  color: #fff !important;
  font-weight: 500;
  border-radius: 8px;
  transition: all 0.3s ease;
  background-color: {{ $dados['configuracoes']->cor_principal }};
}
.hero .btn-hero:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 10px rgba(0,0,0,0.2);
  background-color: {{ $dados['configuracoes']->cor_hover }};
}
.lar-mobile { min-width: 375px; width: 100%; }
.termos-box { text-align: left; font-size: 14px; line-height: 1.5; }
.cur-p { cursor: pointer; }
.lad { text-align: left; }
</style>
<!-- -------------- Final - Estilos CSS ------------------ -->
<!---------------- Início - Script Mostrar/Ocultar Senha ------------------>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const checkbox = document.getElementById('aceitarTermos');
    const btnContinuar = document.getElementById('btnContinuar');

    // Habilita botão "Concordo"
    checkbox.addEventListener('change', () => {
        btnContinuar.disabled = !checkbox.checked;
    });
});
</script>
<!---------------- Final - Script Mostrar/Ocultar Senha ------------------>