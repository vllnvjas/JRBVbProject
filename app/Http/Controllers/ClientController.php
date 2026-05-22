<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function displayGreetings()
    {
        return response('Greetings page');
    }

    public function clientProfile()
    {
        return response('Client profile');
    }

    public function clientDashboard()
    {
        return response('Client dashboard');
    }

    public function clientAboutUs()
    {
        return response('About us');
    }

    public function index()
    {
        return response('Client index');
    }
}
