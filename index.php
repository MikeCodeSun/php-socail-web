<?php
  include "auth.php";
  require 'db.php';
  $user_id = $_SESSION['id'];
  // get all post from follow
  $select_posts_query = "SELECT p.*, u.id as uid, u.name as uname FROM post p LEFT JOIN users u ON p.user_id = u.id WHERE p.user_id in (SELECT f.follow FROM follow f WHERE f.followed_by = '$user_id') ORDER BY p.created_at DESC";
  $all_post_result = mysqli_query($conn, $select_posts_query);
  // post new 
  $post =  $post_err ="";
  if(isset($_POST['newpost'])){
    $post = $_POST['newpost'];
    if(empty(trim($post))){
      $post_err = "Post must be not empty";
    }elseif(strlen($post) > 140) {
      $post_err = "Post must be less than 140";
    } else {
      $uid = 'user_id';
      $new_post_query = sprintf("INSERT INTO post (content, %s) VALUES ('%s', %s)", mysqli_real_escape_string($conn, $uid), mysqli_real_escape_string($conn, $post), mysqli_real_escape_string($conn, intval($user_id)));
      $new_post_result = mysqli_query($conn, $new_post_query);
      if($new_post_result){
        header("location: index.php");
      }
    }
  }

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home</title>
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
      <a href="profile.php?id=<?php echo $_SESSION['id'] ?>" role="button" class="btn btn-primary"><?php echo $_SESSION['name'] ?></a>
      <a role="button" class="btn btn-danger" href="logout.php">logout</a>
      </div>
    </div>
  </div>
</nav>
  <!-- navbar end -->
  <!-- container begin -->
  <div class="container">
    <div class="row"><div class="col-8 mt-5">
      <?php
      while($row = mysqli_fetch_array($all_post_result)){
        echo "<div class='card mb-2'>
      <div class='card-header'>
        {$row['uname']}:
      </div>
      <div class='card-body'>
        <h5 class='card-title'>{$row['content']}</h5>
      </div>
    </div>";
      }
      ?>
    </div>
    <div class="col-4 mt-5">
      <?php
        if($post_err){
          echo "<div class='alert alert-danger' role='alert'>
          $post_err
        </div>";
        }
      ?>
      <form action="index.php" method="post">
      <textarea class="form-control" rows="8" placeholder="post something new..." name="newpost"></textarea>
      <div class="d-grid">
      <button type="submit" class="btn btn-primary btn-block">submit</button>
      </div>
      </form>
    </div></div>
  </div>
  <!-- container end -->
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</html>