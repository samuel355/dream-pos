<?php
session_start();
// Check if email is set in session
if (!isset($_SESSION['reset_email'])) {
  header('Location: login.php');
  exit();
}
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
              <h3>Reset Password</h3>
              <h4>Create your new password</h4>
            </div>
            <form id="reset-password-form">
              <div class="form-login">
                <label>New Password</label>
                <div class="pass-group">
                  <input type="password" id="password" name="password" class="pass-input" placeholder="Enter new password" required>
                  <span class="fas toggle-password fa-eye-slash"></span>
                </div>
              </div>
              <div class="form-login">
                <label>Confirm Password</label>
                <div class="pass-group">
                  <input type="password" id="confirm_password" name="confirm_password" class="pass-input" placeholder="Confirm password" required>
                  <span class="fas toggle-password fa-eye-slash"></span>
                </div>
              </div>
              <div class="form-login">
                <button type="submit" class="btn btn-login">Reset Password</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php include 'includes/scripts.php'; ?>
  <script src="assets/js/reset-password.js"></script>
</body>

</html>