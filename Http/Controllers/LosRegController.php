<?php

namespace Vfjodorovs12\LosReg\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Seat\Web\Models\User;

class LosRegController extends Controller
{
    public function showUnregistered(Request $request)
    {
        // Получаем список членов корпорации через ESI
        $corporationMembers = $this->fetchCorporationMembers();

        // Получаем зарегистрированных пользователей SEAT
        $registeredUsers = $this->fetchRegisteredUsers();

        // Вычисляем незарегистрированных пользователей
        $unregisteredMembers = array_diff($corporationMembers, $registeredUsers);

        // Возвращаем view (только маленькие буквы!)
        return view('losreg::unregistered', compact('unregisteredMembers'));
    }

    private function fetchCorporationMembers()
    {
        $client = new Client();

        $corporationId = env('EVE_CORPORATION_ID', '123456789'); // Укажи свой ID
        $esiToken = env('EVE_ESI_TOKEN');

        $url = "https://esi.evetech.net/latest/corporations/{$corporationId}/members/";

        try {
            $response = $client->get($url, [
                'headers' => [
                    'Authorization' => "Bearer {$esiToken}",
                    'User-Agent' => 'LosRegModule/1.0 (contact@example.com)'
                ],
            ]);
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            \Log::error("Error fetching corporation members: " . $e->getMessage());
            return [];
        }
    }

    private function fetchRegisteredUsers()
    {
        // Проверь, как называется поле с именем персонажа в твоей версии SEAT
        return User::all()->pluck('name')->toArray();
    }
}
