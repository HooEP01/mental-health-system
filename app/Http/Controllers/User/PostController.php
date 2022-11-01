<?php

namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:user post list', ['only' => ['index', 'show']]);
        $this->middleware('can:user post create', ['only' => ['create', 'store']]);
        $this->middleware('can:user post edit', ['only' => ['edit', 'update']]);
        $this->middleware('can:user post delete', ['only' => ['destroy']]);
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
        return Inertia::render('User/Post/Index', [
            'posts' => $posts,
            'can' => [
                'create' => Auth::user()->can('user post create'),
                'edit' => Auth::user()->can('user post edit'),
                'delete' => Auth::user()->can('user post delete'),
            ]
        ]);
    }
}