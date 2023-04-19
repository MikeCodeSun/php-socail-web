<?php
  include 'unauth.php';

  require 'db.php';
  $name=$password = '';
  $name_err=$password_err = '';
  if($_SERVER["REQUEST_METHOD"] == 'POST'){
    $name = $_POST['name'];
    $password = $_POST['password'];
    $err_arr = array();
    // check user name&password input err
    if(empty(trim($name))){
      $name_err = "name must not be empty";
      $err_arr[] = $name_err;
    } else {
      $select_query = "SELECT * FROM users WHERE name = '$name'";
      $select_result = mysqli_query($conn, $select_query);
      if(mysqli_num_rows($select_result) == 0){
        $name_err = "user name not register";
        $err_arr[] = $name_err;
      } elseif(empty($password)){
        $password_err = "password must not be empty";
        $err_arr[] = $password_err;
      } else {
        $row =  mysqli_fetch_assoc($select_result);
        $hash_password = $row['password'];
        $valid = password_verify($password, $hash_password);
        if(!$valid) {
          $password_err = "password not right";
          $err_arr[] = $password_err;
        } else {
          $_SESSION['name'] = $row['name'];
          $_SESSION['id'] = $row['id'];
          $_SESSION['login'] = true;
          header("location: index.php");
        }
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
      <h1 class="text-center">Login</h1>
    <form method="post" action="login.php">
  <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">Name:</label>
    <input type="text" name="name" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" value="<?php echo isset($_POST['name']) ? $_POST['name'] : '' ?>" >
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
    <input type="password" class="form-control" id="exampleInputPassword1" name="password" value="<?php echo isset($_POST['password']) ? $_POST['password'] : '' ?>">
    <?php 
      if($password_err) {
        echo "<div class='alert alert-danger' 'role='alert'>
        $password_err
      </div>";
      }
    ?>
  </div>
  
  <button type="submit" class="btn btn-primary">Login</button>
  <span>or <a  href="register.php">Register</a></span>
</form>
    </div>
  </div>
  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
  </body>
</html>