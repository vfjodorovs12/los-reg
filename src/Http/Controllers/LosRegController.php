<?php

namespace Vfjodorovs12\LosReg\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Seat\Eseye\Eseye;

class LosRegController extends Controller
{
    public function index()
    {
        Log::info('LosRegController: старт index');

        $corporationId = 1599371034; // Укажи свой corp id!
        $esi = new Eseye();

        try {
            $corporationMembers = $esi->invoke('get', '/corporations/{corporation_id}/members/', [
                'corporation_id' => $corporationId
            ]);
            Log::info('LosRegController: corporationMembers', ['corporationMembers' => $corporationMembers]);
        } catch (\Exception $e) {
            Log::error('Ошибка запроса к ESI (corporation members): '.$e->getMessage());
            return view('los-reg::unregistered', [
                'members' => [],
                'error' => 'Ошибка авторизации в ESI: ' . $e->getMessage()
            ]);
        }

        if (!is_array($corporationMembers)) {
            Log::error('ESI вернул не массив для corporationMembers', ['corporationMembers' => $corporationMembers]);
            $corporationMembers = [];
        }

        $registeredIds = DB::table('users')
            ->whereNotNull('character_id')
            ->pluck('character_id')
            ->toArray();
        Log::info('LosRegController: registeredIds', ['registeredIds' => $registeredIds]);

        $unregistered = array_diff($corporationMembers, $registeredIds);
        Log::info('LosRegController: unregistered', ['unregistered' => $unregistered]);

        $unregNames = [];
        foreach (array_slice($unregistered, 0, 50) as $charId) {
            try {
                $resp = $esi->invoke('get', '/characters/{character_id}/', [
                    'character_id' => $charId
                ]);
                $unregNames[] = [
                    'character_id' => $charId,
                    'name' => isset($resp['name']) ? $resp['name'] : $charId,
                ];
            } catch (\Throwable $e) {
                Log::warning('Не удалось получить имя персонажа', [
                    'character_id' => $charId,
                    'error' => $e->getMessage()
                ]);
                $unregNames[] = [
                    'character_id' => $charId,
                    'name' => $charId,
                ];
            }
        }

        Log::info('LosRegController: к выводу', ['unregNames' => $unregNames]);

        return view('los-reg::unregistered', [
            'members' => $unregNames,
            'error' => null
        ]);
    }
}
