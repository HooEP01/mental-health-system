<?php

namespace App\Http\Controllers\Professional;
use App\Http\Controllers\Controller;

// models
use App\Models\Task;
use App\Models\User;
use App\Models\Event;
use App\Models\Appointment;

// illuminate
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

// inertia
use Inertia\Inertia;


class AppointmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:professional appointment list', ['only' => ['index', 'show']]);
        $this->middleware('can:professional appointment create', ['only' => ['create', 'store']]);
        $this->middleware('can:professional appointment edit', ['only' => ['edit', 'update']]);
        $this->middleware('can:professional appointment delete', ['only' => ['destroy']]);
    }   

    public function index(Request $request)
    {

        $status = $keyword = ( $request->input('status'))? $request->input('status'): "";

        if($status === "All"){
            $status = " ";
        }

        $appointments = DB::table('appointments')
        -> join('events', 'appointments.event_id', '=', 'events.id')
        -> join('users', 'appointments.user_id', '=', 'users.id')
        -> select('appointments.*', 'events.title as event_title', 'events.description as event_description', 'events.user_id as professional_id', 'users.name', 'users.first_name', 'users.last_name', 'users.professional_title')
        -> where('events.user_id', '=', Auth::Id())
        -> where('appointments.status', '!=', Appointment::STATUS_BOOKED)
        -> when($request->input('status'), function($query, $status) {
            if($status != Appointment::STATUS_ALL){
                $query-> where('appointments.status', '=', $status);
            }
        })
        -> when($request->input('search'), function($query, $search) {
            $query-> where('users.name', 'like', '%' .$search. '%')
                  -> orWhere('events.title', 'like', '%' .$search. '%');
        }) 
        -> orderBy('appointments.start_date','asc')
        -> orderBy('appointments.start_time', 'asc')
        -> paginate(100);
        


        return Inertia::render('Professional/Appointment/Index', [
            'appointments' => $appointments,
            'can' => [
                'create' => Auth::user()->can('professional appointment create'),
                'edit' => Auth::user()->can('professional appointment edit'),
                'delete' => Auth::user()->can('professional appointment delete'),
            ]
        ]);
    }

    public function show($id, Request $request){
        
        $keyword = ( $request->input('tab'))? $request->input('tab'): 'event';

        $appointment = DB::table('appointments')
        -> join('events','appointments.event_id', '=','events.id')
        -> join('users', 'appointments.user_id', '=', 'users.id')
        -> select('appointments.*', 'events.user_id as professional_id', 'users.first_name', 'users.last_name', 'users.name', 'users.email', 'users.birthday', 'users.relationship_status', 'users.contact_number')
        -> where('appointments.id', '=', $id)
        -> where('events.user_id', '=', Auth::id())
        -> first();

        $event = DB::table('events')
        -> select('events.*')
        -> where('events.id', '=', $appointment->event_id)
        -> first();

        $appointments = DB::table('appointments')
        -> join('users', 'appointments.user_id', '=', 'users.id')
        -> select('appointments.*', 'users.first_name', 'users.last_name', 'users.name', 'users.email', 'users.birthday', 'users.relationship_status', 'users.contact_number')
        -> where('appointments.event_id', '=', $event->id)
        -> where('appointments.start_date', '=', $appointment->start_date)
        -> where('appointments.start_time', '=', $appointment->start_time)
        -> get();
        

        $tasks = DB::table('tasks')
        -> join('contents', 'tasks.content_id', '=', 'contents.id')
        -> select('tasks.*', 'contents.title as content_title')
        -> where('tasks.event_id', '=', $event->id)
        -> where('tasks.category', '=', Task::CATEGORY_USER)
        -> orderBy('tasks.id', 'asc')
        -> get();

        foreach($tasks as $task) {
            $answers = DB::table('content_answers')
            -> join('users', 'content_answers.user_id', '=', 'users.id')
            -> select('content_answers.*', 'users.name as user_name')
            -> where('content_answers.task_id', '=', $task->id)
            -> get(); 
            $task->answer = $answers;
        }

        $tasks_professional = DB::table('tasks')
        -> join('contents', 'tasks.content_id', '=', 'contents.id')
        -> select('tasks.*', 'contents.title as content_title')
        -> where('tasks.event_id', '=', $event->id)
        -> where('tasks.category', '=', Task::CATEGORY_PROFESSIONAL)
        -> orderBy('tasks.id', 'asc')
        -> get();

        foreach($tasks_professional as $task_professional) {
            $answers = DB::table('content_answers')
            -> join('users', 'content_answers.user_id', '=', 'users.id')
            -> select('content_answers.*', 'users.name as user_name')
            -> where('content_answers.task_id', '=', $task_professional->id)
            -> get(); 
            $task_professional->answer = $answers;
        }

        $professional = DB::table('users')
        -> select('users.*')
        -> where('users.id', '=', $event->user_id)
        -> first();

        return Inertia::render('Professional/Appointment/Show', [
            'appointment' => $appointment,
            'appointments' => $appointments,
            'event' => $event,
            'tasks' => $tasks,
            'professional' => $professional,
            'firstTab' => $keyword,
            'tasks_professional' => $tasks_professional,
            'can' => [
                'create' => Auth::user()->can('professional event create'),
                'edit' => Auth::user()->can('professional event edit'),
                'delete' => Auth::user()->can('professional event delete'),
            ]
        ]);

    }


    /**
     * Display a appointment status
     *
     * @param \App\Models\Appointment $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $appointment = Appointment::find($id);
        
        if($appointment->status != Appointment::STATUS_APPROVE){
            $appointment->status = Appointment::STATUS_APPROVE;
        }else{
            $appointment->status = Appointment::STATUS_DISAPPROVE;
        }
        $appointment->save();

        return $this->index();
    }

}