<?php

namespace Vfjodorovs12\LosReg\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;

class LosRegController extends Controller
{
    public function index()
    {
        // Можно оставить пустым или сделать редирект на unregistered
        return redirect()->route('los-reg.unregistered');
    }

    public function unregistered()
    {
        // --- 1. Получить всех членов корпорации через ESI (или SEAT API)
        $esiToken = env('EVE_ESI_TOKEN');
        $corporationId = 1599371034; // <-- твой corporation_id SovNarKom

        // Используем ESI для примера, как в README
        $client = new Client([
            'base_uri' => 'https://esi.evetech.net/latest/',
            'timeout'  => 10.0,
        ]);

        try {
            $response = $client->get("corporations/{$corporationId}/members/", [
                'headers' => [
                    'Authorization' => "Bearer $esiToken",
                    'Accept'        => 'application/json',
                ]
            ]);
            $allMemberIds = json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            return view('unregistered', [
                'members' => [],
                'error' => 'Ошибка получения списка членов корпорации: ' . $e->getMessage()
            ]);
        }

        // --- 2. Получить имена персонажей через ESI bulk lookup
        $allMembers = [];
        if (!empty($allMemberIds)) {
            try {
                $chunks = array_chunk($allMemberIds, 1000); // ESI ограничение
                foreach ($chunks as $chunk) {
                    $res = $client->post('characters/names/', [
                        'json' => $chunk,
                        'headers' => [
                            'Accept' => 'application/json',
                        ],
                    ]);
                    $names = json_decode($res->getBody(), true);
                    foreach ($names as $char) {
                        $allMembers[$char['character_id']] = [
                            'character_id' => $char['character_id'],
                            'name' => $char['character_name'],
                        ];
                    }
                }
            } catch (\Exception $e) {
                return view('unregistered', [
                    'members' => [],
                    'error' => 'Ошибка получения имён персонажей: ' . $e->getMessage()
                ]);
            }
        }

        // --- 3. Получить зарегистрированных персонажей из SEAT
        // Таблица seat: users_characters, поле character_id
        $registeredIds = DB::table('users_characters')->pluck('character_id')->toArray();

        // --- 4. Оставить только незарегистрированных
        $unregistered = [];
        foreach ($allMembers as $member) {
            if (!in_array($member['character_id'], $registeredIds)) {
                $unregistered[] = $member;
            }
        }

        // --- 5. Передать в шаблон
        return view('unregistered', [
            'members' => $unregistered,
            'error' => null
        ]);
    }
}
