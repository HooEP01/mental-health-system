<?php

namespace App\Http\Controllers\Professional;
use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use DB;

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
        // $data = DB::table('appointments')
        // -> select('appointments.*')
        // -> join
        // -> where('appointments.event_id','=',Auth::id())
        // -> orderBy('created_at','desc')
        // -> paginate(100);

        //  ->leftjoin('users', 'users.id', '=', 'appointments.user_id')
        // ->leftjoin('events', 'events.id', '=', 'appointments.event_id')
        // ->select('appointments.*', 'events.professional_id', 'users.name as user_id', 'events.title as event_id')
        // ->where('events.professional_id', '=', Auth::id())
        // ->where('appointments.status', '!=', 'Unpaid')
        // ->orderBy('appointments.start_datetime','asc')
        // ->paginate(10);
    }

}