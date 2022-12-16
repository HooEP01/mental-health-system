<?php

namespace App\Http\Controllers\Professional;
use App\Http\Controllers\Controller;

// models
use App\Models\User;
use App\Models\Event;
use App\Models\Task;
use App\Models\Content;
use App\Models\Appointment;
use App\Models\ContentQuestion;

// illuminate
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

// inertia
use Inertia\Inertia;
use Redirect;

class AppointmentContentController extends Controller
{

    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('can:professional appointment content list', ['only' => ['index', 'show']]);
        $this->middleware('can:professional appointment content create', ['only' => ['create', 'store']]);
        $this->middleware('can:professional appointment content edit', ['only' => ['edit', 'update']]);
        $this->middleware('can:professional appointment content delete', ['only' => ['destroy']]);
    }   


    public function show($appointment_id, $content_id)
    {
        $appointment = Appointment::find($appointment_id);
        
        $event = DB::table('events')
        -> select('events.*')
        -> where('events.id', '=', $appointment->event_id)
        -> first();

        $content = DB::table('contents')
        -> select('contents.*')
        -> where('contents.id', '=', $content_id)
        -> first();
        $content->formats = json_decode($content->formats);

        $questions = DB::table('content_questions')
        ->select('content_questions.*')
        ->where('content_questions.content_id', '=', $content->id)
        ->orderBy('content_questions.index', 'asc')
        ->get();

        foreach($questions as $question) {
            $question->data =  json_decode($question->data);
        }

        return Inertia::render('Professional/AppointmentContent/Show', [
            'appointment' => $appointment,
            'event' => $event,
            'content' => $content,
            'questions' => $questions,
            'can' => [
                'create' => Auth::user()->can('professional event content create'),
                'edit' => Auth::user()->can('professional event content edit'),
                'delete' => Auth::user()->can('professional event content delete'),
            ]
        ]);
    }
}