<?php
  require_once('includes/load.php');
  page_require_level(1);

  $customer_id = (int)$_GET['id'];
  if (delete_by_id('customers', $customer_id)) {
    $session->msg("s", "Customer deleted.");
  } else {
    $session->msg("d", "Customer deletion failed.");
  }
  redirect('customer.php');
?>
