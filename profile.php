<?php
  include 'auth.php';
  require 'db.php';
  $profile_id = '';
  $user_id = $_SESSION['id'];
  if(isset($_GET['id'])){
    $profile_id = $_GET['id'];
  }
  // get user profile info from db
  $select_user_query = "SELECT * FROM users WHERE id='$profile_id'";
  $user_profile = mysqli_query($conn, $select_user_query);
  $profile_row = mysqli_fetch_assoc($user_profile);
  // get following 
  $profile_following_result= mysqli_query($conn, "SELECT * FROM follow WHERE followed_by = '$profile_id'");
  $profile_following_num = mysqli_num_rows($profile_following_result);
  // get followed
  $profile_followed_result= mysqli_query($conn, "SELECT * FROM follow WHERE follow = '$profile_id'");
  $profile_followed_num = mysqli_num_rows($profile_followed_result);
  // check is user follow this profile user
  $is_follow_result= mysqli_query($conn, "SELECT * FROM follow WHERE (follow = '$profile_id' AND followed_by = '$user_id')");
  $is_follow_num = mysqli_num_rows($is_follow_result);
  // toggle follow and unfollow 
    $user_id = intval($user_id);
    $profile_id = intval($profile_id);
  if (isset($_POST['follow'])){
    $follow_result = mysqli_query($conn, "INSERT INTO follow (follow, followed_by) VALUES('$profile_id','$user_id')");
    if($follow_result){
      $is_follow_num = 1;
      $profile_followed_num += 1;
      if($profile_id == $user_id){
        $profile_following_num += 1;
      }
    }
  } elseif(isset($_POST['unfollow'])){
    $unfollow_result = mysqli_query($conn, "DELETE FROM follow WHERE (follow = '$profile_id' AND followed_by='$user_id')");
    if($unfollow_result){
      $is_follow_num = 0;
      $profile_followed_num -= 1;
      if($profile_id == $user_id){
        $profile_following_num -= 1;
      }
    }
  }
  // fetch all post from this profile
  $profile_post_result = mysqli_query($conn, "SELECT * FROM post WHERE user_id = '$profile_id';
  ");
  // delete post 0418 
  if(isset($_POST['delete'])){
    $post_id =  $_POST['delete'];
    $delete_post_result = mysqli_query($conn,"DELETE FROM post WHERE (id = $post_id AND user_id = '$user_id')");
    if($delete_post_result){
      header("location: profile.php?id=$profile_id");
    }
  }
  // edit post
  
  if(isset($_POST['edit'])){
    $edit_post_id = $_POST['edit'];
    if(isset($edit_post_id) && isset($_POST['edit-content'])) {
      $post_content = $_POST['edit-content'];
      $edit_query = sprintf("UPDATE post SET content = '%s' WHERE id = '%s'", mysqli_real_escape_string($conn, $post_content),  mysqli_real_escape_string($conn, $edit_post_id));

      echo $post_content;
      $edit_post_result = mysqli_query($conn, $edit_query);
      if($edit_post_result) {
        header("location: profile.php?id=$profile_id");
      }
    }
  }
  // upload image
  if(isset($_POST['upload'])) {
    $img = $_FILES['image'];
    if(isset($img)){
      // image size 0< & <1mb
      
      if($img['size'] > 0 && filesize($img['tmp_name']) < 1000000) {
        // check file type is image 
        $img_type = exif_imagetype($img['tmp_name']);
        
          if($img_type){
            if($user_id == $profile_id) {
            if(!empty($profile_row['image'])){
              unlink(__DIR__ . "/image/" . $profile_row['image']);
            }
            $image_ext_name = image_type_to_extension($img_type, true);
            $image_name = bin2hex(random_bytes(6)) . $image_ext_name;
            move_uploaded_file($img['tmp_name'], __DIR__ . "/image/" . $image_name);
            $update_image_query = sprintf("UPDATE users SET image = '%s' WHERE id = %s", mysqli_real_escape_string($conn, $image_name), mysqli_real_escape_string($conn, $user_id));

            $update_image_result = mysqli_query($conn, $update_image_query);
            if($update_image_result){
              header("location:profile.php?id=$profile_id");
            }
            }
        }
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
      <a href="profile.php?id=<?php echo $_SESSION['id'] ?>" role="button" class="btn btn-primary"><?php echo $_SESSION['name'] ?></a>
      <a role="button" class="btn btn-danger" href="logout.php">logout</a>
      </div>
    </div>
  </div>
</nav>
  <!-- navbar end -->
  <a href="javascript:history.go(-1)">go back</a>
  <!-- container begin -->
  <div class="container">
  <div class='col-md-10 mt-5 mx-auto' >
    <!-- profile begin -->
      <div class="card" >
      <div class="card-body">
      <div class="row">
        <div class="col-5">
          <img class='filud img' src="<?php echo empty($profile_row['image']) ? "./image/user.jpeg" : "./image/". $profile_row['image'] ?>" alt="user" style="max-height: 200px; max-width: 200px;">
          <?php
            if($user_id == $profile_id){
              echo "
              <form action='profile.php?id={$_SESSION['id']}' method='post' enctype='multipart/form-data' class='d-flex' style='max-width: 200px'>
              <input class='form-control w-50' type='file' id='formFile' name='image'>
              <button name='upload' type='submit'class='btn btn-warning w-50'>Change</button>
              </form>
              ";
            }
          ?>
          
        </div>
        <div class="col-7">
          <div class="fs-2 card-text text-capitalize"><?php echo $profile_row['name'] ?></div>
          <div class="fs-4 mt-3 card-text text-capitalize">
            <span class="text-success">folllowing:</span> <?php echo $profile_following_num ?> <span class="text-info">followers: </span><?php echo $profile_followed_num ?>
          </div>
          
            <form action="profile.php?id=<?php echo $profile_id ?>" method="post">
              <div class="btn-group mt-3 card-text">
              <?php
              if($is_follow_num == 1) {
                echo "
                <button class='btn btn-success disabled'>follow</button>
                <button class='btn btn-danger' name='unfollow' >unfollow</button>
                ";
              } else {
                echo "
                <button class='btn btn-success' name='follow'>follow</button>
                <button class='btn btn-danger disabled'>unfollow</button>
                ";
              }
              ?>
            </div>
            </form>
          
        </div>
      </div>
      </div>
      </div>
    <!-- profile end -->
    <!-- profile post begin -->
    <?php
      if(mysqli_num_rows($profile_post_result) == 0) {
        echo "<div class='card mt-2 p-2 text-center'>No post</div>
        ";
      } else{
        while($row_post = mysqli_fetch_array($profile_post_result)){
          if($user_id == $profile_id) {
            $html = "<div class='card mt-2'>
            <div class='card-body flex justify-between align-center'>
              <div class='d-flex justify-content-between align-items-center'>
              <span class='fs-5'>{$row_post['content']}</span>
              <span class='fs-6'>{$row_post['created_at']}</span>
              <form action='profile.php?id={$_SESSION['id']}' method='post'>
              <div class='btn-group'>
              <a href='#' type='button' class='btn btn-warning open-btn'  data-bs-toggle='modal' data-bs-target='#editModal' data-id='{$row_post['id']}'>
              edit
              </a>
                <button class='btn btn-danger' name='delete' value='{$row_post['id']}'>delete</button>
              </div>
              </form>
              
              </div>
            </div>
        </div>
        ";
        echo $html;
          } else {
            $html = "<div class='card mt-2'>
            <div class='card-body flex justify-between align-center'>
              <div class='d-flex justify-content-between align-items-center'>
              <span class='fs-5'>{$row_post['content']}</span>
              <span class='fs-6'>{$row_post['created_at']}</span>
              </div>
            </div>
        </div>
        "; 
        echo $html;
          }
        }
      }
    ?>
    <!-- profile post end -->
    <!-- Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Post</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="profile.php?id=<?php echo $_SESSION['id'] ?>" method="post">
      <div class="modal-body">
        <input type="text" id="editModalInput" class="form-control" name="edit-content" >
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button name="edit" id="save-btn" type="submit" class="btn btn-primary">Save</button>
      </div>
      </form>
    </div>
  </div>
</div>


  </div>
  </div>
  <!-- container end -->
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script>
  const openBtns = document.querySelectorAll('.open-btn')
  const saveBtn = document.getElementById('save-btn')
  
  openBtns.forEach((openBtn) => {
    openBtn.addEventListener('click', (e)=>{
    const post_id = e.target.getAttribute('data-id')
    console.log(post_id);
    saveBtn.setAttribute("value", post_id)
  })
  })
  
</script>
</html>
