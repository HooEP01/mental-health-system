<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

// models
use App\Models\User;
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
        $this->middleware('can:admin content list', ['only' => ['index', 'show']]);
        $this->middleware('can:admin content create', ['only' => ['create', 'store']]);
        $this->middleware('can:admin content edit', ['only' => ['edit', 'update']]);
        $this->middleware('can:admin content delete', ['only' => ['destroy']]);
    }    
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $contents = DB::table('contents')
        -> join('users', 'contents.user_id', '=' ,'users.id')
        -> select('contents.*', 'users.name', 'users.first_name', 'users.last_name', 'users.professional_title')
        -> when($request->input('search'), function($query, $search) {
            $query-> where('users.name', 'like', '%' .$search. '%')
                  -> orWhere('contents.title', 'like', '%' .$search. '%');
        }) 
        -> when($request->input('status'), function($query, $status) {
            if($status != Content::ALL){
                $query-> where('contents.status', 'like', '%' .$status. '%');
            }
        })
        -> when($request->input('category'), function($query, $category) {
            if($category != Content::ALL){
                $query-> where('contents.category', 'like', '%' .$category. '%');
            }
        })
        -> orderBy('created_at','desc')
        -> paginate(100);
        
        return Inertia::render('Admin/Content/Index', [
            'contents' => $contents,
            'can' => [
                'create' => Auth::user()->can('admin content create'),
                'edit' => Auth::user()->can('admin content edit'),
                'delete' => Auth::user()->can('admin content delete'),
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
        ->first();

        $questions = DB::table('content_questions')
        ->select('content_questions.*')
        ->where('content_questions.content_id', '=', $id)
        ->get();

        $user = DB::table('users')
        ->select('users.*')
        ->where('users.id', '=', $content->user_id)
        ->first();

        foreach($questions as $question) {
            $question->data =  json_decode($question->data);
        }

        return Inertia::render('Admin/Content/Show', [
            'content' => $content,
            'questions' => $questions,
            'user' => $user,
            'can' => [
                'create' => Auth::user()->can('admin content create'),
                'edit' => Auth::user()->can('admin content edit'),
                'delete' => Auth::user()->can('admin content delete'),
            ]
        ]);
    }

    /**
     * Display a content status
     *
     * @param \App\Models\Content $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $content = Content::find($id);
        $content->status = ($event->status != Content::STATUS_APPROVE) ? Content::STATUS_APPROVE : Content::STATUS_DISAPPROVE; 
        $content->save();

        return redirect()->route('contents_view.index');
    }

    /**
     * Remove the specified content from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $content = Content::find($id);
        $content->delete();

        return $this->index();
    }
}