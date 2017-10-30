<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GenerateNameController extends Controller
{
    //
    public function store(Request $request)
    {
      $this->validate($request, [
        'firstname' => 'required',
        'lastname' => 'required',
        'charrace' => 'required',
        'chargender' => 'required'
      ]);

      $inputname = $request->input('firstname') . ' ' . $request->input('lastname');
      $inputrace = $request->input('charrace');
      $inputgender = $request->input('chargender');

      $namejson = file_get_contents(base_path().'/database/characters.json');
      $namearray = json_decode($namejson, true);
      $relevantnames = [];

      foreach ($namearray as $entry) {
        if($entry['race'] == $inputrace) {
          array_push($relevantnames, $entry);
        }
      }

      $charname = 'Not Random';
      return redirect('/generate/personalized')->with([
        'inputname' => $inputname,
        'inputrace' => $inputrace,
        'inputgender' => $inputgender,
        'charname' => $charname
      ]);
    }

    public function index()
    {
      return view('results')->with([
        'charname' => session('charname'),
        'inputname' => session('inputname'),
        'inputrace' => session('inputrace'),
        'inputgender' => session('inputgender')
      ]);
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
        'charname' => $charname
      ]);
    }

}
