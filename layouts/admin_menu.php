<ul class="nav flex-column">

  <!-- Dashboard -->
  <li class="nav-item">
    <a class="nav-link" href="admin.php">
      <i class="glyphicon glyphicon-home"></i> Dashboard
    </a>
  </li>

  <!-- User Management dengan submenu -->
  <li>
    <a href="#userSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
      <i class="glyphicon glyphicon-user"></i> <span>User Management</span>
    </a>
    <ul class="collapse list-unstyled" id="userSubmenu">
      <li><a href="group.php">Categorie User</a></li>
      <li><a href="users.php">Manage Users</a></li>
    </ul>
  </li>

  <!-- Categories -->
  <li class="nav-item">
    <a class="nav-link" href="categorie.php">
      <i class="glyphicon glyphicon-th-large"></i> Categories
    </a>
  </li>

  <!-- Products -->
  <li class="nav-item">
    <a class="nav-link" href="product.php">
      <i class="glyphicon glyphicon-gift"></i> Products
    </a>
  </li>

  <!-- Supplier -->
  <li class="nav-item">
    <a class="nav-link" href="supplier.php">
      <i class="glyphicon glyphicon-briefcase"></i> Supplier
    </a>
  </li>

  <!-- Customer -->
  <li class="nav-item">
    <a class="nav-link" href="customer.php">
      <i class="glyphicon glyphicon-shopping-cart"></i> Customer
    </a>
  </li>

  <!-- Report dengan submenu -->
  <li>
    <a href="#reportSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
      <i class="glyphicon glyphicon-list-alt"></i> <span>Report</span>
    </a>
    <ul class="collapse list-unstyled" id="reportSubmenu">
      <li><a href="stock_in.php">Stock In</a></li>
      <li><a href="stock_out.php">Stock Out</a></li>
    </ul>
  </li>
</ul>
