<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Megjeleníti a főoldalt.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('home');
    }

    /**
     * Megjeleníti a rólunk oldalt.
     *
     * @return \Illuminate\View\View
     */
    public function about()
    {
        return view('about');
    }
}
