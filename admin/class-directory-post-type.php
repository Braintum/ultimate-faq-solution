<?php
if ( ! defined( 'ABSPATH' ) ) exit;
//Registering custom post for FAQ
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
		'menu_name'          => 'Ultimate FAQs'
		
	);
	
	$ufaqsw_list_args = array(
		'labels'        => $ufaqsw_list_labels,
		'description'   => 'This post type holds all FAQs for your site.',		
		'menu_position' => 25,
		'exclude_from_search' => true,
		'show_in_nav_menus' => false,
		'supports'      => array( 'title', 'revisions' ),
		'has_archive'   => false,
		'menu_icon' 	=> '',		
		'public' => false,  // it's not public, it shouldn't have it's own permalink, and so on
		'publicly_queryable' => false,  // you should be able to query it
		'show_ui' => true,  // you should be able to edit it in wp-admin
		'rewrite' => false,  // it shouldn't have rewrite rules
		'menu_icon' 	=> 'dashicons-list-view',
		
	);
	
	register_post_type( 'ufaqsw', $ufaqsw_list_args );		
	
}
add_action( 'init', 'ufaqsw_register_cpt' );

add_filter( 'post_row_actions', 'ufaqsw_remove_row_actions', 10, 1 );

function ufaqsw_remove_row_actions( $actions )
{
    if( get_post_type() === 'ufaqsw' )
        unset( $actions['view'] );
    return $actions;
}


add_action( 'cmb2_admin_init', 'ufaqsw_register_appearance_metabox' );
function ufaqsw_register_appearance_metabox() {
	
	$cmb_demo = new_cmb2_box( array(
		'id'            => 'ufaqsw_faq_conf',
		'title'         => esc_html__( 'Appearance', 'cmb2' ),
		'object_types'  => array( 'ufaqsw' ), // Post type
		 'closed'     => true,
		 'classes'    => 'extra-class',
		 

	) );

	
	
	$cmb_demo->add_field( array(
		'name'             => esc_html__( 'Choose A Template', 'cmb2' ),
		'id'               => 'ufaqsw_template',
		'type'             => 'radio_inline',
		'options'          => array(
			'default' => esc_html__( 'Default', 'cmb2' ),
			'style-1'   => esc_html__( 'Style 1', 'cmb2' ),
			'style-2'     => esc_html__( 'Style 2', 'cmb2' ),
		),
	) );
	
	$cmb_demo->add_field( array(
		'name'    => esc_html__( 'Faq Group Title Color', 'cmb2' ),
		'id'      => 'ufaqsw_title_color',
		'desc' => esc_html__( 'Change the Group Title color', 'cmb2' ),
		'type'    => 'colorpicker',
	) );
	
	$cmb_demo->add_field( array(
		'name' => esc_html__( 'Faq Group Title Font Size (ex: 30px)', 'cmb2' ),
		'desc' => esc_html__( 'Change the Group Title font size ex: 30px', 'cmb2' ),
		'id'   => 'ufaqsw_title_font_size',
		'type' => 'text_small',

	) );

	$cmb_demo->add_field( array(
		'name'    => esc_html__( 'Question Color', 'cmb2' ),
		'id'      => 'ufaqsw_question_color',
		'desc' => esc_html__( 'Change the Question color', 'cmb2' ),
		'type'    => 'colorpicker',

	) );
	
	$cmb_demo->add_field( array(
		'name'    => esc_html__( 'Answer Color', 'cmb2' ),
		'id'      => 'ufaqsw_answer_color',
		'desc' => esc_html__( 'Change the Answer color', 'cmb2' ),
		'type'    => 'colorpicker',

	) );
	
	$cmb_demo->add_field( array(
		'name'    => esc_html__( 'Question Background Color', 'cmb2' ),
		'id'      => 'ufaqsw_question_background_color',
		'desc' => esc_html__( 'Change the Question Background color', 'cmb2' ),
		'type'    => 'colorpicker',

	) );
	
	$cmb_demo->add_field( array(
		'name'    => esc_html__( 'Answer Background Color', 'cmb2' ),
		'id'      => 'ufaqsw_answer_background_color',
		'desc' => esc_html__( 'Change the Answer Background color', 'cmb2' ),
		'type'    => 'colorpicker',

	) );
	$cmb_demo->add_field( array(
		'name' => esc_html__( 'Question Font Size (ex: 20px)', 'cmb2' ),
		'desc' => esc_html__( 'Change the Question Font Size ex: 20px', 'cmb2' ),
		'id'   => 'ufaqsw_question_font_size',
		'type' => 'text_small',

	) );
	
	$cmb_demo->add_field( array(
		'name' => esc_html__( 'Answer Font Size (ex: 20px)', 'cmb2' ),
		'desc' => esc_html__( 'Change the Answer Font Size ex: 20px', 'cmb2' ),
		'id'   => 'ufaqsw_answer_font_size',
		'type' => 'text_small',

	) );
	
	$cmb_demo->add_field( array(
		'name' => esc_html__( 'Normal Icon', 'cmb2' ),
		'id'   => 'ufaqsw_normal_icon',
		'desc' => esc_html__( 'Change the default icon by clicking on the input box.', 'cmb2' ),
		'type' => 'text_medium',
	) );
	
	$cmb_demo->add_field( array(
		'name' => esc_html__( 'Active Icon', 'cmb2' ),
		'id'   => 'ufaqsw_active_icon',
		'desc' => esc_html__( 'Change the default icon by clicking on the input box.', 'cmb2' ),
		'type' => 'text_medium',
	) );

	$cmb_demo->add_field( array(
        'name'          => esc_html__( 'FAQ Behaviour', 'your-text-domain' ),
        'desc'          => esc_html__('Default behaviour is Toggle. You can change it to Accordion', 'cmb2'),
        'id'            => 'ufaqsw_faq_behaviour',
        'type'          => 'select',
        'options'       => array(
            'toggle'    => esc_html__('Toggle', 'cmb2' ),
            'accordion'    => esc_html__('Accordion', 'cmb2'),
        ),
    ) );

	$cmb_demo->add_field( array(
		'name' => esc_html__( 'Show all Answers by Default', 'cmb2' ),
		'desc' => esc_html__( 'Show all Answers by Default', 'cmb2' ),
		'id'   => 'ufaqsw_answer_showall',
		'type' => 'checkbox',
		'attributes'    => array(
            'data-conditional-id'     => 'ufaqsw_faq_behaviour',
            'data-conditional-value'  => 'toggle',
        ),
	) );
	
	$cmb_demo->add_field( array(
		'name' => esc_html__( 'Hide Group Title', 'cmb2' ),
		'desc' => esc_html__( 'Hide Group Title', 'cmb2' ),
		'id'   => 'ufaqsw_hide_title',
		'type' => 'checkbox',
	) );
	
	

	


}


