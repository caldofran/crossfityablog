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

function box_updated_messages( $messages ) {
	global $post, $post_ID;
	$messages['box'] = array(
		0 => '', 
		1 => sprintf( __('Box updated. <a href="%s">View box</a>'), esc_url( get_permalink($post_ID) ) ),
		2 => __('Custom field updated.'),
		3 => __('Custom field deleted.'),
		4 => __('Box updated.'),
		5 => isset($_GET['revision']) ? sprintf( __('Box restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => sprintf( __('Box published. <a href="%s">View box</a>'), esc_url( get_permalink($post_ID) ) ),
		7 => __('Box saved.'),
		8 => sprintf( __('Box submitted. <a target="_blank" href="%s">Preview box</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
		9 => sprintf( __('Box scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview box</a>'), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
		10 => sprintf( __('Box draft updated. <a target="_blank" href="%s">Preview box</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
	);
	return $messages;
}

add_filter( 'post_updated_messages', 'box_updated_messages' );

?>