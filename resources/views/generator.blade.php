@extends('layouts.master')

@section('content')
<div class="container">
  <div class="row">
    <h1>Crystal Orb: a DnD Name Generator</h1>
  </div>
  <div class="row">
    <h3>How it works</h3>
  </div>
  <div class="row">
    <div class="col-md-6">
      <p>The Orb knows all.  Select an option below to recieve guidance from the Orb.  If you choose a personalized name, the Orb will ask a few questions before it consults the manuscript for a best possible match.  Alternatively, you can ask the Orb random selection: you will recieve a random character race, gender and matching full name as the Orb sees fit.</p>
    </div>
  </div>
  <div class="row">
    <div class="col-md-1">
    </div>
    <div class="col-md-5">
      @if(count($errors) > 0)
        <div class="row errors">
          <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif
      <div>
        <button id="show" class="btn btn-primary">I want a personalized name</button>
      </div>
      <div>
        <form method="GET" action="/generate/random">
          <button class="btn btn-primary">Pick at random.</button>
        </form>
      </div>
    </div>
    <div class="col-md-3">
      <img id="orb" src="/images/crystalorb-min.jpg" alt="crystalorb">
    </div>
  </div>
  <div id="personalized" class="row">
    <div class="col-md-1">
    </div>
    <div class="col-md-5">
      <form method="POST" action="/generate">
        {{ csrf_field() }}

        <div class="form-group">
          <label>What is your first name?</label>
          <input type="text" name="firstname" pattern=".{3,}" title="Please enter a minimum of 3 characters" class="form-control" value='' required>
        </div>
        <div class="form-group">
          <label>What is your last name?</label>
          <input type="text" name="lastname" pattern=".{3,}" title="Please enter a minimum of 3 characters" class="form-control" value='' required>
        </div>
        <div class="form-group">
          <label>What gender does your character identify as?</label>
          <div class="radio">
            <label><input type="radio" name="chargender" value="Male" required>Male</label>
          </div>
          <div class="radio">
            <label><input type="radio" name="chargender" value="Female" required>Female</label>
          </div>
        </div>
        <div class="form-group">
          <label>What race is your character?</label>
          <select size="1" class="form-control" name="charrace" required>
            <option><option>
            <option value="Elf">Elf</option>
            <option value="Dwarf">Dwarf</option>
            <option value="Human">Human</option>
          </select>
        </div>
        <button type="submit" name='submit' class="btn btn-success">Submit</button>
      </form>
  </div>
</div>
</div>


@push('javascript')
  <script>
  $(document).ready(function() {
    $("#personalized").hide();
    $("#show").click(function(){
      $("#personalized").show();
      $("#orb").hide();
    });
  });
  </script>
@endpush

@endsection
