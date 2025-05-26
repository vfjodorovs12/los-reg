<?php

namespace Vfjodorovs12\LosReg\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Seat\Eveapi\Models\Corporation\CorporationMember;

class LosRegController extends Controller
{
    public function index()
    {
        $corporationId = 1599371034;

        $members = CorporationMember::where('corporation_id', $corporationId)->get();

        // Получаем карту: character_id => имя пользователя SEAT (если есть связь)
        $seat_users = DB::table('users')->select('id', 'name', 'main_character_id')->get();
        $seat_names = [];
        foreach ($seat_users as $u) {
            $seat_names[$u->main_character_id] = $u->name;
        }

        foreach ($members as $member) {
            // Имя пользователя SEAT (если есть)
            $member->seat_name = $seat_names[$member->character_id] ?? '—';
            // Имя персонажа (EVE API через SEAT)
            // ВАЖНО: если имя не найдено, выводим character_id (без !!!БАГ_ИМЯ!!!)
            $member->corp_name = $member->name ?: $member->character_id;

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
            'members' => $members,
            'log' => $log,
        ]);
    }
}
