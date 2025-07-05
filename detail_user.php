<?php
  $page_title = 'Detail User';
  require_once('includes/load.php');
  page_require_level(1);

  // Ambil data user berdasarkan ID
  $user = find_by_id('users', (int)$_GET['id']);
  if (!$user) {
    $session->msg("d", "User tidak ditemukan.");
    redirect('users.php');
  }
?>
<?php include_once('layouts/header.php'); ?>

<div class="row">
  <div class="col-md-8 col-md-offset-2">
    <?php echo display_msg($msg); ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong><i class="glyphicon glyphicon-user"></i> Detail User</strong>
      </div>
      <div class="panel-body">
        <table class="table table-bordered">
          <tr>
            <th width="30%">Name</th>
            <td><?php echo remove_junk($user['name']); ?></td>
          </tr>
          <tr>
            <th>Username</th>
            <td><?php echo remove_junk($user['username']); ?></td>
          </tr>
          <tr>
            <th>Phone Number</th>
            <td><?php echo remove_junk($user['phone_number']); ?></td>
          </tr>
          <tr>
            <th>Email</th>
            <td><?php echo remove_junk($user['email']); ?></td>
          </tr>
          <tr>
            <th>Address</th>
            <td><?php echo remove_junk($user['address']); ?></td>
          </tr>
          <tr>
            <th>Level</th>
            <td><?php echo remove_junk($user['user_level']); ?></td>
          </tr>
          <tr>
            <th>Status</th>
            <td>
              <?php echo ($user['status'] === '1') ? 'Aktif' : 'Nonaktif'; ?>
            </td>
          </tr>
          <tr>
            <th>Last Login</th>
            <td><?php echo read_date($user['last_login']); ?></td>
          </tr>
        </table>

        <a href="users.php" class="btn btn-default">← Back</a>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>
