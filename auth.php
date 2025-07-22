<?php include_once('includes/load.php'); ?>
<?php
$req_fields = array('username','password');
validate_fields($req_fields);

$username = remove_junk($_POST['username']);
$password = remove_junk($_POST['password']);

if (empty($errors)) {
  // Gunakan authenticate_v2 untuk mendapatkan data user
  $user = authenticate_v2($username, $password);

  if ($user) {
    // Login berhasil
    $session->login($user['id']);
    updateLastLogIn($user['id']);

    $session->msg("s", "Welcome to Inventory Management System");

    // Arahkan ke halaman sesuai level
    if ((int)$user['user_level'] === 1) {
      redirect('admin.php', false); // untuk admin
    } elseif ((int)$user['user_level'] === 2) {
      redirect('op.php', false); // untuk operator
    } else {
      redirect('index.php', false); // fallback jika level tidak dikenali
    }

  } else {
    // Login gagal
    $session->msg("d", "Sorry, username/password salah.");
    redirect('index.php', false);
  }

} else {
  $session->msg("d", $errors);
  redirect('index.php', false);
}
