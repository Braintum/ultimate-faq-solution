<?php
/**
 * Plugin Name: Ultimate FAQ Solution
 * Description: This file contains the custom post type and related functionality for managing FAQs.
 * Author: Your Name
 * Version: 1.0
 *
 * @package UltimateFAQSolution
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Register the custom post type for FAQs.
 *
 * This function registers a custom post type called 'ufaqsw' for managing FAQs.
 * It sets up labels, arguments, and other properties for the post type.
 */
function ufaqsw_register_cpt() {

	$ufaqsw_list_labels = array(
		'name'               => 'Manage FAQ Groups',
		'singular_name'      => 'Manage FAQ Group',
		'add_new'            => 'New FAQ Group',
		'add_new_item'       => 'Add New FAQ Group',
		'edit_item'          => 'Edit FAQ Group',
		'new_item'           => 'New FAQ Group',
		'all_items'          => 'Manage FAQ Groups',
		'view_item'          => 'View FAQ Group',
		'search_items'       => 'Search FAQ Group',
		'not_found'          => 'No FAQ Group found',
		'not_found_in_trash' => 'No FAQ Group found in the Trash',
		'parent_item_colon'  => '',
		'menu_name'          => 'Ultimate FAQs',

	);

	$ufaqsw_list_args = array(
		'labels'              => $ufaqsw_list_labels,
		'description'         => 'This post type holds all FAQs for your site.',
		'menu_position'       => 25,
		'exclude_from_search' => true,
		'show_in_nav_menus'   => false,
		'supports'            => array( 'title', 'revisions' ),
		'has_archive'         => false,
		'menu_icon'           => '',
		'public'              => false,  // it's not public, it shouldn't have its own permalink, and so on.
		'publicly_queryable'  => false,  // you should be able to query it.
		'show_ui'             => true,  // you should be able to edit it in wp-admin.
		'rewrite'             => false,  // it shouldn't have rewrite rules.
		'menu_icon'           => 'dashicons-list-view',
		'show_in_rest'        => true,  // Enable support for the REST API.
		'rest_base'           => 'ufaqsw', // Optional custom REST API base slug.
	);

	register_post_type( 'ufaqsw', $ufaqsw_list_args );

}
add_action( 'init', 'ufaqsw_register_cpt' );

add_filter( 'post_row_actions', 'ufaqsw_remove_row_actions', 10, 1 );

/**
 * Removes the 'view' action link for the custom post type 'ufaqsw'.
 *
 * @param array $actions The existing row actions.
 * @return array Modified row actions without the 'view' link.
 */
function ufaqsw_remove_row_actions( $actions ) {
	if ( get_post_type() === 'ufaqsw' ) {
		unset( $actions['view'] );
	}
	return $actions;
}


add_action( 'cmb2_admin_init', 'ufaqsw_register_appearance_metabox' );
/**
 * Registers the appearance metabox for the custom post type 'ufaqsw'.
 *
 * This function adds fields to customize the appearance of FAQ groups,
 * including template selection, colors, font sizes, and icons.
 */
