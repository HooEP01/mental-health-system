<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

// models
use App\Models\Permission;

// illuminate
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

// inertia
use Inertia\Inertia;


class PermissionController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('can:admin permission list', ['only' => ['index', 'show']]);
        $this->middleware('can:admin permission create', ['only' => ['create', 'store']]);
        $this->middleware('can:admin permission edit', ['only' => ['edit', 'update']]);
        $this->middleware('can:admin permission delete', ['only' => ['destroy']]);
    }    
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $keyword = Request::input('search');
       
        $permissions = DB::table('permissions')
        -> select('permissions.*')
        -> where('permissions.name', 'like', '%' .$keyword. '%')
        -> orderBy('created_at','desc')
        -> paginate(20)
        -> withPath('/admin/permissions_view?search=' .$keyword);

        return Inertia::render('Admin/Permission/Index', [
            'permissions' => $permissions,
            'can' => [
                'create' => Auth::user()->can('admin permission create'),
                'edit' => Auth::user()->can('admin permission edit'),
                'delete' => Auth::user()->can('admin permission delete'),
            ]
        ]);
    }
}