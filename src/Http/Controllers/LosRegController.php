<?php

namespace Vfjodorovs12\LosReg\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Seat\Eveapi\Models\Corporation\CorporationMember;
use GuzzleHttp\Client;

class LosRegController extends Controller
{
    public function index()
    {
        $corporationId = 1599371034;

        $members = CorporationMember::where('corporation_id', $corporationId)->get();

        // Получаем character_id => name
        $character_names = \Seat\Eveapi\Models\Character\CharacterInfo::whereIn('character_id', $members->pluck('character_id'))
            ->pluck('name', 'character_id')
            ->toArray();

        $seat_users = DB::table('users')->select('id', 'name', 'main_character_id')->get();
        $seat_names = [];
        foreach ($seat_users as $u) {
            $seat_names[$u->main_character_id] = $u->name;
        }

        $client = new Client(['base_uri' => 'https://esi.evetech.net/latest/']);

        foreach ($members as $member) {
            $member->seat_name = $seat_names[$member->character_id] ?? '—';
            // Сначала пробуем взять имя из базы
            $name = $character_names[$member->character_id] ?? null;

            // Если имени нет — пробуем получить из ESI
            if (!$name) {
                try {
                    $response = $client->get("characters/{$member->character_id}/", [
                        'headers' => ['Accept' => 'application/json'],
                    ]);
                    $data = json_decode($response->getBody(), true);
                    $name = $data['name'] ?? $member->character_id;
                } catch (\Exception $e) {
                    $name = $member->character_id;
                }
            }

            $member->corp_name = $name;

            $member->start_date_fmt = $member->start_date
                ? \Carbon\Carbon::parse($member->start_date)->format('Y-m-d')
                : ($member->created_at ? \Carbon\Carbon::parse($member->created_at)->format('Y-m-d') : '—');

            $member->logoff_date_fmt = $member->logoff_date
                ? \Carbon\Carbon::parse($member->logoff_date)->format('Y-m-d H:i')
                : ($member->updated_at ? \Carbon\Carbon::parse($member->updated_at)->format('Y-m-d H:i') : '—');

            $member->status = ($member->logoff_date && \Carbon\Carbon::parse($member->logoff_date)->diffInMinutes(now()) < 15)
                ? 'Online'
                : 'Offline';
        }

        // Временный лог для проверки что работает именно этот контроллер!
        $log = 'DEBUG LOG: Members loaded: ' . $members->count() . ' at ' . now();

        return view('los-reg::unregistered', [
            'log' => $log,
            'members' => $members,
        ]);
    }

    public function unregistered()
    {
        // Ваш код для вывода unregistered
        return view('los-reg::unregistered', [
            'members' => [], // или реальные данные
            'log' => '',
        ]);
    }

    public function registered()
    {
        // Ваш код для вывода registered
        return view('los-reg::registered', [
            'members' => [], // или реальные данные
            'log' => '',
        ]);
    }
}
