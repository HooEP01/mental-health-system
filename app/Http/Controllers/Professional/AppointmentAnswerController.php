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
use App\Models\ContentAnswer;
use App\Models\ContentQuestionAnswer;

// illuminate
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

// inertia
use Inertia\Inertia;
use Redirect;

// Carbon
use Carbon\Carbon;

class AppointmentAnswerController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('can:professional appointment list', ['only' => ['index', 'show']]);
        $this->middleware('can:professional appointment create', ['only' => ['create', 'store']]);
        $this->middleware('can:professional appointment edit', ['only' => ['edit', 'update']]);
        $this->middleware('can:professional appointment delete', ['only' => ['destroy']]);
    }   

    public function show($appointment_id, $answer_id)
    {
        $appointment = Appointment::find($appointment_id);
        $answer = DB::table('content_answers')
        -> select('content_answers.*')
        -> where('content_answers.id', '=', $answer_id)
        -> first();

        $task = Task::find($answer->task_id);

        $content = Content::find($answer->content_id);
        $content->formats = json_decode($content->formats);

        $questions = DB::table('content_questions')
        -> select('content_questions.*')
        -> where('content_questions.content_id', '=', $answer->content_id)
        -> get();

        $question_answers = DB::table('content_question_answers')
        -> join('content_questions', 'content_question_answers.content_question_id', '=', 'content_questions.id')
        -> select('content_question_answers.*', 'content_questions.category as content_question_category')
        -> where('content_question_answers.content_answer_id', '=', $answer->id)
        -> get();

        // decode json
        foreach($questions as $question) {
            $question->data =  json_decode($question->data);
        }

        $question_answers_array = [];

        foreach($question_answers as $question_answer) {
            if($question_answer->content_question_category !== 'text' && $question_answer->content_question_category !== 'textarea'){
                $question_answer->answer =  json_decode($question_answer->answer);
            }

            $question_answers_array[$question_answer->content_question_id] = $question_answer->answer;

        }

        return Inertia::render('Professional/AppointmentAnswer/Show', [
            'appointment' => $appointment,
            'content' => $content,
            'questions' => $questions,
            'answer' => $answer,
            'question_answer' => $question_answers,
            'question_answers_array' => $question_answers_array,
            'task' => $task,
            'can' =>  [
                'create' => Auth::user()->can('professional appointment create'),
                'edit' => Auth::user()->can('professional appointment edit'),
                'delete' => Auth::user()->can('professional appointment delete'),
            ],
        ]);

    }

    public function store($appointment_id, Request $request)
    {
        if($request->answer_id != null){
            return $this->updateAnswer($request);
        }else{
            return $this->storeAnswer($appointment_id, $request);
        }
    }

    public function storeAnswer($appointment_id, Request $request)
    {
        $contentAnswer = ContentAnswer::create([
            'appointment_id' => $appointment_id,
            'task_id' => $request->task_id,
            'content_id' => $request->content_id,
            'user_id' => $request->user_id,
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
        return redirect()->route('appointments.answers.show', [$appointment_id, $contentAnswer->id]);
    }

    private function updateAnswer($appointment_id, Request $request)
    {
        $contentAnswer = ContentAnswer::find($request->answer_id);
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

        redirect()->route('appointment.answer.show', [$appointment_id, $request->answer_id]);
    }
}