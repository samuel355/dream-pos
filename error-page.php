<?php
  include_once('includes/head.php');
  require_once 'includes/db_connection.php';
  require_once 'includes/auth.php';

  // Redirect if already logged in
  if (isLoggedIn()) {
    header('Location: /');
    exit();
  }

?>

<body class="account-page">
  <div class="main-wrapper">
    <h1>Error page</h1>
  </div>
  <?php include_once('includes/scripts.php') ?>