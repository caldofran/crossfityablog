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

function box_contextual_help( $contextual_help, $screen_id, $screen ) {
    if ( 'box' == $screen->id ) {

        $contextual_help = '<h2>Boxes</h2>
		<p>Boxes show the details of the items that we promote on the website. You can see a list of them on this page in reverse chronological order - the latest one we added is first.</p>
		<p>You can view/edit the details of each box by clicking on its name, or you can perform bulk actions using the dropdown menu and selecting multiple items.</p>';

    } elseif ( 'edit-box' == $screen->id ) {

        $contextual_help = '<h2>Editing boxes</h2>
		<p>This page allows you to view/modify box details. Please make sure to fill out the available boxes with the appropriate details (box image, price, contact info) and <strong>not</strong> add these details to the box description.</p>';

    }
    return $contextual_help;
}

add_action( 'contextual_help', 'box_contextual_help', 10, 3 );

function taxonomies_box() {
    $labels = array(
        'name'              => _x( 'City', 'taxonomy general name' ),
        'singular_name'     => _x( 'City', 'taxonomy singular name' ),
        'search_items'      => __( 'Search Cities' ),
        'all_items'         => __( 'All Cities' ),
        'parent_item'       => __( 'Parent City' ),
        'parent_item_colon' => __( 'Parent City' ),
        'edit_item'         => __( 'Edit City' ),
        'update_item'       => __( 'Update City' ),
        'add_new_item'      => __( 'Add New City' ),
        'new_item_name'     => __( 'New City' ),
        'menu_name'         => __( 'Cities' ),
    );
    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
    );
    register_taxonomy( 'city', 'box', $args );
}

add_action( 'init', 'taxonomies_box', 0 );

function box_price_box() {
    add_meta_box(
        'box_price_box',
        __( 'Box Price', 'myplugin_textdomain' ),
        'box_price_box_content',
        'box',
        'side',
        'high'
    );
}

function box_price_box_content( $post ) {
    wp_nonce_field( plugin_basename( __FILE__ ), 'box_price_box_content_nonce' );
    echo '<label for="box_price"></label>';
    echo '<input type="text" id="box_price" name="box_price" placeholder="enter a price" />';
}

add_action( 'add_meta_boxes', 'box_price_box' );

function box_price_box_save( $post_id ) {

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
        return;

    if ( !wp_verify_nonce( $_POST['box_price_box_content_nonce'], plugin_basename( __FILE__ ) ) )
        return;

    if ( 'page' == $_POST['post_type'] ) {
        if ( !current_user_can( 'edit_page', $post_id ) )
            return;
    } else {
        if ( !current_user_can( 'edit_post', $post_id ) )
            return;
    }
    $box_price = $_POST['box_price'];
    update_post_meta( $post_id, 'box_price', $box_price );
}

add_action( 'save_post', 'box_price_box_save' );

?>