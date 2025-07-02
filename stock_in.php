<?php
  $page_title = 'Stock In';
  require_once('includes/load.php');
  page_require_level(2);

  // Ambil semua data stock masuk dan join dengan produk & supplier
  $all_stock_in = find_stock_in(); // pastikan fungsi ini dibuat di load.php

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
        <a href="add_stock_in.php" class="btn btn-info pull-right btn-sm">Add Stock In</a>
      </div>
      <div class="panel-body">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th class="text-center" style="width: 50px;">#</th>
              <th>Product</th>
              <th>Supplier</th>
              <th>Quantity</th>
              <th>Date Received</th>
              <th class="text-center" style="width: 100px;">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php $i = 1; foreach ($all_stock_in as $stock): ?>
              <tr>
                <td class="text-center"><?php echo $i++; ?></td>
                <td><?php echo remove_junk($stock['product_name']); ?></td>
                <td><?php echo remove_junk($stock['supplier_name']); ?></td>
                <td><?php echo (int)$stock['quantity']; ?></td>
                <td><?php echo read_date($stock['date_received']); ?></td>
                <td class="text-center">
                  <div class="btn-group">
                    <a href="edit_stock_in.php?id=<?php echo (int)$stock['id'];?>" class="btn btn-xs btn-warning" title="Edit">
                      <span class="glyphicon glyphicon-edit"></span>
                    </a>
                    <a href="delete_stock_in.php?id=<?php echo (int)$stock['id'];?>" class="btn btn-xs btn-danger" title="Delete">
                      <span class="glyphicon glyphicon-trash"></span>
                    </a>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
            <?php if (count($all_stock_in) === 0): ?>
              <tr>
                <td colspan="6" class="text-center">No stock in data found.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>
