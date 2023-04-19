<?php
  include 'unauth.php';
  require 'db.php';

  $name=$password1=$password2 = "";
  $name_err=$password1_err=$password2_err = "";

  

  if($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $password1 = $_POST['password1'];
    $password2 = $_POST['password2'];
    $err_arr = [];

    if(empty(trim($name))){
      $name_err = "Name must not be empty";
      $err_arr[] = $name_err;
    } else {
      $query = "SELECT * FROM users WHERE name='$name'";
      $result = mysqli_query($conn, $query);
      // bug not if (){} 
      if(mysqli_num_rows($result) != 0 ){
        $name_err = "User name already reigstered";
        $err_arr[] = $name_err;
      }
      
    }

    if(empty($password1)){
      $password1_err = "password must not be empty";
      $err_arr[] = $password1_err;
    } elseif(strlen($password1) < 6) {
      $password1_err = "password must more than 6";
      $err_arr[] = $password1_err;
    }

    if(empty($password2)){
      $password2_err = "Confirm password must not be empty";
      $err_arr[] = $password2_err;
    } else if ($password1 != $password2) {
      $password2_err = "password and Confirm password not match";
      $err_arr[] = $password2_err;
    }
    // echo count($err_arr);
    
    if(count($err_arr) == 0) {
      echo('no err');
      $hash_password = password_hash($password1, PASSWORD_DEFAULT);
      $register_query = "INSERT INTO users (name, password) VALUES ('$name', '$hash_password')";
      $insert_result =mysqli_query($conn, $register_query);
      if($insert_result) {
        $name=$password1=$password2 = "";
        $name_err=$password1_err=$password2_err = "";
        unset($err_arr);
  
        header('location:login.php');
      }
    }
  }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
  </head>
  <body>

  <div class="container">
    <div class="col-md-6 mt-5 mx-auto">
      <h1 class="text-center">Register</h1>
    <form method="post" action="">
  <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">Name:</label>
    <input type="text" name="name" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="name" value="<?php echo isset($_POST['name']) ?$_POST['name'] : "";  ?>" >
    <?php 
      if($name_err) {
        echo "<div class='alert alert-danger' 'role='alert'>
        $name_err
      </div>";
      }
    ?>
  </div>
  <div class="mb-3">
    <label for="exampleInputPassword1" class="form-label">Password</label>
    <input type="password" class="form-control" id="exampleInputPassword1" name="password1" value="<?php echo isset($_POST['password1']) ?$_POST['password1'] : "";  ?>"  >
    <?php 
      if($password1_err) {
        echo "<div class='alert alert-danger' 'role='alert'>
        $password1_err
      </div>";
      }
    ?>
  </div>
  <div class="mb-3">
    <label for="exampleInputPassword2" class="form-label">Confrim Password</label>
    <input name="password2" type="password" class="form-control" id="exampleInputPassword2" value="<?php echo isset($_POST['password2']) ?$_POST['password2'] : "";  ?>"  >
    <?php 
      if($password2_err) {
        echo "<div class='alert alert-danger' 'role='alert'>
        $password2_err
      </div>";
      }
    ?>
  </div>
  
  <button type="submit" class="btn btn-primary">Submit</button>
  <span>or <a  href="login.php">login</a></span>
</form>
    </div>
  </div>
  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
  </body>
</html>