<?php
require_once('includes/load.php');
require_once('tcpdf/tcpdf.php'); // Sesuaikan path jika perlu

page_require_level(2);

// Ambil data Stock Out
$all_stock_out = find_stock_out();

// Inisialisasi TCPDF
$pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Pengaturan PDF
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Inventory System');
$pdf->SetTitle('Laporan Stock Out');
$pdf->SetHeaderData('', 0, 'Laporan Barang Keluar (Stock Out)', 'Tanggal: '.date('d-m-Y'));
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', 12));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', 10));
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetMargins(10, 25, 10);
$pdf->SetHeaderMargin(10);
$pdf->SetFooterMargin(10);
$pdf->SetAutoPageBreak(TRUE, 15);
$pdf->SetFont('dejavusans', '', 9);

// Tambah halaman PDF
$pdf->AddPage();

// Buat HTML tabel
$html = '<table border="1" cellpadding="4">
  <thead>
    <tr style="background-color:#f2f2f2;">
      <th width="78.5" align="center">#</th>
      <th width="78.5">Product</th>
      <th width="78.5">Customer</th>
      <th width="78.5">Address</th>
      <th width="78.5" align="center">Qty</th>
      <th width="78.5" align="center">Stock</th>
      <th width="78.5">Date Out</th>
      <th width="78.5" align="center">Qty OK</th>
      <th width="78.5" align="center">Qlt OK</th>
      <th width="78.5">Note</th>
    </tr>
  </thead>
  <tbody>';

$i = 1;
foreach ($all_stock_out as $stock) {
    $html .= '<tr>
      <td align="center">'.$i++.'</td>
      <td>'.htmlspecialchars($stock['product_name']).'</td>
      <td>'.htmlspecialchars($stock['customer_name'] ?? '-').'</td>
      <td>'.htmlspecialchars($stock['address'] ?? '-').'</td>
      <td align="center">'.(int)$stock['quantity'].'</td>
      <td align="center">'.(int)$stock['stock'].'</td>
      <td>'.date('d-m-Y', strtotime($stock['date_received'])).'</td>
      <td align="center">'.($stock['is_quantity_ok'] ? 'OK' : 'Tidak').'</td>
      <td align="center">'.($stock['is_quality_ok'] ? 'OK' : 'Tidak').'</td>
      <td>'.htmlspecialchars($stock['validation_note'] ?? '-').'</td>
    </tr>';
}

if (count($all_stock_out) === 0) {
    $html .= '<tr><td colspan="10" align="center">Tidak ada data Stock Out.</td></tr>';
}

$html .= '</tbody></table>';

// Tulis ke PDF
$pdf->writeHTML($html, true, false, true, false, '');

// Bersihkan buffer output
if (ob_get_length()) ob_end_clean();

// Tampilkan PDF
$pdf->Output('laporan_stock_out.pdf', 'I');
