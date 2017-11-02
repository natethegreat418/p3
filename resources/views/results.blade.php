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
    <div class="col">
      <h1>I dub thee... <br> {{ $charname }}</h1>
    </div>
    @if(isset($returnimg))
    <div class="col">
        <img src="{{ $returnimg }}">
    </div>
    @else
    <div>
      <img src="https://a.disquscdn.com/get?url=https%3A%2F%2Fyt3.ggpht.com%2F-H7Ofqi47o70%2FAAAAAAAAAAI%2FAAAAAAAAAAA%2FcTBdlRrGTMU%2Fs900-c-k-no-mo-rj-c0xffffff%2Fphoto.jpg&key=8C069f0wLJWpOvkfbtn4Tw">
    </div>
    @endif

  </div>
@endsection
