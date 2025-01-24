<?php
include 'includes/head.php';
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
    <div class="account-content">
      <div class="login-wrapper">
        <div class="login-content">
          <form id="login-form" class="login-userset" method="POST">
            <div class="login-logo logo-normal text-center">
              <span style="font-weight: 900; font-size:24px">POPSY BUBBLE TEA SHOP</span>
            </div>
            <div class="login-userheading">
              <h3>Sign In</h3>
              <h4>Please login to your account</h4>
            </div>
            <div class="form-login">
              <label>Email</label>
              <div class="form-addons">
                <input type="email" placeholder="Enter your email address" id="email" name="email">
                <img src="assets/img/icons/mail.svg" alt="img">
              </div>
            </div>
            <div class="form-login">
              <label>Password</label>
              <div class="pass-group">
                <input name="password" id="password" type="password" class="pass-input" placeholder="Enter your password">
                <span class="fas toggle-password fa-eye-slash"></span>
              </div>
            </div>
            <div class="form-login">
              <div class="alreadyuser">
                <h4><a href="#" class="hover-a">Forgot Password?</a></h4>
              </div>
            </div>
            <div class="form-login">
              <button type="submit" class="btn btn-login" id="btn-login">Sign In</button>
            </div>
          </form>
        </div>
        <div class="login-img">
          <img src="assets/img/boba/boba-c.png" alt="img">
        </div>
      </div>
    </div>
  </div>
  <?php include 'includes/scripts.php'; ?>
  </body>

</html>