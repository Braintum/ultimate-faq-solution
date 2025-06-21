<?php
/**
 * FAQ Assistant Settings Page and Admin Functionality
 *
 * This file contains the code for registering and rendering the FAQ Assistant settings page
 * in the WordPress admin using CMB2, as well as related helper functions and admin scripts.
 *
 * @package Ultimate_FAQ_Solution
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

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
			'tabs'         => array(
				array(
					'id'     => 'general',
					'title'  => __( 'General', 'ufaqsw' ),
					'icon'   => 'dashicons-admin-site',
					'fields' => array(
						'enable_chatbot',
						'display_on',
						'display_on_pages',
						'faq_groups',
					),
				),
				array(
					'id'     => 'appearance',
					'title'  => __( 'Appearance', 'ufaqsw' ),
					'icon'   => 'dashicons-admin-appearance',
					'fields' => array(
						'floating_button_icon',
						'header_background_color',
						'header_text_color',
						'loading_animation_color',
					),
				),
				array(
					'id'     => 'ui_labels',
					'title'  => __( 'UI Labels & Messages', 'ufaqsw' ),
					'icon'   => 'dashicons-editor-textcolor',
					'fields' => array(
						'floating_button_title',
						'assistant_window_headline',
						'assistant_window_intro_text',
						'preloader_text',
						'body_text',
						'assistant_back_button_title',
						'assistant_close_button_title',
						'bottom_text',
					),
				),
			),
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
			'name'        => __( 'Loading Animation Color', 'ufaqsw' ),
			'id'          => 'loading_animation_color',
			'type'        => 'colorpicker',
			'default'     => '#222',
			'description' => __( '<i>Choose the color for the loading animation (animated dots) shown while the FAQ Assistant is fetching content. Adjust for better visibility and branding.</i>', 'ufaqsw' ),
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
			'name'        => __( 'Back Button Title', 'ufaqsw' ),
			'id'          => 'assistant_back_button_title',
			'type'        => 'text',
			'default'     => __( 'Go back to the previous view', 'ufaqsw' ),
			'description' => __( '<i>This text appears as a tooltip when hovering over the back button in the FAQ Assistant window. Use it to guide users on its function (e.g., “Go back to the previous view”).</i>', 'ufaqsw' ),
		)
	);

	$cmb->add_field(
		array(
			'name'        => __( 'Close Button Title', 'ufaqsw' ),
			'id'          => 'assistant_close_button_title',
			'type'        => 'text',
			'default'     => __( 'Close the window', 'ufaqsw' ),
			'description' => __( '<i>This text appears as a tooltip when hovering over the close button in the FAQ Assistant window. Use it to let users know that clicking will close the assistant window.</i>', 'ufaqsw' ),
		)
	);

	$cmb->add_field(
		array(
			'name'        => __( 'Preloader Text', 'ufaqsw' ),
			'id'          => 'preloader_text',
			'type'        => 'text',
			'default'     => '',
			'description' => __( '<i>Text displayed while the FAQ content is loading (e.g., “Loading…”) to inform users.</i>', 'ufaqsw' ),
		)
	);

	$cmb->add_field(
		array(
			'name'    => __( 'Body Text', 'ufaqsw' ),
			'desc'    => __( '<i>Enter the text displayed above the list of FAQ categories in the assistant window. Use this to guide visitors on how to navigate and use the categories effectively.</i>', 'ufaqsw' ),
			'default' => __( 'Browse our FAQ categories below to quickly find answers grouped by topic. Click a category to see related questions and solutions.', 'ufaqsw' ),
			'id'      => 'body_text',
			'type'    => 'wysiwyg',
			'options' => array(
				'textarea_rows' => 20,
			),
		)
	);

	$cmb->add_field(
		array(
			'name'    => __( 'Bottom Text', 'ufaqsw' ),
			'desc'    => __( '<i>Enter the text that will appear at the bottom of the FAQ Assistant window, below the FAQ categories and answers. This is a good place for additional instructions, a call-to-action, or any helpful message you want users to see after browsing the FAQs. Button text will be added at the bottom in each screen.</i>', 'ufaqsw' ),
			'id'      => 'bottom_text',
			'type'    => 'wysiwyg',
			'options' => array(
				'textarea_rows' => 20,
			),
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

	echo '<div style="margin-bottom: 20px;">';
	echo esc_html__(
		'The FAQ Assistant adds an interactive, floating help icon to your website, giving visitors quick access to your FAQs in a sleek, chat-style interface. Use the settings below to enable the assistant and customize its behavior. Improve user experience by making answers more accessible—right when and where your visitors need them.',
		'ufaqsw'
	);

	echo '<br><a href="https://www.braintum.com/docs/ultimate-faq-solution/faq-assistant/" target="_blank" style="display:inline-block;margin-top:10px;">' . esc_html__( 'Read the FAQ Assistant Documentation.', 'ufaqsw' ) . '</a>';
	echo '</div>';

}
add_action( 'cmb2_before_form', 'ufaqsw_add_html_before_cmb2_output', 1, 4 );

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

