<?php

namespace App\Http\Controllers\Professional;
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
        $this->middleware('can:professional event task list', ['only' => ['index', 'show']]);
        $this->middleware('can:professional event task create', ['only' => ['create', 'store']]);
        $this->middleware('can:professional event task edit', ['only' => ['edit', 'update']]);
        $this->middleware('can:professional event task delete', ['only' => ['destroy']]);
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

        return Inertia::render('Professional/EventTask/Show', [
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
    public function create($event_id)
    {
        $event = DB::table('events')
        -> select('events.*')
        -> where('events.id', '=', $event_id)
        -> first();

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

        return Inertia::render('Professional/EventTask/Create', [
            'event' => $event,
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

    public function edit($event_id, $task_id)
    {
        $event = Event::find($event_id);

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

        return Inertia::render('Professional/EventTask/Edit', [
            'event' => $event,
            'contents' => $contents,
            'categories' => Task::CATEGORIES,
            'statuses' => Content::STATUSES,
            'formats' => Content::FORMATS,
            'task' => $task,
            'theContent' => $theContent,
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
    public function store(Request $request, $event_id)
    {
        $task_id = $request->task_id;
        if($task_id != null){
            return $this->updateTask($request, $event_id);
        }else{
            return $this->storeTask($request, $event_id);
        }
    }

    public function storeTask(Request $request, $event_id)
    {
        $task = Task::create([
            'event_id' => $event_id,
            'content_id' => $request->content_id,
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
        ]);
        $tab = 'task';
        if($task->category == Task::CATEGORY_PROFESSIONAL){
            $tab = 'report';
        }

        return redirect()->route('events.show', [$event_id , 'tab' => $tab]);
    }

    public function updateTask(Request $request, $event_id)
    {
        $task = Task::find($request->task_id);
        $task->event_id = $request->event_id;
        $task->content_id = $request->content_id;
        $task->title = $request->title;
        $task->description = $request->description;
        $task->category = $request->category;
        $task->save();

        $tab = 'task';
        if($task->category == Task::CATEGORY_PROFESSIONAL){
            $tab = 'report';
        }
            
        return redirect()->route('events.show', [$event_id , 'tab' => $tab]);
    }

    public function destroy($event_id, $task_id)
    {
        $task = Task::find($task_id);

        $tab = 'task';
        if($task->category == Task::CATEGORY_PROFESSIONAL){
            $tab = 'report';
        }


        $task->category = Task::CATEGORY_DELETE;
        $task->save();

        
            
        return redirect()->route('events.show', [$event_id , 'tab' => $tab]);
    }
}