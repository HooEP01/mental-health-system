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

// Encryption
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

// Request
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

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
        $user = User::find(Auth::id());

        try {
            $user->contact_number = Crypt::decryptString($user->contact_number);
        } catch (DecryptException $exception) {
            session()->flash('errors', $exception->errors());
            return redirect()->back();
        }

        return Inertia::render('User/Profile/Index', [
            'user' => $user,
            'genders' => USER::GENDERS,
            'relationship_statuses' => USER::RELATIONSHIP_STATUSES,
            'can' => [
                'create' => Auth::user()->can('user profile create'),
                'edit' => Auth::user()->can('user profile edit'),
                'delete' => Auth::user()->can('user profile delete'),
            ],
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
        return ($name == 'professional') ? $this->professional() : $this->index();
    }

    /**
     * Store a user's information
     *
     * @param $name 
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return ($request->professional_status) ? $this->professionalUpdate($request) : $this->userUpdate($request);
    }

    private function userUpdate(Request $request)
    {
       
        try {
            $validatedData = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'birthday' => 'required|date|before:today',
                'gender' => 'required|string|max:100',
                'relationship_status' => 'required|string|max:100',
                'contact_number' => 'required|string|max:20',
            ]);      

            $data = User::find(Auth::Id());
            $data->first_name = $request->first_name;
            $data->last_name = $request->last_name;
            $data->birthday = $request->birthday;
            $data->gender = $request->gender;
            $data->relationship_status = $request->relationship_status;
            $data->contact_number = Crypt::encryptString($validatedData['contact_number']);
            $data->save();

        } catch (Exception $exception) {
            session()->flash('errors', $exception->errors());
            return redirect()->back();
        }

        session()->flash('success', 'Your account details have been saved.');
        return redirect()->back();
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

    private function professionalUpdate(Request $request)
    {
    
        try {
            $validatedData = $request->validate([
                'image' => 'required|string|max:1000',
                'professional_title' => 'required|string|max:1000',
                'professional_description' => 'required|string',
                'professional_status' => 'required|string|max:100',
            ]); 
            
            $image_path = '';
            if ($request->hasFile('image')) {
                $image_path = $request->file('image')->store('image', 'public');
            }

            $data = User::find(Auth::Id());
            $data->image = $image_path;
            $data->professional_title = $request->professional_title;
            $data->professional_description = $request->professional_description;
            $data->professional_status = USER::PROFESSINAL_STATUS_PENDING;
            $data->save();

        } catch (Exception $exception) {
            session()->flash('errors', $exception->errors());
            return redirect()->back();
        }

        session()->flash('success', 'Your professional account details have been saved.');
        return redirect()->route('profile.show', ['professional']);
    }
}