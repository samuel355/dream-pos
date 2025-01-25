<?php
  include 'includes/head.php';
  include 'includes/auth.php';
  requireLogin();

  if(!isSysAdminOrAdmin()){
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


  <?php include 'includes/scripts.php'; ?>
  </body>

</html>