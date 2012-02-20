<?php
global $wpdb;
global $andro_gallery_table;
global $andro_gallery_image_table;

$galleryResults = $wpdb->get_results( "SELECT * FROM $andro_gallery_table" );

//Select gallery
if(isset($_POST['select_gallery']) || isset($_POST['gallery_id'])) {
	$gid = (isset($_POST['select_gallery'])) ? $_POST['select_gallery'] : $_POST['gallery_id'];
	$imageResults = $wpdb->get_results( "SELECT * FROM $andro_gallery_image_table WHERE gallery_image_gallery_id = $gid ORDER BY gallery_image_sort_order ASC" );
	$gallery = $wpdb->get_row( "SELECT * FROM $andro_gallery_table WHERE gallery_Id = $gid" );
}
	
if(isset($_POST['andro_edit_gallery']))
{
	if($_POST['gallery_name'] != "") {
	  $galleryName = $_POST['gallery_name'];
	  $slug = strtolower(str_replace(" ", "", $_POST['gallery_name']));
	  
	  
	  
	  if(isset($_POST['andro_edit_gallery'])) {
		  $imageEdited = $wpdb->update( $andro_gallery_table, array( 'gallery_name' => $galleryName, 'gallery_slug' => $slug), array( 'gallery_id' => $_POST['andro_edit_gallery'] ) );
			  
			  ?>  
			  <div class="updated"><p><strong><?php _e('Gallery has been edited.' ); ?></strong></p></div>  
			  <?php
	  }
	}}
?>
<div class='wrap'>
	<h2>Andro Gallery - Edit Galleries</h2>
    <?php if(!isset($_POST['select_gallery']) && !isset($_POST['gallery_id'])) { ?>
    <p>Select a galley</p>		
    <form name="gallery" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
    	<select name="select_gallery" onchange="gallery.submit()">
        	<option> - SELECT A GALLERY - </option>
			<?php
				foreach($galleryResults as $gallery) {
					?><option value="<?php echo $gallery->gallery_id; ?>"><?php echo $gallery->gallery_name; ?></option>
                <?php
				}
			?>
        </select>
    </form>
    <?php } else if(isset($_POST['select_gallery']) || isset($_POST['galleryId'])) { ?>    
    <h3>Gallery: <?php echo $gallery->gallery_name; ?></h3>
    <form name="switch_gallery" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
    <input type="hidden" name="switch" value="true" />
    <p><input type="submit" name="Submit" class="button-primary" value="Switch Gallery" /></p>
    </form>
	
    <p>This is where you can edit existing galleries.</p>
    
    <form name="hcg_add_gallery_form" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>" method="post">
    <input type="hidden" name="andro_edit_gallery" value="<?php echo $gid; ?>" />
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
                <td><input type="text" size="30" name="gallery_name" value="<?php echo $gallery->gallery_name; ?>" /></td>
                <td>This name is the internal name for the gallery.<br />Please avoid non-letter characters such as ', ", *, etc.</td>
            </tr>
            <tr>
            	<td class="major-publishing-actions"><input type="submit" name="Submit" class="button-primary" value="Edit Gallery" /></td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
	</table>
    </form>
    <?php } ?>
</div>