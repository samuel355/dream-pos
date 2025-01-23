<?php
  include_once('includes/head.php');
  include_once('includes/auth.php');
  requireLogin();

  if(!isAdmin() || !isSysAdmin()){
    header('Location: /pos');
    exit();
  }
  if(isAdmin() || isSysAdmin()){
    header('Location: /dashboard');
    exit;
  }
?>

<body>
  <div id="global-loader">
    <div class="whirly-loader"> </div>
  </div>


  <?php include_once('includes/scripts.php') ?>