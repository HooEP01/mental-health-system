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
 
        return Inertia::render('User/Profile/Index', [
            'users' => $data,
            'can' => [
                'create' => Auth::user()->can('user post create'),
                'edit' => Auth::user()->can('user post edit'),
                'delete' => Auth::user()->can('user post delete'),
            ]
        ]);
    }

    public function update(Request $request)
    {
        $id = Auth::Id();

        $data = User::find($id);

        $data->first_name = $request->first_name;
        $data->last_name = $request->last_name;
        $data->birthday = $request->birthday;
        $data->genfer = $request->gender;
    }

}