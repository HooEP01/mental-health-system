<?php

namespace App\Http\Controllers\Professional;
use App\Http\Controllers\Controller;

// models
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
        $this->middleware('can:professional event list', ['only' => ['index', 'show']]);
        $this->middleware('can:professional event create', ['only' => ['create', 'store']]);
        $this->middleware('can:professional event edit', ['only' => ['edit', 'update']]);
        $this->middleware('can:professional event delete', ['only' => ['destroy']]);
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
        -> where('events.user_id','=',Auth::id())
        -> orderBy('created_at','desc')
        -> paginate(100);
 
        return Inertia::render('Professional/Event/Index', [
            'events' => $events,
            'can' => [
                'create' => Auth::user()->can('professional event create'),
                'edit' => Auth::user()->can('professional event edit'),
                'delete' => Auth::user()->can('professional event delete'),
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
        $keyword = ( $request->input('tab'))? $request->input('tab'): 'event';
       

        $event = DB::table('events')
        -> select('events.*')
        -> where('events.id', '=', $id)
        -> where('events.user_id', '=', Auth::Id())
        -> first();

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

        $schedules = DB::table('schedules')
        -> select('schedules.*')
        -> where('schedules.event_id', '=', $id)
        -> get();

        $tasks = DB::table('tasks')
        -> join('contents', 'tasks.content_id', '=', 'contents.id')
        -> select('tasks.*', 'contents.title as content_title')
        -> where('tasks.event_id', '=', $id)
        -> where('tasks.appointment_id', '=', null)
        -> where('tasks.category', '=', Task::CATEGORY_USER)
        -> get();

        $tasks_professional = DB::table('tasks')
        -> join('contents', 'tasks.content_id', '=', 'contents.id')
        -> select('tasks.*', 'contents.title as content_title')
        -> where('tasks.event_id', '=', $event->id)
        -> where('tasks.category', '=', Task::CATEGORY_PROFESSIONAL)
        -> where('tasks.appointment_id', '=', null)
        -> orderBy('tasks.id', 'asc')
        -> get();

        $professional = DB::table('users')
        -> select('users.*')
        -> where('users.id', '=', $event->user_id)
        -> first();

        foreach($schedules as $schedule) {
            $schedule->data = json_decode($schedule->data);
        }

        return Inertia::render('Professional/Event/Show', [
            'event' => $event,
            'schedules' => $schedules,
            'tasks' => $tasks,
            'appointments' => $appointments,
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
     * Display create page
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Professional/Event/Create', [
            'categories' => Event::CATEGORIES,
            'statuses' => Event::STATUSES,
            'attendance_individual' => Event::ATTENDANCE_INDIVIDUAL,
        ]);
    }

    public function edit($id)
    {
        $event = DB::table('events')
        -> select('events.*')
        -> where('events.id', '=', $id)
        -> where('events.user_id', '=', Auth::Id())
        -> first();

        $schedules = DB::table('schedules')
        -> select('schedules.*')
        -> where('schedules.event_id', '=', $id)
        -> get();

        foreach($schedules as $schedule) {
            $schedule->data = json_decode($schedule->data);
        }

        return Inertia::render('Professional/Event/Edit', [
            'categories' => Event::CATEGORIES,
            'statuses' => Event::STATUSES,
            'attendance_individual' => Event::ATTENDANCE_INDIVIDUAL,
            'event' => $event,
            'schedules' => $schedules,

        ]);
    }

    /**
     * Remove the specified event from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $event_id = $request->event_id;
        if($event_id != null){
            return $this->updateEvent($request);
        }else{
            return $this->storeEvent($request);
        }
    }

    /**
     * Remove the specified content from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $event = Event::find($id);
        $event->delete();

        return $this->index();
    }

    private function storeEvent(Request $request)
    {
        $image_path = "";
        if ($request->hasFile('image')) {
            $image_path = $request->file('image')->store('image', 'public');
        }

        $attendance = "";
        if ($request->category == Event::CATEGORY_INDIVIDUAL) {
            $attendance = Event::ATTENDANCE_INDIVIDUAL;
        } else {
            $attendance = $request->attendance;
        }

        $session_length = "";
        if($request->category == Event::CATEGORY_SEMINAR) {
            $session_length = Event::SESSION_LENGTH_SEMINAR;
        } else {
            $session_length = $request->session_length;
        }

        $user_id = Auth::Id();
        $event = Event::create([
            'user_id' => $user_id,
            'image' => $image_path,
            'title' => $request->title,
            'category' => $request->category,
            'status' => $request->status,
            'attendance' => $attendance,
            'session_length' => $session_length,
            'price' => $request->price,
            'description' => $request->description,
        ]);

        if($request->schedules != null) {
            foreach ($request->schedules as $schedule) {
                $schedule['event_id'] = $event->id;
                $this->storeSchedule($schedule);
            }
        }

        return redirect()->route('events.show', [$event->id]);
    }

    public function updateEvent(Request $request) {
        $event = Event::find($request->event_id);

        $image_path = "";
        if ($request->hasFile('image')) {
            $image_path = $request->file('image')->store('image', 'public');
            $event->image = $image_path;
        }

        $event->user_id = Auth::id();
        $event->title = $request->title;
        $event->category = $request->category;
        $event->status = $request->status;
        $event->attendance = $request->attendance;
        $event->session_length = $request->session_length;
        $event->description = $request->description;
        $event->price = $request->price;

        if(!empty($request->schedules)){            

            // get ids of exist questions
            $existIds = $event->schedules()->pluck('id')->toArray();
            // get ids of new questions
            $newIds = Arr::pluck($request->schedules, 'id');
            // question to destroy
            $toDestroy = array_diff($existIds, $newIds);
            // question to store
            $toStore = array_diff($newIds, $existIds);

            Schedule::destroy($toDestroy);

            // store questions
            foreach ($request->schedules as $schedule) {
                if (in_array($schedule['id'], $toStore)) {
                    $schedule['event_id'] = $request->event_id;
                    $this->storeSchedule($schedule);
                }
            }
            // update questions

            foreach ($request->schedules as $schedule) {
                if(in_array($schedule['id'], $existIds)){
                    $this->updateSchedule($schedule);
                }
            }


        } else {
            $schedules = DB::table('schedules')
            ->select('schedules.*')
            ->where('schedules.event_id', '=', $event->id)
            ->get();
            foreach($schedules as $schedule){
                Schedule::destroy($schedule->id);
            }
        }
        // save content
        $event->save();

        return $this->index();
    }

    public function storeSchedule($schedule)
    {
        $data = "{}";
        if(!empty($schedule['data'])) {
            $data = json_encode($schedule['data']);
        }
            
        return Schedule::create([
            'event_id'=>$schedule['event_id'],
            'category'=>$schedule['category'],
            'periodical'=>$schedule['periodical'],
            'start_datetime'=>$schedule['start_datetime'],
            'end_datetime'=>$schedule['end_datetime'],
            'recurring'=>$schedule['recurring'],
            'data' => $data,           
        ]);     
    }

    private function updateSchedule($schedule)
    {
        $theSchedule = Schedule::find($schedule['id']);

        $data = $schedule['data'] ?? "";
        if($data != ""){
            if(is_array($schedule['data']) || !($schedule['data'] == "{}") ) {
                if(!empty($schedule['data'])){
                    $theSchedule->data = json_encode($schedule['data']);
                }
            }else{
                $theSchedule->data = "{}";
            }
        }
        
        $theSchedule->event_id = $schedule['event_id'];
        $theSchedule->category = $schedule['category'];
        $theSchedule->periodical = $schedule['periodical'];
        $theSchedule->start_datetime = $schedule['start_datetime'];
        $theSchedule->end_datetime = $schedule['end_datetime'];
        $theSchedule->recurring = $schedule['recurring'];
        
        return $theSchedule->save();
    }

}