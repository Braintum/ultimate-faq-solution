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
			'text'         => array(
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
			'name'        => __( 'Header Background Color', 'ufaqsw' ),
			'id'          => 'header_background_color',
			'type'        => 'colorpicker',
			'default'     => '#1a185e',
			'description' => __( '<i>Choose the background color of the FAQ Assistant header for better branding and visibility.</i>', 'ufaqsw' ),
		)
	);

	$cmb->add_field(
		array(
			'name'        => __( 'Header Text Color', 'ufaqsw' ),
			'id'          => 'header_text_color',
			'type'        => 'colorpicker',
			'default'     => '#fff',
			'description' => __( '<i>Select the color of the text in the FAQ Assistant header to ensure readability against the background.</i>', 'ufaqsw' ),
		)
	);

	$cmb->add_field(
		array(
			'name'        => __( 'Window Headline', 'ufaqsw' ),
			'id'          => 'assistant_window_headline',
			'type'        => 'text',
			'default'     => __( 'Welcome to our Help Center!', 'ufaqsw' ),
			'description' => __( '<i>Enter the main title displayed at the top of the FAQ Assistant window to welcome or guide users.</i>', 'ufaqsw' ),
		)
	);

	$cmb->add_field(
		array(
			'name'        => __( 'Window Intro Text', 'ufaqsw' ),
			'id'          => 'assistant_window_intro_text',
			'type'        => 'text',
			'default'     => __( 'Explore common questions and answers.', 'ufaqsw' ),
			'description' => __( '<i>Add a brief message shown below the headline to explain how visitors can use the assistant.</i>', 'ufaqsw' ),
		)
	);

	$cmb->add_field(
		array(
			'name'        => __( 'Preloader Text', 'ufaqsw' ),
			'id'          => 'preloader_text',
			'type'        => 'text',
			'default'     => __( 'Loading...', 'ufaqsw' ),
			'description' => __( '<i>Text displayed while the FAQ content is loading (e.g., “Loading…”) to inform users.</i>', 'ufaqsw' ),
		)
	);

	$cmb->add_field(
		array(
			'name'    => __( 'Body Text', 'ufaqsw' ),
			'desc'    => __( '<i>Enter the text displayed above the list of FAQ categories in the assistant window. Use this to guide visitors on how to navigate and use the categories effectively.</i>', 'ufaqsw' ),
			'default' => __( 'Browse our FAQ categories below to quickly find answers grouped by topic. Click a category to see related questions and solutions.', 'ufaqsw' ),
			'id'      => 'body_text',
			'type'    => 'textarea',
		)
	);

	$faq_groups = get_posts(
		array(
			'post_type'      => 'ufaqsw',
			'posts_per_page' => -1,
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
			'post_status'    => 'publish',
			'fields'         => 'ids',
		)
	);

	$faq_options = array();
	if ( $faq_groups ) {
		foreach ( $faq_groups as $group_id ) {
			$group_title             = get_the_title( $group_id );
			$faq_options[ $group_id ] = $group_title;
		}
	}

	$cmb->add_field(
		array(
			'name'              => 'FAQ Groups',
			'id'                => 'faq_groups',
			'type'              => 'multicheck',
			'options'           => $faq_options,
			'select_all_button' => true,
			'default'           => array_keys( $faq_options ),
			'description'       => __( '<i>By default all FAQ groups will be displayed, but you can uncheck any groups to not show on the FAQ Assistant window.</i>', 'ufaqsw' ),
		)
	);

	$cmb->add_field(
		array(
			'name'        => __( 'Display FAQ Assistant On', 'ufaqsw' ),
			'id'          => 'display_on',
			'type'        => 'radio_inline',
			'default'     => 'all',
			'options'     => array(
				'all'     => __( 'All Pages (default)', 'ufaqsw' ),
				'specific' => __( 'Specific Pages', 'ufaqsw' ),
			),
			'description' => __( 'Choose whether to display the FAQ Assistant on all pages or only on selected pages.', 'ufaqsw' ),
		)
	);

	$cmb->add_field(
		array(
			'name'        => __( 'Select Pages', 'ufaqsw' ),
			'id'          => 'display_on_pages',
			'type'        => 'multicheck',
			'options'     => ufaqsw_get_all_pages_for_select(),
			'attributes'  => array(
				'data-conditional-id'    => 'display_on',
				'data-conditional-value' => 'specific',
			),
			'description' => __( 'Select the pages where the FAQ Assistant should appear. Only applies if "Specific Pages" is selected above.', 'ufaqsw' ),
		)
	);

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
	echo esc_html__(
		'The FAQ Assistant adds an interactive, floating help icon to your website, giving visitors quick access to your FAQs in a sleek, chat-style interface. Use the settings below to enable the assistant and customize its behavior. Improve user experience by making answers more accessible—right when and where your visitors need them.',
		'ufaqsw'
	);

	echo '<br><a href="https://www.braintum.com/docs/ultimate-faq-solution/faq-assistant/" target="_blank" style="display:inline-block;margin-top:10px;">' . esc_html__( 'Read the FAQ Assistant Documentation.', 'ufaqsw' ) . '</a>';
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

/**
 * Callback to get all pages for select field.
 */
function ufaqsw_get_all_pages_for_select() {
	$pages   = get_pages( array( 'post_status' => 'publish' ) );
	$options = array();
	foreach ( $pages as $page ) {
		$options[ $page->ID ] = $page->post_title;
	}
	return $options;
}

// Enqueue admin scripts/styles for the settings page.
add_action(
	'admin_enqueue_scripts',
	function( $hook ) {
		if ( isset( $_GET['page'] ) && 'ufaqsw_chatbot_settings' === $_GET['page'] ) {
			wp_enqueue_script( 'ufaqsw-cmb2-conditional', UFAQSW_ASSETS_URL . 'js/cmb2-conditional-logic.js', array( 'jquery' ), '1.0.0', true );
		}
	}
);

add_action(
	'admin_footer',
	function() {
		if ( isset( $_GET['page'] ) && 'ufaqsw_chatbot_settings' === $_GET['page'] ) {
			?>
			<style>
				.field_is_hidden {
					display: none !important;
				}
			</style>
			<?php
		}
	}
);
