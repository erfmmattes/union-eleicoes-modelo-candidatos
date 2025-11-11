<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Services\Front\HomeService;

class HomeController extends Controller
{
    protected $homeService;

    public function __construct(HomeService $homeService)
    {
        $this->homeService = $homeService;
    }

    public function index()
    {
        $dados = $this->homeService->getDadosHome();

        if (!$dados) {
            $dados = [
                'configuracoes' => null,
            ];
        }

        return view('home.index', compact('dados'));
    }
}