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

      $requesttype = 'personalized';
      $inputname = $request->input('firstname') . ' ' . $request->input('lastname');
      $inputfname = $request->input('firstname');
      $inputlname = $request->input('lastname');
      $inputrace = $request->input('charrace');
      $inputgender = $request->input('chargender');


      // Fetch and initial trim of firstname options
      $firstnamejson = file_get_contents(base_path().'/database/character_firstname11.6.17.json');
      $firstnamearray = json_decode($firstnamejson, true);
      $relevantfirstnames = [];

      foreach ($firstnamearray as $entry) {
        if($entry['Race'] == $inputrace && $entry['Gender'] == $inputgender) {
          array_push($relevantfirstnames, $entry);
        }
      }

      // Fetch and initial trim of lastname options
      $lastnamejson = file_get_contents(base_path().'/database/character_lastname11.6.17.json');
      $lastnamearray = json_decode($lastnamejson, true);
      $relevantlastnames = [];

      foreach ($lastnamearray as $entry) {
        if($entry['Race'] == $inputrace) {
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
        $splitrname = str_split($entry['Firstname']);
        foreach($splitrname as $letter){
          // Increase rank if the given letter exists at all in inputname
          if(in_array($letter, $splitfname)){
            $entry['rank'] = ($entry['rank']) + 1;
          }
        }

        if($entry['rank'] > $best_fname_rank){
          $best_fname_rank = $entry['rank'];
          $best_fname = $entry['Firstname'];
        }
        // Add to new list of results for sort
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
        $splitrname = str_split($entry['Lastname']);
        foreach($splitrname as $letter){
          // Increase rank if the given letter exists at all in inputname
          if(in_array($letter, $splitlname)){
            $entry['rank'] = ($entry['rank']) + 1;
          }
        }
        if($entry['rank'] > $best_lname_rank){
          $best_lname_rank = $entry['rank'];
          $best_lname = $entry['Lastname'];
        }
        // Add to new list of results for sort
        array_push($rankedlastnames, $entry);
      }

      // Sort names by rank
      $rankedlastnames = array_values(array_sort($rankedlastnames, function ($value) {
        return $value['rank'];
      }));

      // Ensure best matches were found, if not return form and error
      if (isset($best_fname) == False || isset($best_lname) == False){
        $custom_errors = 'Unfortunately, a match could not be made. Please try again with a different name/race/gender combination, or request a random name.';
        return redirect('/')->withErrors($custom_errors);
      }

      // Select appropriate img file
      if ($inputrace === 'Human' && $inputgender === 'Male'){
        $charimg = 'https://www.dandwiki.com/w/images/a/ad/Cyrus.jpg';
      }
      elseif($inputrace === 'Human' && $inputgender === 'Female'){
        $charimg = 'https://media-waterdeep.cursecdn.com/avatars/thumbnails/6/258/420/618/636271801914013762.png';
      }
      elseif($inputrace === 'Elf' && $inputgender === 'Male')
      {
        $charimg = 'http://www.mascotdesigngallery.com/wp/wp-content/uploads/2014/09/jimnelsonart.blogspot-elf-bard.jpg';
      }
      elseif($inputrace === 'Elf' && $inputgender === 'Female'){
        $charimg = 'https://media-waterdeep.cursecdn.com/avatars/thumbnails/7/639/420/618/636287075350739045.png';
      }
      elseif($inputrace === 'Dwarf' && $inputgender === 'Male')
      {
        $charimg = 'https://i.pinimg.com/originals/18/d2/6b/18d26b5ca8e7de4082be1ce23f16f840.png';
      }
      elseif($inputrace === 'Dwarf' && $inputgender === 'Female'){
        $charimg = 'https://i.pinimg.com/736x/06/65/d9/0665d980a61b1014e530df9c8e65ed08--female-dwarf-players-handbook.jpg';
      }

      // Best name match found, hit redirect
      $charname = $best_fname . ' ' . $best_lname;

      return redirect('/generate/personalized')->with([
        'inputname' => $inputname,
        'inputrace' => $inputrace,
        'inputgender' => $inputgender,
        'charname' => $charname,
        'returnimg' => $charimg,
        'ranked_fnamelist'=> $rankedfirstnames,
        'ranked_lnamelist'=> $rankedlastnames,
        'requesttype' => $requesttype
      ]);
    }

    // This method redirects to the results page and passes the results as session data
    public function index()
    {
      return view('results')->with([
        'charname' => session('charname'),
        'inputname' => session('inputname'),
        'inputrace' => session('inputrace'),
        'inputgender' => session('inputgender'),
        'returnimg' => session('returnimg'),
        'ranked_fnamelist' => session('ranked_fnamelist'),
        'ranked_lnamelist' => session('ranked_lnamelist'),
        'requesttype' => session('requesttype')
      ]);
    }

    // This method handles random name generation option
    public function random(Request $request)
    {
      $requesttype = 'random';

      // Randomly assign character race and gender
      $raceoptions = [
          1 => 'Human',
          2 => 'Dwarf',
          3 => 'Elf',
      ];

      $rand_key_race = array_rand($raceoptions, 1);
      $randomrace = $raceoptions[$rand_key_race];

      $genderoptions = [
        1 => 'Male',
        2 => 'Female',
      ];

      $rand_key_gender = array_rand($genderoptions, 1);
      $randomgender = $genderoptions[$rand_key_gender];

      // Fetch name options and convert to arrays
      $firstnamejson = file_get_contents(base_path().'/database/character_firstname11.6.17.json');
      $firstnamearray = json_decode($firstnamejson, true);
      $lastnamejson = file_get_contents(base_path().'/database/character_lastname11.6.17.json');
      $lastnamearray = json_decode($lastnamejson, true);

      // Initial trim of firstname options
      $relevantfirstnames = [];

      foreach ($firstnamearray as $entry) {
        if($entry['Race'] == $randomrace && $entry['Gender'] == $randomgender) {
          array_push($relevantfirstnames, $entry);
        }
      }

      // Initial trim of lastname options
      $relevantlastnames = [];

      foreach ($lastnamearray as $entry) {
        if($entry['Race'] == $randomrace) {
          array_push($relevantlastnames, $entry);
        }
      }

      // Select random positions from first name options
      $rand_keys_firstn = array_rand($relevantfirstnames, 1);
      $firstname = $relevantfirstnames[$rand_keys_firstn]['Firstname'];

      // Select random positions from last name options
      $rand_keys_lastn = array_rand($relevantlastnames, 1);
      $lastname = $relevantlastnames[$rand_keys_lastn]['Lastname'];

      // Return concatenated name to results
      $charname = $firstname . ' ' . $lastname;

      // Select appropriate img file
      if ($randomrace === 'Human' && $randomgender === 'Male'){
        $charimg = 'https://www.dandwiki.com/w/images/a/ad/Cyrus.jpg';
      }
      elseif($randomrace === 'Human' && $randomgender === 'Female'){
        $charimg = 'https://media-waterdeep.cursecdn.com/avatars/thumbnails/6/258/420/618/636271801914013762.png';
      }
      elseif($randomrace === 'Elf' && $randomgender === 'Male')
      {
        $charimg = 'http://www.mascotdesigngallery.com/wp/wp-content/uploads/2014/09/jimnelsonart.blogspot-elf-bard.jpg';
      }
      elseif($randomrace === 'Elf' && $randomgender === 'Female'){
        $charimg = 'https://media-waterdeep.cursecdn.com/avatars/thumbnails/7/639/420/618/636287075350739045.png';
      }
      elseif($randomrace === 'Dwarf' && $randomgender === 'Male')
      {
        $charimg = 'https://i.pinimg.com/originals/18/d2/6b/18d26b5ca8e7de4082be1ce23f16f840.png';
      }
      elseif($randomrace === 'Dwarf' && $randomgender === 'Female'){
        $charimg = 'https://i.pinimg.com/736x/06/65/d9/0665d980a61b1014e530df9c8e65ed08--female-dwarf-players-handbook.jpg';
      }

      return view('results')->with([
        'charname' => $charname,
        'chargender' => $randomgender,
        'charrace' => $randomrace,
        'returnimg' => $charimg,
        'requesttype' => $requesttype

      ]);
    }

}
