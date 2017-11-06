@extends('layouts.master')

@section('content')
<div class="container">
  <div class="row">
    <h1>DnD Name Generator</h1>
  </div>
    @if(count($errors) > 0)
    <div class="row col-sm-6 errors">
      <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
    @endif
  <div class="row">
    <div class="col-sm-6">
      <button id="show" class="btn btn-primary">I want a personalized name</button>
    </div>
    <div class="col-sm-6">
      <form method="GET" action="/generate/random">
        <button class="btn btn-primary">Inspire me.</button>
      </form>
    </div>
  </div>
  <div id="personalized" class="row">
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
        <select size="3" class="form-control" name="charrace" required>
          <option value="Elf">Elf</option>
          <option value="Dwarf">Dwarf</option>
          <option value="Human">Human</option>
        </select>
      </div>
      <button type="submit" name='submit' class="btn btn-success">Submit</button>
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
