<?php

namespace App\Http\Controllers\Professional;
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
        $this->middleware('can:professional profile list', ['only' => ['index', 'show']]);
        $this->middleware('can:professional profile create', ['only' => ['create', 'store']]);
        $this->middleware('can:professional profile edit', ['only' => ['edit', 'update']]);
        $this->middleware('can:professional profile delete', ['only' => ['destroy']]);
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

        if($data->professional_title == null){
            $data->image = null;
            $data->professional_title = "";
            $data->professional_description = "";
            $data->professional_status = "approved";
        }
 
        return Inertia::render('Professional/Profile/Index', [
            'user' => $data,
            'can' => [
                'create' => Auth::user()->can('user post create'),
                'edit' => Auth::user()->can('user post edit'),
                'delete' => Auth::user()->can('user post delete'),
            ]
        ]);
    }

    public function show()
    {
        return $this->index();
    }

    public function store(Request $request)
    {

        $id = Auth::Id();
        $data = User::find($id);

        $image_path = '';

        if ($request->hasFile('image')) {
            $image_path = $request->file('image')->store('image', 'public');
        }

        $data->image = $image_path;
        $data->professional_title = $request->professional_title;
        $data->professional_description = $request->professional_description;
        $data->professional_status = $request->professional_status;

        $data->save();

        return $this->index();
    }
}