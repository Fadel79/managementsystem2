<?php
  $page_title = 'All Customers';
  require_once('includes/load.php');
  page_require_level(2);

  $all_customers = find_all('customers');

  include_once('layouts/header.php');
?>

<div class="row">
  <div class="col-md-12"><?php echo display_msg($msg); ?></div>
</div>
<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <strong><span class="glyphicon glyphicon-user"></span> Customers</strong>
        <a href="add_customer.php" class="btn btn-info pull-right btn-sm">Add New Customer</a>
      </div>
      <div class="panel-body">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th class="text-center" style="width: 50px;">#</th>
              <th>Name</th>
              <th>Phone</th>
              <th>Email</th>
              <th>Address</th>
              <th class="text-center" style="width: 100px;">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php $i = 1; foreach ($all_customers as $customer): ?>
              <tr>
                <td class="text-center"><?php echo $i++; ?></td>
                <td><?php echo remove_junk($customer['customer_name']); ?></td>
                <td><?php echo remove_junk($customer['mobile_phone']); ?></td>
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
            <?php if (count($all_customers) === 0): ?>
              <tr><td colspan="6" class="text-center">No customer data found.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?php include_once('layouts/footer.php'); ?>
