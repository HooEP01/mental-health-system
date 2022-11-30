<?php

namespace App\Http\Controllers\Professional;
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
    public function show($id)
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

        return Inertia::render('Professional/Event/Show', [
            'event' => $event,
            'schedules' => $schedules,
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

    /**
     * Remove the specified event from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $event_id = $request->event_id;
        if($event_id != null){
        
        }else{
            $this->storeEvent($request);
        }
        return $this->index();
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

}