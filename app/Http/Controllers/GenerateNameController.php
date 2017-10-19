<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GenerateNameController extends Controller
{
    //
    public function index($firstname, $lastname, $charrace)
    {
      $charname = 'Not Random';
      return view('results')->with([
        'name' => $charname
      ]);
    }

    public function random()
    {
      $charname = 'Random';
      return view('results')->with([
        'name' => $charname
      ]);
    }

}
