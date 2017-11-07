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

      // Fetch relevant name lists
      $relevantfirstnames = $this->trimfnamelist($inputrace, $inputgender);
      $relevantlastnames = $this->trimlnamelist($inputrace, $inputgender);

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

      // Retrieve appropriate img value
      $charimg = $this->getimg($inputrace, $inputgender);

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
      $inputrace = $raceoptions[$rand_key_race];

      $genderoptions = [
        1 => 'Male',
        2 => 'Female',
      ];

      $rand_key_gender = array_rand($genderoptions, 1);
      $inputgender = $genderoptions[$rand_key_gender];

      // Fetch relevant name lists
      $relevantfirstnames = $this->trimfnamelist($inputrace, $inputgender);
      $relevantlastnames = $this->trimlnamelist($inputrace, $inputgender);

      // Select random positions from first name options
      $rand_keys_firstn = array_rand($relevantfirstnames, 1);
      $firstname = $relevantfirstnames[$rand_keys_firstn]['Firstname'];

      // Select random positions from last name options
      $rand_keys_lastn = array_rand($relevantlastnames, 1);
      $lastname = $relevantlastnames[$rand_keys_lastn]['Lastname'];

      // Get concatenated name
      $charname = $firstname . ' ' . $lastname;

      // Retrieve appropriate img file
      $charimg = $this->getimg($inputrace, $inputgender);

      return view('results')->with([
        'charname' => $charname,
        'chargender' => $inputgender,
        'charrace' => $inputrace,
        'returnimg' => $charimg,
        'requesttype' => $requesttype

      ]);
    }

    // This method redirects to the results page and passes the personalized results as session data
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

    // This method fetchs the entire first name list and trims it to match the request variables
    public function trimfnamelist($inputrace, $inputgender)
    {
      // Fetch and initial trim of firstname options
      $firstnamejson = file_get_contents(base_path().'/database/character_firstname11.6.17.json');
      $firstnamearray = json_decode($firstnamejson, true);
      $relevantfirstnames = [];

      foreach ($firstnamearray as $entry) {
        if($entry['Race'] == $inputrace && $entry['Gender'] == $inputgender) {
          array_push($relevantfirstnames, $entry);
        }
      }
      return $relevantfirstnames;
    }

    // This method fetchs the entire last name list and trims it to match the request variables
    public function trimlnamelist($inputrace, $inputgender)
    {
      // Fetch and initial trim of lastname options
      $lastnamejson = file_get_contents(base_path().'/database/character_lastname11.6.17.json');
      $lastnamearray = json_decode($lastnamejson, true);
      $relevantlastnames = [];

      foreach ($lastnamearray as $entry) {
        if($entry['Race'] == $inputrace) {
          array_push($relevantlastnames, $entry);
        }
      }
      return $relevantlastnames;
    }

    // This method returns the appropriate img content based on the request input
    public function getimg($inputrace, $inputgender)
    {
      // Select appropriate img file
      if ($inputrace === 'Human' && $inputgender === 'Male'){
        //https://www.dandwiki.com/w/images/a/ad/Cyrus.jpg
        $charimg = 'human m-min.jpg';
      }
      elseif($inputrace === 'Human' && $inputgender === 'Female'){
        //https://media-waterdeep.cursecdn.com/avatars/thumbnails/6/258/420/618/636271801914013762.png
        $charimg = 'human f-min.png';
      }
      elseif($inputrace === 'Elf' && $inputgender === 'Male')
      {
        //http://www.mascotdesigngallery.com/wp/wp-content/uploads/2014/09/jimnelsonart.blogspot-elf-bard.jpg
        $charimg = 'elf m-min.jpg';
      }
      elseif($inputrace === 'Elf' && $inputgender === 'Female'){
        //https://media-waterdeep.cursecdn.com/avatars/thumbnails/7/639/420/618/636287075350739045.png
        $charimg = 'elf f-min.png';
      }
      elseif($inputrace === 'Dwarf' && $inputgender === 'Male')
      {
        //https://i.pinimg.com/originals/18/d2/6b/18d26b5ca8e7de4082be1ce23f16f840.png
        $charimg = 'dwarf m-min.png';
      }
      elseif($inputrace === 'Dwarf' && $inputgender === 'Female'){
        //https://i.pinimg.com/736x/06/65/d9/0665d980a61b1014e530df9c8e65ed08--female-dwarf-players-handbook.jpg
        $charimg = 'dwarf f-min.jpg';
      }
      return $charimg;
    }
}
