<?php

declare(strict_types=1);

namespace TTBooking\FiscalRegistrar\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Routing\Controller;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Single page application catch-all route.
     *
     * @return Factory|View
     */
    public function index()
    {
        return view('fiscal-registrar::layout', [
            'fiscalRegistrarScriptVariables' => [
                'path' => config('fiscal-registrar.path'),
            ],
        ]);
    }
}
