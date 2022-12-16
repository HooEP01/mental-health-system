<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;

// models
use App\Models\User;
use App\Models\Content;
use App\Models\ContentQuestion;
use App\Models\ContentAnswer;
use App\Models\ContentQuestionAnswer;
use App\Models\Event;
use App\Models\Appointment;
use App\Models\Task;

// illuminate
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

// inertia
use Inertia\Inertia;

// Carbon
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('can:user payment list', ['only' => ['index', 'show']]);
        $this->middleware('can:user payment create', ['only' => ['create', 'store']]);
        $this->middleware('can:user payment edit', ['only' => ['edit', 'update']]);
        $this->middleware('can:user payment delete', ['only' => ['destroy']]);
    } 

    /**
     * Display a listing of contents 
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $appointments = DB::table('appointments')
        -> join('events', 'appointments.event_id', '=', 'events.id')
        -> select('appointments.*', 'events.title as event_title', 'events.id as event_id')
        -> where('appointments.user_id', '=', Auth::id())
        -> where('appointments.start_date', '>=', Carbon::now())
        -> orderBy('appointments.start_date','asc')
        -> orderBy('appointments.start_time', 'asc')
        -> get();

        foreach($appointments as $appointment) {
            $tasks = DB::table('tasks')
            -> join('contents', 'tasks.content_id', '=', 'contents.id')
            -> select('tasks.*', 'contents.title as content_title')
            -> where('tasks.category', '!=', Task::CATEGORY_DELETE)
            -> where('tasks.event_id', '=', $appointment->event_id)
            -> get();
            $appointment->task = $tasks;
        }

        $collectionAppointments = collect($appointments);
        $uniqueAppointment = $collectionAppointments->unique('event_id');
        $uniqueAppointment->values()->all();

        $answers = DB::table('content_answers')
        -> join('contents', 'content_answers.content_id', '=', 'contents.id')
        -> select('content_answers.*', 'contents.title as content_title')
        -> where('content_answers.user_id', '=', Auth::id())
        -> orderBy('updated_at', 'desc')
        -> get();

        $collectionAnswers = collect($answers);
        $uniqueAnswer = $collectionAnswers->unique('content_id');
        $uniqueAnswer->values()->all();

        $contents = DB::table('contents')
        -> select('contents.*')
        -> where('contents.status','=', Content::STATUS_APPROVE) 
        -> orderBy('created_at','desc')
        -> paginate(10);
        
        return Inertia::render('Dashboard', [
            'user'=> Auth::user(),
            'appointments' => $appointments,
            'uniqueAnswer' => $uniqueAnswer,
            'uniqueAppointment'=> $uniqueAppointment,
            'answers' => $answers,
            'can' => [
                'create' => Auth::user()->can('user appointment create'),
                'edit' => Auth::user()->can('user appointment edit'),
                'delete' => Auth::user()->can('user appointment delete'),
            ]
        ]);
    }
}