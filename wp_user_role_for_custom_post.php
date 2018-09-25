<?php
/*---------------------------------------
#			 Job custom post type
----------------------------------------*/
$labels = array(
	'name'               => _x( 'Jobs', 'post type general name', 'vinculum' ),
	'singular_name'      => _x( 'Job', 'post type singular name', 'vinculum' ),
	'menu_name'          => _x( 'Job Board', 'admin menu', 'vinculum' ),
	'name_admin_bar'     => _x( 'Job Board', 'add new on admin bar', 'vinculum' ),
	'add_new'            => _x( 'Add Jobs', 'New', 'vinculum' ),
	'add_new_item'       => __( 'Add New Job', 'vinculum' ),
	'new_item'           => __( 'New Job', 'vinculum' ),
	'edit_item'          => __( 'Edit Job', 'vinculum' ),
	'view_item'          => __( 'View Job', 'vinculum' ),
	'all_items'          => __( 'All Jobs', 'vinculum' ),
	'search_items'       => __( 'Search Jobs', 'vinculum' ),
	'parent_item_colon'  => __( 'Parent Job:', 'vinculum' ),
	'not_found'          => __( 'No Jobs found.', 'vinculum' ),
	'not_found_in_trash' => __( 'No Jobs found in Trash.', 'vinculum' )
);

$args = array(
	'labels'             => $labels,
	'description'        => __( 'Description.', 'vinculum' ),
	'public'             => true,
	'publicly_queryable' => true,
	'show_ui'            => true,
	'show_in_menu'       => true,
	'query_var'          => true,
	'rewrite'            => array( 'slug' => 'job' ),
	// 'capability_type'    => 'post',
	'has_archive'        => true,
	'hierarchical'       => false,
	'menu_position'      => null,
	'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt' ),
	'capability_type'     => array('vin_job','vin_jobs'),
	'map_meta_cap'        => true,
);
register_post_type( 'job', $args );

// labels for jobs category
$labels = array(
	'name'              => _x( 'Job Categories', 'vinculum' ),
	'singular_name'     => _x( 'Job Category', 'vinculum' ),
	'search_items'      => __( 'Search Jobs Categories' ),
	'all_items'         => __( 'All Job Categories' ),
	'parent_item'       => __( 'Parent Job Category' ),
	'parent_item_colon' => __( 'Parent Job Category:' ),
	'edit_item'         => __( 'Edit Job Category' ), 
	'update_item'       => __( 'Update Job Category' ),
	'add_new_item'      => __( 'Add New Job Category' ),
	'new_item_name'     => __( 'New Job Category' ),
	'menu_name'         => __( 'Job Categories' ),
);

$args = array(
	'labels' => $labels,
	'hierarchical' => true,
	'capabilities' => array(
		'edit_terms' => 'manage_options',
		'assign_terms' => 'edit_vin_job',
	)
);
register_taxonomy( 'jobs', 'job', $args );

/*-----------------------------------------------------
#	new user role for the JOB post type (functions.php)
-------------------------------------------------------- */

function vin_add_job_management_role() {
 add_role('vin_job_manager',
            'Job Manager',
            array(
                'read' => true,
                'edit_posts' => false,
                'delete_posts' => false,
                'publish_posts' => false,
                'upload_files' => true,
            )
        );
   }
add_action('admin_init', 'vin_add_job_management_role' );


add_action('admin_init','vin_add_role_caps',999);
function vin_add_role_caps() {

	// Add the roles you'd like to administer the custom post types
	$roles = array('vin_job_manager','editor','administrator');
	
	// Loop through each role and assign capabilities
	foreach($roles as $the_role) { 
		 $role = get_role($the_role);
		 $role->add_cap( 'read' );
		 $role->add_cap( 'read_vin_job');
		 $role->add_cap( 'read_private_vin_jobs' );
		 $role->add_cap( 'edit_vin_job' );
		 $role->add_cap( 'edit_vin_jobs' );
		 $role->add_cap( 'edit_others_vin_jobs' );
		 $role->add_cap( 'edit_published_vin_jobs' );
		 $role->add_cap( 'publish_vin_jobs' );
		 $role->add_cap( 'delete_others_vin_jobs' );
		 $role->add_cap( 'delete_private_vin_jobs' );
		 $role->add_cap( 'delete_published_vin_jobs' );
	
	}
}
?>
