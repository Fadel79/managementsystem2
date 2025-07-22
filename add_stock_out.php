<?php
$page_title = 'Add Stock Out';
require_once('includes/load.php');
page_require_level(1);

$all_products = find_all('products');
$all_customers = find_all('customers');

if (isset($_POST['add_stock_out'])) {
  $req_fields = array('product_id', 'customer_id', 'quantity', 'date_received');
  validate_fields($req_fields);

  if (empty($errors)) {
    $product_id    = (int)$_POST['product_id'];
    $customer_id   = (int)$_POST['customer_id'];
    $quantity      = (int)$_POST['quantity'];
    $date_input = $_POST['date_received'];
    $formatted_date = date('Y-m-d H:i:s', strtotime($date_input));
    $date_received = $db->escape($formatted_date);
    $is_quantity_ok = isset($_POST['is_quantity_ok']) ? 1 : 0;
    $is_quality_ok  = isset($_POST['is_quality_ok']) ? 1 : 0;
    $validation_note = remove_junk($db->escape($_POST['validation_note']));

    // Validasi quantity positif
    if ($quantity <= 0) {
      $session->msg('d', 'Quantity harus lebih dari 0.');
      redirect('add_stock_out.php', false);
      exit;
    }

    // Ambil stok produk saat ini
    $product = find_by_id('products', $product_id);
    if (!$product) {
      $session->msg('d', 'Produk tidak ditemukan.');
      redirect('add_stock_out.php', false);
      exit;
    }

    if ((int)$product['stock'] < $quantity) {
      $session->msg('d', 'Stok tidak mencukupi untuk jumlah yang diminta.');
      redirect('add_stock_out.php', false);
      exit;
    }

    // Simpan ke tabel stock_out
    $query  = "INSERT INTO stock_out (product_id, customer_id, quantity, date_received, is_quantity_ok, is_quality_ok, validation_note) ";
    $query .= "VALUES ({$product_id}, {$customer_id}, {$quantity}, '{$date_received}', {$is_quantity_ok}, {$is_quality_ok}, '{$validation_note}')";

    if ($db->query($query)) {
      // Kurangi stok dari tabel products
      $update_stock = "UPDATE products SET stock = stock - {$quantity} WHERE id = {$product_id}";
      $db->query($update_stock);

      $session->msg('s', "Stock Out berhasil ditambahkan dan stok diperbarui.");
      redirect('stock_out.php', false);
      exit;
    } else {
      $session->msg('d', 'Gagal menambahkan data.');
      redirect('add_stock_out.php', false);
      exit;
    }
  } else {
    $session->msg("d", $errors);
    redirect('add_stock_out.php', false);
    exit;
  }
}
?>

<?php include_once('layouts/header.php'); ?>

<!-- ✅ MENAMPILKAN PESAN -->
<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
</div>

<div class="row">
  <div class="col-md-9">
    <div class="panel panel-default">
      <div class="panel-heading">Add Stock Out</div>
      <div class="panel-body">
        <form method="post" action="add_stock_out.php">
          <div class="form-group">
            <label for="product_id">Product</label>
            <select name="product_id" class="form-control" required>
              <option value="">-- Select Product --</option>
              <?php foreach ($all_products as $product): ?>
                <option value="<?php echo $product['id']; ?>">
                  <?php echo remove_junk($product['name']); ?> (Stock: <?php echo $product['stock']; ?>)
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-group">
            <label for="customer_id">Customer</label>
            <select name="customer_id" class="form-control" required>
              <option value="">-- Select Customer --</option>
              <?php foreach ($all_customers as $customer): ?>
                <option value="<?php echo $customer['id']; ?>">
                  <?php echo remove_junk($customer['customer_name']); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-group">
            <label for="quantity">Quantity</label>
            <input type="number" name="quantity" class="form-control" required min="1">
          </div>

          <div class="form-group">
            <label for="date_received">Date</label>
            <input type="datetime-local" name="date_received" class="form-control" required>
          </div>

          <button type="submit" name="add_stock_out" class="btn btn-primary">Add</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>
