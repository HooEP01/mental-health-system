<?php

namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;

// models
use App\Models\User;

// illuminate
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

// inertia
use Inertia\Inertia;


class ProfileController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('can:user profile list', ['only' => ['index', 'show']]);
        $this->middleware('can:user profile create', ['only' => ['create', 'store']]);
        $this->middleware('can:user profile edit', ['only' => ['edit', 'update']]);
        $this->middleware('can:user profile delete', ['only' => ['destroy']]);
    }    
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = DB::table('users')
        -> select('users.*')
        -> where('users.id', '=', Auth::Id())
        -> orderBy('created_at','desc')
        -> first();

        return Inertia::render('User/Profile/Index', [
            'user' => $user,
            'genders' => USER::GENDERS,
            'relationship_statuses' => USER::RELATIONSHIP_STATUSES,
            'can' => [
                'create' => Auth::user()->can('user post create'),
                'edit' => Auth::user()->can('user post edit'),
                'delete' => Auth::user()->can('user post delete'),
            ]
        ]);
    }

    /**
     * Display a user's information
     *
     * @param $name 
     * @return \Illuminate\Http\Response
     */
    public function show($name)
    {
        if($name == 'professional'){
            return $this->professional();
        }
        return $this->index();
    }

    public function store(Request $request)
    {
        if($request->professional_status == 'none'){
            return $this->userUpdate($request);
        }else{
            $image_path = '';
            if ($request->hasFile('image')) {
                $image_path = $request->file('image')->store('image', 'public');
            }
            return $this->professionalUpdate($request, $image_path);
        }
    }

    private function userUpdate(Request $request)
    {
        $id = Auth::Id();
        $data = User::find($id);

        $data->first_name = $request->first_name;
        $data->last_name = $request->last_name;
        $data->birthday = $request->birthday;
        $data->gender = $request->gender;
        $data->relationship_status = $request->relationship_status;
        $data->contact_number = $request->contact_number;
        $data->save();
        return redirect()->route('profile.index');
    }

    private function professional()
    {
        $data = DB::table('users')
        -> select('users.*')
        -> where('users.id', '=', Auth::Id())
        -> orderBy('created_at','desc')
        -> first();

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
        $data->professional_status = USER::PROFESSINAL_STATUS_PENDING;
        $data->save();

        return redirect()->route('profile.show', ['professional']);
    }


}