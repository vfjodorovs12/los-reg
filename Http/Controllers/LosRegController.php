<?php

namespace Vfjodorovs12\LosReg;

use Illuminate\Routing\Controller;

class LosRegController extends Controller
{
    public function showUnregistered()
    {
        // Возвращает вьюшку resources/views/unregistered.blade.php
        return view('losreg::unregistered');
    }
}
