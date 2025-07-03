<?php
  $page_title = 'All Image';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
?>
<?php $media_files = find_all('media');?>
<?php
  if(isset($_POST['submit'])) {
  $photo = new Media();
  $photo->upload($_FILES['file_upload']);
    if($photo->process_media()){
        $session->msg('s','photo has been uploaded.');
        redirect('media.php');
    } else{
      $session->msg('d',join($photo->errors));
      redirect('media.php');
    }

  }

?>
<?php include_once('layouts/header.php'); ?>
     <div class="row">
        <div class="col-md-6">
          <?php echo display_msg($msg); ?>
        </div>

      <div class="col-md-12">
        <div class="panel panel-default">
          <div class="panel-heading clearfix">
            <span class="glyphicon glyphicon-camera"></span>
            <span>All Photos</span>
            <div class="pull-right">


               </div>
              </div>
             </form>
            </div>
          </div>
          <div class="panel-body">
            <table class="table">
              <thead>
                <tr>
                  <th class="text-center" style="width: 50px;">#</th>
                  <th class="text-center">Photo</th>
                  <th class="text-center">Photo Name</th>
                  <th class="text-center" style="width: 20%;">Photo Type</th>
                  <th class="text-center" style="width: 50px;">Actions</th>
                </tr>
              </thead>
                <tbody>
                <?php foreach ($media_files as $media_file): ?>
                <tr class="list-inline">
                 <td class="text-center"><?php echo count_id();?></td>
                  <td class="text-center">
                      <img src="uploads/products/<?php echo $media_file['file_name'];?>" class="img-thumbnail" />
                  </td>
                <td class="text-center">
                  <?php echo $media_file['file_name'];?>
                </td>
                <td class="text-center">
                  <?php echo $media_file['file_type'];?>
                </td>
               </tr>
              <?php endforeach;?>
            </tbody>
          </div>
        </div>
      </div>
</div>


<?php include_once('layouts/footer.php'); ?>
