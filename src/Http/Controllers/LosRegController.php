<?php

namespace Vfjodorovs12\LosReg\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;

class LosRegController extends Controller
{
    public function index()
    {
        return redirect()->route('los-reg.unregistered');
    }

    public function unregistered()
    {
        $esiToken = env('EVE_ESI_TOKEN');
        $corporationId = 1599371034;

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
            return view('los-reg::unregistered', [
                'members' => [],
                'error' => 'Ошибка получения списка членов корпорации: ' . $e->getMessage()
            ]);
        }

        $allMembers = [];
        if (!empty($allMemberIds)) {
            try {
                $chunks = array_chunk($allMemberIds, 1000);
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
                return view('los-reg::unregistered', [
                    'members' => [],
                    'error' => 'Ошибка получения имён персонажей: ' . $e->getMessage()
                ]);
            }
        }

        $registeredIds = DB::table('users_characters')->pluck('character_id')->toArray();

        $unregistered = [];
        foreach ($allMembers as $member) {
            if (!in_array($member['character_id'], $registeredIds)) {
                $unregistered[] = $member;
            }
        }

        return view('los-reg::unregistered', [
            'members' => $unregistered,
            'error' => null
        ]);
    }
}
