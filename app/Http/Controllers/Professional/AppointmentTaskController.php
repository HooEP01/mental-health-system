<?php

namespace App\Http\Controllers\Professional;
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
        $this->middleware('can:professional appointment task list', ['only' => ['index', 'show']]);
        $this->middleware('can:professional appointment task create', ['only' => ['create', 'store']]);
        $this->middleware('can:professional appointment task edit', ['only' => ['edit', 'update']]);
        $this->middleware('can:professional appointment task delete', ['only' => ['destroy']]);
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

        return Inertia::render('Professional/AppointmentTask/Show', [
            'appointment' => $appointment,
            'event' => $event,
            'task' => $task,
            'content' => $content,
            'questions' => $questions,
            'can' => [
                'create' => Auth::user()->can('professional event task create'),
                'edit' => Auth::user()->can('professional event task edit'),
                'delete' => Auth::user()->can('professional event task delete'),
            ]
        ]);

    }

    /**
     * Display create page
     *
     * @return \Illuminate\Http\Response
     */
    public function create($appointment_id)
    {
        $appointment = Appointment::find($appointment_id);
        $event = Event::find($appointment->event_id);

        $contents = DB::table('contents')
        -> join('users', 'contents.user_id', '=', 'users.id')
        -> select('contents.*', 'users.name')
        -> where('contents.status', '=', Content::STATUS_APPROVE)
        -> get();

        foreach($contents as $content){
            $question = DB::table('content_questions')
            ->select('content_questions.*')
            ->where('content_questions.content_id', '=', $content->id)
            ->count();

            $content->questionCount = $question;
        }

        return Inertia::render('Professional/AppointmentTask/Create', [
            'event' => $event,
            'appointment' => $appointment,
            'contents' => $contents,
            'categories' => Task::CATEGORIES,
            'statuses' => Content::STATUSES,
            'formats' => Content::FORMATS,
            'can' => [
                'create' => Auth::user()->can('professional event task create'),
                'edit' => Auth::user()->can('professional event task edit'),
                'delete' => Auth::user()->can('professional event task delete'),
            ]
        ]);
    }

    public function edit($appointment_id, $task_id)
    {
        $appointment = Appointment::find($appointment_id);
        $event = Event::find($appointment->event_id);

        $task = Task::find($task_id);

        $theContent = null;
        if($task->content_id != null)
            $theContent = Content::find($task->content_id);

        $contents = DB::table('contents')
        -> join('users', 'contents.user_id', '=', 'users.id')
        -> select('contents.*', 'users.name')
        -> where('contents.status', '=', Content::STATUS_APPROVE)
        -> get();

        foreach($contents as $content){
            $question = DB::table('content_questions')
            ->select('content_questions.*')
            ->where('content_questions.content_id', '=', $content->id)
            ->count();

            $content->questionCount = $question;
        }

        return Inertia::render('Professional/AppointmentTask/Edit', [
            'event' => $event,
            'contents' => $contents,
            'categories' => Task::CATEGORIES,
            'statuses' => Content::STATUSES,
            'formats' => Content::FORMATS,
            'task' => $task,
            'theContent' => $theContent,
            'appointment' => $appointment,
            'can' => [
                'create' => Auth::user()->can('professional event task create'),
                'edit' => Auth::user()->can('professional event task edit'),
                'delete' => Auth::user()->can('professional event task delete'),
            ]
        ]);

    }

    /**
     * Remove the specified event from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $appointment_id)
    {
        $task_id = $request->task_id;
        if($task_id != null){   
            return $this->updateTask($request, $appointment_id);
        }else{
            return $this->storeTask($request, $appointment_id);
        }
    }

    public function storeTask(Request $request, $appointment_id)
    {
        $appointment = Appointment::find($appointment_id);
        $event = Event::find($appointment->event_id);
        $task = Task::create([
            'appointment_id' => $appointment->id,
            'event_id' => $event->id,
            'content_id' => $request->content_id,
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
        ]);
        return redirect()->route('appointments.show', [$appointment_id , 'tab' => 'task']);
    }

    public function updateTask(Request $request, $appointment_id)
    {
        $task = Task::find($request->task_id);
        $task->event_id = $request->event_id;
        $task->appointment_id = $appointment_id;
        $task->content_id = $request->content_id;
        $task->title = $request->title;
        $task->description = $request->description;
        $task->category = $request->category;
        $task->save();

        return redirect()->route('appointments.show', [$appointment_id , 'tab' => 'task']);
    }

    public function destroy($appointment_id, $task_id)
    {
        $task = Task::find($task_id);
        $task->category = Task::CATEGORY_DELETE;
        $task->save();

        return redirect()->route('appointments.show', [$appointment_id , 'tab' => 'task']);
    }
} 