function ufaqsw_register_appearance_metabox() {

	$cmb_demo = new_cmb2_box(
		array(
			'id'           => 'ufaqsw_faq_conf',
			'title'        => esc_html__( 'Appearance', 'ufaqsw' ),
			'object_types' => array( 'ufaqsw' ), // Post type.
			'closed'       => true,
			'classes'      => 'extra-class',
		)
	);

	$cmb_demo->add_field(
		array(
			'name'    => esc_html__( 'Choose A Template', 'ufaqsw' ),
			'id'      => 'ufaqsw_template',
			'type'    => 'radio_inline',
			'options' => array(
				'default' => esc_html__( 'Default', 'ufaqsw' ),
				'style-1' => esc_html__( 'Style 1', 'ufaqsw' ),
				'style-2' => esc_html__( 'Style 2', 'ufaqsw' ),
			),
			'default' => 'default',
		)
	);

	$cmb_demo->add_field(
		array(
			'name' => esc_html__( 'Faq Group Title Color', 'ufaqsw' ),
			'id'   => 'ufaqsw_title_color',
			'desc' => esc_html__( 'Change the Group Title color', 'ufaqsw' ),
			'type' => 'colorpicker',
		)
	);

	$cmb_demo->add_field(
		array(
			'name' => esc_html__( 'Faq Group Title Font Size (ex: 30px)', 'ufaqsw' ),
			'desc' => esc_html__( 'Change the Group Title font size ex: 30px', 'ufaqsw' ),
			'id'   => 'ufaqsw_title_font_size',
			'type' => 'text_small',

		)
	);

	$cmb_demo->add_field(
		array(
			'name' => esc_html__( 'Question Color', 'ufaqsw' ),
			'id'   => 'ufaqsw_question_color',
			'desc' => esc_html__( 'Change the Question color', 'ufaqsw' ),
			'type' => 'colorpicker',

		)
	);

	$cmb_demo->add_field(
		array(
			'name' => esc_html__( 'Answer Color', 'ufaqsw' ),
			'id'   => 'ufaqsw_answer_color',
			'desc' => esc_html__( 'Change the Answer color', 'ufaqsw' ),
			'type' => 'colorpicker',

		)
	);

	$cmb_demo->add_field(
		array(
			'name' => esc_html__( 'Question Background Color', 'ufaqsw' ),
			'id'   => 'ufaqsw_question_background_color',
			'desc' => esc_html__( 'Change the Question Background color', 'ufaqsw' ),
			'type' => 'colorpicker',

		)
	);

	$cmb_demo->add_field(
		array(
			'name' => esc_html__( 'Answer Background Color', 'ufaqsw' ),
			'id'   => 'ufaqsw_answer_background_color',
			'desc' => esc_html__( 'Change the Answer Background color', 'ufaqsw' ),
			'type' => 'colorpicker',

		)
	);
	$cmb_demo->add_field(
		array(
			'name' => esc_html__( 'Question Font Size (ex: 20px)', 'ufaqsw' ),
			'desc' => esc_html__( 'Change the Question Font Size ex: 20px', 'ufaqsw' ),
			'id'   => 'ufaqsw_question_font_size',
			'type' => 'text_small',

		)
	);

	$cmb_demo->add_field(
		array(
			'name' => esc_html__( 'Answer Font Size (ex: 20px)', 'ufaqsw' ),
			'desc' => esc_html__( 'Change the Answer Font Size ex: 20px', 'ufaqsw' ),
			'id'   => 'ufaqsw_answer_font_size',
			'type' => 'text_small',

		)
	);

	$cmb_demo->add_field(
		array(
			'name' => esc_html__( 'Normal Icon', 'ufaqsw' ),
			'id'   => 'ufaqsw_normal_icon',
			'desc' => esc_html__( 'Change the default icon by clicking on the input box.', 'ufaqsw' ),
			'type' => 'text_medium',
		)
	);

	$cmb_demo->add_field(
		array(
			'name' => esc_html__( 'Active Icon', 'ufaqsw' ),
			'id'   => 'ufaqsw_active_icon',
			'desc' => esc_html__( 'Change the default icon by clicking on the input box.', 'ufaqsw' ),
			'type' => 'text_medium',
		)
	);

	$cmb_demo->add_field(
		array(
			'name'    => esc_html__( 'FAQ Behaviour', 'ufaqsw' ),
			'desc'    => esc_html__( 'Default behaviour is Toggle. You can change it to Accordion', 'ufaqsw' ),
			'id'      => 'ufaqsw_faq_behaviour',
			'type'    => 'select',
			'options' => array(
				'toggle'    => esc_html__( 'Toggle', 'ufaqsw' ),
				'accordion' => esc_html__( 'Accordion', 'ufaqsw' ),
			),
		)
	);

	$cmb_demo->add_field(
		array(
			'name'       => esc_html__( 'Show all Answers by Default', 'ufaqsw' ),
			'desc'       => esc_html__( 'Show all Answers by Default', 'ufaqsw' ),
			'id'         => 'ufaqsw_answer_showall',
			'type'       => 'checkbox',
			'attributes' => array(
				'data-conditional-id'    => 'ufaqsw_faq_behaviour',
				'data-conditional-value' => 'toggle',
			),
		)
	);

	$cmb_demo->add_field(
		array(
			'name' => esc_html__( 'Hide Group Title', 'ufaqsw' ),
			'desc' => esc_html__( 'Hide Group Title', 'ufaqsw' ),
			'id'   => 'ufaqsw_hide_title',
			'type' => 'checkbox',
		)
	);
}


add_action( 'cmb2_admin_init', 'ufaqsw_register_repeatable_group_field_metabox' );
/**
 * Registers a repeatable group field metabox for the custom post type 'ufaqsw'.
 *
 * This function adds a metabox to manage FAQs within a group, allowing users to add,
 * edit, and remove FAQ entries with fields for questions and answers.
 */
