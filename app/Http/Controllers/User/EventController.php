<?php

namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;

// models
use App\Models\User;
use App\Models\Event;
use App\Models\Schedule;
use App\Models\Appointment;
use App\Models\Task;

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
        -> where('events.status','=', Event::STATUS_APPROVE)
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
    public function show($id, Request $request)
    {
        $keyword = ($request->input('tab'))? $request->input('tab'): 'event';

        $event = DB::table('events')
        -> select('events.*')
        -> where('events.id', '=', $id)
        -> where('events.status','=', Event::STATUS_APPROVE)
        -> first();

        $professional = User::find($event->user_id);

        $schedules = DB::table('schedules')
        -> select('schedules.*')
        -> where('schedules.event_id', '=', $id)
        -> get();
        foreach($schedules as $schedule) {
            $schedule->data = json_decode($schedule->data);
        }

        $appointments = DB::table('appointments')
        -> select('appointments.*')
        -> where('appointments.event_id', '=', $event->id)
        -> when(function($query) {
            $query-> where('appointments.status', '=', Appointment::STATUS_PAID)
                  -> orWhere('appointments.status', '=', Appointment::STATUS_APPROVE);
            })
        -> orderBy('appointments.start_date','asc')
        -> orderBy('appointments.start_time', 'asc')
        -> get();

        $tasks = DB::table('tasks')
        -> join('contents', 'tasks.content_id', '=', 'contents.id')
        -> select('tasks.*', 'contents.title as content_title', 'contents.image as content_image')
        -> where('tasks.event_id', '=', $id)
        -> where('tasks.category', '=', Task::CATEGORY_USER)
        -> get();

        return Inertia::render('User/Event/Show', [
            'event' => $event,
            'schedules' => $schedules,
            'appointments' => $appointments,
            'tasks' => $tasks,
            'professional' => $professional,
            'firstTab' => $keyword,
            'can' => [
                'create' => Auth::user()->can('user event create'),
                'edit' => Auth::user()->can('user event edit'),
                'delete' => Auth::user()->can('user event delete'),
            ]
        ]);
    }


}
