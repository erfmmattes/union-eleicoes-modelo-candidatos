@extends('layouts.appMasterFront')
@section('title', 'Unir Votações - Votar')
@section('content')

@if(session('front_logado'))

{{-- ========================================================= --}}
{{--  QUANDO NÃO EXISTE ETAPA ATUAL --}}
{{-- ========================================================= --}}
@if(!$etapaAtual)
<section class="hero d-flex align-items-center justify-content-center mt-5">
    <div class="card shadow-lg border-0 rounded-4 p-4 lar-mobile" style="max-width: 500px; width: 100%;">

        <div class="text-center">
            <h2 class="h4 fw-bold text-dark">Nenhuma etapa disponível</h2>
            <p class="text-muted mt-2">
                No momento não há etapas de votação ativas para você.
            </p>
        </div>

    </div>
</section>
@endif



{{-- ========================================================= --}}
{{--  QUANDO EXISTE ETAPA ATUAL --}}
{{-- ========================================================= --}}
@if($etapaAtual)
<section class="hero d-flex align-items-center justify-content-center mt-5">
    <div class="card shadow-lg border-0 rounded-4 p-4 lar-mobile" style="max-width: 500px; width: 100%;">
        
        <div class="text-center">
            @php
                $posicaoReal = $listaEtapas->pluck('id')->search($etapaAtual->id) + 1;
            @endphp
            <h2 class="h4 fw-bold text-dark">
                {{ $etapaAtual->nome }}
                <!-- Etapa {{ $posicaoReal }} de {{ $listaEtapas->count() }} -->
            </h2>

            <div class="text-muted mt-1 mb-2">
                Etapa {{ $posicaoReal }} de {{ $listaEtapas->count() }}
                <!-- {{ $etapaAtual->nome }} -->
            </div>

            @if($etapaAtual->multipla_escolha)
                <div class="text-primary small mb-3">
                    Selecione entre {{ $etapaAtual->quantidade_minima_escolhas }}
                    e {{ $etapaAtual->quantidade_maxima_escolhas }} opções.
                </div>
            @else
                <p class="text-primary small">
                    Escolha apenas uma opção.
                </p>
            @endif
        </div>

        <form action="{{ route('votar.salvarEtapa') }}" method="POST">
            @csrf

            <input type="hidden" name="etapa_id" value="{{ $etapaAtual->id }}">

            {{-- LISTA DE ESCOLHAS --}}
            @foreach($etapaAtual->escolhas as $escolha)
                <label class="d-flex align-items-center p-3 border rounded mb-2 escolha-item">

                    {{-- RADIO ou CHECKBOX --}}
                    @if($etapaAtual->multipla_escolha)
                        <input 
                            type="checkbox" 
                            name="escolhas[]" 
                            value="{{ $escolha->id }}"
                            class="me-2 escolha-check"
                        >
                    @else
                        <input 
                            type="radio" 
                            name="escolha" 
                            value="{{ $escolha->id }}"
                            class="me-2 escolha-radio"
                        >
                    @endif

                    <div class="d-flex">
                        @if($escolha->tem_foto)
                            <div class="ms-1">
                                <img src="{{ asset('storage/'.$escolha->caminho) }}" width="60" class="rounded shadow-sm" alt="{{ $escolha->nome }}" title="{{ $escolha->nome }}">
                            </div>
                        <div class="mt-2 ms-3">
                            <strong>{{ $escolha->nome }}</strong><br>
                            @if($escolha->cargo)
                                <small class="text-muted">{{ $escolha->cargo }}</small>
                            @endif
                        </div>
                        @else
                            <div class="ms-1">
                                <strong>{{ $escolha->nome }}</strong><br>
                                @if($escolha->cargo)
                                    <small class="text-muted">{{ $escolha->cargo }}</small>
                                @endif
                            </div>
                        @endif
                    </div>

                </label>
            @endforeach

            <button type="button" id="btnAbrirModal" class="btn btn-hero btn-lg w-100">
                @if($etapaAtual->sequencia == $listaEtapas->max('sequencia'))
                    Confirmar e Finalizar
                @else
                    Confirmar e Continuar
                @endif
            </button>

            <!-- Modal de Confirmação de Voto -->
            <div class="modal fade" id="modalConfirmarVoto" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg rounded-3">

                        <div class="modal-header" style="background: {{ $dados['configuracoes']->cor_principal }};">
                            <h5 class="modal-title text-white m-auto">Confirmar Voto</h5>
                        </div>

                        <div class="modal-body text-center">
                            <i class="fas fa-exclamation-triangle fa-3x mb-3" 
                            style="color: {{ $dados['configuracoes']->cor_principal }};">
                            </i>
                            <p class="fs-6">
                                Tem certeza que deseja confirmar seu voto nesta etapa?
                            </p>
                        </div>

                        <div class="modal-footer d-flex justify-content-center">

                            <button type="button" class="btn bot-cancela px-4" data-bs-dismiss="modal">
                                Cancelar
                            </button>

                            <button type="button" id="btnConfirmarEnvio" 
                                class="btn botao-confirmar px-4"
                                style="background: {{ $dados['configuracoes']->cor_principal }}; border: none;">
                                Confirmar
                            </button>

                        </div>

                    </div>
                </div>
            </div>

            <!-- Modal de Erro de Validação -->
            <div class="modal fade" id="modalErroValidacao" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg rounded-3">

                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title m-auto">Validação Necessária</h5>
                        </div>

                        <div class="modal-body text-center">
                            <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>

                            <!-- Mensagem dinâmica -->
                            <p class="fs-6" id="textoErroValidacao"></p>
                        </div>

                        <div class="modal-footer d-flex justify-content-center">
                            <button type="button" class="btn bot-cancela px-4" data-bs-dismiss="modal">
                                Ok, entendi
                            </button>
                        </div>

                    </div>
                </div>
            </div>

        </form>
    </div>
