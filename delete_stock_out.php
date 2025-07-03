<?php
require_once('includes/load.php');
page_require_level(1);

$stock_out_id = (int)$_GET['id'];
if (delete_by_id('stock_out', $stock_out_id)) {
  $session->msg("s", "Stock-out record deleted.");
} else {
  $session->msg("d", "Failed to delete stock-out record.");
}
redirect('stock_out.php');
?>
