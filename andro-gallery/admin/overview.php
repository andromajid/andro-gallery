<?php
global $wpdb;
global $andro_gallery_table;

if(isset($_POST['galleryId'])) {
	$wpdb->query( "DELETE FROM $andro_gallery_table WHERE gid = '".$_POST['galleryId']."'" );
	$wpdb->query( "DELETE FROM $andro_gallery_table WHERE Id = '".$_POST['galleryId']."'" );
		
	?>  
	<div class="updated"><p><strong><?php _e('Gallery has been deleted.' ); ?></strong></p></div>  
	<?php	
}

$galleryResults = $wpdb->get_results( "SELECT * FROM $andro_gallery_table" );
?>
<div class='wrap'>
	<h2>Andro Gallery</h2>
    <p>This is a listing of all galleries.</p>
    <table class="widefat post fixed" cellspacing="0">
    	<thead>
        <tr>
        	<th>Gallery Name</th>
            <th>Gallery Short Code</th>
            <th>Description</th>
            <th width="136"></th>
        </tr>
        </thead>
        <tfoot>
        <tr>
        	<th>Gallery Name</th>
            <th>Gallery Short Code</th>
            <th>Description</th>
            <th></th>
        </tr>
        </tfoot>
        <tbody>
        	<?php foreach($galleryResults as $gallery) { ?>				
            <tr>
            	<td><?php echo $gallery->gallery_name; ?></td>
                <td><input type="text" size="40" value="[andro_gallery id='<?php echo $gallery->gallery_slug; ?>']" /></td>
                <td><?php echo $gallery->description; ?></td>
                <td class="major-publishing-actions">
                <form name="delete_gallery_<?php echo $gallery->gallery_id; ?>" method ="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
                	<input type="hidden" name="galleryId" value="<?php echo $gallery->gallery_id; ?>" />
                    <input type="submit" name="Submit" class="button-primary" value="Delete Gallery" />
                </form>
                </td>
            </tr>
			<?php } ?>
        </tbody>
     </table>
</div>