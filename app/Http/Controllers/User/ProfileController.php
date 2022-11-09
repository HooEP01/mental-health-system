<?php

namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use DB;


class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:user profile list', ['only' => ['index', 'show']]);
        $this->middleware('can:user profile create', ['only' => ['create', 'store']]);
        $this->middleware('can:user profile edit', ['only' => ['edit', 'update']]);
        $this->middleware('can:user profile delete', ['only' => ['destroy']]);
    }    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = DB::table('users')
        -> select('users.*')
        -> where('users.id', '=', Auth::Id())
        -> orderBy('created_at','desc')
        -> first();

        if($data->first_name == null){
            $data->first_name = "";
            $data->last_name = "";
            $data->birthday = "";
            $data->gender = "";
            $data->relationship_status = "";
            $data->contact_number = "";
            $data->professional_status = "";
        }
 
        return Inertia::render('User/Profile/Index', [
            'user' => $data,
            'can' => [
                'create' => Auth::user()->can('user post create'),
                'edit' => Auth::user()->can('user post edit'),
                'delete' => Auth::user()->can('user post delete'),
            ]
        ]);
    }

    public function show($name)
    {
        if($name == 'professional'){
            return $this->professional();
        }
        return $this->index();
    }

    public function store(Request $request)
    {

        $status = $request->professional_status;
        if($status == "setup" || $status == "pending"){
            $image_path = '';
            if ($request->hasFile('image')) {
                $image_path = $request->file('image')->store('image', 'public');
            }
            return $this->professionalUpdate($request, $image_path);
        }

        $id = Auth::Id();

        $data = User::find($id);

        $data->first_name = $request->first_name;
        $data->last_name = $request->last_name;
        $data->birthday = $request->birthday;
        $data->gender = $request->gender;
        $data->relationship_status = $request->relationship_status;
        $data->contact_number = $request->contact_number;

        $data->save();

        return $this->index();
    }

    private function professional()
    {
        $data = DB::table('users')
        -> select('users.*')
        -> where('users.id', '=', Auth::Id())
        -> orderBy('created_at','desc')
        -> first();

        if($data->professional_title == null){
            $data->image = null;
            $data->professional_title = "";
            $data->professional_description = "";
            $data->professional_status = "setup";
        }
 
        return Inertia::render('User/Profile/Professional', [
            'user' => $data,
            'can' => [
                'create' => Auth::user()->can('user post create'),
                'edit' => Auth::user()->can('user post edit'),
                'delete' => Auth::user()->can('user post delete'),
            ],
        ]);
    }

    public function professionalUpdate(Request $request, $image_path)
    {
        $id = Auth::Id();

        $data = User::find($id);

        $data->image = $image_path;
        $data->professional_title = $request->professional_title;
        $data->professional_description = $request->professional_description;
        $data->professional_status = "pending";

        $data->save();

        return $this->index();
    }


}