@extends('layouts.master')

@section('content')
<div class="container">
  <div class="row">
    <h1>DnD Name Generator</h1>
  </div>
    @if(count($errors) > 0)
    <div class="row col-sm-3 errors">
      <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
    @endif
  <div class="row">
    <button id="show" class="btn btn-primary">I want a personalized name</button>
    <form method="GET" action="/generate/random">
      <button class="btn btn-primary">I'm feeling lucky</button>
    </form>
  </div>
  <div id="personalized" class="row">
    <form method="POST" action="/generate">
      {{ csrf_field() }}

      <div class="form-group">
        <label>What is your first name?</label>
        <input type="text" name="firstname" class="form-control" value='' required>
      </div>
      <div class="form-group">
        <label>What is your last name?</label>
        <input type="text" name="lastname" class="form-control" value='' required>
      </div>
      <div class="form-group">
        <label>What gender does your character identify as?</label>
        <select size="2" class="form-control" name="chargender" required>
          <option value="male">Male</option>
          <option value="female">Female</option>
        </select>
      </div>
      <div class="form-group">
        <label>What race is your character?</label>
        <select size="3" class="form-control" name="charrace" required>
          <option value="elf">Elf</option>
          <option value="dwarf">Dwarf</option>
          <option value="human">Human</option>
        </select>
      </div>
      <button type="submit" name='submit' class="btn btn-primary">Submit</button>
    </form>
  </div>
</div>

@push('javascript')
  <script>
  $(document).ready(function() {
    $("#personalized").hide();
    $("#show").click(function(){
      $("#personalized").show();
    });
  });
  </script>
@endpush

@endsection
