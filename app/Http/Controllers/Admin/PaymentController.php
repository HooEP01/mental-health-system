<?php

namespace App\Http\Controllers\Admin;
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
        $this->middleware('can:admin payment list', ['only' => ['index', 'show']]);
        $this->middleware('can:admin payment create', ['only' => ['create', 'store']]);
        $this->middleware('can:admin payment edit', ['only' => ['edit', 'update']]);
        $this->middleware('can:admin payment delete', ['only' => ['destroy']]);
    }    


    public function index() 
    {

        $payments = DB::table('payments')
        ->join('users', 'payments.user_id', '=', 'users.id')
        ->join('appointments', 'payments.appointment_id', '=', 'appointments.id')
        ->select('payments.*', 'users.name as user_name', 'appointments.start_date', 'appointments.start_time', 'appointments.event_id')
        ->orderBy('created_at', 'desc')
        ->paginate (100);

        foreach($payments as $payment) {
            $payment->event = Event::find($payment->event_id);
        }

        return Inertia::render('Admin/Payment/Index', [
            'payments' => $payments,
            'can' => [
                'create' => Auth::user()->can('admin payment create'),
                'edit' => Auth::user()->can('admin payment edit'),
                'delete' => Auth::user()->can('admin payment delete'),
            ]
        ]);
    }

}