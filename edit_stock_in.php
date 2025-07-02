<?php
// ================================
// File: edit_stock_in.php
// ================================
require_once('includes/load.php');
page_require_level(1);

$stock_in = find_by_id('stock_in', (int)$_GET['id']);
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
    $product_id    = (int)$db->escape($_POST['product_id']);
    $supplier_id   = (int)$db->escape($_POST['supplier_id']);
    $quantity      = (int)$db->escape($_POST['quantity']);
    $date_received = $db->escape($_POST['date_received']);

    $query  = "UPDATE stock_in SET ";
    $query .= "product_id='{$product_id}', supplier_id='{$supplier_id}', quantity='{$quantity}', date_received='{$date_received}' ";
    $query .= "WHERE id='{$stock_in['id']}'";

    if ($db->query($query)) {
      $session->msg('s', "Stock-in updated.");
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
                <option value="<?php echo (int)$product['id']; ?>" <?php if($product['id'] == $stock_in['product_id']) echo 'selected'; ?>>
                  <?php echo remove_junk($product['name']); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-group">
            <label for="supplier_id">Supplier</label>
            <select name="supplier_id" class="form-control" required>
              <?php foreach ($suppliers as $supplier): ?>
                <option value="<?php echo (int)$supplier['id']; ?>" <?php if($supplier['id'] == $stock_in['supplier_id']) echo 'selected'; ?>>
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

          <button type="submit" name="update_stock_in" class="btn btn-primary">Update</button>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include_once('layouts/footer.php'); ?>