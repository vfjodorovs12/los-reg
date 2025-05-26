<?php

namespace Vfjodorovs12\LosReg\Http\Controllers;

use Illuminate\Routing\Controller;
use Seat\Eveapi\Models\Corporation\CorporationMember;
use Illuminate\Support\Facades\DB;

class LosRegController extends Controller
{
    public function index()
    {
        return redirect()->route('los-reg.unregistered');
    }

    public function unregistered()
    {
        $corporationId = 1599371034; // SovNarKom corp ID — замени на свой если нужно

        // Получаем всех членов корпорации через SEAT API
        $members = CorporationMember::where('corporation_id', $corporationId)->get();

        // Получаем зарегистрированных персонажей
        $registered_ids = DB::table('users_characters')->pluck('character_id')->toArray();

        // Оставляем только незарегистрированных
        $unregistered = $members->filter(function($member) use ($registered_ids) {
            return !in_array($member->character_id, $registered_ids);
        });

        return view('los-reg::unregistered', [
            'members' => $unregistered,
            'error' => null
        ]);
    }
}
