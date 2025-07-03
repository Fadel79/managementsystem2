<?php
// ================================
// File: edit_stock_out.php
// ================================
require_once('includes/load.php');
page_require_level(1);

// Ambil data stock_out berdasarkan ID
$stock_out = find_by_id('stock_out', (int)$_GET['id']);
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
    $product_id    = (int)$db->escape($_POST['product_id']);
    $customer_id   = (int)$db->escape($_POST['customer_id']);
    $quantity      = (int)$db->escape($_POST['quantity']);
    $date_received = $db->escape($_POST['date_received']);

    $query  = "UPDATE stock_out SET ";
    $query .= "product_id='{$product_id}', customer_id='{$customer_id}', quantity='{$quantity}', date_received='{$date_received}' ";
    $query .= "WHERE id='{$stock_out['id']}'";

    if ($db->query($query)) {
      $session->msg('s', "Stock-out updated.");
      redirect('stock_out.php', false);
    } else {
      $session->msg('d', "Update failed.");
      redirect('edit_stock_out.php?id=' . (int)$stock_out['id'], false);
    }
  } else {
    $session->msg("d", $errors);
    redirect('edit_stock_out.php?id=' . (int)$stock_out['id'], false);
  }
}

include_once('layouts/header.php');
?>
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
                  <?php echo remove_junk($product['name']); ?>
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
            <label for="date_received">Date Issued</label>
            <input type="date" name="date_received" class="form-control" value="<?php echo date('Y-m-d', strtotime($stock_out['date_received'])); ?>" required>
          </div>

          <button type="submit" name="update_stock_out" class="btn btn-primary">Update</button>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include_once('layouts/footer.php'); ?>
