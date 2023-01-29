<?php

namespace App\Http\Controllers\Professional;
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

// Encryption
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

// Request
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AppointmentChatController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('can:professional appointment chat list', ['only' => ['index', 'show']]);
        $this->middleware('can:professional appointment chat create', ['only' => ['create', 'store']]);
        $this->middleware('can:professional appointment chat edit', ['only' => ['edit', 'update']]);
        $this->middleware('can:professional appointment chat delete', ['only' => ['destroy']]);
    }   

    public function store($appointment_id, Request $request)
    {
        $chat_id = $request->chat_id;
        return ($chat_id) ? $this->updateChat($appointment_id, $request) : $this->storeChat($appointment_id, $request); 
    }

    private function storeChat($appointment_id, Request $request)
    {
        try {
            $validatedData = $request->validate([
                'message' => 'required|string|max:1000',
            ]);

            $appointment = Appointment::find($appointment_id);

            $chat = Chat::create([
                'appointment_id' => $appointment->id,
                'user_id' => Auth::id(),
                'message' => Crypt::encryptString($validatedData['message']),
                'status' => Chat::STATUS_DELIVERED,
            ]);
        } catch (Exception $exception) {
            session()->flash('errors', $exception->errors());
            return redirect()->back();
        }

        return redirect()->route('appointments.show', [$appointment_id , 'tab' => 'chat']);
    }

    private function updateChat($appointment_id, Request $request)
    {
        try {
            $validatedData = $request->validate([
                'message' => 'required|string|max:1000',
            ]);

            $chat = Chat::find($request->chat_id);
            if($chat->user_id == Auth::id()){
                $chat->message = Crypt::encryptString($validatedData['message']);
                $chat->status = Chat::STATUS_EDITED;
            }
            $chat->save();

        } catch (Exception $exception) {
            session()->flash('errors', $exception->errors());
            return redirect()->back();
        }
        
        return redirect()->route('appointments.show', [$appointment_id , 'tab' => 'chat']);
    }

    public function destroy($appointment_id, $chat_id)
    {
        $chat = Chat::find($chat_id);
        $chat->status = Chat::STATUS_DELETED;
        $chat->save();

        return redirect()->route('appointments.show', [$appointment_id , 'tab' => 'chat']);
    }
}