<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;

class PoliticaDePrivacidadeController extends Controller
{

    public function index()
    {
        return view('politicaDePrivacidade.index');
    }
}