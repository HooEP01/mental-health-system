<?php

namespace App\Http\Controllers\Professional;
use App\Http\Controllers\Controller;

// models
use App\Models\Content;
use App\Models\ContentQuestion;
use App\Models\ContentAnswer;
use App\Models\ContentQuestionAnswer;

// illuminate
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

// inertia
use Inertia\Inertia;

// Carbon
use Carbon\Carbon;

class AnswerController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('can:professional answer list', ['only' => ['index', 'show']]);
        $this->middleware('can:professional answer create', ['only' => ['create', 'store']]);
        $this->middleware('can:professional answer edit', ['only' => ['edit', 'update']]);
        $this->middleware('can:professional answer delete', ['only' => ['destroy']]);
    }   

    /**
     * Display a listing of contents 
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $answers = DB::table('content_answers')
        -> join('contents', 'content_answers.content_id', '=', 'contents.id')
        -> select('content_answers.*', 'contents.title', 'contents.description', 'contents.image', 'contents.category', 'contents.format_category', 'contents.id as content_id')
        -> where('content_answers.user_id','=', Auth::id())
        // -> where('contents.category', '=', Content::PROFESSIONAL_CATEGORY)
        -> orderBy('updated_at','desc')
        -> paginate(9);
 
        return Inertia::render('Professional/Answer/Index', [
            'answers' => $answers,
            'can' => [
                'create' => Auth::user()->can('professional answer create'),
                'edit' => Auth::user()->can('professional answer edit'),
                'delete' => Auth::user()->can('professional answer delete'),
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
        $answer = DB::table('content_answers')
        -> select('content_answers.*')
        -> where('content_answers.id', '=', $id)
        -> first();

        $content = DB::table('contents')
        -> select('contents.*')
        -> where('contents.id', '=', $answer->content_id)
        -> first();
        $content->formats = json_decode($content->formats);

        $questions = DB::table('content_questions')
        -> select('content_questions.*')
        -> where('content_questions.content_id', '=', $answer->content_id)
        -> get();
        foreach($questions as $question) {
            $question->data = json_decode($question->data);
        }

        $question_answers = DB::table('content_question_answers')
        -> join('content_questions', 'content_question_answers.content_question_id', '=', 'content_questions.id')
        -> select('content_question_answers.*', 'content_questions.category as content_question_category')
        -> where('content_question_answers.content_answer_id', '=', $id)
        -> get();

        $question_answers_array = [];
        foreach($question_answers as $question_answer) {
            if($question_answer->content_question_category !== 'text' && $question_answer->content_question_category !== 'textarea'){
                $question_answer->answer =  json_decode($question_answer->answer);
            }
            $question_answers_array[$question_answer->content_question_id] = $question_answer->answer;
        }

        return Inertia::render('Professional/Answer/Show', [
            'content' => $content,
            'questions' => $questions,
            'answer' => $answer,
            'question_answer' => $question_answers,
            'question_answers_array' => $question_answers_array,
            'can' =>  [
                'create' => Auth::user()->can('professional answer create'),
                'edit' => Auth::user()->can('professional answer edit'),
                'delete' => Auth::user()->can('professional answer delete'),
            ],
        ]);

        
    }

    public function store(Request $request)
    {
        if($request->answer_id){
            return $this->updateAnswer($request);
        }else{
            return $this->storeAnswer($request);
        }
    }

    /**
     * Remove the specified content from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $contentAnswer = ContentAnswer::find($id);
        $contentAnswer->delete();
        return redirect()->route('answers.index');
    }

    private function storeAnswer(Request $request)
    {
        $contentAnswer = ContentAnswer::create([
            'content_id' => $request->content_id,
            'user_id' => Auth::Id(),
            'status' => ContentAnswer::STATUS_ANSWERED,
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
        return redirect()->route('answers.show', [$contentAnswer->id]);

    }

    private function updateAnswer(Request $request)
    {
        $contentAnswer = ContentAnswer::find($request->answer_id);
        $contentAnswer->user_id = Auth::Id();
        $contentAnswer->updated_at = date('Y-m-d H:i:s');
        $contentAnswer->save();

        foreach ($request->answers as $question_id => $answer){
            $questionAnswer = ContentQuestionAnswer::where(['content_question_id' => $question_id, 'content_answer_id' => $request->answer_id])->first();
            if($questionAnswer){
                $questionAnswer->answer = is_array($answer) ? json_encode($answer) : $answer;
                $questionAnswer->save();
            }else{
                $questionAnswer = ContentQuestionAnswer::create([
                    'content_question_id'=>$question_id,
                    'content_answer_id'=>$request->answer_id,
                    'answer'=>is_array($answer) ? json_encode($answer) : $answer,
                ]);
            }
        }

        redirect()->route('answers.show', [$request->answer_id]);
    }


}