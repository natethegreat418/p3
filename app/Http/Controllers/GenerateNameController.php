<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GenerateNameController extends Controller
{
    // This method handles the personalized name creation option
    public function store(Request $request)
    {
      // Validate and store submitted data
      $this->validate($request, [
        'firstname' => 'required',
        'lastname' => 'required',
        'charrace' => 'required',
        'chargender' => 'required'
      ]);

      $inputname = $request->input('firstname') . ' ' . $request->input('lastname');
      $inputfname = $request->input('firstname');
      $inputlname = $request->input('lastname');
      $inputrace = $request->input('charrace');
      $inputgender = $request->input('chargender');

      // Fetch and initial trim of firstname options
      $firstnamejson = file_get_contents(base_path().'/database/character_firstn.json');
      $firstnamearray = json_decode($firstnamejson, true);
      $relevantfirstnames = [];

      foreach ($firstnamearray as $entry) {
        if($entry['race'] == $inputrace && $entry['gender'] == $inputgender) {
          array_push($relevantfirstnames, $entry);
        }
      }

      // Fetch and initial trim of lastname options
      $lastnamejson = file_get_contents(base_path().'/database/character_lastn.json');
      $lastnamearray = json_decode($lastnamejson, true);
      $relevantlastnames = [];

      foreach ($lastnamearray as $entry) {
        if($entry['race'] == $inputrace && $entry['gender'] == $inputgender) {
          array_push($relevantlastnames, $entry);
        }
      }

      // Determine best match for firstname
      $splitfname = str_split($inputfname);
      $best_fname_rank = 0;
      $best_fname;
      $rankedfirstnames = [];
      foreach ($relevantfirstnames as $entry) {
        $entry['rank'] = 0;
        $splitrname = str_split($entry['firstname']);
        foreach($splitrname as $letter){
          // Award a rank point if the given letter exists at all inputname
          if(in_array($letter, $splitfname)){
            $entry['rank'] = ($entry['rank']) + 1;
          }
        }

        if($entry['rank'] > $best_fname_rank){
          $best_fname_rank = $entry['rank'];
          $best_fname = $entry['firstname'];
        }
        array_push($rankedfirstnames, $entry);
      }

      $rankedfirstnames = array_values(array_sort($rankedfirstnames, function ($value) {
        return $value['rank'];
      }));

      // Determine best match for lastname
      $splitlname = str_split($inputlname);
      $best_lname_rank = 0;
      $best_lname;
      $rankedlastnames = [];
      foreach ($relevantlastnames as $entry) {
        $entry['rank'] = 0;
        $splitrname = str_split($entry['lastname']);
        foreach($splitrname as $letter){
          // Award a rank point if the given letter exists at all inputname
          if(in_array($letter, $splitlname)){
            $entry['rank'] = ($entry['rank']) + 1;
          }
        }
        if($entry['rank'] > $best_lname_rank){
          $best_lname_rank = $entry['rank'];
          $best_lname = $entry['lastname'];
        }
        // Add new list for ranking
        array_push($rankedlastnames, $entry);
      }

      // Sort names by rank
      $rankedlastnames = array_values(array_sort($rankedlastnames, function ($value) {
        return $value['rank'];
      }));

      // Ensure best matches were found, if not return form and error
      if (isset($best_fname) == False || isset($best_lname) == False){
        $my_errors = 'Unfortunately, a match could not be made. Please try again with a different name/race combination, or request a random name.';
        return redirect('/')->withErrors($my_errors);
      }

      // Best name match found, hit redirect
      $charname = $best_fname . ' ' . $best_lname;
      return redirect('/generate/personalized')->with([
        'inputname' => $inputname,
        'inputrace' => $inputrace,
        'inputgender' => $inputgender,
        'charname' => $charname,
        'rankedfirstlist'=> $rankedfirstnames,
        'rankedlastlist' => $rankedlastnames
      ]);
    }

    // This method redirects to the results page and passes the results as session data
    public function index()
    {
      return view('results')->with([
        'charname' => session('charname'),
        'inputname' => session('inputname'),
        'inputrace' => session('inputrace'),
        'inputgender' => session('inputgender')
      ]);
    }

    // This method handles the totally random name generation option
    public function random(Request $request)
    {
      // Fetch name options and convert to arrays
      $firstnamejson = file_get_contents(base_path().'/database/character_firstn.json');
      $firstnamearray = json_decode($firstnamejson, true);
      $lastnamejson = file_get_contents(base_path().'/database/character_lastn.json');
      $lastnamearray = json_decode($lastnamejson, true);

      // Select random positions from first and last name options
      $rand_keys_firstn = array_rand($firstnamearray, 1);
      $firstname = $firstnamearray[$rand_keys_firstn]['firstname'];
      $rand_keys_lastn = array_rand($lastnamearray, 1);
      $lastname = $lastnamearray[$rand_keys_lastn]['lastname'];

      // Return concatenated name to results
      $charname = $firstname . ' ' . $lastname;
      return view('results')->with([
        'charname' => $charname
      ]);
    }

}
