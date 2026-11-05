<?php

namespace App\Http\Controllers;

use App\Models\Event;

class PageController extends Controller
{
    public function home()
    {
        $latest = Event::published()->orderByDesc('created_at')->take(3)->get();

        return view('pages.home', compact('latest'));
    }

    public function about() { return view('pages.about'); }
    public function vision() { return view('pages.vision'); }
    public function mission() { return view('pages.mission'); }
    public function history() { return view('pages.history'); }
    public function location() { return view('pages.location'); }
}
