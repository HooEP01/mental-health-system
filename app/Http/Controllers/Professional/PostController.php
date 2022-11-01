<?php

namespace App\Http\Controllers\Professional;
use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:professional post list', ['only' => ['index', 'show']]);
        $this->middleware('can:professional post create', ['only' => ['create', 'store']]);
        $this->middleware('can:professional post edit', ['only' => ['edit', 'update']]);
        $this->middleware('can:professional post delete', ['only' => ['destroy']]);
    }    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = (new Post)->newQuery();
        $posts->latest();
        $posts = $posts->paginate(100)->onEachSide(2)->appends(request()->query());        
        return Inertia::render('Professional/Post/Index', [
            'posts' => $posts,
            'can' => [
                'create' => Auth::user()->can('user post create'),
                'edit' => Auth::user()->can('user post edit'),
                'delete' => Auth::user()->can('user post delete'),
            ]
        ]);
    }

    public function create()
    {
        return Inertia::render('Professional/Post/Create');
    }

    public function store()
    {
        return Inertia::render('Professional/Post/Index');
    }
}