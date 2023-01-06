<?php

namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;

// models
use App\Models\Content;
use App\Models\ContentQuestion;

// illuminate
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

// inertia
use Inertia\Inertia;

class ContentController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('can:user content list', ['only' => ['index', 'show']]);
        $this->middleware('can:user content create', ['only' => ['create', 'store']]);
        $this->middleware('can:user content edit', ['only' => ['edit', 'update']]);
        $this->middleware('can:user content delete', ['only' => ['destroy']]);
    }    
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contents = DB::table('contents')
        -> select('contents.*')
        -> where('contents.status','=', Content::STATUS_APPROVE) 
        -> where('contents.category', '!=', Content::PROFESSIONAL_CATEGORY)
        -> orderBy('created_at','desc')
        -> paginate(9);

        foreach($contents as $content){
            $question = DB::table('content_questions')
            ->select('content_questions.*')
            ->where('content_questions.content_id', '=', $content->id)
            ->count();

            $content->questionCount = $question;
        }
 
        return Inertia::render('User/Content/Index', [
            'contents' => $contents,
            'can' => [
                'create' => Auth::user()->can('user contents create'),
                'edit' => Auth::user()->can('user contents edit'),
                'delete' => Auth::user()->can('user contents delete'),
            ]
        ]);
    }

    /**
     * Display a content with a list of questions
     *
     * @param \App\Models\Content $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $content = DB::table('contents')
        ->select('contents.*')
        ->where('contents.id', '=', $id)
        ->where('contents.status', '=', Content::STATUS_APPROVE)
        ->first();

        $questions = DB::table('content_questions')
        ->select('content_questions.*')
        ->where('content_questions.content_id', '=', $id)
        ->get();

        foreach($questions as $question) {
            $question->data = json_decode($question->data);
        }

        return Inertia::render('User/Content/Show', [
            'content' => $content,
            'questions' => $questions,
            'can' => [
                'create' => Auth::user()->can('user content create'),
                'edit' => Auth::user()->can('user content edit'),
                'delete' => Auth::user()->can('user content delete'),
            ]
        ]);
    }

    
}