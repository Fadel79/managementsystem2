<?php
require_once('includes/load.php');
page_require_level(1);

$stock_in_id = (int)$_GET['id'];

// Ambil data stock_in berdasarkan ID
$stock_in = find_by_id('stock_in', $stock_in_id);

if (!$stock_in) {
  $session->msg("d", "Data stock-in tidak ditemukan.");
  redirect('stock_in.php');
}

// Ambil data quantity dan product_id
$product_id = (int)$stock_in['product_id'];
$quantity   = (int)$stock_in['quantity'];

// Hapus record stock_in
$delete_query = "DELETE FROM stock_in WHERE id = {$stock_in_id}";

if ($db->query($delete_query)) {
  // Kurangi stok produk
  $db->query("UPDATE products SET stock = stock - {$quantity} WHERE id = {$product_id}");

  $session->msg("s", "Stock-in berhasil dihapus dan stok produk dikurangi.");
} else {
  $session->msg("d", "Gagal menghapus stock-in.");
}

redirect('stock_in.php');
?>
