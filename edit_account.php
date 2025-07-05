<?php
  $page_title = 'Edit Account';
  require_once('includes/load.php');
  page_require_level(3);

  // Ambil data user login sekarang
  $user = find_by_id('users', (int)$_SESSION['user_id']);
  if (!$user) {
    $session->msg("d", "User tidak ditemukan.");
    redirect('home.php');
  }
?>

<?php
// Upload foto profil
if (isset($_POST['submit'])) {
  $photo = new Media();
  $user_id = (int)$_POST['user_id'];
  $photo->upload($_FILES['file_upload']);
  if ($photo->process_user($user_id)) {
    $session->msg('s', 'Foto berhasil diupload.');
    redirect('edit_account.php');
  } else {
    $session->msg('d', join($photo->errors));
    redirect('edit_account.php');
  }
}
?>

<?php
// Update data profil
if (isset($_POST['update'])) {
  $req_fields = array('name', 'username', 'phone_number', 'email', 'address');
  validate_fields($req_fields);

  if (empty($errors)) {
    $id       = (int)$user['id'];
    $name     = remove_junk($db->escape($_POST['name']));
    $username = remove_junk($db->escape($_POST['username']));
    $phone    = remove_junk($db->escape($_POST['phone_number']));
    $email    = remove_junk($db->escape($_POST['email']));
    $address  = remove_junk($db->escape($_POST['address']));

    // Validasi nomor HP dan email
    if (!preg_match('/^\+?[0-9]{10,15}$/', $phone)) {
      $errors[] = "Nomor telepon tidak valid!";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $errors[] = "Format email tidak valid!";
    }

    if (empty($errors)) {
      $sql  = "UPDATE users SET ";
      $sql .= "name='{$name}', username='{$username}', phone_number='{$phone}', ";
      $sql .= "email='{$email}', address='{$address}' ";
      $sql .= "WHERE id='{$id}'";

      $result = $db->query($sql);
      if ($result && $db->affected_rows() >= 0) {
        $session->msg('s', "Profil berhasil diperbarui.");
        redirect('edit_account.php', false);
      } else {
        $session->msg('d', "Gagal memperbarui profil.");
        redirect('edit_account.php', false);
      }
    }
  }
}
?>

<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-12"><?php echo display_msg($msg); ?></div>

  <!-- Ubah Foto -->
  <div class="col-md-6">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <span class="glyphicon glyphicon-camera"></span>
        <span>Ganti Foto</span>
      </div>
      <div class="panel-body">
        <div class="row">
          <div class="col-md-4">
            <img class="img-circle img-size-2" src="uploads/users/<?php echo $user['image']; ?>" alt="">
          </div>
          <div class="col-md-8">
            <form class="form" action="edit_account.php" method="POST" enctype="multipart/form-data">
              <div class="form-group">
                <input type="file" name="file_upload" class="btn btn-default btn-file" />
              </div>
              <div class="form-group">
                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                <button type="submit" name="submit" class="btn btn-warning">Change</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Ubah Info Akun -->
  <div class="col-md-6">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <span class="glyphicon glyphicon-edit"></span>
        <span>Edit Profil Saya</span>
      </div>
      <div class="panel-body">
        <form method="post" action="edit_account.php" class="clearfix">
          <div class="form-group">
            <label for="name" class="control-label">Nama</label>
            <input type="text" class="form-control" name="name" value="<?php echo remove_junk($user['name']); ?>">
          </div>
          <div class="form-group">
            <label for="username" class="control-label">Username</label>
            <input type="text" class="form-control" name="username" value="<?php echo remove_junk($user['username']); ?>">
          </div>
          <div class="form-group">
            <label for="phone_number" class="control-label">No. Telepon</label>
            <input type="text" class="form-control" name="phone_number" value="<?php echo remove_junk($user['phone_number']); ?>">
          </div>
          <div class="form-group">
            <label for="email" class="control-label">Email</label>
            <input type="email" class="form-control" name="email" value="<?php echo remove_junk($user['email']); ?>">
          </div>
          <div class="form-group">
            <label for="address" class="control-label">Alamat</label>
            <textarea class="form-control" name="address"><?php echo remove_junk($user['address']); ?></textarea>
          </div>
          <div class="form-group clearfix">
            <a href="change_password.php" class="btn btn-danger pull-right">Ganti Password</a>
            <button type="submit" name="update" class="btn btn-info">Update</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>
