<?php
session_start();
include 'includes/head.php';
?>

<body class="account-page">
  <div class="main-wrapper">
    <div class="account-content">
      <div class="login-wrapper">
        <div class="login-content">
          <div class="login-userset">
            <div class="login-logo">
              <a href="/"><span style="font-weight: 900; font-size:24px">POPSY BUBBLE TEA SHOP</span></a>
            </div>
            <div class="login-userheading">
              <h3>Forgot Password</h3>
              <h4>Enter your email to reset password</h4>
            </div>
            <form id="forgot-password-form">
              <div class="form-login">
                <label>Email</label>
                <div class="form-addons">
                  <input type="email" id="email" name="email" placeholder="Enter your email" required>
                  <img src="assets/img/icons/mail.svg" alt="img">
                </div>
              </div>
              <div class="form-login">
                <button type="submit" class="btn btn-login">Submit</button>
              </div>
            </form>
            <div class="form-setlogin">
              <h4>Remember your password? <a href="login" class="hover-a">Sign In</a></h4>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php include 'includes/scripts.php'; ?>
  <script src="assets/js/forgot-password.js"></script>
</body>

</html>