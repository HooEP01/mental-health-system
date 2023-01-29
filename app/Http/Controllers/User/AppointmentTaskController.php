<?php

namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;

// models
use App\Models\User;
use App\Models\Event;
use App\Models\Task;
use App\Models\Appointment;
use App\Models\Content;
use App\Models\ContentQuestion;

// illuminate
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

// inertia
use Inertia\Inertia;
use Redirect;

class AppointmentTaskController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('can:user appointment task list', ['only' => ['index', 'show']]);
        $this->middleware('can:user appointment task create', ['only' => ['create', 'store']]);
        $this->middleware('can:user appointment task edit', ['only' => ['edit', 'update']]);
        $this->middleware('can:user appointment task delete', ['only' => ['destroy']]);
    }   

    public function show($appointment_id, $task_id)
    {
        $appointment = Appointment::find($appointment_id);
        $event = Event::find($appointment->event_id);
        $task = Task::find($task_id);
        $content = Content::find($task->content_id);
        $content->formats = json_decode($content->formats);

        $questions = DB::table('content_questions')
        ->select('content_questions.*')
        ->where('content_questions.content_id', '=', $content->id)
        ->orderBy('content_questions.index', 'asc')
        ->get();

        foreach($questions as $question) {
            $question->data =  json_decode($question->data);
        }

        return Inertia::render('User/AppointmentTask/Show', [
            'appointment' => $appointment,
            'event' => $event,
            'task' => $task,
            'content' => $content,
            'questions' => $questions,
            'can' => [
                'create' => Auth::user()->can('user appointment task create'),
                'edit' => Auth::user()->can('user appointment task edit'),
                'delete' => Auth::user()->can('user appointment task delete'),
            ]
        ]);

    }

} 