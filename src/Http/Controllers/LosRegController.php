<?php

namespace Vfjodorovs12\LosReg\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Seat\Eveapi\Models\Corporation\CorporationMember;
use Seat\Eveapi\Models\Character\CharacterInfo;

class LosRegController extends Controller
{
    public function unregistered()
    {
        $corporationId = 1599371034;

        // Альт-маппинг: character_id альта => character_id майна
        $alts_map = [
            2113730684 => 91340839, // Kite Jonsi => Clive Scott
            // Добавляй дальше
        ];

        // Получаем всех пользователей, character_id => username
        $users = DB::table('users')->select('id', 'name', 'main_character_id')->get();
        $main_map = [];
        foreach ($users as $user) {
            $main_map[$user->main_character_id] = $user->name;
        }

        $members = CorporationMember::where('corporation_id', $corporationId)->get();

        $filled = $members->map(function ($member) use ($main_map, $alts_map) {
            // Имя персонажа: сначала из character_infos, потом из corporation_members, иначе ID
            $character = CharacterInfo::find($member->character_id);
            if ($character && $character->name) {
                $member->character_name = $character->name;
            } elseif ($member->name) {
                $member->character_name = $member->name;
            } else {
                $member->character_name = 'ID:' . $member->character_id;
            }

            // Майн/альт
            if (isset($main_map[$member->character_id])) {
                $member->main_alt = 'Майн (' . $main_map[$member->character_id] . ')';
            } elseif (isset($alts_map[$member->character_id]) && isset($main_map[$alts_map[$member->character_id]])) {
                $main_name = $main_map[$alts_map[$member->character_id]];
                $member->main_alt = 'Альт (' . $main_name . ')';
            } else {
                $member->main_alt = 'Не зарегистрирован';
            }

            // Дата вступления (start_date) в формате Y-m-d
            $member->start_date_fmt = $member->start_date
                ? \Carbon\Carbon::parse($member->start_date)->format('Y-m-d')
                : ($member->created_at ? \Carbon\Carbon::parse($member->created_at)->format('Y-m-d') : '—');

            // Последний онлайн (logoff_date) в формате Y-m-d H:i
            $member->logoff_date_fmt = $member->logoff_date
                ? \Carbon\Carbon::parse($member->logoff_date)->format('Y-m-d H:i')
                : ($member->updated_at ? \Carbon\Carbon::parse($member->updated_at)->format('Y-m-d H:i') : '—');

            // Онлайн/оффлайн
            $member->status = ($member->logoff_date && \Carbon\Carbon::parse($member->logoff_date)->diffInMinutes(now()) < 15)
                ? 'Online'
                : 'Offline';

            return $member;
        });

        return view('los-reg::unregistered', [
            'members' => $filled,
        ]);
    }
}
