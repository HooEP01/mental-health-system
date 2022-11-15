<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use DB;

// spatie - role permission
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class UserController extends Controller
{
    // spatie 
    public function __construct()
    {
        $this->middleware('can:admin user list', ['only' => ['index', 'show']]);
        $this->middleware('can:admin user create', ['only' => ['create', 'store']]);
        $this->middleware('can:admin user edit', ['only' => ['edit', 'update']]);
        $this->middleware('can:admin user delete', ['only' => ['destroy']]);
    }  

    public function index()
    {
        // users's information
        $users = DB::table('model_has_roles')
        -> join ('roles', 'model_has_roles.role_id', '=', 'roles.id')
        -> join ('users', 'model_has_roles.model_id', '=', 'users.id')
        -> select ('model_has_roles.*', 'roles.name as role_name', 'users.*')
        -> orderBy ('created_at', 'desc')
        -> paginate (100);

        return Inertia::render('Admin/User/Index', [
            'users' => $users,
            'can' => [
                'create' => Auth::user()->can('admin user create'),
                'edit' => Auth::user()->can('admin user edit'),
                'delete' => Auth::user()->can('admin user delete'),
            ]
        ]);
    }

    public function edit($id)
    {
        $user = Auth::user();

        $model = DB::table('model_has_roles')
        -> join ('roles', 'model_has_roles.role_id', '=', 'roles.id')
        -> join ('users', 'model_has_roles.model_id', '=', 'users.id')
        -> select('model_has_roles.*', 'roles.name as role_name', 'users.*')
        -> where('model_has_roles.model_id', '=', $id)
        -> first();

        $roles = DB::table('roles')
        -> select('roles.*')
        -> orderBy('created_at','desc')
        -> paginate(100);

        return Inertia::render('Admin/User/Edit', [
            'user' => $model,
            'roles' => $roles,
            'can' => [
                'create' => Auth::user()->can('user create'),
                'edit' => Auth::user()->can('user edit'),
                'delete' => Auth::user()->can('user delete'),
            ]
        ]);
    }

    public function store(Request $request)
    {
        $data = User::find($request->id);

        $data->removeRole($request->old_role_name);
        $data->assignRole($request->role_name);

        
        $data->professional_status = $request->professional_status;
        $data->save();

        return $this->index();
    }

}