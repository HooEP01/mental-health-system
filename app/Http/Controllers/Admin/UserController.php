<?php

namespace App\Http\Controllers\Admin;
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


class UserController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('can:admin user list', ['only' => ['index', 'show']]);
        $this->middleware('can:admin user create', ['only' => ['create', 'store']]);
        $this->middleware('can:admin user edit', ['only' => ['edit', 'update']]);
        $this->middleware('can:admin user delete', ['only' => ['destroy']]);
    }  

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = DB::table('model_has_roles')
        -> join ('roles', 'model_has_roles.role_id', '=', 'roles.id')
        -> join ('users', 'model_has_roles.model_id', '=', 'users.id')
        -> select ('model_has_roles.*', 'roles.name as role_name', 'users.*')
        -> orderBy ('created_at', 'desc')
        -> paginate (100);

        return Inertia::render('Admin/User/Index', [
            'users' => $users,
            'can' => [
                'create' => Auth::user()->can('admin user create'),
                'edit' => Auth::user()->can('admin user edit'),
                'delete' => Auth::user()->can('admin user delete'),
            ]
        ]);
    }

    /**
     * Display a user's information
     *
     * @param \App\Models\Content $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = Auth::user();

        $model = DB::table('model_has_roles')
        -> join ('roles', 'model_has_roles.role_id', '=', 'roles.id')
        -> join ('users', 'model_has_roles.model_id', '=', 'users.id')
        -> select('model_has_roles.*', 'roles.name as role_name', 'users.*')
        -> where('model_has_roles.model_id', '=', $id)
        -> first();

        $roles = DB::table('roles')
        -> select('roles.*')
        -> orderBy('created_at','desc')
        -> paginate(100);

        $answers = DB::table('content_answers')
        -> join('contents', 'content_answers.content_id', '=', 'contents.id')
        -> select('content_answers.*', 'contents.title', 'contents.description', 'contents.image', 'contents.category', 'contents.format_category', 'contents.id as content_id')
        -> where('content_answers.user_id','=', $id)
        -> orderBy('updated_at','desc')
        -> paginate(100);
 
        foreach($answers as $answer){
            $question = DB::table('content_questions')
            ->select('content_questions.*')
            ->where('content_questions.content_id', '=', $answer->content_id)
            ->count();
            $answer->questionCount = $question;
        }

        $appointments = DB::table('appointments')
        -> join('events','appointments.event_id', '=','events.id')
        -> join('users', 'appointments.user_id', '=', 'users.id')
        -> select('appointments.*', 'events.id as event_id', 'events.title as event_title', 'events.image as event_image', 'events.user_id as professional_id', 'users.first_name', 'users.last_name', 'users.name', 'users.email', 'users.birthday', 'users.relationship_status', 'users.contact_number')
        -> where('appointments.user_id', '=', $id)
        -> get();

        foreach($appointments as $appointment){
            $tasks = DB::table('tasks')
            -> join('content_answers', 'tasks.id', '=', 'content_answers.task_id')
            -> join('contents', 'tasks.content_id', '=', 'contents.id')
            -> select('tasks.*', 'contents.title as content_title', 'content_answers.id as content_answer_id')
            -> where('tasks.event_id', '=', $appointment->event_id)
            -> where('content_answers.appointment_id', '=', $appointment->id)
            -> where('content_answers.user_id', '=', $user->id)
            -> where('tasks.category', '!=', Task::CATEGORY_DELETE)
            -> get();
            $appointment->task = $tasks;
        }

        return Inertia::render('Admin/User/Edit', [
            'user' => $model,
            'roles' => $roles,
            'professional_statuses' => USER::PROFESSIONAL_STATUSES_ADMINISTRATOR,
            'answers' => $answers,
            'appointments' => $appointments,
            'can' => [
                'create' => Auth::user()->can('user create'),
                'edit' => Auth::user()->can('user edit'),
                'delete' => Auth::user()->can('user delete'),
            ]
        ]);
    }

    /**
     * Store edited user in storage 
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = User::find($request->id);

        $data->removeRole($request->old_role_name);
        $data->assignRole($request->role_name);
        $data->professional_status = $request->professional_status;
        $data->save();

        return $this->index();
    }

}