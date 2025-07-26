<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{

    public function index(): Response
    {
        $opa = Http::get('https://dadosabertos.camara.leg.br/api/v2/deputados?ordem=ASC&ordenarPor=id&dataInicio=1900-01-01');

        return Inertia::render('dashboard');
    }
}
