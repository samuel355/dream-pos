<?php
  include_once('includes/head.php');
  include_once('includes/auth.php');

  if(!isAdmin()){
    header('Location: /pos');
    exit();
  }
  if(isAdmin()){
    header('Location: /dashboard');
  }
?>

<body>
  <div id="global-loader">
    <div class="whirly-loader"> </div>
  </div>


  <?php include_once('includes/scripts.php') ?>