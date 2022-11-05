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
                            'event' => Auth::user()->can('user event list'),
                            'appointment' => Auth::user()->can('user appointment list'),
                            'payment' => Auth::user()->can('user payment list'),

                            'profiles' => Auth::user()->can('professional profile list'),
                            'contents' => Auth::user()->can('professional content list'),
                            'events' => Auth::user()->can('professional event list'),
                            'appointments' => Auth::user()->can('professional appointment list'),
                            'payments' => Auth::user()->can('professional payment list'),

                            'contents_view' => Auth::user()->can('admin content list'),
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
