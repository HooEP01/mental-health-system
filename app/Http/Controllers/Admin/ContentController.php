<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Content;
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
        $contents = (new Content)->newQuery();
        $contents->latest();
        $contents = $contents->paginate(100)->onEachSide(2)->appends(request()->query());        
        return Inertia::render('Admin/Content/Index', [
            'contents' => $contents,
            'can' => [
                'create' => Auth::user()->can('admin content create'),
                'edit' => Auth::user()->can('admin content edit'),
                'delete' => Auth::user()->can('admin content delete'),
            ]
        ]);
    }
}