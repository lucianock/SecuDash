<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ServerStatsController extends Controller
{
    public function index(): View
    {
        return view('server-stats.index');
    }
}
