<?php

namespace App\Http\Controllers\Professional;
use App\Http\Controllers\Controller;
use App\Models\Content;
use App\Models\ContentQuestion;
use App\Models\ContentAnswer;
use App\Models\ContentQuestionAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use DB;


class AnswerController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:professional answer list', ['only' => ['index', 'show']]);
        $this->middleware('can:professional answer create', ['only' => ['create', 'store']]);
        $this->middleware('can:professional answer edit', ['only' => ['edit', 'update']]);
        $this->middleware('can:professional answer delete', ['only' => ['destroy']]);
    }   

    public function index()
    {
        $data = DB::table('content_answers')
        -> join('contents', 'content_answers.content_id', '=', 'contents.id')
        -> select('content_answers.*', 'contents.title', 'contents.description', 'contents.image', 'contents.category')
        -> where('content_answers.user_id','=', Auth::id())
        -> orderBy('updated_at','desc')
        -> paginate(100);
 
        return Inertia::render('Professional/Answer/Index', [
            'answers' => $data,
            'can' => [
                'create' => Auth::user()->can('professional answer create'),
                'edit' => Auth::user()->can('professional answer edit'),
                'delete' => Auth::user()->can('professional answer delete'),
            ]
        ]);
    }

    public function show($id)
    {

        $answer = DB::table('content_answers')
        -> select('content_answers.*')
        -> where('content_answers.id', '=', $id)
        -> where('content_answers.user_id', '=', Auth::Id())
        -> first();

        $content = DB::table('contents')
        -> select('contents.*')
        -> where('contents.id', '=', $answer->content_id)
        -> first();

        $questions = DB::table('content_questions')
        -> select('content_questions.*')
        -> where('content_questions.content_id', '=', $answer->content_id)
        -> get();

        $question_answers = DB::table('content_question_answers')
        -> join('content_questions', 'content_question_answers.content_question_id', '=', 'content_questions.id')
        -> select('content_question_answers.*', 'content_questions.category as content_question_category')
        -> where('content_question_answers.content_answer_id', '=', $id)
        -> get();

        // decode json
        foreach($questions as $question) {
            $question->data =  json_decode($question->data);
        }

        $array = [];

        foreach($question_answers as $question_answer) {
            if($question_answer->content_question_category == 'checkbox'){
                $question_answer->answer =  json_decode($question_answer->answer);
            }

            $array[$question_answer->content_question_id] = $question_answer->answer;

        }


        

        return Inertia::render('Professional/Answer/Show', [
            'content' => [
                'id' => $id,
                'title' => $content->title,
                'image' => $content->image,
                'category' => $content->category,
                'status' => $content->status,
                'description' =>$content->description,
                'questions' =>$questions,
            ],
            'answer' => $answer,
            'array' => $array,
            'question_answer' => $question_answers,
        ]);

        
    }

    public function store(Request $request)
    {
        if($request->id != null){
            // update
        }else{
            // create
        }

        $this->storeAnswer($request);

        return $this->index();
    }

    private function storeAnswer(Request $request)
    {
        $contentAnswer = ContentAnswer::create([
            'content_id' => $request->content_id,
            'user_id' => Auth::Id(),
            'status' => 'hidden',
            'start_date' => date('Y-m-d H:i:s'),
            'end_date' => date('Y-m-d H:i:s'),
        ]);

        foreach ($request->answers as $question_id => $answer){
            $question = ContentQuestion::where(['id' => $question_id, 'content_id' => $request->content_id])->get();

            $questionAnswer = ContentQuestionAnswer::create([
                'content_question_id'=>$question_id,
                'content_answer_id'=>$contentAnswer->id,
                'answer'=>is_array($answer) ? json_encode($answer) : $answer,
            ]);

        }

    }


}