<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GenerateNameController extends Controller
{
    //
    public function index($firstname, $lastname, $charrace)
    {
      return 'Your personalized character name is...';
    }

    public function random()
    {
      return 'A totally random name is..';
    }

}
