<?php

namespace App\Http\Controllers\Professional;
use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use DB;


class EventController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:professional event list', ['only' => ['index', 'show']]);
        $this->middleware('can:professional event create', ['only' => ['create', 'store']]);
        $this->middleware('can:professional event edit', ['only' => ['edit', 'update']]);
        $this->middleware('can:professional event delete', ['only' => ['destroy']]);
    }   

    public function index()
    {
        $data = DB::table('events')
        -> select('events.*')
        -> where('events.user_id','=',Auth::id())
        -> orderBy('created_at','desc')
        -> paginate(100);
 
        return Inertia::render('Professional/Post/Index', [
            'events' => $data,
            'can' => [
                'create' => Auth::user()->can('professional event create'),
                'edit' => Auth::user()->can('professional event edit'),
                'delete' => Auth::user()->can('professional event delete'),
            ]
        ]);
    }

    public function show($id)
    {
        $data = DB::table('events')
        -> select('events.*')
        -> where('events.id', '=', $id)
        -> where('events.user_id', '=', Auth::Id())
        -> first();

        return Inertia::render('Professional/Post/Show', [
            'post' => [
                'id' => $id,
                'title' => $data->title,
                'image' => $data->image,
                'category' => $data->category,
                'status' => $data->status,
                'description' =>$data->description,
            ]
        ]);
    }

}