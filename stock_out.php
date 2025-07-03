<?php
  $page_title = 'Stock Out';
  require_once('includes/load.php');
  page_require_level(2);

  // Ambil semua data stock keluar dan join dengan produk & customer
  $all_stock_out = find_stock_out(); // Pastikan fungsi ini dibuat di load.php

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
        <a href="add_stock_out.php" class="btn btn-info pull-right btn-sm">Add Stock Out</a>
      </div>
      <div class="panel-body">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th class="text-center" style="width: 50px;">#</th>
              <th>Product</th>
              <th>Customer</th>
              <th>Quantity</th>
              <th>Date Received</th>
              <th class="text-center" style="width: 100px;">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php $i = 1; foreach ($all_stock_out as $stock): ?>
              <tr>
                <td class="text-center"><?php echo $i++; ?></td>
                <td><?php echo remove_junk($stock['product_name']); ?></td>
                <td><?php echo remove_junk($stock['customer_name'] ?? ''); ?></td>
                <td><?php echo (int)$stock['quantity']; ?></td>
                <td><?php echo read_date($stock['date_received']); ?></td>
                <td class="text-center">
                  <div class="btn-group">
                    <a href="edit_stock_out.php?id=<?php echo (int)$stock['id'];?>" class="btn btn-xs btn-warning" title="Edit">
                      <span class="glyphicon glyphicon-edit"></span>
                    </a>
                    <a href="delete_stock_out.php?id=<?php echo (int)$stock['id'];?>" class="btn btn-xs btn-danger" title="Delete">
                      <span class="glyphicon glyphicon-trash"></span>
                    </a>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
            <?php if (count($all_stock_out) === 0): ?>
              <tr>
                <td colspan="6" class="text-center">No stock out data found.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>
