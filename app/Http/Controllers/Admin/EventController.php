<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

// models
use App\Models\User;
use App\Models\Event;
use App\Models\Schedule;

// illuminate
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

// inertia
use Inertia\Inertia;

class EventController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('can:admin event list', ['only' => ['index', 'show']]);
        $this->middleware('can:admin event create', ['only' => ['create', 'store']]);
        $this->middleware('can:admin event edit', ['only' => ['edit', 'update']]);
        $this->middleware('can:admin event delete', ['only' => ['destroy']]);
    }    

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $events = DB::table('events')
        -> join('users', 'events.user_id', '=' ,'users.id')
        -> select('events.*', 'users.name', 'users.first_name', 'users.last_name', 'users.professional_title')
        -> orderBy('created_at','desc')
        -> paginate(100);
        
        return Inertia::render('Admin/Event/Index', [
            'events' => $events,
            'can' => [
                'create' => Auth::user()->can('admin event create'),
                'edit' => Auth::user()->can('admin event edit'),
                'delete' => Auth::user()->can('admin event delete'),
            ]
        ]);
    }


    /**
     * Display a content status
     *
     * @param \App\Models\Content $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $event = Event::find($id);
        $event->status = ($event->status != EVENT::STATUS_APPROVE) ? EVENT::STATUS_APPROVE : EVENT::STATUS_DISAPPROVE; 
        $event->save();

        return $this->index();
    }
}
