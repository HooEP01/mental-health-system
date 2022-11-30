<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

// models
use App\Models\Role;

// illuminate
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

// inertia
use Inertia\Inertia;


class RoleController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('can:admin role list', ['only' => ['index', 'show']]);
        $this->middleware('can:admin role create', ['only' => ['create', 'store']]);
        $this->middleware('can:admin role edit', ['only' => ['edit', 'update']]);
        $this->middleware('can:admin role delete', ['only' => ['destroy']]);
    }    
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = DB::table('roles')
        -> select('roles.*')
        -> orderBy('created_at', 'desc')
        -> paginate(100);

        return Inertia::render('Admin/Role/Index', [
            'roles' => $roles,
            'can' => [
                'create' => Auth::user()->can('admin role create'),
                'edit' => Auth::user()->can('admin role edit'),
                'delete' => Auth::user()->can('admin role delete'),
            ]
        ]);
    }
}