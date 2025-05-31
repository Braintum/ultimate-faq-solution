<?php

/**
 * Add the settings page using CMB2.
 */
function ufaqsw_register_settings_page() {
	$cmb = new_cmb2_box(
		array(
			'id'           => 'ufaqsw_settings_page',
			'title'        => __( 'Chatbot Settings', 'ufaqsw' ),
			'object_types' => array( 'options-page' ),
			'option_key'   => 'ufaqsw_chatbot_settings', // Option key for storing settings.
			'parent_slug'  => 'edit.php?post_type=ufaqsw', // Parent menu slug.
			'capability'   => 'manage_options', // Capability required to access the page.
			'icon_url'     => 'dashicons-admin-generic', // Menu icon.
			'position'     => 2, // Position in the menu.
		)
	);

	// Add fields to the settings page.
	$cmb->add_field(
		array(
			'name'    => __( 'Chatbot Title', 'ufaqsw' ),
			'id'      => 'chatbot_title',
			'type'    => 'text',
			'default' => __( 'Chatbot', 'ufaqsw' ),
		)
	);

	$cmb->add_field(
		array(
			'name'    => __( 'Enable Chatbot', 'ufaqsw' ),
			'id'      => 'enable_chatbot',
			'type'    => 'checkbox',
			'default' => true,
		)
	);

	$cmb->add_field(
		array(
			'name'    => __( 'Chatbot Welcome Message', 'ufaqsw' ),
			'id'      => 'chatbot_welcome_message',
			'type'    => 'textarea',
			'default' => __( 'Welcome! How can I assist you today?', 'ufaqsw' ),
		)
	);

	$cmb->add_field(
		array(
			'name'    => __( 'Chatbot Theme Color', 'ufaqsw' ),
			'id'      => 'chatbot_theme_color',
			'type'    => 'colorpicker',
			'default' => '#da5c34',
		)
	);

	//$welcome_message = cmb2_get_option( 'ufaqsw_chatbot_settings', 'chatbot_welcome_message' );

	//echo $welcome_message;exit;
}

add_action( 'cmb2_admin_init', 'ufaqsw_register_settings_page' );


