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
        if(!Auth::guest()) {
            return array_merge(parent::share($request), [
                'auth' => [
                    'user' => Auth::user(),
                    'permission' => [
                            'user' => Auth::user()->can('user list'),
                            'role' => Auth::user()->can('role list'),
                            'userPostList' => Auth::user()->can('user post list'),
                            'professionalPostList' => Auth::user()->can('professional post list'),
                            'adminPostList' => Auth::user()->can('admin post list'),
                            'permission' => Auth::user()->can('permission list'),
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
