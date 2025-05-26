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
            $member->seat_name = $seat_names[$member->character_id] ?? '—';
            // Имя из корпорации (EVE API, должно быть всегда!)
            $member->corp_name = $member->name ?? '!!!БАГ_ИМЯ!!!';

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

        return view('los-reg::unregistered', [
            'members' => $members,
        ]);
    }
}
