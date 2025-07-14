<?php
  $page_title = 'All Supplier';
  require_once('includes/load.php');
  page_require_level(2);
  $all_suppliers = find_all('suppliers');
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
          <span class="glyphicon glyphicon-briefcase"></span>
          <span>Suppliers</span>
        </strong>
        <!-- Tombol Add New Supplier dihapus -->
      </div>
      <div class="panel-body">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th class="text-center" style="width: 50px;">#</th>
              <th>Supplier Name</th>
              <th>Phone Number</th>
              <th>Email</th>
              <th>Address</th>
              <!-- Kolom Actions dihapus -->
            </tr>
          </thead>
          <tbody>
            <?php $i = 1; foreach ($all_suppliers as $supplier): ?>
              <tr>
                <td class="text-center"><?php echo $i++; ?></td>
                <td><?php echo remove_junk($supplier['supplier_name']); ?></td>
                <td><?php echo remove_junk($supplier['mobile_phone']); ?></td>
                <td><?php echo remove_junk($supplier['email']); ?></td>
                <td><?php echo remove_junk($supplier['address']); ?></td>
                <!-- Kolom Actions dihapus -->
              </tr>
            <?php endforeach; ?>
            <?php if (count($all_suppliers) === 0): ?>
              <tr>
                <td colspan="5" class="text-center">No supplier data found.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>
