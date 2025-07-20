<?php
require_once('includes/load.php');
require_once('tcpdf/tcpdf.php');

page_require_level(1);

// Ambil data Stock Out
$all_stock_out = find_stock_out();

// Inisialisasi TCPDF
$pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Pengaturan PDF
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Inventory System');
$pdf->SetTitle('Laporan Stock Out');

// Nonaktifkan header/footer default bawaan TCPDF
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// Margin dan font
$pdf->SetMargins(10, 15, 10); // margin atas diperkecil
$pdf->SetAutoPageBreak(TRUE, 15);
$pdf->SetFont('dejavusans', '', 9);

// Tambah halaman PDF
$pdf->AddPage();

// ===== HEADER MANUAL TENGAH =====
$pdf->SetFont('dejavusans', 'B', 14);
$pdf->Cell(0, 10, 'Laporan Barang Keluar (Stock Out)', 0, 1, 'C');

$pdf->SetFont('dejavusans', '', 11);
$pdf->Cell(0, 7, 'Tanggal: ' . date('d-m-Y'), 0, 1, 'C');

$pdf->Ln(5); // spasi bawah header

// ===== TABEL DATA =====
$html = '<table border="1" cellpadding="4">
  <thead>
    <tr style="background-color:#f2f2f2; font-weight:bold; text-align:center;">
      <th width="78.5">No</th>
      <th width="78.5">Product</th>
      <th width="78.5">Customer</th>
      <th width="78.5">Address</th>
      <th width="78.5">Qty</th>
      <th width="78.5">Stock</th>
      <th width="78.5">Date Out</th>
      <th width="78.5">Qty OK</th>
      <th width="78.5">Qlt OK</th>
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

// Tulis HTML ke PDF
$pdf->writeHTML($html, true, false, true, false, '');

// Bersihkan output buffer
if (ob_get_length()) ob_end_clean();

// Output PDF
$pdf->Output('laporan_stock_out.pdf', 'I');
