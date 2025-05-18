<?php

namespace Vfjodorovs12\LosReg;

use Illuminate\Routing\Controller;

class LosRegController extends Controller
{
    public function showUnregistered()
    {
        // НЕ вызывать никаких view() вне методов контроллера!
        return view('losreg::unregistered');
    }
}
