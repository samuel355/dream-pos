<div class="header">

  <div class="header-left active">
    <a href="/" class="logo logo-normal">
      <!-- <img src="assets/img/logo.png" alt> -->
       <span style="font-weight: 800">POPSY BUBBLE TEA SHOP</span>
    </a>
    <a href="/" class="logo logo-white">
      <!-- <img src="assets/img/logo-white.png" alt> -->
      <span style="font-weight: 800">POPSY BUBBLE TEA SHOP</span>
    </a>
    <a href="/" class="logo-small">
      <!-- <img src="assets/img/logo-small.png" alt> -->
      <span style="font-weight: 800">POPSY BUBBLE TEA SHOP</span>
    </a>
    <a id="toggle_btn" href="javascript:void(0);">
      <i data-feather="chevrons-left" class="feather-16"></i>
    </a>
  </div>

  <a id="mobile_btn" class="mobile_btn" href="#sidebar">
    <span class="bar-icon">
      <span></span>
      <span></span>
      <span></span>
    </span>
  </a>

  <ul class="nav user-menu">

    <li class="nav-item nav-searchinputs">
      <!--Global Search -->
    </li>

    <!-- Notifications -->

    <li class="nav-item dropdown nav-item-box">
      <a href="javascript:void(0);" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
        <i data-feather="bell"></i><span class="badge rounded-pill">2</span>
      </a>
      <div class="dropdown-menu notifications">
        <div class="topnav-dropdown-header">
          <span class="notification-title">Notifications</span>
          <a href="javascript:void(0)" class="clear-noti"> Clear All </a>
        </div>
        <div class="noti-content">
          <ul class="notification-list notifications-content">
            <!-- Notification content -->
          </ul>
        </div>
        <div class="topnav-dropdown-footer">
          <a href="/">View all Notifications</a>
        </div>
      </div>
    </li>

    <li class="nav-item dropdown has-arrow main-drop">
      <a href="javascript:void(0);" class="dropdown-toggle nav-link userset" data-bs-toggle="dropdown">
        <span class="user-info">
          <span class="user-letter">
            <?php if (isset($_SESSION['image']) && !empty($_SESSION['image'])): ?>
              <img src="php/<?php echo $_SESSION['image']; ?>" alt="Profile" class="img-fluid">
            <?php else: ?>
              <img src="assets/img/boba/boba-c.png" alt="Default Profile" class="img-fluid">
            <?php endif; ?>
          </span>
          <span class="user-detail">
            <span class="user-name"><?php echo isset($_SESSION['fullname']) ? $_SESSION['fullname'] : 'Guest'; ?></span>
            <span class="user-role"><?php echo isset($_SESSION['role']) ? ucfirst($_SESSION['role']) : ''; ?></span>
          </span>
        </span>
      </a>
      <div class="dropdown-menu menu-drop-user">
        <div class="profilename">
          <div class="profileset">
            <span class="user-img">
              <?php if (isset($_SESSION['image']) && !empty($_SESSION['image'])): ?>
                <img src="php/<?php echo $_SESSION['image']; ?>" alt="Profile">
              <?php else: ?>
                <img src="assets/img/boba/boba-c.png" alt="Default Profile">
              <?php endif; ?>
              <span class="status online"></span>
            </span>
            <div class="profilesets">
              <h6><?php echo isset($_SESSION['fullname']) ? $_SESSION['fullname'] : 'Guest'; ?></h6>
              <h5><?php echo isset($_SESSION['role']) ? ucfirst($_SESSION['role']) : ''; ?></h5>
            </div>
          </div>
          <hr class="m-0">
          <a class="dropdown-item logout pb-0" href="/pos">
            <img src="assets/img/icons/debitcard.svg" class="me-2" alt="img">POS
          </a>
          <hr class="m-0">
          <a class="dropdown-item logout pb-0" href="javascript:void(0);" onclick="logout()">
            <img src="assets/img/icons/log-out.svg" class="me-2" alt="img">Logout
          </a>
        </div>
      </div>
    </li>
  </ul>

  <div class="dropdown mobile-user-menu">
    <a href="javascript:void(0);" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"
      aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
    <div class="dropdown-menu dropdown-menu-right">
      <a class="dropdown-item" href="/pos">POS</a>
      <a class="dropdown-item" href="javascript:void(0);" onclick="logout()">Logout</a>
    </div>
  </div>

</div>