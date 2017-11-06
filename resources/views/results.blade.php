@extends('layouts.master')

@section('content')
<div class="container">
    @if($requesttype == 'personalized')
      <div class ="row">
        <h4>Your name: {{ $inputname }} </h3>
      </div>
      <div class ="row">
        <h4>Requested character race: {{ $inputrace }} </h3>
      </div>
      <div class ="row">
        <h4>Requested character gender: {{ $inputgender }} </h3>
      </div>
      <div class="row">
        <div class="col-md-4">
          <h2>I dub thee... </h2><br><h1> {{ $charname }}</h1>
            Don't like the results? <a href="/">Try again!</a></br>
            Or get inspiration from a <a href="/generate/random">randomly generated name.</a>
        </div>
        <div class="col-md-4">
          <img class="resultsimg" src="{{ $returnimg }}">
        </div>
      </div>
    @else
      <div class="row">
        <div class="col-md-4">
          <h2>The Orb has selected... </h2></br><h1> a {{$chargender}} {{ $charrace }} </br> named {{ $charname }}</h1>
          Don't like the results? <a href="/generate/random">Get a new random name!</a></br>
          Or, try the <a href="/">personalized name generator.</a>
        </div>
        <div class="col-md-4">
          <img class="resultsimg" src="{{ $returnimg }}">
        </div>
      </div>
    @endif
</div>
@endsection
