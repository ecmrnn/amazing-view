<?php

namespace App\Http\Controllers;

use App\Models\Promo;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    public function index() {
        $promos = Promo::all();
        return view('app.promos.index', [
            'promos' => $promos,
        ]);
    }
}
