<?php
  $page_title = 'Admin Home Page';
  require_once('includes/load.php');
  page_require_level(2);

  $c_categorie     = count_by_id('categories');
  $c_product       = count_by_id('products');
  $c_user          = count_by_id('users');
  $c_supplier      = count_by_id('suppliers');
  $c_customer      = count_by_id('customers');
  $c_stock_in      = count_by_id('stock_in');
  $c_stock_out     = count_by_id('stock_out');
  $recent_products = find_recent_product_added('5');

  // Ambil 5 stok terendah
  $low_stock_query = $db->query("SELECT name, stock FROM products ORDER BY stock ASC LIMIT 5");
  $low_stock_data = [];
  while ($row = $db->fetch_assoc($low_stock_query)) {
    $low_stock_data[] = [
      'label' => $row['name'],
      'y' => (int)$row['stock']
    ];
  }

  // Ambil 5 data stock_in terbaru
  $recent_stock_in = $db->query("SELECT stock_in.*, products.name AS product_name, stock_in.date_received FROM stock_in JOIN products ON stock_in.product_id = products.id ORDER BY stock_in.date_received DESC LIMIT 5");
  $recent_stock_ins = [];
  while ($row = $db->fetch_assoc($recent_stock_in)) {
    $recent_stock_ins[] = $row;
  }

  // Ambil 5 data stock_out terbaru
  $recent_stock_out = $db->query("SELECT stock_out.*, products.name AS product_name, stock_out.date_received FROM stock_out JOIN products ON stock_out.product_id = products.id ORDER BY stock_out.date_received DESC LIMIT 5");
  $recent_stock_outs = [];
  while ($row = $db->fetch_assoc($recent_stock_out)) {
    $recent_stock_outs[] = $row;
  }
?>

<?php include_once('layouts/header.php'); ?>

<div class="row">
  <div class="col-md-6">
    <?php echo display_msg($msg); ?>
  </div>
</div>

<div class="row">
  <?php
    $panels = [
      ['href' => 'users.php', 'icon' => 'glyphicon-user', 'count' => $c_user['total'], 'label' => 'Users', 'bg' => 'bg-secondary1'],
      ['href' => 'categorie.php', 'icon' => 'glyphicon-th-large', 'count' => $c_categorie['total'], 'label' => 'Categories', 'bg' => 'bg-red'],
      ['href' => 'product.php', 'icon' => 'glyphicon-shopping-cart', 'count' => $c_product['total'], 'label' => 'Products', 'bg' => 'bg-blue2'],
      ['href' => 'supplier.php', 'icon' => 'glyphicon-send', 'count' => $c_supplier['total'], 'label' => 'Suppliers', 'bg' => 'bg-green'],
      ['href' => 'customer.php', 'icon' => 'glyphicon-user', 'count' => $c_customer['total'], 'label' => 'Customers', 'bg' => 'bg-yellow'],
      ['href' => 'stock_in.php', 'icon' => 'glyphicon-log-in', 'count' => $c_stock_in['total'], 'label' => 'Stock In', 'bg' => 'bg-secondary1'],
      ['href' => 'stock_out.php', 'icon' => 'glyphicon-log-out', 'count' => $c_stock_out['total'], 'label' => 'Stock Out', 'bg' => 'bg-blue2'],
    ];
  ?>
  <?php foreach ($panels as $panel): ?>
    <a href="<?php echo $panel['href']; ?>" style="color:black;">
      <div class="col-md-3">
        <div class="panel panel-box clearfix">
          <div class="panel-icon pull-left <?php echo $panel['bg']; ?>">
            <i class="glyphicon <?php echo $panel['icon']; ?>"></i>
          </div>
          <div class="panel-value pull-right">
            <h2 class="margin-top"><?php echo $panel['count']; ?></h2>
            <p class="text-muted"><?php echo $panel['label']; ?></p>
          </div>
        </div>
      </div>
    </a>
  <?php endforeach; ?>
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

<!-- Recently Added Products -->
<div class="col-md-6">
  <div class="panel panel-default">
    <div class="panel-heading">
      <strong><span class="glyphicon glyphicon-th"></span> Recently Added Products</strong>
    </div>
    <div class="panel-body">
      <div class="list-group" style="max-height: 350px; overflow-y: auto;">
        <?php foreach ($recent_products as $recent_product): ?>
          <a class="list-group-item clearfix" href="edit_product.php?id=<?php echo (int)$recent_product['id']; ?>">
            <h4 class="list-group-item-heading">
              <?php if (empty($recent_product['image']) || !file_exists("uploads/products/" . $recent_product['image'])): ?>
              <img class="img-avatar img-circle" src="uploads/products/no_image.png" alt="No image">
              <?php else: ?>
              <img class="img-avatar img-circle" src="uploads/products/<?php echo remove_junk($recent_product['image']); ?>" alt="">
              <?php endif; ?>
              <?php echo remove_junk(first_character($recent_product['name'])); ?>
              <span class="label label-warning pull-right">Rp<?php echo number_format((float)$recent_product['selling_price'], 0, ',', '.'); ?></span>
            </h4>
            <span class="list-group-item-text pull-right"><?php echo remove_junk(first_character($recent_product['categorie'])); ?></span>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <!-- Latest Stock-In -->
  <div class="col-md-6">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong><span class="glyphicon glyphicon-log-in"></span> Latest Stock-In</strong>
      </div>
      <div class="panel-body">
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>Product</th>
              <th>Qty</th>
              <th>Date</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($recent_stock_ins as $stock): ?>
              <tr>
                <td><?php echo remove_junk($stock['product_name']); ?></td>
                <td><?php echo (int)$stock['quantity']; ?></td>
                <td><?php echo date("d-m-Y", strtotime($stock['date_received'])); ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Latest Stock-Out -->
  <div class="col-md-6">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong><span class="glyphicon glyphicon-log-out"></span> Latest Stock-Out</strong>
      </div>
      <div class="panel-body">
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>Product</th>
              <th>Qty</th>
              <th>Date</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($recent_stock_outs as $stock): ?>
              <tr>
                <td><?php echo remove_junk($stock['product_name']); ?></td>
                <td><?php echo (int)$stock['quantity']; ?></td>
                <td><?php echo date("d-m-Y", strtotime($stock['date_received'])); ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
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
