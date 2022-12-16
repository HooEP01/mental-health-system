<?php

namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;

// models
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
use App\Http\Requests;
use Validator;
use URL;
use Session;
use Redirect;
use Input;
use Stripe\Error\Card;
use Stripe;

class PaymentController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:user payment list', ['only' => ['index', 'show']]);
        $this->middleware('can:user payment create', ['only' => ['create', 'store']]);
        $this->middleware('can:user payment edit', ['only' => ['edit', 'update']]);
        $this->middleware('can:user payment delete', ['only' => ['destroy']]);
    }    

    public function index() 
    {
        $payments = DB::table('payments')
        ->join('appointments', 'payments.appointment_id', '=', 'appointments.id')
        ->select('payments.*', 'appointments.start_date', 'appointments.start_time', 'appointments.event_id')
        ->where('payments.user_id', '=', Auth::id())
        ->orderBy('created_at', 'desc')
        ->paginate (100);

        foreach($payments as $payment) {
            $payment->event = Event::find($payment->event_id);
        }

        return Inertia::render('User/Payment/Index', [
            'payments' => $payments,
            'can' => [
                'create' => Auth::user()->can('user payment create'),
                'edit' => Auth::user()->can('user payment edit'),
                'delete' => Auth::user()->can('user payment delete'),
            ]
        ]);
    }

    public function create($id)
    {
        $appointment = DB::table('appointments')
        ->join('events', 'appointments.event_id', '=', 'events.id')
        ->select('appointments.*', 'events.price as event_price')
        ->where('appointments.user_id', '=', Auth::id())
        ->where('appointments.id', '=', $id)
        ->orderBy('created_at', 'desc')
        ->paginate (100);;
        
        return Inertia::render('User/Payment/Create', [
            'appointment' => $appointment,
            'can' => [
                'create' => Auth::user()->can('user payment create'),
                'edit' => Auth::user()->can('user payment edit'),
                'delete' => Auth::user()->can('user payment delete'),
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
        $payment_id = $request->payment_id;
        if($payment_id != null){

        }else{
            return $this->storePayment($request);
        }
    }

    private function storePayment(Request $request)
    {

        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        
        $token = Stripe\Token::create([
            'card' => [
            'number' => $request->card_number,
            'exp_month' => $request->card_expiry_month,
            'exp_year' => $request->card_expiry_year,
            'cvc' => $request->card_cvc,
            ],
        ]);

        $charge = Stripe\Charge::create([
            'card' => $token['id'],
            'currency' => 'MYR',
            'amount' => ($request->amount) * 100,
            'description' => 'This is a description',
        ]);

        if($charge['status'] == 'succeeded') { 
        
            $payment = Payment::create([
                'appointment_id'=>$request->appointment_id,
                'amount'=>$request->amount,
                'status'=>Payment::STATUS_SUCCEEDED,
                'method'=>"card",
                'user_id'=>Auth::Id(),
            ]);

            $appointment = Appointment::find($request->appointment_id);
            $appointment->status = APPOINTMENT::STATUS_PAID;
            $appointment->save();
        }

        return redirect()->route('appointment.index');
    }

    
}

