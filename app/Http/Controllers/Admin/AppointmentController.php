<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

// models
use App\Models\User;
use App\Models\Event;
use App\Models\Appointment;

// illuminate
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

// inertia
use Inertia\Inertia;


class AppointmentController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('can:admin appointment list', ['only' => ['index', 'show']]);
        $this->middleware('can:admin appointment create', ['only' => ['create', 'store']]);
        $this->middleware('can:admin appointment edit', ['only' => ['edit', 'update']]);
        $this->middleware('can:admin appointment delete', ['only' => ['destroy']]);
    }    

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $appointments = DB::table('appointments')
        -> join('events', 'appointments.event_id', '=', 'events.id')
        -> join('users', 'appointments.user_id', '=', 'users.id')
        -> select('appointments.*', 'events.title as event_title', 'events.description as event_description', 'events.user_id as professional_id', 'users.name', 'users.first_name', 'users.last_name', 'users.professional_title')
        -> orderBy('created_at','desc')
        -> paginate(100);

        foreach($appointments as $appointment) {
            $appointment->professional_id = User::find($appointment->professional_id);
        }
        
        return Inertia::render('Admin/Appointment/Index', [
            'appointments' => $appointments,
            'can' => [
                'create' => Auth::user()->can('admin appointment create'),
                'edit' => Auth::user()->can('admin appointment edit'),
                'delete' => Auth::user()->can('admin appointment delete'),
            ]
        ]);
    }

    /**
     * Display a appointment status
     *
     * @param \App\Models\Appointment $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $appointment = Appointment::find($id);
        
        if($appointment->status != Appointment::STATUS_APPROVE){
            $appointment->status = Appointment::STATUS_APPROVE;
        }else{
            $appointment->status = Appointment::STATUS_DISAPPROVE;
        }
        $appointment->save();

        return $this->index();
    }
}