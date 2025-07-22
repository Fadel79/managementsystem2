<?php include_once('includes/load.php'); ?>
<?php
$req_fields = array('username','password' );
validate_fields($req_fields);
$username = remove_junk($_POST['username']);
$password = remove_junk($_POST['password']);

  if(empty($errors)){

    $user = authenticate_v2($username, $password);
    print_r($user);
    exit; 

        if($user):
           //create session with id
           $session->login($user['id']);
           //Update Sign in time
           updateLastLogIn($user['id']);
           // redirect user to group home page by user level
          if($user['user_level'] === '1'):
              $session->msg("s", "Hello ".$user['username'].", Welcome Admin.");
              redirect('admin.php', false);

          elseif ($user['user_level'] === '2'):
              $session->msg("s", "Hello ".$user['username'].", Welcome Operator.");
              redirect('op.php', false);

          else:
              $session->msg("s", "Hello ".$user['username'].", Welcome User.");
              redirect('op.php', false);

          endif;

        else:
          $session->msg("d", "Sorry Username/Password incorrect.");
          redirect('index.php',false);
        endif;

  } else {

     $session->msg("d", $errors);
     redirect('login_v2.php',false);
  }

?>
