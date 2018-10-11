<?php
/*#########   HTML FORM   ############*/
?>
<p class="success_msg"></p><p class="error_msg"></p>
<form action="" method="post" class="form-horizontal" id="myform" enctype="multipart/form-data">
			<div class="form-group col-md-6">
					<input type="text" name="name" id="name" placeholder="NAME" class="form-control">
			</div>
      <div class="form-group col-md-6">
					<input type="text" name="email" id="email" placeholder="E-mail" class="form-control">
			</div>
      <div class="form-group col-md-6">
					<input type="file" name="att_resume" id="att_resume" placeholder="RESUME/CV Attach" class="form-control">
			</div>
      <button type="submit" class="but-send-apply" id="job_apply">Apply <i class="fa fa-spinner fa-spin spin_icon" style="display:none"></i></button>
      <?php wp_nonce_field( 'resume-upload-nonce', 'resume_security' ); ?>
</form>

<?php 
/*###################   Jquery    ##################*/
?>
<script>
jQuery('form#myform').validate({
	rules : {
		name:{
			required: true,
			lettersonly: true
		},
		email: { required: true},
		phone:{
			required: true,
			number_spe: true,
			minlength: 10,
		},
		exp:{ required: true},
		ctc:{ required: true},
		exp_ctc:{ required: true},
		notice:{ required: true},
		location:{ required: true},
		att_resume:{ required: true	}
	}
});
jQuery('form#myform').submit(function(e){
	e.preventDefault();
	var formData = new FormData(this);
	formData.append('action', 'job_apply_callback');
		
	if (jQuery(this).valid()) {
		jQuery('#myform .spin_icon').show();	// show the process icon
		jQuery.ajax({
			type: 'post',
			dataType: 'json',
			url: ajax_object.ajax_url,
			cache: false,
			contentType: false,
			processData: false,
			data: formData,
			success: function(data) { 
				jQuery('#myform .spin_icon').hide();	// stop the process icon
				if(data.res == true){
					jQuery('.success_msg').html(data.message);	// show the success message
					jQuery('.error_msg').hide();	// hide error msg
					jQuery('#myform').hide();
				}else{
					jQuery('.error_msg').show().html(data.message);	// show error msg
				}
				$('#myform').trigger('reset');		// reset the form
			}
		});
	}
});
</script>
<?php
/*############    Ajax Script   ##############*/

// Apply for a job
add_action('wp_ajax_job_apply_callback','job_apply_callback');
add_action('wp_ajax_nopriv_job_apply_callback', 'job_apply_callback');
function job_apply_callback(){
	global $wpdb;
	
	// First check the nonce, if it fails the function will break
    check_ajax_referer( 'resume-upload-nonce', 'resume_security' );
	
	//required files for the image upload
	require_once(ABSPATH . 'wp-admin/includes/image.php');
	require_once(ABSPATH . 'wp-admin/includes/file.php');
	require_once(ABSPATH . 'wp-admin/includes/media.php');
	
	$posted_data =  isset( $_POST ) ? $_POST : array();
	$file_data = isset( $_FILES ) ? $_FILES : array();
	$data = array_merge( $posted_data, $file_data );
	
	$name = sanitize_text_field($data['name']);
	$email = sanitize_email($data['email']);
		
	$current_date = date('d-m-Y H:m:s');
	$filename = $data["att_resume"]["name"];
	$filetype = $data["att_resume"]["type"];
	$fileNameChanged = str_replace(" ", "_", $filename);
	$temp_name = $data["att_resume"]["tmp_name"];
	$file_size = $data["att_resume"]["size"];
	$fileError = $data["att_resume"]["error"];
	// get file extension
	$ext = pathinfo($filename, PATHINFO_EXTENSION);
	// check the file size
	$mb = 2 * 1024 * 1024;
	if($file_size > $mb){
		echo json_encode(array('res'=>false, 'message'=>__('File is too large. Max file size is 2 MB.')));
		exit;
	}
	if ($fileError !== UPLOAD_ERR_OK) {
		echo json_encode(array('res'=>false, 'message'=>__('There is something wrong or file type not supported')));
		exit;
	}else{
		// check the file extention
		if($ext == 'docs' || $ext == 'doc' || $ext == 'pdf'){
			// change the file name 
			$filename = $name.'_'.$current_date.'.'.$ext;		
			$wp_filetype = wp_check_filetype( basename($filename), null );
			$wp_upload_dir = wp_upload_dir();

			// Move the uploaded file into the WordPress uploads directory
			move_uploaded_file( $data["att_resume"]["tmp_name"], $wp_upload_dir['path']  . '/' . $filename );
			// set the parameters for the email
			$params = array(
				'name' => $name, 
				'email' => $email, 
				'att_resume' => $wp_upload_dir['path']  . '/' . $filename,
			);
			// call the mail tempalte
			job_apply_form_mail_template($params);
			echo json_encode(array('res'=>true, 'message'=>__('Thanks for sharing the details. We will contact you shortly!')));
		}else{ 
			echo json_encode(array('res'=>false, 'message'=>__('File type not supported.')));
			exit;
		}
	}
	unlink($filename);	// delete the resume from server
	wp_die();
}
?>

<?php
/*#################   Mail Template   ################*/

// job apply form mail template
function job_apply_form_mail_template($params = array()){
	$headers[] = "MIME-Version: 1.0" . "\r\n";
	$headers[] .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	$headers[] .= 'From: Sender <sendermail@domain.com>';
	$body = "<table border='0'><tbody>
			<tr><td colspan='2'>New job application </td></tr><tr><td colspan='2'>&nbsp;</td></tr>
			<tr><td><b>Name: </b></td><td>".$params['name']."</td></tr>
			<tr><td><b>E-mail: </b></td><td>".$params['email']."</td></tr>
			<tr><td></td><td></td></tr>
			<tr><td><br/><br/>Thanks,</td><td></td><td></td></tr>
			</tbody></table>";
	$to = 'yourmail@domain.com';
	$subject = 'New Job Application'
	wp_mail($to, $subject, $body, $headers, $params['att_resume']);
}
?>
