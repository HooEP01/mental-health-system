<?php

namespace App\Http\Controllers\Professional;
use App\Http\Controllers\Controller;

// models
use App\Models\User;
use App\Models\ContentAnswer;
use App\Models\Appointment;
use App\Models\Content;
use App\Models\Event;
use App\Models\Task;

// illuminate
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

// inertia
use Inertia\Inertia;

// spatie - role permission
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

// Request
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

// Encryption
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;


class AppointmentUserController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('can:professional user list', ['only' => ['index', 'show']]);
        $this->middleware('can:professional user create', ['only' => ['create', 'store']]);
        $this->middleware('can:professional user edit', ['only' => ['edit', 'update']]);
        $this->middleware('can:professional user delete', ['only' => ['destroy']]);
    }  


    /**
     * Display a user's information
     *
     * @param \App\Models\Appointment $id
     * @return \Illuminate\Http\Response
     */
    public function show($appointment_id, $user_id)
    {
        $user = User::find($user_id);

        try {
            $user->contact_number = Crypt::decryptString($user->contact_number);
        } catch (DecryptException $exception) {
            session()->flash('errors', $exception->errors());
            return redirect()->back();
        }

        $answers = DB::table('content_answers')
        -> join('contents', 'content_answers.content_id', '=', 'contents.id')
        -> select('content_answers.*', 'contents.title', 'contents.description', 'contents.image', 'contents.category', 'contents.format_category', 'contents.id as content_id')
        -> where('content_answers.user_id','=', $user_id)
        -> orderBy('updated_at','desc')
        -> paginate(100);
 
        foreach($answers as $answer){
            $question = DB::table('content_questions')
            ->select('content_questions.*')
            ->where('content_questions.content_id', '=', $answer->content_id)
            ->count();
            $answer->questionCount = $question;
        }

        $appointment = DB::table('appointments')
        -> join('events','appointments.event_id', '=','events.id')
        -> join('users', 'appointments.user_id', '=', 'users.id')
        -> select('appointments.*', 'events.id as event_id', 'events.title as event_title', 'events.image as event_image', 'events.user_id as professional_id', 'users.first_name', 'users.last_name', 'users.name', 'users.email', 'users.birthday', 'users.relationship_status', 'users.contact_number')
        -> where('appointments.user_id', '=', $user_id)
        -> where('appointments.id', '=', $appointment_id)
        -> first();
 
        $appointments = DB::table('appointments')
        -> join('events','appointments.event_id', '=','events.id')
        -> join('users', 'appointments.user_id', '=', 'users.id')
        -> select('appointments.*', 'events.id as event_id', 'events.title as event_title', 'events.image as event_image', 'events.user_id as professional_id', 'users.first_name', 'users.last_name', 'users.name', 'users.email', 'users.birthday', 'users.relationship_status', 'users.contact_number')
        -> where('appointments.user_id', '=', $user_id)
        -> orderBy('appointments.start_date','desc')
        -> orderBy('appointments.start_time', 'desc')
        -> get();

        foreach($appointments as $appointment){
            $tasks = DB::table('tasks')
            -> join('content_answers', 'tasks.id', '=', 'content_answers.task_id')
            -> join('contents', 'tasks.content_id', '=', 'contents.id')
            -> select('tasks.*', 'contents.title as content_title', 'content_answers.id as content_answer_id')
            -> where('tasks.event_id', '=', $appointment->event_id)
            -> where('content_answers.appointment_id', '=', $appointment->id)
            -> where('content_answers.user_id', '=', $user_id)
            -> where('tasks.category', '!=', Task::CATEGORY_DELETE)
            -> get();
            $appointment->task = $tasks;
        }

    
        return Inertia::render('Professional/AppointmentUser/Show', [
            'user' => $user,
            'answers' => $answers,
            'appointments' => $appointments,
            'appointment' => $appointment,
            'can' => [
                'create' => Auth::user()->can('professional user create'),
                'edit' => Auth::user()->can('professional user edit'),
                'delete' => Auth::user()->can('professional user delete'),
            ]
        ]);
    }

}