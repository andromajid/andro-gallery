<?php
$galleryAdded = FALSE;
if (isset($_POST['andro_add_gallery'])) {
  if ($_POST['gallery_name'] != "") {
    $galleryName = $_POST['gallery_name'];
    $slug = strtolower(str_replace(" ", "", $_POST['gallery_name']));

    global $wpdb;
    global $andro_gallery_table;

    $galleryAdded = $wpdb->insert($andro_gallery_table, array('gallery_name' => $galleryName, 'gallery_slug' => $slug));
    //var_dump($galleryAdded);
    if ($galleryAdded) {
      ?>  
      <div class="updated"><p><strong><?php _e('Gallery Added.'); ?></strong></p></div>  
      <?php
    }
  } else {
    ?>  
    <div class="updated"><p><strong><?php _e('Please enter a gallery name.'); ?></strong></p></div>  
    <?php
  }
}
?>
<div class='wrap'>
  <h2>Andro Gallery - Add Galleries</h2>
<?php
if ($galleryAdded) {
  ?>
    <div class="updated"><p>Copy and paste this code into the page or post that you would like to display the gallery.</p>
      <p><input type="text" name="galleryCode" value="[andro_gallery id='<?php echo $slug; ?>']" size="40" /></p></div>
<?php
} else {
  ?>
    <p>This is where you can create new galleries. Once the new gallery has been added, a short code will be provided for use in posts.</p>
  <?php } ?>

  <form name="hcg_add_gallery_form" action="<?php echo str_replace('%7E', '~', $_SERVER['REQUEST_URI']); ?>" method="post">
    <input type="hidden" name="andro_add_gallery" value="true" />
    <table class="widefat post fixed" cellspacing="0">
      <thead>
        <tr>
          <th width="250">Field Name</th>
          <th>Entry</th>
          <th>Description</th>
        </tr>
      </thead>
      <tfoot>
        <tr>
          <th>Field Name</th>
          <th>Entry</th>
          <th>Description</th>
        </tr>
      </tfoot>
      <tbody>
        <tr>
          <td><strong>Enter Gallery Name:</strong></td>
          <td><input type="text" size="30" name="gallery_name" value="<?php echo isset($galleryName)?$galleryName:""; ?>" /></td>
          <td>This name is the internal name for the gallery.<br />Please avoid non-letter characters such as ', ", *, etc.</td>
        </tr>
        <tr>
          <td class="major-publishing-actions"><input type="submit" name="Submit" class="button-primary" value="Add Gallery" /></td>
          <td></td>
          <td></td>
        </tr>
      </tbody>
    </table>
  </form>