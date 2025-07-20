<?php
$page_title = 'Edit Stock In';
require_once('includes/load.php');
page_require_level(2);

$id = (int)$_GET['id'];
$sql = "SELECT stock_in.*, products.name AS product_name, suppliers.supplier_name 
        FROM stock_in 
        JOIN products ON stock_in.product_id = products.id 
        JOIN suppliers ON stock_in.supplier_id = suppliers.id 
        WHERE stock_in.id = {$id} LIMIT 1";
$result = find_by_sql($sql);
$stock_in = !empty($result) ? $result[0] : null;

if (!$stock_in) {
  $session->msg("d", "Missing stock-in ID.");
  redirect('stock_in_op.php');
}

if (isset($_POST['update_stock_in'])) {
  $is_quantity_ok  = isset($_POST['is_quantity_ok']) ? 1 : 0;
  $is_quality_ok   = isset($_POST['is_quality_ok']) ? 1 : 0;
  $validation_note = $db->escape($_POST['validation_note']);

  $query  = "UPDATE stock_in SET ";
  $query .= "is_quantity_ok = {$is_quantity_ok}, ";
  $query .= "is_quality_ok = {$is_quality_ok}, ";
  $query .= "validation_note = '{$validation_note}' ";
  $query .= "WHERE id = {$stock_in['id']}";

  if ($db->query($query)) {
    $session->msg('s', "Stock-in validation updated successfully.");
    redirect('stock_in_op.php', false);
  } else {
    $session->msg('d', "Update failed.");
    redirect('edit_stock_in_op.php?id=' . (int)$stock_in['id'], false);
  }
}

include_once('layouts/header.php');
?>

<div class="row">
  <div class="col-md-9">
    <div class="panel panel-default">
      <div class="panel-heading"><strong>Edit Stock In - Validation Only</strong></div>
      <div class="panel-body">
        <form method="post" action="edit_stock_in_op.php?id=<?php echo (int)$stock_in['id']; ?>">
          <div class="form-group">
            <label>Product</label>
            <input type="text" class="form-control" value="<?php echo remove_junk($stock_in['product_name']); ?>" readonly>
          </div>

          <div class="form-group">
            <label>Supplier</label>
            <input type="text" class="form-control" value="<?php echo remove_junk($stock_in['supplier_name']); ?>" readonly>
          </div>

          <div class="form-group">
            <label>Quantity</label>
            <input type="text" class="form-control" value="<?php echo (int)$stock_in['quantity']; ?>" readonly>
          </div>

          <div class="form-group">
            <label>Date Received</label>
            <input type="text" class="form-control" value="<?php echo date('Y-m-d', strtotime($stock_in['date_received'])); ?>" readonly>
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
