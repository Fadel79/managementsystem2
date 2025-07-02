<?php
  $page_title = 'Edit Customer';
  require_once('includes/load.php');
  page_require_level(1);

  $customer = find_by_id('customers', (int)$_GET['id']);
  if (!$customer) {
    $session->msg("d", "Missing customer id.");
    redirect('customer.php');
  }

  if (isset($_POST['update_customer'])) {
    $req_fields = array('customer_name', 'mobile_phone', 'email', 'address');
    validate_fields($req_fields);

    if (empty($errors)) {
      $name    = remove_junk($db->escape($_POST['customer_name']));
      $phone   = remove_junk($db->escape($_POST['mobile_phone']));
      $email   = remove_junk($db->escape($_POST['email']));
      $address = remove_junk($db->escape($_POST['address']));

      $query  = "UPDATE customers SET ";
      $query .= "customer_name='{$name}', mobile_phone='{$phone}', email='{$email}', address='{$address}' ";
      $query .= "WHERE id='{$customer['id']}'";
      if ($db->query($query)) {
        $session->msg('s', "Customer updated.");
        redirect('customer.php', false);
      } else {
        $session->msg('d', 'Failed to update.');
        redirect('edit_customer.php?id=' . (int)$customer['id'], false);
      }
    } else {
      $session->msg("d", $errors);
      redirect('edit_customer.php?id=' . (int)$customer['id'], false);
    }
  }
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-9">
    <div class="panel panel-default">
      <div class="panel-heading"><strong>Edit Customer</strong></div>
      <div class="panel-body">
        <form method="post" action="edit_customer.php?id=<?php echo (int)$customer['id']; ?>">
          <div class="form-group">
            <label for="customer_name">Customer Name</label>
            <input type="text" class="form-control" name="customer_name" value="<?php echo remove_junk($customer['customer_name']); ?>" required>
          </div>
          <div class="form-group">
            <label for="mobile_phone">Mobile Phone</label>
            <input type="text" class="form-control" name="mobile_phone" value="<?php echo remove_junk($customer['mobile_phone']); ?>">
          </div>
          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" name="email" value="<?php echo remove_junk($customer['email']); ?>">
          </div>
          <div class="form-group">
            <label for="address">Address</label>
            <textarea name="address" class="form-control"><?php echo remove_junk($customer['address']); ?></textarea>
          </div>
          <button type="submit" name="update_customer" class="btn btn-primary">Update Customer</button>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include_once('layouts/footer.php'); ?>
