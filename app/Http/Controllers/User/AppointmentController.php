<?php

namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;

// models
use App\Models\User;
use App\Models\Task;
use App\Models\Event;
use App\Models\Appointment;

// illuminate
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

// inertia
use Inertia\Inertia;

class AppointmentController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('can:user appointment list', ['only' => ['index', 'show']]);
        $this->middleware('can:user appointment create', ['only' => ['create', 'store']]);
        $this->middleware('can:user appointment edit', ['only' => ['edit', 'update']]);
        $this->middleware('can:user appointment delete', ['only' => ['destroy']]);
    }    

    public function index() 
    {
        $appointments = DB::table('appointments')
        -> join('events', 'appointments.event_id', '=', 'events.id')
        -> join('users', 'appointments.user_id', '=', 'users.id')
        -> select('appointments.*', 'events.title as event_title', 'events.description as event_description', 'events.user_id as professional_id', 'events.image as event_image',  'users.name', 'users.first_name', 'users.last_name', 'users.professional_title')
        -> where('appointments.user_id', '=', Auth::id())
        -> where('appointments.status', '!=', Appointment::STATUS_BOOKED)
        -> orderBy('appointments.start_date','asc')
        -> orderBy('appointments.start_time', 'asc')
        -> paginate(100);
        

        return Inertia::render('User/Appointment/Index', [
            'appointments' => $appointments,
            'can' => [
                'create' => Auth::user()->can('user appointment create'),
                'edit' => Auth::user()->can('user appointment edit'),
                'delete' => Auth::user()->can('user appointment delete'),
            ]
        ]);
    }

    public function show($id, Request $request)
    {

        $keyword = ( $request->input('tab'))? $request->input('tab'): 'event';

        $appointment = Appointment::find($id);
        if($appointment->status == Appointment::STATUS_BOOKED){
            return $this->createPayment($id);
        }

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
        -> get();

        $professional = User::find($event->user_id);

        foreach($tasks as $task) {
            $answer = DB::table('content_answers')
            -> select('content_answers.*')
            -> where('content_answers.appointment_id', '=', $id)
            -> where('content_answers.task_id', '=', $task->id)
            -> where('content_answers.user_id', '=', Auth::Id())
            -> get(); 
            $task->answer = $answer;
        }
        

        

        return Inertia::render('User/Appointment/Show', [
            'appointment' => $appointment,
            'appointments' => $appointments,
            'event' => $event,
            'tasks' => $tasks,
            'professional' => $professional,
            'firstTab' => $keyword,
            'can' => [
                'create' => Auth::user()->can('user appointment create'),
                'edit' => Auth::user()->can('user appointment edit'),
                'delete' => Auth::user()->can('user appointment delete'),
            ]
        ]);
        
    }

    private function createPayment($id)
    {

        $appointment = Appointment::find($id);
        $event = Event::find($appointment->event_id);
        return Inertia::render('User/Payment/Create', [
            'appointment' => $appointment,
            'event' => $event,
            'can' => [
                'create' => Auth::user()->can('user appointment create'),
                'edit' => Auth::user()->can('user appointment edit'),
                'delete' => Auth::user()->can('user appointment delete'),
            ]
        ]);
    }

    /**
     * Store a newly created or edited appointment in storage 
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $appointment_id = $request->appointment_id;
        if($appointment_id != null){
            // $this->updateContent($request);
        }else{
            return $this->storeAppointment($request);
        }

        return $this->index();
    }

    public function destroy($id)
    {
        $appointment = Appointment::find($id);
        $appointment->status = Appointment::STATUS_CANCEL;
        $appointment->save();
        return redirect()->route('appointment.index');
    }

    private function storeAppointment(Request $request)
    {

        $user_id = Auth::Id();
        $status = APPOINTMENT::STATUS_BOOKED;
        $appointment = Appointment::create([
            'event_id'=>$request->event_id,
            'user_id'=>$user_id,
            'reason'=>$request->reason,
            'status'=>$status,
            'start_date'=>$request->scheduleDateTime['start_date'],
            'end_date'=>$request->scheduleDateTime['end_date'],
            'start_time'=>$request->scheduleDateTime['start_time'],
            'end_time'=>$request->scheduleDateTime['end_time'],
        ]);

        $event = Event::find($appointment->event_id);
        $price = $event->price;
        if($price == EVENT::PRICE_FREE || $price == EVENT::PRICE_FREE_STRING) {
            return rediect()->route('content.index');
        }

        return redirect()->route('appointment.show', [$appointment->id]);
    }

    
}

