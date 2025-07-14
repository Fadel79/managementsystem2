<?php
  $page_title = 'Edit Supplier';
  require_once('includes/load.php');
  page_require_level(1);

  $supplier = find_by_id('suppliers', (int)$_GET['id']);
  if (!$supplier) {
    $session->msg("d", "Missing supplier id.");
    redirect('supplier.php');
  }

  if (isset($_POST['update_supplier'])) {
    $errors = array();
    $req_fields = array('supplier_name', 'mobile_phone', 'email', 'address');
    validate_fields($req_fields);

    $name    = remove_junk($db->escape($_POST['supplier_name']));
    $phone   = remove_junk($db->escape($_POST['mobile_phone']));
    $email   = remove_junk($db->escape($_POST['email']));
    $address = remove_junk($db->escape($_POST['address']));

    // Validasi format nomor telepon
    if (!preg_match('/^\+?[0-9]{10,15}$/', $phone)) {
      $errors[] = "Nomor telepon tidak valid! Gunakan angka (maks. 15 digit). Contoh: +628123456789";
    }

    // Validasi email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $errors[] = "Format email tidak valid!";
    }

    if (empty($errors)) {
      $query  = "UPDATE suppliers SET ";
      $query .= "supplier_name='{$name}', mobile_phone='{$phone}', email='{$email}', address='{$address}' ";
      $query .= "WHERE id='{$supplier['id']}'";

      if ($db->query($query)) {
        $session->msg('s', "Supplier updated.");
        redirect('supplier.php', false);
      } else {
        $session->msg('d', 'Failed to update supplier.');
        redirect('edit_supplier.php?id=' . (int)$supplier['id'], false);
      }
    } else {
      $session->msg("d", implode("<br>", $errors));
      redirect('edit_supplier.php?id=' . (int)$supplier['id'], false);
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
      <div class="panel-heading"><strong>Edit Supplier</strong></div>
      <div class="panel-body">
        <form method="post" action="edit_supplier.php?id=<?php echo (int)$supplier['id']; ?>">
          <div class="form-group">
            <label for="supplier_name">Supplier Name</label>
            <input type="text" class="form-control" name="supplier_name" value="<?php echo remove_junk($supplier['supplier_name']); ?>" required>
          </div>
          <div class="form-group">
            <label for="mobile_phone">Mobile Phone</label>
            <input type="text" class="form-control" name="mobile_phone"
              value="<?php echo remove_junk($supplier['mobile_phone']); ?>"
              pattern="^\+?[0-9]{10,15}$"
              title="Nomor telepon hanya angka dan maksimal 15 digit. Contoh: +628123456789"
              maxlength="15"
              required>
          </div>
          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" name="email" value="<?php echo remove_junk($supplier['email']); ?>" required>
          </div>
          <div class="form-group">
            <label for="address">Address</label>
            <textarea name="address" class="form-control" required><?php echo remove_junk($supplier['address']); ?></textarea>
          </div>
          <button type="submit" name="update_supplier" class="btn btn-primary">Update Supplier</button>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include_once('layouts/footer.php'); ?>
