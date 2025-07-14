<?php
  $page_title = 'Edit Product';
  require_once('includes/load.php');
  page_require_level(2);

  $product = find_by_id('products', (int)$_GET['id']);
  $all_categories = find_all('categories');

  if (!$product) {
    $session->msg("d", "Missing product id.");
    redirect('product.php');
  }

  if (isset($_POST['edit_product'])) {
    $req_fields = array('name', 'description', 'categorie_id', 'selling_price', 'stock');
    validate_fields($req_fields);

    if (empty($errors)) {
      $name         = remove_junk($db->escape($_POST['name']));
      $description  = remove_junk($db->escape($_POST['description']));
      $category_id  = (int)$db->escape($_POST['categorie_id']);
      $selling_price = (float)$db->escape($_POST['selling_price']);
      $stock        = (int)$db->escape($_POST['stock']);

      // Jika upload gambar baru
      $image_name = $product['image'];
      if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $file_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image_name = uniqid() . "." . $file_ext;
        move_uploaded_file($_FILES['image']['tmp_name'], "uploads/products/" . $image_name);
      }

      $query  = "UPDATE products SET ";
      $query .= "name='{$name}', description='{$description}', categorie_id={$category_id}, ";
      $query .= "selling_price='{$selling_price}', stock={$stock}, image='{$image_name}' ";
      $query .= "WHERE id='{$product['id']}'";

      if ($db->query($query)) {
        $session->msg('s', "Product updated successfully.");
        redirect('product.php', false);
      } else {
        $session->msg('d', 'Failed to update product.');
        redirect('edit_product.php?id=' . $product['id'], false);
      }
    } else {
      $session->msg("d", $errors);
      redirect('edit_product.php?id=' . $product['id'], false);
    }
  }

  include_once('layouts/header.php');
?>

<div class="row">
  <div class="col-md-9">
    <?php echo display_msg($msg); ?>
    <div class="panel panel-default">
      <div class="panel-heading"><strong>Edit Product</strong></div>
      <div class="panel-body">
        <form method="post" action="edit_product.php?id=<?php echo (int)$product['id'] ?>" enctype="multipart/form-data">
          <div class="form-group">
            <label for="name">Product Name</label>
            <input type="text" name="name" class="form-control" value="<?php echo remove_junk($product['name']); ?>" required>
          </div>

          <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" class="form-control" rows="3" required><?php echo remove_junk($product['description']); ?></textarea>
          </div>

          <div class="form-group">
            <label for="categorie_id">Category</label>
            <select name="categorie_id" class="form-control" required>
              <option value="">Select a category</option>
              <?php foreach ($all_categories as $cat): ?>
                <option value="<?php echo (int)$cat['id']; ?>" <?php if ($product['categorie_id'] == $cat['id']) echo "selected"; ?>>
                  <?php echo remove_junk($cat['name']); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-group">
            <label for="selling_price">Selling Price (Rp)</label>
            <input type="number" step="0.01" name="selling_price" class="form-control" value="<?php echo remove_junk($product['selling_price']); ?>" required>
          </div>

          <div class="form-group">
            <label for="stock">Stock</label>
            <input type="number" name="stock" class="form-control" value="<?php echo remove_junk($product['stock']); ?>" required>
          </div>

          <div class="form-group">
            <label for="image">Current Image:</label><br>
            <?php if (!empty($product['image'])): ?>
              <img src="uploads/products/<?php echo remove_junk($product['image']); ?>" class="img-thumbnail" width="150"><br><br>
            <?php else: ?>
              <em>No image uploaded.</em><br><br>
            <?php endif; ?>
            <label for="image">Upload New Image (optional)</label>
            <input type="file" name="image" class="form-control" accept="image/*">
          </div>

          <button type="submit" name="edit_product" class="btn btn-success">Update Product</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>
