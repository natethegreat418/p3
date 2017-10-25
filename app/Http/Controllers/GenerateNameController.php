<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GenerateNameController extends Controller
{
    //
    public function index()
    {
      return view('results')->with([
        'name' => session('name')
      ]);
    }

    public function store(Request $request)
    {
      $charname = 'Not Random';
      return redirect('/generate/personalized')->with([
        'name' => $charname
      ]);
    }

    public function random(Request $request)
    {
      $charname = 'Random';
      return view('results')->with([
        'name' => $charname
      ]);
    }

}
