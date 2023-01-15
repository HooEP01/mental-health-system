<?php

namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;

// models
use App\Models\User;
use App\Models\Appointment;
use App\Models\Chat;

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

class AppointmentChatController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('can:user appointment chat list', ['only' => ['index', 'show']]);
        $this->middleware('can:user appointment chat create', ['only' => ['create', 'store']]);
        $this->middleware('can:user appointment chat edit', ['only' => ['edit', 'update']]);
        $this->middleware('can:user appointment chat delete', ['only' => ['destroy']]);
    }   

    public function store($appointment_id, Request $request)
    {
        $chat_id = $request->chat_id;
        return ($chat_id) ? $this->updateChat($appointment_id, $request) : $this->storeChat($appointment_id, $request); 
    }

    private function storeChat($appointment_id, Request $request)
    {
        $appointment = Appointment::find($appointment_id);

        $chat = Chat::create([
            'appointment_id' => $appointment->id,
            'user_id' => Auth::id(),
            'message' => $request->message,
            'status' => Chat::STATUS_DELIVERED,
        ]);

        return redirect()->route('appointment.show', [$appointment_id , 'tab' => 'chat']);
    }

    private function updateChat($appointment_id, Request $request)
    {
        $chat = Chat::find($request->chat_id);
        if($chat->user_id == Auth::id()){
            $chat->message = $request->message;
            $chat->status = Chat::STATUS_EDITED;
        }
        $chat->save();
        
        return redirect()->route('appointment.show', [$appointment_id , 'tab' => 'chat']);
    }

    public function destroy($appointment_id, $chat_id)
    {
        $chat = Chat::find($chat_id);
        $chat->status = Chat::STATUS_DELETED;
        $chat->save();

        return redirect()->route('appointment.show', [$appointment_id , 'tab' => 'chat']);
    }
}