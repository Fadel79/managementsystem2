<?php
  $page_title = 'Add Supplier';
  require_once('includes/load.php');
  page_require_level(1);

  if (isset($_POST['add_supplier'])) {
    $req_fields = array('supplier_name', 'mobile_phone', 'email', 'address');
    validate_fields($req_fields);

    $errors = array(); // reset errors

    $name    = remove_junk($db->escape($_POST['supplier_name']));
    $phone   = remove_junk($db->escape($_POST['mobile_phone']));
    $email   = remove_junk($db->escape($_POST['email']));
    $address = remove_junk($db->escape($_POST['address']));

    // ✅ Validasi nomor telepon: hanya angka dan maksimal 15 digit
    if (!preg_match('/^\+?[0-9]{10,15}$/', $phone)) {
      $errors[] = "Nomor telepon tidak valid! Gunakan angka (maks. 15 digit). Contoh: +628123456789";
    }

    // ✅ Validasi email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $errors[] = "Format email tidak valid!";
    }

    if (empty($errors)) {
      $query  = "INSERT INTO suppliers (supplier_name, mobile_phone, email, address)";
      $query .= " VALUES ('{$name}', '{$phone}', '{$email}', '{$address}')";
      if ($db->query($query)) {
        $session->msg('s', "Supplier added successfully.");
        redirect('supplier.php', false);
      } else {
        $session->msg('d', 'Failed to add supplier.');
        redirect('add_supplier.php', false);
      }
    } else {
      $session->msg("d", implode("<br>", $errors)); // tampilkan semua error
      redirect('add_supplier.php', false);
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
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-plus"></span>
          <span>Add New Supplier</span>
        </strong>
      </div>
      <div class="panel-body">
        <form method="post" action="add_supplier.php">
          <div class="form-group">
            <label for="supplier_name">Supplier Name</label>
            <input type="text" class="form-control" name="supplier_name" placeholder="Supplier name" required>
          </div>
          <div class="form-group">
            <label for="mobile_phone">Mobile Phone</label>
            <input type="text" class="form-control" name="mobile_phone" maxlength="15" 
              pattern="^\+?[0-9]{10,15}$" 
              title="Nomor telepon hanya angka dan maksimal 15 digit. Contoh: +628123456789" 
              placeholder="Contoh: +628123456789" required>
          </div>
          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" name="email" placeholder="Supplier email" required>
          </div>
          <div class="form-group">
            <label for="address">Address</label>
            <textarea name="address" class="form-control" placeholder="Full address" required></textarea>
          </div>
          <button type="submit" name="add_supplier" class="btn btn-primary">Add Supplier</button>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include_once('layouts/footer.php'); ?>
