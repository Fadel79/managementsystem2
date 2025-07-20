<?php
$page_title = 'Add Stock In';
require_once('includes/load.php');
page_require_level(1);

$all_products = find_all('products');
$all_suppliers = find_all('suppliers');

if (isset($_POST['add_stock_in'])) {
  $req_fields = array('product_id', 'supplier_id', 'quantity', 'date_received');
  validate_fields($req_fields);

  if (empty($errors)) {
    $product_id      = (int)$_POST['product_id'];
    $supplier_id     = (int)$_POST['supplier_id'];
    $quantity        = (int)$_POST['quantity'];
    $date_received   = $db->escape($_POST['date_received']);
    $is_quantity_ok  = isset($_POST['is_quantity_ok']) ? 1 : 0;
    $is_quality_ok   = isset($_POST['is_quality_ok']) ? 1 : 0;
    $validation_note = $db->escape($_POST['validation_note']);

    $query  = "INSERT INTO stock_in (product_id, supplier_id, quantity, date_received, is_quantity_ok, is_quality_ok, validation_note) ";
    $query .= "VALUES ({$product_id}, {$supplier_id}, {$quantity}, '{$date_received}', {$is_quantity_ok}, {$is_quality_ok}, '{$validation_note}')";

    if ($db->query($query)) {
      // Tambahkan stok ke produk
      $update_stock = "UPDATE products SET stock = stock + {$quantity} WHERE id = {$product_id}";
      $db->query($update_stock);

      $session->msg('s', "Stock In added dan stok produk diperbarui.");
      redirect('stock_in.php', false);
    } else {
      $session->msg('d', 'Gagal menambahkan Stock In.');
      redirect('add_stock_in.php', false);
    }
  } else {
    $session->msg("d", $errors);
    redirect('add_stock_in.php', false);
  }
}
?>

<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-9">
    <div class="panel panel-default">
      <div class="panel-heading">Add Stock In</div>
      <div class="panel-body">
        <form method="post" action="add_stock_in.php">
          <div class="form-group">
            <label for="product_id">Product</label>
            <select name="product_id" class="form-control" required>
              <option value="">Select a product</option>
              <?php foreach ($all_products as $product): ?>
                <option value="<?php echo $product['id']; ?>">
                  <?php echo remove_junk($product['name']); ?> (Stock: <?php echo (int)$product['stock']; ?>)
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-group">
            <label for="supplier_id">Supplier</label>
            <select name="supplier_id" class="form-control" required>
              <option value="">Select a supplier</option>
              <?php foreach ($all_suppliers as $supplier): ?>
                <option value="<?php echo $supplier['id']; ?>">
                  <?php echo remove_junk($supplier['supplier_name']); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-group">
            <label for="quantity">Quantity</label>
            <input type="number" name="quantity" class="form-control" required>
          </div>

          <div class="form-group">
            <label for="date_received">Date Received</label>
            <input type="date" name="date_received" class="form-control" required>
          </div>

          <button type="submit" name="add_stock_in" class="btn btn-primary">Add</button>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include_once('layouts/footer.php'); ?>
