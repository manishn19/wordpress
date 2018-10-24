<?php
/*
	Plugin Name: Contact Form
	Author: Manish Negi
	Description: Contact form.
	Version: 1.0
	Author URI: https://www.facebook.com/PHP-discussion-with-HTML-CSS-JS-217494234928060/
	Text Domain: contact-form
	Licence: GPL
*/
$textdomain = 'contact-form';

/* function my_add_menu_items(){
  $hook = add_menu_page( __( 'Contact Form', $textdomain ), 'Contact Form', 'manage_options', 'contact-form', 'contact_form_callback');
} */
function form_html_callback(){
	include('form.php');
}
function form_validation($name, $email, $phone)  { 
	global $error;
	$error = new WP_Error;
	// empty field validation
	if(empty($name) || empty($email) || empty($phone)){
		$error->add('field', 'Please fill the required fields');
	}
	// email validation
	if(!is_email($email)){
		$error->add('valid_email', 'E-mail is not valid');
	}
	// phone field validation
	if(12 >= strlen($phone) ){
		$error->add('phone_len', 'Phone number must be 10 digit');
	}
	
	if ( is_wp_error( $error ) ) {
		foreach ( $error->get_error_messages() as $err ) {
			echo '<div>';
			echo '<strong>ERROR</strong>:';
			echo $err . '<br/>';
			echo '</div>';
		}
	}
}
function complete_reg_callback(){
	global $error;
	if ( 1 > count( $error->get_error_messages() ) ) {
		echo 'Thankyou, for your interest!';
	}
}
function custom_form_callback(){
	if ( isset($_POST['submit'] ) ) { 
		global $name, $email, $phone;
		$name = sanitize_text_field($_POST['cfname']);
		$email = sanitize_email($_POST['cfemail']);
		$phone = sanitize_text_field($_POST['cfphone']);
		// form validation
		form_validation($name, $email, $phone);
		// form completed
		complete_reg_callback($name, $email, $phone);
	}
	form_html_callback();
}

// Register a new shortcode: [cr_custom_registration]
add_shortcode( 'cr_custom_registration', 'custom_form_shortcode' );
 
// The callback function that will replace [book]
function custom_form_shortcode() {
    ob_start();
    custom_form_callback();
	wp_enqueue_script( 'form-script', plugin_dir_url( __FILE__ ) . '/form-validation.js', array('jquery'), '0.1', true );
    return ob_get_clean();
}