function ufaqsw_register_repeatable_group_field_metabox() {

	$cmb_group = new_cmb2_box(
		array(
			'id'           => 'ufaqsw_faq_items',
			'title'        => esc_html__( 'FAQs', 'ufaqsw' ),
			'object_types' => array( 'ufaqsw' ),
		)
	);

	$group_field_id = $cmb_group->add_field(
		array(
			'id'          => 'ufaqsw_faq_item01',
			'type'        => 'group',
			'description' => esc_html__( 'Add FAQ to this group by click on "Add FAQ Entry" Button', 'ufaqsw' ),
			'options'     => array(
				'group_title'    => esc_html__( 'FAQ {#}', 'ufaqsw' ), // {#} gets replaced by row number.
				'add_button'     => esc_html__( 'Add FAQ Entry', 'ufaqsw' ),
				'remove_button'  => esc_html__( 'Remove FAQ Entry', 'ufaqsw' ),
				'sortable'       => true,
				'closed'         => false, // true to have the groups closed by default.
				'remove_confirm' => esc_html__( 'Are you sure you want to remove the FAQ entry?', 'ufaqsw' ),
			),
		)
	);

	$cmb_group->add_group_field(
		$group_field_id,
		array(
			'name' => esc_html__( 'Question', 'ufaqsw' ),
			'id'   => 'ufaqsw_faq_question',
			'desc' => esc_html__( 'Write Your Question', 'ufaqsw' ),
			'type' => 'text_html',
		)
	);

	$cmb_group->add_group_field(
		$group_field_id,
		array(
			'name'    => esc_html__( 'Answer', 'ufaqsw' ),
			'desc'    => esc_html__( 'Write Your Answer', 'ufaqsw' ),
			'id'      => 'ufaqsw_faq_answer',
			'type'    => 'wysiwyg',
			'options' => array(
				'textarea_rows' => 20,
			),
		)
	);

}

/**
 * Modifies the columns displayed in the admin list table for the 'ufaqsw' post type.
 *
 * @param array $defaults The default columns.
 * @return array Modified columns with additional custom columns.
 */
function ufaqsw_faq_columns_head( $defaults ) {

	$new_columns['cb']                = '<input type="checkbox" />';
	$new_columns['title']             = __( 'Title', 'ufaqsw' );
	$new_columns['ufaqsw_item_count'] = 'Number of Questions & Answers';
	$new_columns['shortcode_col']     = 'Shortcode';
	$new_columns['date']              = __( 'Date', 'ufaqsw' );
	return $new_columns;
}

/**
 * Outputs custom column content for the 'ufaqsw' post type in the admin list table.
 *
 * @param string $column_name The name of the column to display content for.
 * @param int    $post_ID     The ID of the current post.
 */
function ufaqsw_faq_columns_content( $column_name, $post_ID ) {

	if ( 'ufaqsw_item_count' === $column_name ) {
		$faqs = get_post_meta( $post_ID, 'ufaqsw_faq_item01' );
		echo count( isset( $faqs[0] ) ? $faqs[0] : array() );
	}
	if ( 'shortcode_col' === $column_name ) {
		echo '<input type="text" value="[ufaqsw id=' . esc_attr( $post_ID ) . ']" class="ufaqsw_admin_faq_shorcode_copy" />';
	}
}

add_filter( 'manage_ufaqsw_posts_columns', 'ufaqsw_faq_columns_head' );
add_action( 'manage_ufaqsw_posts_custom_column', 'ufaqsw_faq_columns_content', 10, 2 );

/**
 * Duplicates a post as a draft for the custom post type 'ufaqsw'.
 *
 * This function handles duplicating a post, including its metadata and terms,
 * and redirects the user to the edit screen of the duplicated post.
 */
