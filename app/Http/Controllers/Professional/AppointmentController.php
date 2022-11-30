<?php

namespace App\Http\Controllers\Professional;
use App\Http\Controllers\Controller;

// models
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
    public function __construct()
    {
        $this->middleware('can:professional appointment list', ['only' => ['index', 'show']]);
        $this->middleware('can:professional appointment create', ['only' => ['create', 'store']]);
        $this->middleware('can:professional appointment edit', ['only' => ['edit', 'update']]);
        $this->middleware('can:professional appointment delete', ['only' => ['destroy']]);
    }   

    public function index()
    {
        $events = DB::table('events')
        ->join('appointments', 'events.id', '=', 'appointments.event_id')
        ->select('events.*', 'appointments.id as appointment_id', 'appointments.user_id as appointment_user_id', 'appointments.reason as appointment_reason', 'appointments.status as appointment_status', 'appointments.start_date as appointment_start_date', 'appointments.end_date as appointment_end_date', 'appointments.start_time as appointment_start_time', 'appointments.end_time as appointment_end_time')
        ->where('events.user_id', '=', Auth::Id())
        ->where('appointments.status', '!=', Appointment::STATUS_BOOKED)
        ->where('appointments.status', '!=', Appointment::STATUS_DISAPPROVED)
        ->orderBy('created_at','desc')
        ->paginate(100);

        
        return Inertia::render('Professional/Appointment/Index', [
            'events' => $events,
            'can' => [
                'create' => Auth::user()->can('professional appointment create'),
                'edit' => Auth::user()->can('professional appointment edit'),
                'delete' => Auth::user()->can('professional appointment delete'),
            ]
        ]);
    }

}