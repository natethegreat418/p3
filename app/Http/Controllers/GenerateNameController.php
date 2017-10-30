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
      $this->validate($request, [
        'firstname' => 'required',
        'lastname' => 'required',
        'charrace' => 'required'
      ]);
      dump($request);
      $charname = 'Not Random';
      // return redirect('/generate/personalized')->with([
      //   'name' => $charname
      // ]);
    }

    public function random(Request $request)
    {
      $namejson = file_get_contents(base_path().'/database/characters.json');
      $namearray = json_decode($namejson, true);
      $rand_keys = array_rand($namearray, 2);
      $firstname = $namearray[$rand_keys[0]]['firstname'];
      $lastname = $namearray[$rand_keys[1]]['lastname'];
      $charname = $firstname . ' ' . $lastname;
      return view('results')->with([
        'name' => $charname
      ]);
    }

}
