<?php
  require_once('includes/load.php');
  page_require_level(1);

  $supplier_id = (int)$_GET['id'];
  if (delete_by_id('suppliers', $supplier_id)) {
    $session->msg("s", "Supplier deleted.");
  } else {
    $session->msg("d", "Supplier deletion failed.");
  }
  redirect('supplier.php');
?>
