<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;
use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request)
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed[]
     */
    public function share(Request $request)
    {

        // can's name syntax follow routing syntax
        if(!Auth::guest()) {
            return array_merge(parent::share($request), [
                'auth' => [
                    'user' => Auth::user(),
                    'can' => [
                            'profile' => Auth::user()->can('user profile list'),
                            'content' => Auth::user()->can('user content list'),
                            'answer' => Auth::user()->can('user answer list'),
                            'event' => Auth::user()->can('user event list'),
                            'appointment' => Auth::user()->can('user appointment list'),
                            'payment' => Auth::user()->can('user payment list'),
                            'event.task' => Auth::user()->can('user event task list'),
                            'appointment.task' => Auth::user()->can('user appointment task list'),
                            'appointment.answer' => Auth::user()->can('user appointment answer list'),
                            'chat' => Auth::user()->can('user chat list'),
                            'appointment.chat' => Auth::user()->can('user appointment chat list'),

                            'profiles' => Auth::user()->can('professional profile list'),
                            'contents' => Auth::user()->can('professional content list'),
                            'answers' => Auth::user()->can('professional answer list'),
                            'events' => Auth::user()->can('professional event list'),
                            'appointments' => Auth::user()->can('professional appointment list'),
                            'appointments.contents' => Auth::user()->can('professional appointment content list'),
                            'appointments.answers' => Auth::user()->can('professional appointment answer list'),
                            'appointments.tasks' => Auth::user()->can('professional appointment task list'),
                            'payments' => Auth::user()->can('professional payment list'),
                            'appointments.users' => Auth::user()->can('professional user list'),
                            'events.tasks' => Auth::user()->can('professional event task list'),
                            'events.contents' => Auth::user()->can('professional event content list'),
                            'chats' => Auth::user()->can('professional chat list'),
                            'appointments.chats' => Auth::user()->can('professional appointment chat list'),

                            'contents_view' => Auth::user()->can('admin content list'),
                            'answers_view' => Auth::user()->can('admin answer list'),
                            'events_view' => Auth::user()->can('admin event list'),
                            'appointments_view' => Auth::user()->can('admin appointment list'),
                            'payments_view' => Auth::user()->can('admin payment list'),

                            'users_view' => Auth::user()->can('admin user list'),
                            'roles_view' => Auth::user()->can('admin role list'),
                            'permissions_view' => Auth::user()->can('admin permission list'),
                        ]
                ],
                'ziggy' => function () use ($request) {
                    return array_merge((new Ziggy)->toArray(), [
                        'location' => $request->url(),
                    ]);
                },
                'success' => $request->session()->get('success'),
            ]);
        }
        return array_merge(parent::share($request), [
            'auth' => [
                'user' => Auth::user(),
            ],
            'ziggy' => function () use ($request) {
                return array_merge((new Ziggy)->toArray(), [
                    'location' => $request->url(),
                ]);
            },
        ]);
    }
}
