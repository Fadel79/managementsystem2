<?php
  $page_title = 'Add Product';
  require_once('includes/load.php');
  page_require_level(1);

  $all_categories = find_all('categories');
  $all_media = find_all('media');

  if (isset($_POST['add_product'])) {
    $req_fields = array('name', 'description', 'categorie_id', 'selling_price', 'stock', 'media_id');
    validate_fields($req_fields);

    if (empty($errors)) {
      $name         = remove_junk($db->escape($_POST['name']));
      $description  = remove_junk($db->escape($_POST['description']));
      $category_id  = (int)$db->escape($_POST['categorie_id']);
      $selling_price = (float)$db->escape($_POST['selling_price']);
      $stock        = (int)$db->escape($_POST['stock']);
      $media_id     = (int)$db->escape($_POST['media_id']);

      $query  = "INSERT INTO products (name, description, categorie_id, selling_price, stock, media_id, date) ";
      $query .= "VALUES ('{$name}', '{$description}', {$category_id}, '{$selling_price}', {$stock}, {$media_id}, NOW())";

      if ($db->query($query)) {
        $session->msg('s', "Product added successfully.");
        redirect('product.php', false);
      } else {
        $session->msg('d', 'Failed to add product.');
        redirect('add_product.php', false);
      }
    } else {
      $session->msg("d", $errors);
      redirect('add_product.php', false);
    }
  }

  include_once('layouts/header.php');
?>

<div class="row">
  <div class="col-md-9">
    <?php echo display_msg($msg); ?>
    <div class="panel panel-default">
      <div class="panel-heading"><strong>Add New Product</strong></div>
      <div class="panel-body">
        <form method="post" action="add_product.php">
          <div class="form-group">
            <label for="name">Product Name</label>
            <input type="text" name="name" class="form-control" required>
          </div>

          <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" class="form-control" rows="3" required></textarea>
          </div>

          <div class="form-group">
            <label for="categorie_id">Category</label>
            <select name="categorie_id" class="form-control" required>
              <option value="">Select a category</option>
              <?php foreach ($all_categories as $cat): ?>
                <option value="<?php echo (int)$cat['id']; ?>">
                  <?php echo remove_junk($cat['name']); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-group">
            <label for="selling_price">Selling Price (Rp)</label>
            <input type="number" step="0.01" name="selling_price" class="form-control" required>
          </div>

          <div class="form-group">
            <label for="stock">Stock</label>
            <input type="number" name="stock" class="form-control" required>
          </div>

          <div class="form-group">
            <label for="media_id">Image</label>
            <select name="media_id" class="form-control" required>
              <option value="">Select image</option>
              <?php foreach ($all_media as $media): ?>
                <option value="<?php echo (int)$media['id']; ?>">
                  <?php echo remove_junk($media['file_name']); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <button type="submit" name="add_product" class="btn btn-primary">Add Product</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>