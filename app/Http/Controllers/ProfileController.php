<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Group;
use App\Models\User;
use App\Models\UserGroup;
use App\Models\GroupAdvertisement;
use App\Models\Advertisement;
use App\Models\Logins;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $userId = Auth::user()->id;
        $user = User::where('id', $userId)->first();
        $logins = Logins::where('user_id', $userId)
                        ->orderBy('created_at', 'asc')
                        ->get();
        $userGroup = UserGroup::where('user_id', $userId)->first();
        $group = Group::where('id', $userGroup->group_id)->first();
        $groupAdvertisement = GroupAdvertisement::where('group_id', $group->id)
                                                ->orderBy('created_at', 'asc')
                                                ->get();

        $events = [];
        $advertisementId = [];

        for ($i=0; $i < count($logins); $i++) { 
            $log = $logins[$i];
            $date_obj = $log->created_at;

            if ($log->login) {
                array_push($events, [
                    'title' => $date_obj->format('g:i A'),
                    'start' => $date_obj->format('Y-m-d'),
                    'color' => '#34C38F',
                    'icon' => 'uil-arrow-to-right'
                ]);
            } else {
                array_push($events, [
                    'title' => $date_obj->format('g:i A'),
                    'start' => $date_obj->format('Y-m-d'),
                    'color' => '#F46A6A',
                    'icon' => 'uil-left-arrow-to-left'
                ]);
            };
        };
        foreach ($groupAdvertisement as $GA) {
            array_push($advertisementId, $GA->advertisement_id);
        };

        $advertisements = Advertisement::whereIn('id', $advertisementId)
                                        ->orderBy('created_at', 'desc')
                                        ->get();

        return view('profile', compact('user','events','advertisements','group'));
    }
}