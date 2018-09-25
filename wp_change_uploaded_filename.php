<?php
############################################
#	Change the uploaded filename in wp
############################################
// file name change
$rand_num = rand(1, 100000);	// generate random numbers
$rand_again = rand(2, 100000);
$filename = $current_date.'_'.$rand_num.'_'.$rand_again.'.'.$ext;		// change the filename
$wp_filetype = wp_check_filetype( basename($filename), null );
$wp_upload_dir = wp_upload_dir();

// Move the uploaded file into the WordPress uploads directory
move_uploaded_file( $data["xls_file"]["tmp_name"], $wp_upload_dir['path']  . '/' . $filename );

$attachment = array(
	'guid' => $wp_upload_dir['url'] . '/' . basename( $filename ), 
	'post_mime_type' => $wp_filetype['type'],
	'post_title' => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
	'post_content' => '',
	'post_status' => 'inherit'
);

$filename = $wp_upload_dir['path']  . '/' . $filename;

$attach_id = wp_insert_attachment( $attachment, $filename, 37 );
require_once( ABSPATH . 'wp-admin/includes/image.php' );
$attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
wp_update_attachment_metadata( $attach_id, $attach_data );  
?>
