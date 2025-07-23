<?php
ob_start();
require_once('includes/load.php');
if ($session->isUserLoggedIn(true)) {
  redirect('admin.php', false);
}
?>

<head>
  <meta charset="UTF-8">
  <title><?php if (!empty($page_title))
            echo remove_junk($page_title);
          elseif (!empty($user))
            echo ucfirst($user['name']);
          else echo "Inventory Management System"; ?>
  </title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" />
  <link rel="stylesheet" href="libs/css/main.css" />
</head>
<div class="login-wrapper">
  <div class="login-page">
    <div class="text-center">
      <h1>Login Panel</h1>
      <h4>Inventory Management System</h4>
    </div>
    <?php echo display_msg($msg); ?>
    <form method="post" action="auth.php" class="clearfix">
      <div class="form-group">
        <label for="username" class="control-label">Username</label>
        <input type="text" class="form-control" name="username" placeholder="Username">
      </div>
      <div class="form-group">
        <label for="Password" class="control-label">Password</label>
        <input type="password" name="password" class="form-control" placeholder="Password">
      </div>
      <div class="form-group">
        <button type="submit" class="btn btn-danger" style="border-radius:0%">Login</button>
      </div>
    </form>
  </div>
</div>
<?php include_once('layouts/footer.php'); ?>