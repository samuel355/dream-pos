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
            <li class="<?php echo isActiveRoute('orders') ? 'active' : ''; ?>">
              <a href="orders"><i data-feather="box"></i><span>Orders</span></a>
            </li>
          </ul>
        </li>

        <!-- Products -->
        <li class="submenu-open">
          <h6 class="submenu-hdr">Products</h6>
          <ul>
            <li class="<?php echo isActiveRoute('products') ? 'active' : ''; ?>">
              <a href="/products"><i data-feather="box"></i><span>Products</span></a>
            </li>
            <li class="<?php echo isActiveRoute('add-product') ? 'active' : ''; ?>">
              <a href="/add-product"><i data-feather="plus-square"></i><span>Add Product</span></a>
            </li>
            <li class="<?php echo isActiveRoute('categories') ? 'active' : ''; ?>">
              <a href="/categories"><i data-feather="codepen"></i><span>Categories</span></a>
            </li>
            <li class="<?php echo isActiveRoute('add-category') ? 'active' : ''; ?>">
              <a href="/add-category"><i data-feather="plus-circle"></i><span>Add Category</span></a>
            </li>
            <li class="<?php echo isActiveRoute('product-pricing') ? 'active' : ''; ?>">
              <a href="/product-pricing"><i data-feather="dollar-sign"></i><span>Product Pricing</span></a>
            </li>
          </ul>
        </li>

        <!-- Sales -->
        <li class="submenu-open">
          <h6 class="submenu-hdr">Sales</h6>
          <ul>
            <li class="<?php echo isActiveRoute('sales') ? 'active' : ''; ?>">
              <a href="/sales"><i data-feather="shopping-cart"></i><span>Sales</span></a>
            </li>
            <li class="<?php echo isActiveRoute('invoices') ? 'active' : ''; ?>">
              <a href="/invoices"><i data-feather="file-text"></i><span>Invoices</span></a>
            </li>
          </ul>
        </li>

        <!-- People -->
        <li class="submenu-open">
          <h6 class="submenu-hdr">People</h6>
          <ul>
            <li class="<?php echo isActiveRoute('users') ? 'active' : ''; ?>">
              <a href="/users"><i data-feather="users"></i><span>Users</span></a>
            </li>
            <li class="<?php echo isActiveRoute('add-user') ? 'active' : ''; ?>">
              <a href="/add-user"><i data-feather="user-plus"></i><span>Add User</span></a>
            </li>
            <li class="<?php echo isActiveRoute('customers') ? 'active' : ''; ?>">
              <a href="/customers"><i data-feather="user"></i><span>Customers</span></a>
            </li>
          </ul>
        </li>

        <!-- Settings -->
        <li class="submenu-open">
          <h6 class="submenu-hdr">Settings</h6>
          <ul>
            <li class="<?php echo isActiveRoute('settings') ? 'active' : ''; ?>">
              <a href="/settings"><i data-feather="settings"></i><span>Settings</span></a>
            </li>
            <li>
              <a href="javascript:void(0);" onclick="logout()">
                <i data-feather="log-out"></i><span>Logout</span>
              </a>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</div>