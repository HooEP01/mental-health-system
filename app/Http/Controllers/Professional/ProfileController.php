<?php

namespace App\Http\Controllers\Professional;
use App\Http\Controllers\Controller;

// models
use App\Models\User;

// illuminate
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
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
        $this->middleware('can:professional profile list', ['only' => ['index', 'show']]);
        $this->middleware('can:professional profile create', ['only' => ['create', 'store']]);
        $this->middleware('can:professional profile edit', ['only' => ['edit', 'update']]);
        $this->middleware('can:professional profile delete', ['only' => ['destroy']]);
    }    
    
    /**
     * Display a user's information.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = DB::table('users')
        ->select('users.*')
        ->where('users.id', '=', Auth::Id())
        ->orderBy('created_at','desc')
        ->first();

        return Inertia::render('Professional/Profile/Index', [
            'user' => $user,
            'can' => [
                'create' => Auth::user()->can('professional profile create'),
                'edit' => Auth::user()->can('professional profile edit'),
                'delete' => Auth::user()->can('professional profile delete'),
            ]
        ]);
    }

    /**
     * Display a user's information
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return $this->index();
    }

    /**
     * Store a edited user's information in storage 
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $id = Auth::Id();
        $data = User::find($id);

        $image_path = '';
        if ($request->hasFile('image')) {
            $image_path = $request->file('image')->store('image', 'public');
        }

        $file_path = '';
        if ($request->hasFile('file')) {
            $file_path = $request->file('file')->store('file', 'public');
        }

        $data->image = $image_path;
        $data->file = $file_path;
        $data->professional_title = $request->professional_title;
        $data->professional_description = $request->professional_description;
        $data->professional_status = $request->professional_status;
        $data->save();

        return $this->index();
    }
}