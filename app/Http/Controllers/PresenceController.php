<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Presence;

class PresenceController extends Controller
{
   public function index()
   {
       $presences = Presence::all();
       return view('presences.index', compact('presences'));


    }
}