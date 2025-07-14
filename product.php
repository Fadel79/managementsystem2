<?php
  $page_title = 'All Products';
  require_once('includes/load.php');
  page_require_level(2);

  // Fungsi untuk mengambil semua produk beserta kategori dan gambar
function find_all_products_with_category() {
  global $db;
  $sql  = "SELECT p.id, p.name, p.description, p.selling_price, p.stock, ";
  $sql .= "c.name AS category_name, p.image "; // Ubah m.file_name => p.image
  $sql .= "FROM products p ";
  $sql .= "LEFT JOIN categories c ON p.categorie_id = c.id ";
  $sql .= "ORDER BY p.id DESC";
  return find_by_sql($sql);
}

  $products = find_all_products_with_category();

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
          <span class="glyphicon glyphicon-th-large"></span>
          <span>Products</span>
        </strong>
        <a href="add_product.php" class="btn btn-info pull-right btn-sm">Add New Product</a>
      </div>
      <div class="panel-body">
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th class="text-center" style="width: 50px;">#</th>
              <th>Product Name</th>
              <th>Category</th>
              <th>Selling Price</th>
              <th>Description</th>
              <th>Stock</th>
              <th>Image</th>
              <th class="text-center" style="width: 100px;">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($products as $product): ?>
              <tr>
                <td class="text-center"><?php echo count_id(); ?></td>
                <td><?php echo remove_junk($product['name']); ?></td>
                <td><?php echo remove_junk($product['category_name']); ?></td>
                <td><?php echo 'Rp. ' . number_format($product['selling_price'], 2, ',', '.'); ?></td>
                <td><?php echo remove_junk($product['description']); ?></td>
                <td><?php echo remove_junk($product['stock']); ?></td>
                <td>
                  <?php if (!empty($product['image'])): ?>
                    <img src="uploads/products/<?php echo $product['image']; ?>" class="img-avatar img-circle" width="50">
                  <?php else: ?>
                    <img src="uploads/products/no_image.png" class="img-avatar img-circle" width="50">
                  <?php endif; ?>
                </td>
                <td class="text-center">
                  <div class="btn-group">
                    <a href="edit_product.php?id=<?php echo (int)$product['id']; ?>" class="btn btn-xs btn-warning" data-toggle="tooltip" title="Edit">
                      <span class="glyphicon glyphicon-edit"></span>
                    </a>
                    <a href="delete_product.php?id=<?php echo (int)$product['id']; ?>" class="btn btn-xs btn-danger" data-toggle="tooltip" title="Delete">
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
