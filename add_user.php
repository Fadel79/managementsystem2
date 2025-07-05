<?php
  $page_title = 'Add User';
  require_once('includes/load.php');
  // Cek level akses
  page_require_level(1);
  $groups = find_all('user_groups');

  if (isset($_POST['add_user'])) {
    $req_fields = array('full-name', 'username', 'password', 'level', 'phone_number', 'email', 'address');
    validate_fields($req_fields);

    $errors = array(); // pastikan array error di-reset

    // Ambil data & bersihkan
    $name       = remove_junk($db->escape($_POST['full-name']));
    $username   = remove_junk($db->escape($_POST['username']));
    $password   = remove_junk($db->escape($_POST['password']));
    $user_level = (int)$db->escape($_POST['level']);
    $phone      = remove_junk($db->escape($_POST['phone_number']));
    $email      = remove_junk($db->escape($_POST['email']));
    $address    = remove_junk($db->escape($_POST['address']));

    // Validasi format nomor telepon
    if (!preg_match('/^\+?[0-9]{10,15}$/', $phone)) {
      $errors[] = "Nomor telepon tidak valid! Gunakan format angka, contoh: +628123456789";
    }

    // Validasi email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $errors[] = "Format email tidak valid!";
    }

    if (empty($errors)) {
      $password = sha1($password); // hash password
      $query  = "INSERT INTO users (";
      $query .= "name, username, password, user_level, phone_number, email, address, status";
      $query .= ") VALUES (";
      $query .= "'{$name}', '{$username}', '{$password}', '{$user_level}', '{$phone}', '{$email}', '{$address}', '1'";
      $query .= ")";

      if ($db->query($query)) {
        $session->msg('s', "User account has been created!");
        redirect('add_user.php', false);
      } else {
        $session->msg('d', 'Gagal menyimpan ke database!');
        redirect('add_user.php', false);
      }
    } else {
      $session->msg("d", $errors);
      redirect('add_user.php', false);
    }
  }
?>
<?php include_once('layouts/header.php'); ?>
<?php echo display_msg($msg); ?>
<div class="row">
  <div class="panel panel-default">
    <div class="panel-heading">
      <strong>
        <span class="glyphicon glyphicon-th"></span>
        <span>Add New User</span>
      </strong>
    </div>
    <div class="panel-body">
      <div class="col-md-6">
        <form method="post" action="add_user.php">
          <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" name="full-name" placeholder="Full Name" required>
          </div>
          <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" name="username" placeholder="Username" required>
          </div>
          <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" name="password" placeholder="Password" required>
          </div>
          <div class="form-group">
            <label for="phone">Phone Number</label>
            <input type="text" class="form-control" name="phone_number" placeholder="+6281234567890" required>
          </div>
          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" name="email" placeholder="user@example.com" required>
          </div>
          <div class="form-group">
            <label for="address">Address</label>
            <textarea class="form-control" name="address" placeholder="User Address" required></textarea>
          </div>
          <div class="form-group">
            <label for="level">User Role</label>
            <select class="form-control" name="level">
              <?php foreach ($groups as $group): ?>
                <option value="<?php echo $group['group_level']; ?>">
                  <?php echo ucwords($group['group_name']); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group clearfix">
            <button type="submit" name="add_user" class="btn btn-primary">Add User</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include_once('layouts/footer.php'); ?>
