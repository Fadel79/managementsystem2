<?php
$page_title = 'Edit Stock In';
require_once('includes/load.php');
page_require_level(1);

// Ambil data berdasarkan ID dengan lengkap (termasuk product_id & quantity)
$id = (int)$_GET['id'];
$sql = "SELECT * FROM stock_in WHERE id = {$id} LIMIT 1";
$result = find_by_sql($sql);
$stock_in = !empty($result) ? $result[0] : null;

if (!$stock_in) {
  $session->msg("d", "Missing stock-in ID.");
  redirect('stock_in.php');
}

$products = find_all('products');
$suppliers = find_all('suppliers');

if (isset($_POST['update_stock_in'])) {
  $req_fields = array('product_id', 'supplier_id', 'quantity', 'date_received');
  validate_fields($req_fields);

  if (empty($errors)) {
    $new_product_id   = (int)$db->escape($_POST['product_id']);
    $new_supplier_id  = (int)$db->escape($_POST['supplier_id']);
    $new_quantity     = (int)$db->escape($_POST['quantity']);
    $date_received    = $db->escape($_POST['date_received']);
    $is_quantity_ok   = isset($_POST['is_quantity_ok']) ? 1 : 0;
    $is_quality_ok    = isset($_POST['is_quality_ok']) ? 1 : 0;
    $validation_note  = $db->escape($_POST['validation_note']);

    $old_product_id = (int)$stock_in['product_id'];
    $old_quantity = (int)$stock_in['quantity'];

    // Update record
    $query  = "UPDATE stock_in SET ";
    $query .= "product_id='{$new_product_id}', supplier_id='{$new_supplier_id}', quantity='{$new_quantity}', ";
    $query .= "date_received='{$date_received}', is_quantity_ok='{$is_quantity_ok}', ";
    $query .= "is_quality_ok='{$is_quality_ok}', validation_note='{$validation_note}' ";
    $query .= "WHERE id='{$stock_in['id']}'";

    if ($db->query($query)) {
      // Update stock jika product atau quantity berubah
      if ($new_product_id == $old_product_id) {
        $diff = $new_quantity - $old_quantity;
        $db->query("UPDATE products SET stock = stock + {$diff} WHERE id = {$new_product_id}");
      } else {
        $db->query("UPDATE products SET stock = stock - {$old_quantity} WHERE id = {$old_product_id}");
        $db->query("UPDATE products SET stock = stock + {$new_quantity} WHERE id = {$new_product_id}");
      }
      $session->msg('s', "Stock-in updated dan stok produk disesuaikan.");
      redirect('stock_in.php', false);
    } else {
      $session->msg('d', "Update failed.");
      redirect('edit_stock_in.php?id=' . (int)$stock_in['id'], false);
    }
  } else {
    $session->msg("d", $errors);
    redirect('edit_stock_in.php?id=' . (int)$stock_in['id'], false);
  }
}

include_once('layouts/header.php');
?>
<div class="row">
  <div class="col-md-9">
    <div class="panel panel-default">
      <div class="panel-heading"><strong>Edit Stock In</strong></div>
      <div class="panel-body">
        <form method="post" action="edit_stock_in.php?id=<?php echo (int)$stock_in['id']; ?>">
          <div class="form-group">
            <label for="product_id">Product</label>
            <select name="product_id" class="form-control" required>
              <?php foreach ($products as $product): ?>
                <option value="<?php echo (int)$product['id']; ?>" <?php if ($product['id'] == $stock_in['product_id']) echo 'selected'; ?>>
                  <?php echo remove_junk($product['name']); ?> (Stock: <?php echo (int)$product['stock']; ?>)
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-group">
            <label for="supplier_id">Supplier</label>
            <select name="supplier_id" class="form-control" required>
              <?php foreach ($suppliers as $supplier): ?>
                <option value="<?php echo (int)$supplier['id']; ?>" <?php if ($supplier['id'] == $stock_in['supplier_id']) echo 'selected'; ?>>
                  <?php echo remove_junk($supplier['supplier_name']); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-group">
            <label for="quantity">Quantity</label>
            <input type="number" name="quantity" class="form-control" value="<?php echo (int)$stock_in['quantity']; ?>" required>
          </div>

          <div class="form-group">
            <label for="date_received">Date Received</label>
            <input type="date" name="date_received" class="form-control" value="<?php echo date('Y-m-d', strtotime($stock_in['date_received'])); ?>" required>
          </div>

          <div class="form-group">
            <label><input type="checkbox" name="is_quantity_ok" value="1" <?php if ($stock_in['is_quantity_ok']) echo 'checked'; ?>> Quantity OK</label><br>
            <label><input type="checkbox" name="is_quality_ok" value="1" <?php if ($stock_in['is_quality_ok']) echo 'checked'; ?>> Quality OK</label>
          </div>

          <div class="form-group">
            <label for="validation_note">Validation Note</label>
            <textarea name="validation_note" class="form-control" rows="3"><?php echo remove_junk($stock_in['validation_note']); ?></textarea>
          </div>

          <button type="submit" name="update_stock_in" class="btn btn-primary">Update</button>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include_once('layouts/footer.php'); ?>