add_action( 'cmb2_admin_init', 'yourprefix_register_repeatable_group_field_metabox' );
function yourprefix_register_repeatable_group_field_metabox() {

	$cmb_group = new_cmb2_box( array(
		'id'           => 'ufaqsw_faq_items',
		'title'        => esc_html__( 'FAQs', 'cmb2' ),
		'object_types' => array( 'ufaqsw' ),
	) );

	$group_field_id = $cmb_group->add_field( array(
		'id'          => 'ufaqsw_faq_item01',
		'type'        => 'group',
		'description' => esc_html__( 'Add FAQ to this group by click on "Add FAQ Entry" Button', 'cmb2' ),
		'options'     => array(
			'group_title'    => esc_html__( 'FAQ {#}', 'cmb2' ), // {#} gets replaced by row number
			'add_button'     => esc_html__( 'Add FAQ Entry', 'cmb2' ),
			'remove_button'  => esc_html__( 'Remove FAQ Entry', 'cmb2' ),
			'sortable'       => true,
			'closed'      => false, // true to have the groups closed by default
			'remove_confirm' => esc_html__( 'Are you sure you want to remove the FAQ entry?', 'cmb2' ), 
		),
	) );

	$cmb_group->add_group_field( $group_field_id, array(
		'name'       => esc_html__( 'Question', 'cmb2' ),
		'id'         => 'ufaqsw_faq_question',
		'desc' => esc_html__( 'Write Your Question', 'cmb2' ),
		'type'       => 'text_html',
	) );

	$cmb_group->add_group_field($group_field_id, array(
		'name'    => esc_html__( 'Answer', 'cmb2' ),
		'desc'    => esc_html__( 'Write Your Answer', 'cmb2' ),
		'id'      => 'ufaqsw_faq_answer',
		'type'    => 'wysiwyg',
		'options' => array(
			'textarea_rows' => 5,
		),
	) );

}


function ufaqsw_faq_columns_head($defaults) {

    $new_columns['cb'] = '<input type="checkbox" />';
    $new_columns['title'] = __('Title');
    $new_columns['ufaqsw_item_count'] = 'Number of Questions & Answers';
    $new_columns['shortcode_col'] = 'Shortcode';
    $new_columns['date'] = __('Date');
    return $new_columns;
}
 

