<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Content;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use DB;

class ContentController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:admin content list', ['only' => ['index', 'show']]);
        $this->middleware('can:admin content create', ['only' => ['create', 'store']]);
        $this->middleware('can:admin content edit', ['only' => ['edit', 'update']]);
        $this->middleware('can:admin content delete', ['only' => ['destroy']]);
    }    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = DB::table('contents')
        -> join('users', 'contents.user_id', '=' ,'users.id')
        -> select('contents.*', 'users.name', 'users.first_name', 'users.last_name', 'users.professional_title')
        -> orderBy('created_at','desc')
        -> paginate(100);
        
        return Inertia::render('Admin/Content/Index', [
            'contents' => $data,
            'can' => [
                'create' => Auth::user()->can('admin content create'),
                'edit' => Auth::user()->can('admin content edit'),
                'delete' => Auth::user()->can('admin content delete'),
            ]
        ]);
    }

    public function show()
    {
        return $this->index();
    }

    public function edit($id)
    {
        $data = Content::find($id);

        if($data->status != "approve"){
            $data->status = "approve";
        }else{
            $data->status = "complete";
        }

        $data->save();

        return $this->index();
    }
}