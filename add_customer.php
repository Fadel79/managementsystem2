<?php
  $page_title = 'Add Customer';
  require_once('includes/load.php');
  page_require_level(1);

  if (isset($_POST['add_customer'])) {
    $req_fields = array('customer_name', 'mobile_phone', 'email', 'address');
    validate_fields($req_fields);

    if (empty($errors)) {
      $name    = remove_junk($db->escape($_POST['customer_name']));
      $phone   = remove_junk($db->escape($_POST['mobile_phone']));
      $email   = remove_junk($db->escape($_POST['email']));
      $address = remove_junk($db->escape($_POST['address']));

      $query  = "INSERT INTO customers (customer_name, mobile_phone, email, address)";
      $query .= " VALUES ('{$name}', '{$phone}', '{$email}', '{$address}')";
      if ($db->query($query)) {
        $session->msg('s', "Customer added successfully.");
        redirect('customer.php', false);
      } else {
        $session->msg('d', 'Failed to add customer.');
        redirect('add_customer.php', false);
      }
    } else {
      $session->msg("d", $errors);
      redirect('add_customer.php', false);
    }
  }
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-12"><?php echo display_msg($msg); ?></div>
</div>
<div class="row">
  <div class="col-md-9">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong><span class="glyphicon glyphicon-plus"></span> Add New Customer</strong>
      </div>
      <div class="panel-body">
        <form method="post" action="add_customer.php">
          <div class="form-group">
            <label for="customer_name">Customer Name</label>
            <input type="text" class="form-control" name="customer_name" required>
          </div>
          <div class="form-group">
            <label for="mobile_phone">Mobile Phone</label>
            <input type="text" class="form-control" name="mobile_phone">
          </div>
          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" name="email">
          </div>
          <div class="form-group">
            <label for="address">Address</label>
            <textarea name="address" class="form-control"></textarea>
          </div>
          <button type="submit" name="add_customer" class="btn btn-primary">Add Customer</button>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include_once('layouts/footer.php'); ?>
