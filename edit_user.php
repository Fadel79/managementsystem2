<?php
  $page_title = 'Edit User';
  require_once('includes/load.php');
  page_require_level(1);

  $e_user = find_by_id('users', (int)$_GET['id']);
  $groups = find_all('user_groups');

  if (!$e_user) {
    $session->msg("d", "Missing user id.");
    redirect('users.php');
  }

  // Update User basic info
  if (isset($_POST['update'])) {
    $req_fields = array('name', 'username', 'level', 'status', 'phone_number', 'email', 'address');
    validate_fields($req_fields);

    if (empty($errors)) {
      $id       = (int)$e_user['id'];
      $name     = remove_junk($db->escape($_POST['name']));
      $username = remove_junk($db->escape($_POST['username']));
      $level    = (int)$db->escape($_POST['level']);
      $status   = remove_junk($db->escape($_POST['status']));
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
        $sql .= "name='{$name}', username='{$username}', user_level='{$level}', status='{$status}', ";
        $sql .= "phone_number='{$phone}', email='{$email}', address='{$address}' ";
        $sql .= "WHERE id='{$db->escape($id)}'";

        $result = $db->query($sql);

        if ($result && $db->affected_rows() >= 0) {
          $session->msg('s', "Account updated.");
          redirect('edit_user.php?id=' . (int)$e_user['id'], false);
        } else {
          $session->msg('d', 'Sorry, failed to update.');
          redirect('edit_user.php?id=' . (int)$e_user['id'], false);
        }
      } else {
        $session->msg("d", $errors);
        redirect('edit_user.php?id=' . (int)$e_user['id'], false);
      }
    } else {
      $session->msg("d", $errors);
      redirect('edit_user.php?id=' . (int)$e_user['id'], false);
    }
  }

  // Update password
  if (isset($_POST['update-pass'])) {
    $req_fields = array('password');
    validate_fields($req_fields);

    if (empty($errors)) {
      $id       = (int)$e_user['id'];
      $password = remove_junk($db->escape($_POST['password']));
      $h_pass   = sha1($password);

      $sql  = "UPDATE users SET password='{$h_pass}' WHERE id='{$db->escape($id)}'";
      $result = $db->query($sql);

      if ($result && $db->affected_rows() === 1) {
        $session->msg('s', "Password updated.");
        redirect('edit_user.php?id=' . (int)$e_user['id'], false);
      } else {
        $session->msg('d', 'Failed to update password.');
        redirect('edit_user.php?id=' . (int)$e_user['id'], false);
      }
    } else {
      $session->msg("d", $errors);
      redirect('edit_user.php?id=' . (int)$e_user['id'], false);
    }
  }
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-12"><?php echo display_msg($msg); ?></div>

  <!-- Edit Form -->
  <div class="col-md-6">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          Update <?php echo remove_junk(ucwords($e_user['name'])); ?> Account
        </strong>
      </div>
      <div class="panel-body">
        <form method="post" action="edit_user.php?id=<?php echo (int)$e_user['id']; ?>" class="clearfix">
          <div class="form-group">
            <label for="name" class="control-label">Name</label>
            <input type="text" class="form-control" name="name" value="<?php echo remove_junk($e_user['name']); ?>">
          </div>
          <div class="form-group">
            <label for="username" class="control-label">Username</label>
            <input type="text" class="form-control" name="username" value="<?php echo remove_junk($e_user['username']); ?>">
          </div>
          <div class="form-group">
            <label for="phone_number" class="control-label">Phone Number</label>
            <input type="text" class="form-control" name="phone_number" value="<?php echo remove_junk($e_user['phone_number']); ?>">
          </div>
          <div class="form-group">
            <label for="email" class="control-label">Email</label>
            <input type="email" class="form-control" name="email" value="<?php echo remove_junk($e_user['email']); ?>">
          </div>
          <div class="form-group">
            <label for="address" class="control-label">Address</label>
            <textarea class="form-control" name="address"><?php echo remove_junk($e_user['address']); ?></textarea>
          </div>
          <div class="form-group">
            <label for="level">User Role</label>
            <select class="form-control" name="level">
              <?php foreach ($groups as $group): ?>
                <option <?php if ($group['group_level'] == $e_user['user_level']) echo 'selected="selected"'; ?> value="<?php echo $group['group_level']; ?>">
                  <?php echo ucwords($group['group_name']); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <label for="status">Status</label>
            <select class="form-control" name="status">
              <option <?php if ($e_user['status'] === '1') echo 'selected="selected"'; ?> value="1">Active</option>
              <option <?php if ($e_user['status'] === '0') echo 'selected="selected"'; ?> value="0">Non Active</option>
            </select>
          </div>
          <div class="form-group clearfix">
            <button type="submit" name="update" class="btn btn-info">Update</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Password Change -->
  <div class="col-md-6">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          Change Password for <?php echo remove_junk(ucwords($e_user['name'])); ?>
        </strong>
      </div>
      <div class="panel-body">
        <form method="post" action="edit_user.php?id=<?php echo (int)$e_user['id']; ?>" class="clearfix">
          <div class="form-group">
            <label for="password" class="control-label">New Password</label>
            <input type="password" class="form-control" name="password" placeholder="Type new password">
          </div>
          <div class="form-group clearfix">
            <button type="submit" name="update-pass" class="btn btn-danger pull-right">Change Password</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include_once('layouts/footer.php'); ?>
