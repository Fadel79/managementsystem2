<?php
require_once('includes/load.php');
require_once('tcpdf/tcpdf.php'); // Pastikan path ini sesuai lokasi TCPDF-mu

page_require_level(2);

// Ambil data
$all_stock_in = find_stock_in();

// Inisialisasi TCPDF
$pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Pengaturan PDF
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Inventory System');
$pdf->SetTitle('Laporan Stock In');
$pdf->SetHeaderData('', 0, 'Laporan Barang Masuk (Stock In)', 'Tanggal: '.date('d-m-Y'));
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', 12));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', 10));
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetMargins(10, 25, 10);
$pdf->SetHeaderMargin(10);
$pdf->SetFooterMargin(10);
$pdf->SetAutoPageBreak(TRUE, 15);
$pdf->SetFont('dejavusans', '', 9);

// Tambah halaman
$pdf->AddPage();

// Buat HTML untuk tabel
$html = '<table border="1" cellpadding="4">
  <thead>
    <tr style="background-color:#f2f2f2;">
      <th width="78.5" align="center">#</th>
      <th width="78.5">Product</th>
      <th width="78.5">Supplier</th>
      <th width="78.5">Phone</th>
      <th width="78.5" align="center">Qty</th>
      <th width="78.5" align="center">Stock</th>
      <th width="78.5">Date</th>
      <th width="78.5" align="center">Qty OK</th>
      <th width="78.5" align="center">Qlt OK</th>
      <th width="78.5">Note</th>
    </tr>
  </thead>
  <tbody>';

$i = 1;
foreach ($all_stock_in as $stock) {
    $html .= '<tr>
      <td align="center">'.$i++.'</td>
      <td>'.htmlspecialchars($stock['product_name']).'</td>
      <td>'.htmlspecialchars($stock['supplier_name']).'</td>
      <td>'.htmlspecialchars($stock['mobile_phone']).'</td>
      <td align="center">'.(int)$stock['quantity'].'</td>
      <td align="center">'.(int)$stock['current_stock'].'</td>
      <td>'.date('d-m-Y', strtotime($stock['date_received'])).'</td>
      <td align="center">'.($stock['is_quantity_ok'] ? 'OK' : 'Tidak').'</td>
      <td align="center">'.($stock['is_quality_ok'] ? 'OK' : 'Tidak').'</td>
      <td>'.htmlspecialchars($stock['validation_note']).'</td>
    </tr>';
}

if (count($all_stock_in) === 0) {
    $html .= '<tr><td colspan="10" align="center">Tidak ada data Stock In.</td></tr>';
}

$html .= '</tbody></table>';

// Tulis HTML ke PDF
$pdf->writeHTML($html, true, false, true, false, '');

// Hapus buffer output yang tertinggal
if (ob_get_length()) ob_end_clean();

// Outputkan PDF
$pdf->Output('laporan_stock_in.pdf', 'I');
