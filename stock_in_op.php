<?php
// ===== stock_in.php =====
$page_title = 'Stock In';
require_once('includes/load.php');
page_require_level(2);

if (isset($_POST['pdf_stock_in_op'])) {
  header("Location: pdf_stock_in_op.php");
  exit();
}

$all_stock_in = find_stock_in(); // JOIN product, supplier, dan ambil kolom stock
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
    <span class="glyphicon glyphicon-download-alt"></span>
    <span>Stock In</span>
  </strong>
  <form method="POST" style="display: inline;">
    <button type="submit" name="pdf_stock_in_op" class="btn btn-info pull-right btn-sm">
      <span class="glyphicon glyphicon-file"></span> PDF
    </button>
  </form>
</div>
      <div class="panel-body">
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th class="text-center" style="width: 50px;">No</th>
              <th>Product</th>
              <th>Supplier</th>
              <th>Phone</th>
              <th>Quantity</th>
              <th>Stock</th>
              <th>Date Received</th>
              <th>Qty Status</th>
              <th>Quality Status</th>
              <th>Note</th>
              <th class="text-center" style="width: 100px;">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php $i = 1; foreach ($all_stock_in as $stock): ?>
              <tr>
                <td class="text-center"><?php echo $i++; ?></td>
                <td><?php echo remove_junk($stock['product_name']); ?></td>
                <td><?php echo remove_junk($stock['supplier_name']); ?></td>
                <td><?php echo remove_junk($stock['mobile_phone']); ?></td>
                <td><?php echo (int)$stock['quantity']; ?></td>
                <td><?php echo (int)$stock['current_stock']; ?></td>
                <td><?php echo read_date($stock['date_received']); ?></td>
                <td><?php echo $stock['is_quantity_ok'] ? '<span class="label label-success">OK</span>' : '<span class="label label-danger">No</span>'; ?></td>
                <td><?php echo $stock['is_quality_ok'] ? '<span class="label label-success">OK</span>' : '<span class="label label-danger">No</span>'; ?></td>
                <td><?php echo remove_junk($stock['validation_note']); ?></td>
                <td class="text-center">
                  <div class="btn-group">
                    <a href="edit_stock_in_op.php?id=<?php echo (int)$stock['id']; ?>" class="btn btn-xs btn-warning" title="Edit">
                      <span class="glyphicon glyphicon-edit"></span>
                    </a>
                    <a href="delete_stock_in.php?id=<?php echo (int)$stock['id']; ?>" class="btn btn-xs btn-danger" title="Delete">
                      <span class="glyphicon glyphicon-trash"></span>
                    </a>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
            <?php if (count($all_stock_in) === 0): ?>
              <tr>
                <td colspan="11" class="text-center">No stock in data found.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>
