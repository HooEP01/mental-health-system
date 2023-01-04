<?php

namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;

// models
use App\Models\User;
use App\Models\Event;
use App\Models\Task;
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

class EventTaskController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('can:user event task list', ['only' => ['index', 'show']]);
        $this->middleware('can:user event task create', ['only' => ['create', 'store']]);
        $this->middleware('can:user event task edit', ['only' => ['edit', 'update']]);
        $this->middleware('can:user event task delete', ['only' => ['destroy']]);
    }   

    public function show($event_id, $task_id)
    {
        $event = DB::table('events')
        -> select('events.*')
        -> where('events.id', '=', $event_id)
        -> first();

        $task = DB::table('tasks')
        -> select('tasks.*')
        -> where('tasks.id', '=', $task_id)
        -> first();

        $content = DB::table('contents')
        -> select('contents.*')
        -> where('contents.id', '=', $task->content_id)
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

        return Inertia::render('User/EventTask/Show', [
            'event' => $event,
            'task' => $task,
            'content' => $content,
            'questions' => $questions,
            'can' => [
                'create' => Auth::user()->can('user event task create'),
                'edit' => Auth::user()->can('user event task edit'),
                'delete' => Auth::user()->can('user event task delete'),
            ]
        ]);

    }
}