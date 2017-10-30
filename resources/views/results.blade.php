@extends('layouts.master')

@section('content')
<div class="container">
    @if(isset($inputname))
      <div class ="row">
        <h4>Your name: {{ $inputname }} </h3>
      </div>
      <div class ="row">
        <h4>Requested character race: {{ $inputrace }} </h3>
      </div>
      <div class ="row">
        <h4>Requested character gender: {{ $inputgender }} </h3>
      </div>
    @endif

  <div class="row">
    <h1>I dub thee... {{ $charname }}</h1>
  </div>
@endsection
