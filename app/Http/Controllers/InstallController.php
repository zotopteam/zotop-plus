<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InstallController extends Controller
{
    /**
     * Display the installer welcome page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('install.index');
    }    
}
