<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Sklad_now_historys;
use App\Models\Sklad_nomenclature_historys;
use Illuminate\Support\Facades\Auth;

class Profile extends Controller{

    public function profile($id){
        $user = User::where('id', '=', $id)->get();
        $list_now = Sklad_now_historys::where('id_user', '=', $id)->orderby('id', 'desc')->take(10)->get();
        $list_nomenclature = Sklad_nomenclature_historys::where('id_user', '=', $id)->orderby('id', 'desc')->take(10)->get();
        return view('profile/index', ['data'=>$user, 'list_now'=>$list_now, 'list_nomenclature'=>$list_nomenclature]);
    }

}