</section>
@endif
@endif

@endsection
<!-- -------------- Início - Estillos CSS ------------------ -->
<style>
    .escolha-item:hover {
        cursor: pointer;
        background: #f8f9fa;
    }
    .escolha-item input {
        transform: scale(1.2);
    }
    .btn-hero {
        color: #ffffff !important;
        font-weight: 500 !important;
        padding: 0.75rem 2rem !important;
        border-radius: 8px !important;
        text-decoration: none !important;
        transition: all 0.3s ease !important;
        background-color: {{ $dados['configuracoes']->cor_principal }} !important;
    }
    .btn-hero:hover {
        color: #ffffff !important;
        font-weight: bold !important;
        transform: translateY(-2px) !important;
        box-shadow: 0 4px 10px rgba(0,0,0,0.2) !important;
        background-color: {{ $dados['configuracoes']->cor_hover }} !important;
    }
    input[type="checkbox"].escolha-check,
    input[type="radio"].escolha-radio {
        accent-color: {{ $dados['configuracoes']->cor_principal }};
    }
    .botao-confirmar {
        background-color: #3498db !important;
        color: #fff !important;
    }
    .botao-confirmar:hover {
        background: linear-gradient(135deg, #3498db) !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        font-weight: 600 !important;
    }
    .bot-cancela {
        background: linear-gradient(135deg, #6c757d, #6c757d);
        color: #fff !important;
    }
    .bot-cancela:hover {
        background: linear-gradient(135deg, #6c757d, #6c757d);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        font-weight: 600 !important;

    }
    .escolha-item {
    transition: border 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    .escolha-item.selecionado {
        border: 3px solid #dee2e6 !important; /* Cor da borda ao selecionar */
        /* box-shadow: 0 0 8px rgba(24, 63, 119, 0.4); */
    }
</style>
<!-- -------------- Final - Estillos CSS ------------------ -->
<!---------------- Início - Script ------------------>
@if($etapaAtual)
<script>
document.addEventListener('DOMContentLoaded', function () {

    /* IMPORTANTE: valores dinâmicos */
    const multipla = {{ $etapaAtual->multipla_escolha ? 'true' : 'false' }};
    const max = {{ $etapaAtual->quantidade_maxima_escolhas ?? 1 }};
    const min = {{ $etapaAtual->quantidade_minima_escolhas ?? 1 }};

    const brancoIDs = @json(
        $etapaAtual->escolhas->where('branco_nulo_abstencao', 1)->pluck('id')
    );

    const form = document.querySelector("form");
    const modalConfirmar = new bootstrap.Modal(document.getElementById('modalConfirmarVoto'));
    const modalErro = new bootstrap.Modal(document.getElementById('modalErroValidacao'));
    const textoErro = document.getElementById('textoErroValidacao');

    const checkboxes = document.querySelectorAll('.escolha-check');
    const radios = document.querySelectorAll('.escolha-radio');

    /* FUNÇÃO PARA EXIBIR ERRO */
    function mostrarErro(msg) {
        textoErro.innerText = msg;
        modalErro.show();
    }


    /* ============================
       MULTIPLA ESCOLHA
    ============================ */
    if (multipla) {

        checkboxes.forEach(chk => {
            chk.addEventListener('change', function () {

                const selected = document.querySelectorAll('.escolha-check:checked');
                const isBranco = brancoIDs.includes(parseInt(this.value));

                if (isBranco && this.checked) {
                    checkboxes.forEach(c => { if (c !== this) c.checked = false; });
                    return;
                }

                if (!isBranco) {
                    checkboxes.forEach(c => {
                        if (brancoIDs.includes(parseInt(c.value))) c.checked = false;
                    });
                }

                if (selected.length > max) {
                    this.checked = false;
                    return mostrarErro(`Você só pode selecionar até ${max} opções.`);
                }

            });
        });
    }


    /* ============================
       ESCOLHA ÚNICA
    ============================ */
    radios.forEach(r => {
        r.addEventListener('change', function () {
            const isBranco = brancoIDs.includes(parseInt(this.value));

            if (isBranco) {
                radios.forEach(r2 => {
                    if (parseInt(r2.value) !== parseInt(this.value)) r2.checked = false;
                });
            }
        });
    });


    /* ============================
       BOTÃO ABRIR MODAL
    ============================ */
    document.getElementById('btnAbrirModal').addEventListener('click', function () {

        if (multipla) {
            const count = document.querySelectorAll('.escolha-check:checked').length;

            if (count < min) {
                return mostrarErro(`Você deve selecionar pelo menos ${min} opção(ões).`);
            }
        }

        if (!multipla) {
            const escolhido = document.querySelector('.escolha-radio:checked');
            if (!escolhido) {
                return mostrarErro('Você deve selecionar uma opção.');
            }
        }

        modalConfirmar.show();
    });


    /* ============================
       CONFIRMAR VOTO
    ============================ */
    document.getElementById('btnConfirmarEnvio').addEventListener('click', function () {
        modalConfirmar.hide();
        form.submit();
    });

});
</script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const itens = document.querySelectorAll('.escolha-item');

    const multipla = {{ $etapaAtual->multipla_escolha ? 'true' : 'false' }};
    const max = {{ $etapaAtual->quantidade_maxima_escolhas ?? 1 }};
    const min = {{ $etapaAtual->quantidade_minima_escolhas ?? 1 }};
    const brancoIDs = @json(
        $etapaAtual->escolhas->where('branco_nulo_abstencao', 1)->pluck('id')
    );

    function atualizarBordas() {
        itens.forEach(item => {
            const input = item.querySelector('input');
            if (input.checked) {
                item.classList.add('selecionado');
            } else {
                item.classList.remove('selecionado');
            }
        });
    }

    itens.forEach(item => {
        const input = item.querySelector('input');

        input.addEventListener('change', () => {

            const isBranco = brancoIDs.includes(parseInt(input.value));

            /* =============================
                RADIO (Escolha única)
            ============================== */
            if (input.type === "radio") {
                itens.forEach(i => i.classList.remove('selecionado'));
                item.classList.add('selecionado');
                return;
            }

            /* =============================
                CHECKBOX (Múltipla escolha)
            ============================== */

            const selecionados = document.querySelectorAll('.escolha-check:checked');

            // ✔ Branco selecionado → desmarca todos os outros
            if (isBranco && input.checked) {
                document.querySelectorAll('.escolha-check').forEach(chk => {
                    if (chk !== input) chk.checked = false;
                });
                atualizarBordas();
                return;
            }

            // ✔ Selecionou uma opção normal → desmarca brancos
            if (!isBranco) {
                document.querySelectorAll('.escolha-check').forEach(chk => {
                    if (brancoIDs.includes(parseInt(chk.value))) {
                        chk.checked = false;
                    }
                });
            }

            // ✔ Passou do limite → desfaz seleção + tira borda
            if (selecionados.length > max) {
                input.checked = false;
                item.classList.remove('selecionado');
                return;
            }

            atualizarBordas();
        });
    });
});
</script>
@endif
<!---------------- Final - Script ------------------>