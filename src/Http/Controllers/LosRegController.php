<?php

namespace Vfjodorovs12\LosReg\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;

class LosRegController extends Controller
{
    public function index()
    {
        // 1. Получи ESI токен и ID корпорации
        $esiToken = env('EVE_ESI_TOKEN');
        $corporationId = 'ВАШ_ID_КОРПОРАЦИИ'; // Укажи свой ID!

        // 2. Получи список членов корпорации из ESI
        $client = new Client(['base_uri' => 'https://esi.evetech.net/latest/']);
        $response = $client->request('GET', "corporations/{$corporationId}/members/", [
            'headers' => [
                'Authorization' => "Bearer {$esiToken}",
                'Accept'        => 'application/json',
            ]
        ]);
        $corporationMembers = json_decode($response->getBody()->getContents(), true);

        // 3. Получи зарегистрированных пользователей из SEAT
        $registeredIds = DB::table('users')
            ->whereNotNull('character_id')
            ->pluck('character_id')
            ->toArray();

        // 4. Сравни списки
        $unregistered = array_diff($corporationMembers, $registeredIds);

        // 5. (Необязательно) Получи имена персонажей
        $unregNames = [];
        foreach (array_slice($unregistered, 0, 50) as $charId) {
            try {
                $resp = $client->request('GET', "characters/{$charId}/", [
                    'headers' => [
                        'Accept' => 'application/json',
                    ]
                ]);
                $char = json_decode($resp->getBody()->getContents(), true);
                $unregNames[] = [
                    'character_id' => $charId,
                    'name' => $char['name'] ?? $charId,
                ];
            } catch (\Throwable $e) {
                $unregNames[] = [
                    'character_id' => $charId,
                    'name' => $charId,
                ];
            }
        }

        // 6. Передай список в шаблон
        return view('los-reg::unregistered', [
            'members' => $unregNames
        ]);
    }
}
