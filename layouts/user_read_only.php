<?php
  $page_title = 'User Read Only';
  require_once('includes/load.php');

  // Hanya izinkan user dengan level 2
  if ($user['user_level'] != 2) {
    $session->msg("d", "Anda tidak memiliki izin untuk mengakses halaman ini.");
    redirect('home.php');
  }

  $all_users = find_all_user(); // ambil semua user dari database
?>
<?php include_once('layouts/header.php'); ?>

<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Daftar Pengguna (Read-Only)</span>
        </strong>
      </div>

      <div class="panel-body">
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th class="text-center" style="width: 50px;">#</th>
              <th>Nama</th>
              <th>Username</th>
              <th class="text-center" style="width: 15%;">Peran</th>
              <th class="text-center" style="width: 10%;">Status</th>
              <th style="width: 20%;">Login Terakhir</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($all_users as $a_user): ?>
              <tr>
                <td class="text-center"><?php echo count_id(); ?></td>
                <td><?php echo remove_junk(ucwords($a_user['name'])); ?></td>
                <td><?php echo remove_junk(ucwords($a_user['username'])); ?></td>
                <td class="text-center"><?php echo remove_junk(ucwords($a_user['group_name'])); ?></td>
                <td class="text-center">
                  <?php if ($a_user['status'] === '1'): ?>
                    <span class="label label-success">Aktif</span>
                  <?php else: ?>
                    <span class="label label-danger">Nonaktif</span>
                  <?php endif; ?>
                </td>
                <td><?php echo read_date($a_user['last_login']); ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>
