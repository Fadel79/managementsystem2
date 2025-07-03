<?php
// add_stock_out.php
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
    $date_received = $db->escape($_POST['date_received']); // format: YYYY-MM-DD

    $query  = "INSERT INTO stock_out (product_id, customer_id, quantity, date_received) ";
    $query .= "VALUES ({$product_id}, {$customer_id}, {$quantity}, '{$date_received}')";

    if ($db->query($query)) {
      $session->msg('s', "Stock Out added.");
      redirect('stock_out.php', false);
    } else {
      $session->msg('d', 'Failed to add.');
      redirect('add_stock_out.php', false);
    }
  } else {
    $session->msg("d", $errors);
    redirect('add_stock_out.php', false);
  }
}
?>

<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-9">
    <div class="panel panel-default">
      <div class="panel-heading">Add Stock Out</div>
      <div class="panel-body">
        <form method="post" action="add_stock_out.php">
          <div class="form-group">
            <label for="product_id">Product</label>
            <select name="product_id" class="form-control" required>
              <?php foreach ($all_products as $product): ?>
                <option value="<?php echo $product['id']; ?>">
                  <?php echo remove_junk($product['name']); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-group">
            <label for="customer_id">Customer</label>
            <select name="customer_id" class="form-control" required>
              <?php foreach ($all_customers as $customer): ?>
                <option value="<?php echo $customer['id']; ?>">
                  <?php echo remove_junk($customer['customer_name']); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-group">
            <label for="quantity">Quantity</label>
            <input type="number" name="quantity" class="form-control" required>
          </div>

          <div class="form-group">
            <label for="date_received">Date</label>
            <input type="date" name="date_received" class="form-control" required>
          </div>

          <button type="submit" name="add_stock_out" class="btn btn-primary">Add</button>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include_once('layouts/footer.php'); ?>
