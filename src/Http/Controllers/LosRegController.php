<?php

namespace Vfjodorovs12\LosReg\Http\Controllers;

use Illuminate\Routing\Controller;

class LosRegController extends Controller
{
    public function index()
    {
        return view('los-reg::unregistered');
    }
}
