<?php
  $page_title = 'Admin Home Page';
  require_once('includes/load.php');
  page_require_level(1);

  $c_categorie     = count_by_id('categories');
  $c_product       = count_by_id('products');
  $c_sale          = count_by_id('sales');
  $c_user          = count_by_id('users');
  $products_sold   = find_higest_saleing_product('10');
  $recent_products = find_recent_product_added('5');
  $recent_sales    = find_recent_sale_added('5');

  // Ambil 5 stok terendah
  $low_stock_query = $db->query("SELECT name, stock FROM products ORDER BY stock ASC LIMIT 5");
  $low_stock_data = [];
  while ($row = $db->fetch_assoc($low_stock_query)) {
    $low_stock_data[] = [
      'label' => $row['name'],
      'y' => (int)$row['stock']
    ];
  }
?>

<?php include_once('layouts/header.php'); ?>

<div class="row">
  <div class="col-md-6">
    <?php echo display_msg($msg); ?>
  </div>
</div>

<div class="row">
  <a href="users.php" style="color:black;">
    <div class="col-md-3">
      <div class="panel panel-box clearfix">
        <div class="panel-icon pull-left bg-secondary1">
          <i class="glyphicon glyphicon-user"></i>
        </div>
        <div class="panel-value pull-right">
          <h2 class="margin-top"><?php echo $c_user['total']; ?></h2>
          <p class="text-muted">Users</p>
        </div>
      </div>
    </div>
  </a>

  <a href="categorie.php" style="color:black;">
    <div class="col-md-3">
      <div class="panel panel-box clearfix">
        <div class="panel-icon pull-left bg-red">
          <i class="glyphicon glyphicon-th-large"></i>
        </div>
        <div class="panel-value pull-right">
          <h2 class="margin-top"><?php echo $c_categorie['total']; ?></h2>
          <p class="text-muted">Categories</p>
        </div>
      </div>
    </div>
  </a>

  <a href="product.php" style="color:black;">
    <div class="col-md-3">
      <div class="panel panel-box clearfix">
        <div class="panel-icon pull-left bg-blue2">
          <i class="glyphicon glyphicon-shopping-cart"></i>
        </div>
        <div class="panel-value pull-right">
          <h2 class="margin-top"><?php echo $c_product['total']; ?></h2>
          <p class="text-muted">Products</p>
        </div>
      </div>
    </div>
  </a>
</div>

<div class="row">
  <!-- Stok Terendah Chart -->
  <div class="col-md-6">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong><span class="glyphicon glyphicon-stats"></span> Stok Terendah (Top 5)</strong>
      </div>
      <div class="panel-body">
        <div id="stockChart" style="height: 300px; width: 100%;"></div>
      </div>
    </div>
  </div>

  <!-- Latest Sales -->
  <div class="col-md-6">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong><span class="glyphicon glyphicon-th"></span> LATEST SALES</strong>
      </div>
      <div class="panel-body">
        <table class="table table-striped table-bordered table-condensed">
          <thead>
            <tr>
              <th class="text-center" style="width: 50px;">#</th>
              <th>Product Name</th>
              <th>Date</th>
              <th>Total Sale</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($recent_sales as $recent_sale): ?>
              <tr>
                <td class="text-center"><?php echo count_id(); ?></td>
                <td><a href="edit_sale.php?id=<?php echo (int)$recent_sale['id']; ?>"><?php echo remove_junk(first_character($recent_sale['name'])); ?></a></td>
                <td><?php echo remove_junk(ucfirst($recent_sale['date'])); ?></td>
                <td>$<?php echo remove_junk(first_character($recent_sale['price'])); ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Recent Products -->
<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong><span class="glyphicon glyphicon-th"></span> Recently Added Products</strong>
      </div>
      <div class="panel-body">
        <div class="list-group">
          <?php foreach ($recent_products as $recent_product): ?>
            <a class="list-group-item clearfix" href="edit_product.php?id=<?php echo (int)$recent_product['id']; ?>">
              <h4 class="list-group-item-heading">
                <?php if ($recent_product['media_id'] === '0'): ?>
                  <img class="img-avatar img-circle" src="uploads/products/no_image.png" alt="">
                <?php else: ?>
                  <img class="img-avatar img-circle" src="uploads/products/<?php echo $recent_product['image']; ?>" alt="">
                <?php endif; ?>
                <?php echo remove_junk(first_character($recent_product['name'])); ?>
                <span class="label label-warning pull-right">$<?php echo (int)$recent_product['selling_price']; ?></span>
              </h4>
              <span class="list-group-item-text pull-right"><?php echo remove_junk(first_character($recent_product['categorie'])); ?></span>
            </a>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Chart Script -->
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<script>
  window.onload = function () {
    var chart = new CanvasJS.Chart("stockChart", {
      animationEnabled: true,
      theme: "light2",
      title: { text: "Top 5 Produk dengan Stok Terendah" },
      axisY: { title: "Jumlah Stok" },
      data: [{
        type: "column",
        dataPoints: <?php echo json_encode($low_stock_data, JSON_NUMERIC_CHECK); ?>
      }]
    });
    chart.render();
  }
</script>

<?php include_once('layouts/footer.php'); ?>
