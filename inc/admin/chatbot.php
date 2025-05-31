<?php

/**
 * Add the settings page using CMB2.
 */
function ufaqsw_register_settings_page() {
	$cmb = new_cmb2_box(
		array(
			'id'           => 'ufaqsw_settings_page',
			'title'        => __( 'FAQ Assistant Settings', 'ufaqsw' ),
			'object_types' => array( 'options-page' ),
			'option_key'   => 'ufaqsw_chatbot_settings', // Option key for storing settings.
			'parent_slug'  => 'edit.php?post_type=ufaqsw', // Parent menu slug.
			'capability'   => 'manage_options', // Capability required to access the page.
			'icon_url'     => 'dashicons-admin-generic', // Menu icon.
			'position'     => 2, // Position in the menu.
			'menu_title'   => __( 'FAQ Assistant', 'ufaqsw' ), // Custom menu title.
		)
	);

	$cmb->add_field(
		array(
			'name'        => __( 'Enable FAQ Assistant', 'ufaqsw' ),
			'id'          => 'enable_chatbot',
			'type'        => 'checkbox',
			'description' => __( '<i>Display a floating help icon on every page of your site. When clicked, it opens the interactive FAQ Assistant, allowing visitors to browse and search your FAQs in a chat-style window.</i>', 'ufaqsw' ),
		)
	);
	$cmb->add_field(
		array(
			'name'        => __( 'Floating Button Icon', 'ufaqsw' ),
			'id'          => 'floating_button_icon',
			'type'        => 'file',
			'description' => __( '<i>Upload a custom icon for the floating FAQ Assistant button. The image should be 80 × 80 pixels. Any image format is allowed, but a transparent background (e.g., PNG, SVG) is recommended for the best visual appearance. If no icon is uploaded, a default icon will be used instead.</i>', 'ufaqsw' ),
			'options'     => array(
				'url' => false, // Hide the text input for the URL.
			),
			'text'    => array(
				'add_upload_file_text' => __( 'Add Icon', 'ufaqsw' ),
			),
			'preview_size' => 'thumbnail', // Image preview size.
		)
	);

	// Add fields to the settings page.
	$cmb->add_field(
		array(
			'name'        => __( 'Floating Button Title', 'ufaqsw' ),
			'id'          => 'floating_button_title',
			'type'        => 'text',
			'description' => __( '<i>The text will display when you hover over the floating button.</i>', 'ufaqsw' ),
		)
	);

	$cmb->add_field(
		array(
			'name'    => __( 'Header Background Color', 'ufaqsw' ),
			'id'      => 'header_background_color',
			'type'    => 'colorpicker',
			'default' => '#1a185e',
			'description' => __( '<i>Choose the background color of the FAQ Assistant header for better branding and visibility.</i>', 'ufaqsw' ),
		)
	);

	$cmb->add_field(
		array(
			'name'    => __( 'Header Text Color', 'ufaqsw' ),
			'id'      => 'header_text_color',
			'type'    => 'colorpicker',
			'default' => '#fff',
			'description' => __( '<i>Select the color of the text in the FAQ Assistant header to ensure readability against the background.</i>', 'ufaqsw' ),
		)
	);

	$cmb->add_field(
		array(
			'name'    => __( 'Window Headline', 'ufaqsw' ),
			'id'      => 'assistant_window_headline',
			'type'    => 'text',
			'default' => __( 'Welcome to our Help Center!', 'ufaqsw' ),
			'description' => __( '<i>Enter the main title displayed at the top of the FAQ Assistant window to welcome or guide users.</i>', 'ufaqsw' ),
		)
	);

	$cmb->add_field(
		array(
			'name'    => __( 'Window Intro Text', 'ufaqsw' ),
			'id'      => 'assistant_window_intro_text',
			'type'    => 'text',
			'default' => __( 'Explore common questions and answers.', 'ufaqsw' ),
			'description' => __( '<i>Add a brief message shown below the headline to explain how visitors can use the assistant.</i>', 'ufaqsw' ),
		)
	);

	$cmb->add_field(
		array(
			'name'    => __( 'Preloader Text', 'ufaqsw' ),
			'id'      => 'preloader_text',
			'type'    => 'text',
			'default' => __( 'Loading...', 'ufaqsw' ),
			'description' => __( '<i>Text displayed while the FAQ content is loading (e.g., “Loading…”) to inform users.</i>', 'ufaqsw' ),
		)
	);

	$cmb->add_field( array(
		'name' => __( 'Body Text', 'ufaqsw' ),
		'desc' => __( '<i>Enter the text displayed above the list of FAQ categories in the assistant window. Use this to guide visitors on how to navigate and use the categories effectively.</i>', 'ufaqsw' ),
		'default' => __( 'Browse our FAQ categories below to quickly find answers grouped by topic. Click a category to see related questions and solutions.', 'ufaqsw' ),
		'id' => 'body_text',
		'type' => 'textarea'
	) );

}

add_action( 'cmb2_admin_init', 'ufaqsw_register_settings_page' );

/**
 * Output custom HTML before the CMB2 form.
 *
 * @param string $cmb_id      The CMB2 metabox ID.
 * @param int    $object_id   The object ID.
 * @param string $object_type The object type.
 * @param object $cmb         The CMB2 object.
 */
function ufaqsw_add_html_before_cmb2_output( $cmb_id, $object_id, $object_type, $cmb ) {

	// Only output above the ufaqsw_settings_page metabox.
	if ( 'ufaqsw_settings_page' !== $cmb_id ) {
		return;
	}

	echo '<div class="faq-assistant-wrapper">';
	echo '<div style="margin-bottom: 20px;">';
	echo esc_html__( 'The FAQ Assistant adds an interactive, floating help icon to your website, giving visitors quick access to your FAQs in a sleek, chat-style interface. Use the settings below to enable the assistant and customize its behavior. Improve user experience by making answers more accessible—right when and where your visitors need them.', 'ufaqsw' );
	echo '</div>';

	add_action( 'cmb2_after_form', 'ufaqsw_add_html_after_cmb2_output', 10, 4 );
}
add_action( 'cmb2_before_form', 'ufaqsw_add_html_before_cmb2_output', 10, 4 );

/**
 * Output custom HTML after the CMB2 form.
 *
 * @param string $cmb_id      The CMB2 metabox ID.
 * @param int    $object_id   The object ID.
 * @param string $object_type The object type.
 * @param object $cmb         The CMB2 object.
 */
function ufaqsw_add_html_after_cmb2_output( $cmb_id, $object_id, $object_type, $cmb ) {
	echo '</div><!-- .faq-assistant-wrapper -->';
}
