<?php 
  
  $conn = mysqli_connect(
    "localhost",
    "root",
    "1234567890a",
    "php_test"
  );
  
  

  if(!$conn) {
    die('connect error:' . mysqli_connect_error());
  }
  // echo "Connected successfully";
?>