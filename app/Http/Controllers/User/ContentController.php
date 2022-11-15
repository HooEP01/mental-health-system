<?php

namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use DB;

class ContentController extends Controller
{
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
        $data = DB::table('contents')
        -> select('contents.*')
        -> where('contents.status','=','approve') 
        -> orderBy('created_at','desc')
        -> paginate(100);
 
        return Inertia::render('User/Content/Index', [
            'contents' => $data,
            'can' => [
                'create' => Auth::user()->can('user contents create'),
                'edit' => Auth::user()->can('user contents edit'),
                'delete' => Auth::user()->can('user contents delete'),
            ]
        ]);
    }

    // incomplete
    public function show($id)
    {

        $data = DB::table('contents')
        -> select('contents.*')
        -> where('contents.id', '=', $id)
        -> where('contents.status', '=', 'approve')
        -> first();

        $questions = DB::table('content_questions')
        -> select('content_questions.*')
        -> where('content_questions.content_id', '=', $id)
        -> get();

        // decode json
        foreach($questions as $question) {
            $question->data =  json_decode($question->data);
        }

        return Inertia::render('User/Content/Show', [
            'content' => [
                'id' => $id,
                'title' => $data->title,
                'image' => $data->image,
                'category' => $data->category,
                'status' => $data->status,
                'description' =>$data->description,
                'questions' =>$questions,
            ],
            'can' => [
                'create' => Auth::user()->can('user content create'),
                'edit' => Auth::user()->can('user content edit'),
                'delete' => Auth::user()->can('user content delete'),
            ]

        ]);

    }
}