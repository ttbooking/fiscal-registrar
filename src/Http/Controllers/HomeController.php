<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Http\Controllers;

use Illuminate\Routing\Controller;

class HomeController extends Controller
{
    /**
     * Single page application catch-all route.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('fiscal-registrar::layout', [
            'fiscalRegistrarScriptVariables' => [
                'path' => config('fiscal-registrar.path'),
                'pusher' => config('broadcasting.connections.pusher'),
            ],
        ]);
    }
}
