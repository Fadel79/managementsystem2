<?php
require_once('includes/load.php');
page_require_level(1);

$stock_in_id = (int)$_GET['id'];
if (delete_by_id('stock_in', $stock_in_id)) {
  $session->msg("s", "Stock-in record deleted.");
} else {
  $session->msg("d", "Failed to delete stock-in record.");
}
redirect('stock_in.php');
?>
