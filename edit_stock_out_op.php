<?php
$page_title = 'Edit Stock Out';
require_once('includes/load.php');
page_require_level(2);

// Ambil data berdasarkan ID
$id = (int)$_GET['id'];
$stock_out = find_by_id('stock_out', $id);
if (!$stock_out) {
  $session->msg("d", "Missing stock-out ID.");
  redirect('stock_out_op.php');
}

// Hanya update 3 field ini
if (isset($_POST['update_stock_out'])) {
  $is_quantity_ok   = isset($_POST['is_quantity_ok']) ? 1 : 0;
  $is_quality_ok    = isset($_POST['is_quality_ok']) ? 1 : 0;
  $validation_note  = $db->escape($_POST['validation_note']);

  $query  = "UPDATE stock_out SET ";
  $query .= "is_quantity_ok='{$is_quantity_ok}', ";
  $query .= "is_quality_ok='{$is_quality_ok}', ";
  $query .= "validation_note='{$validation_note}' ";
  $query .= "WHERE id='{$id}'";

  if ($db->query($query)) {
    $session->msg('s', "Stock-out berhasil diperbarui.");
    redirect('stock_out_op.php');
  } else {
    $session->msg('d', "Gagal memperbarui data.");
    redirect("edit_stock_out_op.php?id={$id}");
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
        <form method="post" action="edit_stock_out_op.php?id=<?php echo (int)$stock_out['id']; ?>">

          <!-- Product (Read-only) -->
          <div class="form-group">
            <label for="product_id">Product</label>
            <input type="text" class="form-control" value="<?php 
              $product = find_by_id('products', $stock_out['product_id']);
              echo remove_junk($product['name']);
            ?>" readonly>
          </div>

          <!-- Customer (Read-only) -->
          <div class="form-group">
            <label for="customer_id">Customer</label>
            <input type="text" class="form-control" value="<?php 
              $customer = find_by_id('customers', $stock_out['customer_id']);
              echo remove_junk($customer['customer_name']);
            ?>" readonly>
          </div>

          <!-- Quantity (Read-only) -->
          <div class="form-group">
            <label for="quantity">Quantity</label>
            <input type="number" class="form-control" value="<?php echo (int)$stock_out['quantity']; ?>" readonly>
          </div>

          <!-- Date Received (Read-only) -->
          <div class="form-group">
            <label for="date_received">Date</label>
            <input type="date" class="form-control" value="<?php echo date('Y-m-d', strtotime($stock_out['date_received'])); ?>" readonly>
          </div>

          <!-- Quantity OK -->
          <div class="form-group">
            <label><input type="checkbox" name="is_quantity_ok" value="1" <?php if ($stock_out['is_quantity_ok']) echo 'checked'; ?>> Quantity OK</label>
          </div>

          <!-- Quality OK -->
          <div class="form-group">
            <label><input type="checkbox" name="is_quality_ok" value="1" <?php if ($stock_out['is_quality_ok']) echo 'checked'; ?>> Quality OK</label>
          </div>

          <!-- Validation Note -->
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
