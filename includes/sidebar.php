<div class="sidebar" id="sidebar">
  <div class="sidebar-inner slimscroll">
    <div id="sidebar-menu" class="sidebar-menu">
      <ul>
        <!-- Main -->
        <li class="submenu-open">
          <h6 class="submenu-hdr">Main</h6>
          <ul>
            <li class="<?php echo isActiveRoute('/') ? 'active' : ''; ?>">
              <a href="/"><i data-feather="grid"></i><span>Dashboard</span></a>
            </li>
            <li class="<?php echo isActiveRoute('pos') ? 'active' : ''; ?>">
              <a href="pos"><i data-feather="hard-drive"></i><span>POS</span></a>
            </li>
            <?php if (isset($_SESSION['sysadmin']) || isset($_SESSION['role']) === 'admin'): ?>
              <li class="<?php echo isActiveRoute('sales') ? 'active' : ''; ?>">
                <a href="/sales"><i data-feather="box"></i><span>Sales</span></a>
              </li>
            <?php endif; ?>
          </ul>
        </li>

        <!-- Products -->
        <li class="submenu-open">
          <h6 class="submenu-hdr">Products</h6>
          <ul>
            <li class="<?php echo isActiveRoute('products') ? 'active' : ''; ?>">
              <a href="/products"><i data-feather="box"></i><span>Products</span></a>
            </li>
            <li class="<?php echo isActiveRoute('categories') ? 'active' : ''; ?>">
              <a href="/categories"><i data-feather="codepen"></i><span>Categories</span></a>
            </li>
            <?php if (isset($_SESSION['sysadmin']) || isset($_SESSION['role']) === 'admin'): ?>
              <li class="<?php echo isActiveRoute('product-pricing') ? 'active' : ''; ?>">
                <a href="/product-pricing"><i data-feather="dollar-sign"></i><span>Product Pricing</span></a>
              </li>
            <?php endif; ?>
          </ul>
        </li>

        <!-- People -->
        <li class="submenu-open">
          <h6 class="submenu-hdr">People</h6>
          <ul>
            <?php if (isset($_SESSION['sysadmin']) || isset($_SESSION['role']) === 'admin'): ?>
              <li class="<?php echo isActiveRoute('users') ? 'active' : ''; ?>">
                <a href="/users"><i data-feather="users"></i><span>Users</span></a>
              </li>
            <?php endif; ?>

            <li class="<?php echo isActiveRoute('customers') ? 'active' : ''; ?>">
              <a href="/customers"><i data-feather="user"></i><span>Customers</span></a>
            </li>
          </ul>
        </li>

        <!-- Logout -->
        <li>
          <a href="javascript:void(0);" onclick="logout()">
            <i data-feather="log-out"></i><span>Logout</span>
          </a>
        </li>
      </ul>
    </div>
  </div>
</div>