<?php
  include "auth.php";
  require 'db.php';
  $select_all_user_query = "SELECT * FROM users";
  $result = mysqli_query($conn, $select_all_user_query);
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>all profile</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
</head>
<body>
  <!-- navbar begin -->
<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">Twitter</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="all-profile.php">all profile</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="search.php">search</a>
        </li>
      </ul>
      <div class="btn-group">
      <a href="profile.php?id=<?php echo $_SESSION['id'] ?> " role="button" class="btn btn-primary"><?php echo $_SESSION['name'] ?></a>
      <a role="button" class="btn btn-danger" href="logout.php">logout</a>
      </div>
    </div>
  </div>
</nav>
  <!-- navbar end -->
  <a href="javascript:history.go(-1)">go back</a>
  <!-- container begin -->
  <div class="container">
  <div class='col-md-8 mx-auto mt-5' >
    <?php
      while ($row = mysqli_fetch_array($result)){
        $user_img =  "./image/user.jpeg";
        if(!empty($row['image'])){
          $user_img = "./image/". $row['image']; 
        }
        
        echo "
        <a href='profile.php?id={$row['id']}' class='text-decoration-none' >
        <div class='card mb-3'>
          <div class='row'>
            <div class='col-4'>
              <img src='$user_img' alt='user'  class='img-thumbnail' style='max-height: 100px; max-width: 100px;' >
            </div>
            <div class='col-8 card-body'>
              <div class='card-text text-capitalize fs-5'>{$row['name']}</div>
            </div>
          </div>
        </div>
      </a>
        ";
      }
    ?>
    </div>
    </div>
  <!-- container end -->
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</html>
