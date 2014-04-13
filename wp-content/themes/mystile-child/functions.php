<?php
// Custom functions to include
function custom_post_box() {
	$labels = array(
		'name'               => _x( 'Boxes', 'post type general name' ),
		'singular_name'      => _x( 'Box', 'post type singular name' ),
		'add_new'            => _x( 'Add New', 'box' ),
		'add_new_item'       => __( 'Add New Box' ),
		'edit_item'          => __( 'Edit Box' ),
		'new_item'           => __( 'New Box' ),
		'all_items'          => __( 'All Boxes' ),
		'view_item'          => __( 'View Box' ),
		'search_items'       => __( 'Search Boxes' ),
		'not_found'          => __( 'No boxes found' ),
		'not_found_in_trash' => __( 'No boxes found in the Trash' ), 
		'parent_item_colon'  => '',
		'menu_name'          => 'Boxes'
	);

	$args = array(
		'labels'        => $labels,
		'description'   => 'Holds our boxes and box specific data',
		'public'        => true,
		'menu_position' => 5,
		'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments' ),
		'has_archive'   => true,
	);

	register_post_type( 'box', $args );	
}

add_action( 'init', 'custom_post_box' );

?>