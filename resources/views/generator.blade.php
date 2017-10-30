@extends('layouts.master')

@section('content')
<div class="container">
  <div class="row">
    <h1>DnD Name Generator</h1>
  </div>
  <div class="row">
    <button id="show" class="btn btn-primary">I want a personalized name</button>
    <form method="GET" action="/generate/random">
      <button class="btn btn-primary">I want a random name</button>
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
        <label>What race is your character?</label>
        <select size="4" class="form-control" name="charrace" required>
          <option value="elf">Elf</option>
          <option value="dwarf">Dwarf</option>
          <option value="human">Human</option>
          <option value="orc">Orc</option>
        </select>
      </div>
      <button type="submit" name='submit' class="btn btn-primary">Submit</button>
    </form>
  </div>

  @push('errors')
    @if(count($errors) > 0)
    <div class="row">
      <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
    @endif
  @endpush

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