function ufaqsw_rd_duplicate_post_as_draft() {
	global $wpdb;
	if ( ! ( isset( $_GET['post'] ) || isset( $_POST['post'] ) || ( isset( $_REQUEST['action'] ) && 'rd_duplicate_post_as_draft' === $_REQUEST['action'] ) ) ) {
		wp_die( 'No post to duplicate has been supplied!' );
	}

	/*
	 * Nonce verification
	 */
	if ( ! isset( $_GET['duplicate_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['duplicate_nonce'] ) ), basename( __FILE__ ) ) ) {
		return;
	}

	/*
	 * get the original post id
	 */
	$post_id = ( isset( $_GET['post'] ) ? absint( $_GET['post'] ) : absint( $_POST['post'] ) );

	/*
	 * and all the original post data then
	 */
	$post = get_post( $post_id );

	/*
	 * if you don't want current user to be the new post author,
	 * then change next couple of lines to this: $new_post_author = $post->post_author;
	 */
	$current_user    = wp_get_current_user();
	$new_post_author = $current_user->ID;

	/*
	 * if post data exists, create the post duplicate
	 */
	if ( isset( $post ) && null !== $post ) {

		$args = array(
			'comment_status' => $post->comment_status,
			'ping_status'    => $post->ping_status,
			'post_author'    => $new_post_author,
			'post_content'   => $post->post_content,
			'post_excerpt'   => $post->post_excerpt,
			'post_name'      => $post->post_name,
			'post_parent'    => $post->post_parent,
			'post_password'  => $post->post_password,
			'post_status'    => 'draft',
			'post_title'     => $post->post_title,
			'post_type'      => $post->post_type,
			'to_ping'        => $post->to_ping,
			'menu_order'     => $post->menu_order,
		);

		/*
		 * insert the post by wp_insert_post() function
		 */
		$new_post_id = wp_insert_post( $args );

		/*
		 * get all current post terms ad set them to the new post draft
		 */
		$taxonomies = get_object_taxonomies( $post->post_type ); // returns array of taxonomy names for post type, ex array("category", "post_tag").
		foreach ( $taxonomies as $taxonomy ) {
			$post_terms = wp_get_object_terms( $post_id, $taxonomy, array( 'fields' => 'slugs' ) );
			wp_set_object_terms( $new_post_id, $post_terms, $taxonomy, false );
		}

		/*
		 * duplicate all post meta just in two SQL queries
		 */
		$post_meta_infos = $wpdb->get_results( "SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$post_id" ); // phpcs:ignore
		if ( count( $post_meta_infos ) !== 0 ) {
			$sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
			foreach ( $post_meta_infos as $meta_info ) {
				$meta_key = $meta_info->meta_key;
				if ( '_wp_old_slug' === $meta_key ) {
					continue;
				}
				$meta_value      = addslashes( $meta_info->meta_value );
				$sql_query_sel[] = "SELECT $new_post_id, '$meta_key', '$meta_value'";
			}
			$sql_query .= implode( ' UNION ALL ', $sql_query_sel );
			$wpdb->query( $sql_query ); // phpcs:ignore
		}

		/*
		 * finally, redirect to the edit post screen for the new draft
		 */
		wp_safe_redirect( admin_url( 'post.php?action=edit&post=' . $new_post_id ) );
		exit;
	} else {
		wp_die( 'Post creation failed, could not find original post: ' . esc_html( $post_id ) );
	}
}
add_action( 'admin_action_rd_duplicate_post_as_draft', 'ufaqsw_rd_duplicate_post_as_draft' );


/**
 * Adds a 'Duplicate' link to the row actions for the custom post type 'ufaqsw'.
 *
 * @param array  $actions The existing row actions.
 * @param object $post    The current post object.
 * @return array Modified row actions with the 'Duplicate' link.
 */
function ufaqsw_rd_duplicate_post_link( $actions, $post ) {

	if ( current_user_can( 'edit_posts' ) && 'ufaqsw' === $post->post_type ) {
		$actions['duplicate'] = '<a href="' . wp_nonce_url( 'admin.php?action=rd_duplicate_post_as_draft&post=' . $post->ID, basename( __FILE__ ), 'duplicate_nonce' ) . '" title="' . esc_html__( 'Duplicate this item', 'ufaqsw' ) . '" rel="permalink">' . esc_html__( 'Duplicate', 'ufaqsw' ) . '</a>';
	}
	return $actions;
}

add_filter( 'post_row_actions', 'ufaqsw_rd_duplicate_post_link', 10, 2 );

/**
 * Custom render callback for CMB2 text_html field type.
 *
 * This function renders a text input field for the 'text_html' field type in CMB2.
 *
 * @param array  $field            The field array.
 * @param mixed  $escaped_value    The escaped value of the field.
 * @param int    $object_id        The ID of the object being edited.
 * @param string $object_type      The type of object being edited (e.g., post, user, term).
 * @param object $field_type_object The CMB2_Field object type.
 */
function bt_cmb2_render_callback_for_text_html( $field, $escaped_value, $object_id, $object_type, $field_type_object ) {
	echo $field_type_object->input( array( 'type' => 'text' ) ); // phpcs:ignore
}
add_action( 'cmb2_render_text_html', 'bt_cmb2_render_callback_for_text_html', 10, 5 );

/**
 * Sanitization callback for the 'text_html' field type in CMB2.
 *
 * This function returns the value as-is without any sanitization.
 *
 * @param mixed $override_value The override value, if any.
 * @param mixed $value          The value to sanitize.
 * @return mixed The sanitized value.
 */
function bt_cmb2_sanitize_text_html_callback( $override_value, $value ) {
	return $value;
}
add_filter( 'cmb2_sanitize_text_html', 'bt_cmb2_sanitize_text_html_callback', 10, 2 );




