<?php

namespace App\Http\Controllers\Professional;
use App\Http\Controllers\Controller;

// models
use App\Models\User;
use App\Models\Event;
use App\Models\Appointment;
use App\Models\Payment;

// illuminate
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

// inertia
use Inertia\Inertia;

use Stripe;
use App\Http\Requests;

use Validator;
use URL;
use Session;
use Redirect;
use Input;
use Stripe\Error\Card;

class PaymentController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('can:professional payment list', ['only' => ['index', 'show']]);
        $this->middleware('can:professional payment create', ['only' => ['create', 'store']]);
        $this->middleware('can:professional payment edit', ['only' => ['edit', 'update']]);
        $this->middleware('can:professional payment delete', ['only' => ['destroy']]);
    }    


    public function index() 
    {

        $appointments = DB::table('appointments')
        -> join('events', 'appointments.event_id', '=', 'events.id')
        -> select('appointments.*', 'events.id as event_id', 'events.title as event_title')
        -> where('events.user_id', '=', Auth::id())
        -> when(function($query) {
            $query-> where('appointments.status', '=', Appointment::STATUS_PAID)
                  -> orWhere('appointments.status', '=', Appointment::STATUS_APPROVE);
            })
        -> orderBy('updated_at', 'desc')
        -> paginate(100);

        foreach($appointments as $appointment){
            $payment = DB::table('payments')
            -> select('payments.*')
            -> where('payments.appointment_id', '=', $appointment->id)
            -> first();
            $appointment->payment = $payment;
        }



        return Inertia::render('Professional/Payment/Index', [
            'appointments' => $appointments,
            'can' => [
                'create' => Auth::user()->can('professional payment create'),
                'edit' => Auth::user()->can('professional payment edit'),
                'delete' => Auth::user()->can('professional payment delete'),
            ]
        ]);
    }

}