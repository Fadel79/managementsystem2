<?php
  $page_title = 'All Products';
  require_once('includes/load.php');
  page_require_level(2);

  $all_products = find_all('products'); // Pastikan fungsi find_all tersedia

  include_once('layouts/header.php');
?>

<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <strong>
          <span class="glyphicon glyphicon-th-large"></span>
          <span>Products</span>
        </strong>
        <a href="add_product.php" class="btn btn-info pull-right btn-sm">Add New Product</a>
      </div>
      <div class="panel-body">
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th class="text-center" style="width: 50px;">#</th>
              <th>Product Name</th>
              <th>Description</th>
              <th>Category ID</th>
              <th>Selling Price</th>
              <th>Stock</th>
              <th>Image</th>
              <th class="text-center" style="width: 100px;">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php