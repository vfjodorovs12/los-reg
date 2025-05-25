<?php

namespace Vfjodorovs12\LosReg\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Seat\Eseye\Eseye;

class LosRegController extends Controller
{
    public function index()
    {
        $corporationId = 1599371034; // Твой corp id!
        $esi = new Eseye();

        try {
            $corporationMembers = $esi->invoke('get', '/corporations/{corporation_id}/members/', [
                'corporation_id' => $corporationId
            ]);
        } catch (\Exception $e) {
            return view('los-reg::unregistered', [
                'members' => [],
                'error' => 'Ошибка авторизации в ESI: ' . $e->getMessage()
            ]);
        }

        $registeredIds = DB::table('users')
            ->whereNotNull('character_id')
            ->pluck('character_id')
            ->toArray();

        $unregistered = array_diff($corporationMembers, $registeredIds);

        return view('los-reg::unregistered', [
            'members' => $unregistered,
            'error' => null
        ]);
    }
}
