<?php

namespace Modules\LosReg\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Seat\Web\Models\User;

class LosRegController extends Controller
{
    public function showUnregistered(Request $request)
    {
        // Fetch corporation members from EVE Online API
        $corporationMembers = $this->fetchCorporationMembers();

        // Fetch registered users from SEAT
        $registeredUsers = $this->fetchRegisteredUsers();

        // Compare the lists
        $unregisteredMembers = array_diff($corporationMembers, $registeredUsers);

        // Return a view with the unregistered members
        return view('LosReg::unregistered', compact('unregisteredMembers'));
    }

    private function fetchCorporationMembers()
    {
        $client = new Client();

        $corporationId = '123456789'; // Replace with your EVE Online Corporation ID
        $esiToken = env('EVE_ESI_TOKEN'); // Replace with your ESI token

        $url = "https://esi.evetech.net/latest/corporations/{$corporationId}/members/";

        try {
            $response = $client->get($url, [
                'headers' => [
                    'Authorization' => "Bearer {$esiToken}",
                    'User-Agent' => 'LosRegModule/1.0 (contact@example.com)' // Add contact email here
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
        return User::all()->pluck('name')->toArray();
    }
}
