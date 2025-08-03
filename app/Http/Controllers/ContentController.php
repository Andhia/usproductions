<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ContentController extends Controller
{
    public function home()
    {
        $services = Service::all();

        return Inertia::render('Home', [
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
            'services' => $services
        ]);
    }

    public function about()
    {
        return Inertia::render('About');
    }

    public function service()
    {
        $services = Service::all();

        return Inertia::render('Service', [
            'services' => $services
        ]);
    }
}
