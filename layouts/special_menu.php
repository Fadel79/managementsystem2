<ul class="nav flex-column">

  <!-- Dashboard -->
  <li class="nav-item">
    <a class="nav-link" href="op.php">
      <i class="glyphicon glyphicon-home"></i> Dashboard
    </a>
  </li>

  <!-- Products -->
  <li class="nav-item">
    <a class="nav-link" href="product_op.php">
      <i class="glyphicon glyphicon-gift"></i> Products
    </a>
  </li>

  <!-- Supplier -->
  <li class="nav-item">
    <a class="nav-link" href="supplier_op.php">
      <i class="glyphicon glyphicon-briefcase"></i> Supplier
    </a>
  </li>

  <!-- Customer -->
  <li class="nav-item">
    <a class="nav-link" href="customer_op.php">
      <i class="glyphicon glyphicon-shopping-cart"></i> Customer
    </a>
  </li>

  <!-- Report dengan submenu -->
  <li>
    <a href="#reportSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
      <i class="glyphicon glyphicon-list-alt"></i> <span>Report</span>
    </a>
    <ul class="collapse list-unstyled" id="reportSubmenu">
      <li><a href="stock_in_op.php">Stock In</a></li>
      <li><a href="stock_out_op.php">Stock Out</a></li>
    </ul>
  </li>
</ul>