function ufaqsw_faq_columns_content($column_name, $post_ID) {
    
    if ($column_name == 'ufaqsw_item_count') {
		$faqs = get_post_meta( $post_ID, 'ufaqsw_faq_item01' );
        echo count(isset($faqs[0])?$faqs[0]:array());
    }
    if ($column_name == 'shortcode_col') {
        echo '<input type="text" value="[ufaqsw id='.esc_attr($post_ID).']" class="ufaqsw_admin_faq_shorcode_copy" />';
    }
}

add_filter('manage_ufaqsw_posts_columns', 'ufaqsw_faq_columns_head');
add_action('manage_ufaqsw_posts_custom_column', 'ufaqsw_faq_columns_content', 10, 2);



function ufaqsw_rd_duplicate_post_as_draft(){
	global $wpdb;
	if (! ( isset( $_GET['post']) || isset( $_POST['post'])  || ( isset($_REQUEST['action']) && 'rd_duplicate_post_as_draft' == $_REQUEST['action'] ) ) ) {
		wp_die('No post to duplicate has been supplied!');
	}
 
	/*
	 * Nonce verification
	 */
	if ( !isset( $_GET['duplicate_nonce'] ) || !wp_verify_nonce( $_GET['duplicate_nonce'], basename( __FILE__ ) ) )
		return;
 
	/*
	 * get the original post id
	 */
	$post_id = (isset($_GET['post']) ? absint( $_GET['post'] ) : absint( $_POST['post'] ) );
	/*
	 * and all the original post data then
	 */
	$post = get_post( $post_id );
 
	/*
	 * if you don't want current user to be the new post author,
	 * then change next couple of lines to this: $new_post_author = $post->post_author;
	 */
	$current_user = wp_get_current_user();
	$new_post_author = $current_user->ID;
 
	/*
	 * if post data exists, create the post duplicate
	 */
	if (isset( $post ) && $post != null) {
 
		/*
		 * new post data array
		 */
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
			'menu_order'     => $post->menu_order
		);
 
		/*
		 * insert the post by wp_insert_post() function
		 */
		$new_post_id = wp_insert_post( $args );
 
		/*
		 * get all current post terms ad set them to the new post draft
		 */
		$taxonomies = get_object_taxonomies($post->post_type); // returns array of taxonomy names for post type, ex array("category", "post_tag");
		foreach ($taxonomies as $taxonomy) {
			$post_terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'slugs'));
			wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
		}
 
		/*
		 * duplicate all post meta just in two SQL queries
		 */
		$post_meta_infos = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$post_id");
		if (count($post_meta_infos)!=0) {
			$sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
			foreach ($post_meta_infos as $meta_info) {
				$meta_key = $meta_info->meta_key;
				if( $meta_key == '_wp_old_slug' ) continue;
				$meta_value = addslashes($meta_info->meta_value);
				$sql_query_sel[]= "SELECT $new_post_id, '$meta_key', '$meta_value'";
			}
			$sql_query.= implode(" UNION ALL ", $sql_query_sel);
			$wpdb->query($sql_query);
		}
 
 
		/*
		 * finally, redirect to the edit post screen for the new draft
		 */
		wp_redirect( admin_url( 'post.php?action=edit&post=' . $new_post_id ) );
		exit;
	} else {
		wp_die('Post creation failed, could not find original post: ' . $post_id);
	}
}
add_action( 'admin_action_rd_duplicate_post_as_draft', 'ufaqsw_rd_duplicate_post_as_draft' );
 
/*
 * Add the duplicate link to action list for post_row_actions
 */
function ufaqsw_rd_duplicate_post_link( $actions, $post ) {
	
	if (current_user_can('edit_posts') && $post->post_type=='ufaqsw') {
		$actions['duplicate'] = '<a href="' . wp_nonce_url('admin.php?action=rd_duplicate_post_as_draft&post=' . $post->ID, basename(__FILE__), 'duplicate_nonce' ) . '" title="'.esc_html__('Duplicate this item', 'ufaqsw').'" rel="permalink">'.esc_html__('Duplicate', 'ufaqsw').'</a>';
	}
	return $actions;
}
 
add_filter( 'post_row_actions', 'ufaqsw_rd_duplicate_post_link', 10, 2 );

function bt_cmb2_render_callback_for_text_html( $field, $escaped_value, $object_id, $object_type, $field_type_object ) {
	echo $field_type_object->input( array( 'type' => 'text' ) );
}
add_action( 'cmb2_render_text_html', 'bt_cmb2_render_callback_for_text_html', 10, 5 );

function bt_cmb2_sanitize_text_html_callback( $override_value, $value ) {
	return $value;
}
add_filter( 'cmb2_sanitize_text_html', 'bt_cmb2_sanitize_text_html_callback', 10, 2 );



