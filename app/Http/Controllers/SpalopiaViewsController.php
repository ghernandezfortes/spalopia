<?php

namespace Spalopia\Http\Controllers;

use Illuminate\Http\Request;

class SpalopiaViewsController extends Controller
{
    // cargamos la vista de la app
    public function Index() {
        return view('spalopia');
    }
}
