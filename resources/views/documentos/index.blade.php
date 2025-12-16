@extends('layouts.appMasterFront')
@section('title', 'Unir Votações - Documentos')
@section('content')
<section class="hero">
    <div class="aba-geral">
        <h1>Documentos</h1>

        <div class="document-list mt-4 card p-4">
            @forelse($documentos as $documento)
                <div class="document-item d-flex flex-wrap justify-content-between align-items-center gap-3 p-3 mb-2 border rounded shadow-sm">
                    <span class="doc-name text-break flex-grow-1">{{ $documento->arquivo }}</span>
                    <a href="{{ asset('storage/documentos/' . basename($documento->caminho)) }}"
                       target="_blank"
                       class="btn btn-sm btn-hero tam-botao">Ver</a>
                </div>
            @empty
                <div class="text-center text-muted py-4">
                    Nenhum documento anexado até o momento.
                </div>
            @endforelse
        </div>
    </div>
</section>
@endsection

<!-- -------------- Início - Estilos CSS ------------------ -->
<style>
    .aba-geral {
        margin: 100px 0 35px;
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
        font-size: 22px;
        font-weight: 700;
        margin-bottom: 1.5rem;
    }
    .document-list {
        margin: auto;
        width: 50%;
        background-color: #fff;
    }
    .document-item {
        transition: transform 0.2s, box-shadow 0.2s;
        background-color: #f9fafb;
    }
    .document-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .doc-name {
        color: #000000;
        font-weight: 500;
        font-size: 15px;
        line-height: 1.4;
        word-break: break-word;
    }
    .hero .btn-hero {
        display: inline-block;
        color: #ffffff;
        font-weight: 500;
        padding: 0.6rem 1.2rem;
        border-radius: 6px;
        text-decoration: none;
        transition: all 0.3s ease;
        background-color: {{ $dados['configuracoes']->cor_principal }};
        white-space: nowrap;
    }
    .hero .btn-hero:hover {
        color: #ffffff;
        font-weight: bold;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        background-color: {{ $dados['configuracoes']->cor_hover }};
    }
    @media (max-width: 992px) {
        .document-list {
            width: 90%;
            padding: 1rem;
        }
        .document-item {
            flex-direction: column;
            align-items: stretch;
            text-align: left;
            gap: 0.75rem;
            padding: 1rem;
        }
        .doc-name {
            font-size: 16px;
        }
        .tam-botao {
            width: 100%;
            text-align: center;
        }
        .hero h1 {
            font-size: 20px;
        }
        .aba-geral {
            margin: 60px 0 25px;
        }
    }

    @media (max-width: 576px) {
        .document-list {
            width: 100%;
            border-radius: 0;
            box-shadow: none;
        }
        .document-item {
            border: 1px solid #e5e7eb;
            background: #fff;
        }
        .hero {
            padding: 0 1rem;
        }
    }
</style>
<!-- -------------- Final - Estilos CSS ------------------ -->