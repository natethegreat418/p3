<!doctype html>
<html>
    <head>
      <title>DnD Name Generator</title>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <!-- <link rel="stylesheet" href="<?php echo asset('css/welcome.css')?>"; -->
      <!-- Bootstrap -->
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
    </head>
    <body>

  <div class="container">
    <div class="row">
      <h1>DnD Name Generator</h1>
    </div>
    <div class="row">
      <button id="show" class="btn btn-primary">I want a personalized name</button>
      <button class="btn btn-primary">I want a random name</button>
    </div>
    <div id="personalized" class="row">
       <form method="POST">
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
          <select size="4" class="form-control" name="charrace">
            <option value="elf">Elf</option>
            <option value="dwarf">Dwarf</option>
            <option value="human">Human</option>
            <option value="orc">Orc</option>
          </select>
        </div>
        <button type="submit" name='submit' class="btn btn-primary">Submit</button>
      </form>
    </div>
  </div>

  <!-- Jquery -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script>
  $(document).ready(function() {
    $("#personalized").hide();
    $("#show").click(function(){
      $("#personalized").show();
      });
  });
  </script>
</body>
</html>
