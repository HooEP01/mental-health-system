<?php

namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;

// models
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
        $this->middleware('can:user event list', ['only' => ['index', 'show']]);
        $this->middleware('can:user event create', ['only' => ['create', 'store']]);
        $this->middleware('can:user event edit', ['only' => ['edit', 'update']]);
        $this->middleware('can:user event delete', ['only' => ['destroy']]);
    }   

    /**
     * Display a listing of contents 
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $events = DB::table('events')
        -> select('events.*')
        -> where('events.status','=', 'Approve')
        -> orderBy('created_at','desc')
        -> paginate(100);
 
        return Inertia::render('User/Event/Index', [
            'events' => $events,
            'can' => [
                'create' => Auth::user()->can('user event create'),
                'edit' => Auth::user()->can('user event edit'),
                'delete' => Auth::user()->can('user event delete'),
            ]
        ]);
    }

    /**
     * Display a event with a list of schedule
     *
     * @param \App\Models\Content $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $event = DB::table('events')
        -> select('events.*')
        -> where('events.id', '=', $id)
        -> where('events.status','=', 'Approve')
        -> first();

        $schedules = DB::table('schedules')
        -> select('schedules.*')
        -> where('schedules.event_id', '=', $id)
        -> get();

        foreach($schedules as $schedule) {
            $schedule->data = json_decode($schedule->data);
        }

        return Inertia::render('User/Event/Show', [
            'event' => $event,
            'schedules' => $schedules,
            'can' => [
                'create' => Auth::user()->can('user event create'),
                'edit' => Auth::user()->can('user event edit'),
                'delete' => Auth::user()->can('user event delete'),
            ]
        ]);
    }


}
