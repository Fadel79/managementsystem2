<?php
$page_title = 'Edit Stock Out';
require_once('includes/load.php');
page_require_level(1);

// Ambil data berdasarkan ID
$id = (int)$_GET['id'];
$stock_out = find_by_id('stock_out', $id);
if (!$stock_out) {
  $session->msg("d", "Missing stock-out ID.");
  redirect('stock_out.php');
}

$products = find_all('products');
$customers = find_all('customers');

if (isset($_POST['update_stock_out'])) {
  $req_fields = array('product_id', 'customer_id', 'quantity', 'date_received');
  validate_fields($req_fields);

  if (empty($errors)) {
    $new_product_id   = (int)$db->escape($_POST['product_id']);
    $new_customer_id  = (int)$db->escape($_POST['customer_id']);
    $new_quantity     = (int)$db->escape($_POST['quantity']);
    $date_received    = $db->escape($_POST['date_received']);
    $is_quantity_ok   = isset($_POST['is_quantity_ok']) ? 1 : 0;
    $is_quality_ok    = isset($_POST['is_quality_ok']) ? 1 : 0;
    $validation_note  = $db->escape($_POST['validation_note']);

    $old_product_id = (int)$stock_out['product_id'];
    $old_quantity   = (int)$stock_out['quantity'];

    // Kembalikan stok lama ke produk lama
    $db->query("UPDATE products SET stock = stock + {$old_quantity} WHERE id = {$old_product_id}");

    // Ambil stok produk yang dipilih sekarang
    $new_product = find_by_id('products', $new_product_id);
    if (!$new_product) {
      $session->msg("d", "Produk tidak ditemukan.");
      redirect("edit_stock_out.php?id={$id}");
      exit;
    }

    // Cek apakah stok cukup untuk quantity baru
    if ((int)$new_product['stock'] < $new_quantity) {
      // Batalkan, kembalikan stok ke kondisi sebelumnya
      $db->query("UPDATE products SET stock = stock - {$old_quantity} WHERE id = {$old_product_id}");
      $session->msg("d", "Stok tidak mencukupi untuk produk yang dipilih.");
      redirect("edit_stock_out.php?id={$id}");
      exit;
    }

    // Update record stock_out
    $query  = "UPDATE stock_out SET ";
    $query .= "product_id='{$new_product_id}', customer_id='{$new_customer_id}', quantity='{$new_quantity}', ";
    $query .= "date_received='{$date_received}', is_quantity_ok='{$is_quantity_ok}', ";
    $query .= "is_quality_ok='{$is_quality_ok}', validation_note='{$validation_note}' ";
    $query .= "WHERE id='{$id}'";

    if ($db->query($query)) {
      // Kurangi stok dari produk yang dipilih
      $db->query("UPDATE products SET stock = stock - {$new_quantity} WHERE id = {$new_product_id}");

      $session->msg('s', "Stock-out berhasil diperbarui dan stok disesuaikan.");
      redirect('stock_out.php');
      exit;
    } else {
      $session->msg('d', "Gagal memperbarui data.");
      redirect("edit_stock_out.php?id={$id}");
      exit;
    }
  } else {
    $session->msg("d", $errors);
    redirect("edit_stock_out.php?id={$id}");
    exit;
  }
}
?>

<?php include_once('layouts/header.php'); ?>

<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
</div>

<div class="row">
  <div class="col-md-9">
    <div class="panel panel-default">
      <div class="panel-heading"><strong>Edit Stock Out</strong></div>
      <div class="panel-body">
        <form method="post" action="edit_stock_out.php?id=<?php echo (int)$stock_out['id']; ?>">
          <div class="form-group">
            <label for="product_id">Product</label>
            <select name="product_id" class="form-control" required>
              <?php foreach ($products as $product): ?>
                <option value="<?php echo (int)$product['id']; ?>" <?php if ($product['id'] == $stock_out['product_id']) echo 'selected'; ?>>
                  <?php echo remove_junk($product['name']); ?> (Stock: <?php echo $product['stock']; ?>)
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-group">
            <label for="customer_id">Customer</label>
            <select name="customer_id" class="form-control" required>
              <?php foreach ($customers as $customer): ?>
                <option value="<?php echo (int)$customer['id']; ?>" <?php if ($customer['id'] == $stock_out['customer_id']) echo 'selected'; ?>>
                  <?php echo remove_junk($customer['customer_name']); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-group">
            <label for="quantity">Quantity</label>
            <input type="number" name="quantity" class="form-control" value="<?php echo (int)$stock_out['quantity']; ?>" required>
          </div>

          <div class="form-group">
            <label for="date_received">Date</label>
            <input type="date" name="date_received" class="form-control" value="<?php echo date('Y-m-d', strtotime($stock_out['date_received'])); ?>" required>
          </div>

          <div class="form-group">
            <label><input type="checkbox" name="is_quantity_ok" value="1" <?php if ($stock_out['is_quantity_ok']) echo 'checked'; ?>> Quantity OK</label><br>
            <label><input type="checkbox" name="is_quality_ok" value="1" <?php if ($stock_out['is_quality_ok']) echo 'checked'; ?>> Quality OK</label>
          </div>

          <div class="form-group">
            <label for="validation_note">Validation Note</label>
            <textarea name="validation_note" class="form-control" rows="3"><?php echo remove_junk($stock_out['validation_note']); ?></textarea>
          </div>

          <button type="submit" name="update_stock_out" class="btn btn-primary">Update</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>
