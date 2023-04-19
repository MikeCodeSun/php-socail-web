<?php
  session_start();
  if(!empty($_SESSION['login'])) {
    header("location: index.php");
    exit;
  }
?>