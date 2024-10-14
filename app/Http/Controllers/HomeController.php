<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserGroup;
use App\Models\Group;
use App\Models\GroupAdvertisement;
use App\Models\Advertisement;
use App\Models\Logins;

use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $userID = Auth::user()->id;
        $user = User::where('id', $userID)->first();
        $logins = Logins::where('user_id', $userID)->get();
        $userGroup = UserGroup::where('user_id', $userID)->first();
        $group = Group::where('id', $userGroup->group_id)->first();
        $groupAdvertisement = GroupAdvertisement::where('group_id', $group->id)
                                                ->orderBy('created_at', 'asc')
                                                ->get();

        $advertisementId = [];

        foreach ($groupAdvertisement as $GA) {
            array_push($advertisementId, $GA->advertisement_id);
        }

        $advertisements = Advertisement::whereIn('id', $advertisementId)->get();

        return view('home')->with(compact('user', 'group', 'advertisements','logins'));
    }
}
