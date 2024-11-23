<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Group;
use App\Models\User;
use App\Models\UserGroup;
use App\Models\GroupAdvertisement;
use App\Models\Advertisement;
use App\Models\Logins;
use App\Models\Horario;
use App\Models\Day;
use App\Models\File;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdvertisementsController extends Controller
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
        $advertisements = Advertisement::get();
        $groups = Group::get();
        $groups_advertisements = GroupAdvertisement::select(
            'groups_advertisements.id  as id',
            'groups_advertisements.advertisement_id  as advertisement_id',
            'groups.id as group_id',
            'groups.name as group_name'
        )
            ->leftjoin('groups', 'groups.id', '=', 'groups_advertisements.group_id')
            ->get();

        return view('advertisements', compact('advertisements','groups','groups_advertisements'));
    }

    public function validateModalForm()
    {

        $messages = [
            'title.required'  => 'Debe completar el título.',
        ];

        $rules = [
            'title'           => 'required',
        ];

        request()->validate($rules, $messages);
        return request()->all();
    }

    public function SaveAdvertisement(Request $request)
    {

        $this->validateModalForm();

        $id = request('id');
        $title = request('title');
        $text = request('text');
        $upload_id = NULL;
        $group_advertisement = request('group_advertisement_ids');
        $state = 1;

        if ($request->hasFile('file')) {
            $fileUpload = $request->file('file');
            $fileName = pathinfo($fileUpload->getClientOriginalName(), PATHINFO_FILENAME);
            $fileExtension = $fileUpload->getClientOriginalExtension();

            $uniqueFileName = $fileName . '_' . time() . '.' . $fileExtension;

            $path = $fileUpload->storeAs('public/uploads', $uniqueFileName);

            $file = new File();
            $file->name = $uniqueFileName;
            $file->path = $path;
            $file->created_at_user = Auth::user()->name;
            $file->save();

            $upload_id = $file->id;
        }

        if (isset($id)) {
            $advertisement =  Advertisement::findOrFail($id);
            $msg = 'Registro actualizado exitosamente';
            $advertisement->updated_at_user = Auth::user()->name;
        } else {
            $advertisement = new Advertisement();
            $advertisement->state = $state;
            $advertisement->created_at_user = Auth::user()->name;
            $msg = 'Registro creado exitosamente';
        }

        $advertisement->title = $title;
        $advertisement->text = $text;

        if ($upload_id) {
            $advertisement->upload_id = $upload_id;
        }

        $advertisement->save();

        if (isset($id)) {
            GroupAdvertisement::where('advertisement_id', $id)->delete();
        }

        $groupAdvertisement = [];

        foreach ($group_advertisement as $groupId) {
            $groupAdvertisement[] = [
                'advertisement_id' => $advertisement->id,
                'group_id' => $groupId,
                'created_at_user' => Auth::user()->name,
            ];
        }

        GroupAdvertisement::insert($groupAdvertisement);

        $advertisements = Advertisement::get();
        $groups = Group::get();
        $groups_advertisements = GroupAdvertisement::select(
            'groups_advertisements.id  as id',
            'groups_advertisements.advertisement_id  as advertisement_id',
            'groups.id as group_id',
            'groups.name as group_name'
        )
            ->leftjoin('groups', 'groups.id', '=', 'groups_advertisements.group_id')
            ->get();

        return redirect()->back()->with([
            'advertisements' => $advertisements,
            'groups' => $groups,
        ]);
    }

    public function getAdvertisement()
    {
        $id = request('id');
        $advertisement = Advertisement::findOrFail($id);
        $groups_advertisements = GroupAdvertisement::where('advertisement_id', $id)
                                                            ->leftjoin('groups', 'groups.id', '=', 'groups_advertisements.group_id')
                                                            ->select(
                                                                'groups.id as id',
                                                                'groups.name as name',
                                                            )
                                                            ->get();

        return response()->json([
                                'advertisement'       => $advertisement,
                                'group_advertisement' => $groups_advertisements,
                            ]);
    }

    public function DeleteAdvertisement($id)
    {
        $element = Advertisement::findOrFail($id);
        $element->delete();

        $msg = 'Registro eliminado exitosamente';
        $type = 1;
        $title = '¡Ok!';

        return response()->json([
            'type'    => $type,
            'title'    => $title,
            'msg'    => $msg,
        ]);
    }

    public function DeshabilitarAdvertisement($id)
    {
        $element = Advertisement::findOrFail($id);
        $element->state = 0;
        $element->save();

        $msg = 'Registro deshabilitado exitosamente';
        $type = 1;
        $title = '¡Ok!';

        return response()->json([
            'type'    => $type,
            'title'    => $title,
            'msg'    => $msg,
        ]);
    }

    public function HabilitarAdvertisement($id)
    {
        $element = Advertisement::findOrFail($id);
        $element->state = 1;
        $element->save();

        $msg = 'Registro habilitado exitosamente';
        $type = 1;
        $title = '¡Ok!';

        return response()->json([
            'type'    => $type,
            'title'    => $title,
            'msg'    => $msg,
        ]);
    }
}