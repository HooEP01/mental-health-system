<?php

namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;

// models
use App\Models\Event;
use App\Models\Appointment;

// illuminate
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

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
        $this->middleware('can:user appointment list', ['only' => ['index', 'show']]);
        $this->middleware('can:user appointment create', ['only' => ['create', 'store']]);
        $this->middleware('can:user appointment edit', ['only' => ['edit', 'update']]);
        $this->middleware('can:user appointment delete', ['only' => ['destroy']]);
    }    

    public function index() 
    {
        $appointments = DB::table('appointments')
        ->select('appointments.*')
        ->where('appointments.user_id', '=', Auth::Id())
        ->orderBy('created_at', 'desc')
        ->paginate (100);
 
        return Inertia::render('User/Appointment/Index', [
            'appointments' => $appointments,
            'can' => [
                'create' => Auth::user()->can('user appointment create'),
                'edit' => Auth::user()->can('user appointment edit'),
                'delete' => Auth::user()->can('user appointment delete'),
            ]
        ]);
    }

    public function show($id)
    {
        return $this->createPayment($id);
    }

    private function createPayment($id)
    {

        $appointment = Appointment::find($id);
        $event = Event::find($appointment->event_id);
        return Inertia::render('User/Payment/Create', [
            'appointment' => $appointment,
            'event' => $event,
            'can' => [
                'create' => Auth::user()->can('user appointment create'),
                'edit' => Auth::user()->can('user appointment edit'),
                'delete' => Auth::user()->can('user appointment delete'),
            ]
        ]);
    }

    /**
     * Store a newly created or edited appointment in storage 
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $appointment_id = $request->appointment_id;
        if($appointment_id != null){
            // $this->updateContent($request);
        }else{
            return $this->storeAppointment($request);
        }

        return $this->index();
    }

    private function storeAppointment(Request $request)
    {

        $user_id = Auth::Id();
        $status = APPOINTMENT::STATUS_BOOKED;
        $appointment = Appointment::create([
            'event_id'=>$request->event_id,
            'user_id'=>$user_id,
            'reason'=>$request->reason,
            'status'=>$status,
            'start_date'=>$request->scheduleDateTime['date'],
            'start_time'=>$request->scheduleDateTime['time'],
        ]);

        $event = Event::find($appointment->event_id);
        $price = $event->price;
        if($price == EVENT::PRICE_FREE || $price == EVENT::PRICE_FREE_STRING) {
            return rediect()->route('content.index');
        }

        return redirect()->route('appointment.show', [$appointment->id]);
    }

    
}

