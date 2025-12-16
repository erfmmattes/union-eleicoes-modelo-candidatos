@extends('layouts.appMasterFront')
@section('title', 'Unir Votações - Ajuda')
@section('content')
<section class="hero">
    <div class="aba-geral">
        <h1>Ajuda</h1>

        <div class="descricao-ajuda card p-4">
            <div class="accordion" id="accordionExample">
                @forelse($ajudas as $ajuda)
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading-{{ $ajuda->sequencia }}">
                        <button 
                            class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" 
                            type="button" 
                            data-bs-toggle="collapse" 
                            data-bs-target="#collapse-{{ $ajuda->sequencia }}" 
                            aria-expanded="{{ $loop->first ? 'true' : 'false' }}" 
                            aria-controls="collapse-{{ $ajuda->sequencia }}">
                            {{ $ajuda->titulo ?? "Ajuda #{$ajuda->sequencia}" }}
                        </button>
                    </h2>
                    <div 
                        id="collapse-{{ $ajuda->sequencia }}" 
                        class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}" 
                        aria-labelledby="heading-{{ $ajuda->sequencia }}" 
                        data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            {!! $ajuda->descricao !!}
                        </div>
                    </div>
                </div>
                @empty
                    <div class="text-center text-muted py-4">
                        Nenhuma descrição de ajuda até o momento.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</section>
@endsection
<!-- -------------- Início - Estilos CSS ------------------ -->
<style>
    .aba-geral {
        margin: 100px 0 35px 0;
    }
    .hero {
        justify-content: center;
        align-items: center;
        text-align: center;
        padding: 0 2rem;
        color: #fff;
    }
    .hero h1 {
        color: #000000;
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 1rem;
    }
    .descricao-ajuda {
        font-size: 16px !important;
        margin: auto;
        text-align: justify;
        width: 50%;
    }
    .accordion-button {
        font-weight: 600;
    }
    .accordion-button {
        color: #ffffff !important;
        background-color: {{ $dados['configuracoes']->cor_principal }} !important;
        transition: all 0.3s ease;
    }
    .accordion-button:hover {
        background-color: {{ $dados['configuracoes']->cor_hover }} !important;
        color: #ffffff !important;
    }
    .accordion-button:not(.collapsed) {
        background-color: {{ $dados['configuracoes']->cor_hover }} !important;
        color: #ffffff !important;
    }
    .accordion-button::after {
        filter: brightness(0) invert(1);
    }
    @media (max-width: 992px) {
        .descricao-ajuda {
            width: 100%;
        }
    }
</style>
<!-- -------------- Final - Estilos CSS ------------------ -->