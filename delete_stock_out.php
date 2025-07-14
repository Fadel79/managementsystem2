<?php
require_once('includes/load.php');
page_require_level(1);

$stock_out_id = (int)$_GET['id'];

// Ambil data stock_out berdasarkan ID
$stock_out = find_by_id('stock_out', $stock_out_id);
if (!$stock_out) {
  $session->msg("d", "Data stock-out tidak ditemukan.");
  redirect('stock_out.php');
  exit;
}

// Ambil data produk
$product_id = (int)$stock_out['product_id'];
$product = find_by_id('products', $product_id);
if (!$product) {
  $session->msg("d", "Produk terkait tidak ditemukan.");
  redirect('stock_out.php');
  exit;
}

// Kembalikan stok ke produk
$quantity = (int)$stock_out['quantity'];
$db->query("UPDATE products SET stock = stock + {$quantity} WHERE id = {$product_id}");

// Hapus record stock_out
if (delete_by_id('stock_out', $stock_out_id)) {
  $session->msg("s", "Data stock-out berhasil dihapus dan stok dikembalikan.");
} else {
  $session->msg("d", "Gagal menghapus data stock-out.");
}

redirect('stock_out.php');
exit;
?>
