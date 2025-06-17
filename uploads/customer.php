
<?php
/*
  $page_title = 'All Customer';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
  $products = join_product_table();

<?php include_once('layouts/header.php'); ?>
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
          <span class="glyphicon glyphicon-user"></span>
          <span>Customers</span>
        </strong>
        <a href="add_customer.php" class="btn btn-info pull-right btn-sm"> Add New Customer</a>
      </div>
      <div class="panel-body">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th class="text-center" style="width: 50px;">#</th>
              <th>Customer Name</th>
              <th>Phone Number</th>
              <th>Email</th>
              <th>Address</th>
              <th class="text-center" style="width: 100px;">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php $i = 1; foreach ($all_customers as $customer): ?>
              <tr>
                <td class="text-center"><?php echo $i++; ?></td>
                <td><?php echo remove_junk($customer['name']); ?></td>
                <td><?php echo remove_junk($customer['phone']); ?></td>
                <td><?php echo remove_junk($customer['email']); ?></td>
                <td><?php echo remove_junk($customer['address']); ?></td>
                <td class="text-center">
                  <div class="btn-group">
                    <a href="edit_customer.php?id=<?php echo (int)$customer['id'];?>" class="btn btn-xs btn-warning" title="Edit">
                      <span class="glyphicon glyphicon-edit"></span>
                    </a>
                    <a href="delete_customer.php?id=<?php echo (int)$customer['id'];?>" class="btn btn-xs btn-danger" title="Delete">
                      <span class="glyphicon glyphicon-trash"></span>
                    </a>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?php include_once('layouts/footer.php'); ?>
*/