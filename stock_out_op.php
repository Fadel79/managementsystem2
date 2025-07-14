<?php
$page_title = 'Stock Out';
require_once('includes/load.php');
page_require_level(2);

// Redirect ke PDF jika tombol diklik
if (isset($_POST['pdf_stock_out_op'])) {
  header("Location: pdf_stock_out_op.php");
  exit();
}

// Ambil semua data Stock Out
$all_stock_out = find_stock_out(); // JOIN ke products & customers

include_once('layouts/header.php');
?>

<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <strong>
          <span class="glyphicon glyphicon-upload"></span>
          <span>Stock Out</span>
        </strong>
        <a href="add_stock_out_op.php" class="btn btn-info pull-right btn-sm" style="margin-left: 5px;">
          <span class="glyphicon glyphicon-plus"></span> Add Stock Out
        </a>
        <form method="POST" style="display: inline;">
          <button type="submit" name="pdf_stock_out_op" class="btn btn-info pull-right btn-sm">
            <span class="glyphicon glyphicon-file"></span> PDF
          </button>
        </form>
      </div>
      <div class="panel-body">
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th class="text-center" style="width: 50px;">#</th>
              <th>Product</th>
              <th>Customer</th>
              <th>Address</th>
              <th>Quantity</th>
              <th>Stock</th>
              <th>Date Out</th>
              <th>Qty Status</th>
              <th>Quality Status</th>
              <th>Note</th>
            </tr>
          </thead>
          <tbody>
            <?php $i = 1; foreach ($all_stock_out as $stock): ?>
              <tr>
                <td class="text-center"><?php echo $i++; ?></td>
                <td><?php echo remove_junk($stock['product_name']); ?></td>
                <td><?php echo remove_junk($stock['customer_name'] ?? '-'); ?></td>
                <td><?php echo remove_junk($stock['address'] ?? '-'); ?></td>
                <td><?php echo (int)$stock['quantity']; ?></td>
                <td><?php echo (int)$stock['stock']; ?></td>
                <td><?php echo read_date($stock['date_received']); ?></td>
                <td><?php echo $stock['is_quantity_ok'] ? '<span class="label label-success">OK</span>' : '<span class="label label-danger">Tidak</span>'; ?></td>
                <td><?php echo $stock['is_quality_ok'] ? '<span class="label label-success">OK</span>' : '<span class="label label-danger">Tidak</span>'; ?></td>
                <td><?php echo remove_junk($stock['validation_note'] ?? '-'); ?></td>
              </tr>
            <?php endforeach; ?>
            <?php if (count($all_stock_out) === 0): ?>
              <tr>
                <td colspan="11" class="text-center">No stock out data found.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>
