<?php
/**
 * FAQ Assistant Settings Page and Admin Functionality
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
			'option_key'   => 'ufaqsw_chatbot_settings',
			'parent_slug'  => 'edit.php?post_type=ufaqsw',
			'capability'   => 'manage_options',
			'icon_url'     => 'dashicons-admin-generic',
			'position'     => 2,
			'menu_title'   => __( 'FAQ Assistant', 'ufaqsw' ),
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
						'pulse_enabled',
						'badge_enabled',
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
						'faqs_count_text',
					),
				),
				array(
					'id'     => 'search',
					'title'  => __( 'Search', 'ufaqsw' ),
					'icon'   => 'dashicons-search',
					'fields' => array(
						'search_enabled',
						'search_placeholder',
						'search_in_answers',
						'search_no_results_text',
					),
				),
				array(
					'id'     => 'ask_question',
					'title'  => __( 'Ask a Question', 'ufaqsw' ),
					'icon'   => 'dashicons-format-chat',
					'fields' => array(
						'ask_enabled',
						'ask_notification_email',
						'ask_form_title',
						'ask_button_label',
						'ask_name_label',
						'ask_email_label',
						'ask_question_label',
						'ask_submit_label',
						'ask_cancel_label',
						'ask_gdpr_enabled',
						'ask_gdpr_text',
						'ask_gdpr_error',
						'ask_success_title',
						'ask_success_message',
						'ask_back_to_faqs_label',
					),
				),
				array(
					'id'     => 'feedback',
					'title'  => __( 'Feedback & Ratings', 'ufaqsw' ),
					'icon'   => 'dashicons-star-half',
					'fields' => array(
						'feedback_enabled',
						'feedback_label',
						'feedback_helpful_text',
						'feedback_not_helpful_text',
						'feedback_thanks_text',
						'related_enabled',
						'related_title',
						'related_count',
					),
				),
			),
		)
	);

	// -------------------------------------------------------------------------
	// GENERAL TAB
	// -------------------------------------------------------------------------

	$cmb->add_field(
		array(
			'name'        => __( 'Enable FAQ Assistant', 'ufaqsw' ),
			'id'          => 'enable_chatbot',
			'type'        => 'checkbox',
			'description' => __( '<i>Display a floating help icon on every page of your site. When clicked, it opens the interactive FAQ Assistant, allowing visitors to browse your FAQs in a chat-style window.</i>', 'ufaqsw' ),
		)
	);

	$cmb->add_field(
		array(
			'name'        => __( 'Display FAQ Assistant On', 'ufaqsw' ),
			'id'          => 'display_on',
			'type'        => 'radio_inline',
			'default'     => 'all',
			'options'     => array(
				'all'      => __( 'All Pages (default)', 'ufaqsw' ),
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

	$faq_groups   = get_posts(
		array(
			'post_type'      => 'ufaqsw',
			'posts_per_page' => -1,
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
			'post_status'    => 'publish',
			'fields'         => 'ids',
		)
	);
	$faq_options  = array();
	if ( $faq_groups ) {
		foreach ( $faq_groups as $group_id ) {
			$faq_options[ $group_id ] = get_the_title( $group_id );
		}
	}

	$cmb->add_field(
		array(
			'name'              => __( 'FAQ Groups', 'ufaqsw' ),
			'id'                => 'faq_groups',
			'type'              => 'multicheck',
			'options'           => $faq_options,
			'select_all_button' => true,
			'default'           => array_keys( $faq_options ),
			'description'       => __( '<i>By default all FAQ groups will be displayed. Uncheck any groups to exclude them from the FAQ Assistant.</i>', 'ufaqsw' ),
		)
	);

	// -------------------------------------------------------------------------
	// APPEARANCE TAB
	// -------------------------------------------------------------------------

	$cmb->add_field(
		array(
			'name'        => __( 'Floating Button Icon', 'ufaqsw' ),
			'id'          => 'floating_button_icon',
			'type'        => 'file',
			'description' => __( '<i>Upload a custom icon for the floating FAQ Assistant button. Recommended size: 80×80 pixels. A transparent background (PNG, SVG) looks best. If no icon is uploaded, a default icon will be used.</i>', 'ufaqsw' ),
			'options'     => array(
				'url' => false,
			),
			'text'         => array(
				'add_upload_file_text' => __( 'Add Icon', 'ufaqsw' ),
			),
			'preview_size' => 'thumbnail',
		)
	);

	$cmb->add_field(
		array(
			'name'        => __( 'Enable Pulse Ring Animation', 'ufaqsw' ),
			'id'          => 'pulse_enabled',
			'type'        => 'checkbox',
			'description' => __( '<i>Show a subtle pulse animation around the floating button when the page loads (plays 3 times then stops). Helps draw attention to the assistant.</i>', 'ufaqsw' ),
		)
	);

	$cmb->add_field(
		array(
			'name'        => __( 'Enable Notification Badge', 'ufaqsw' ),
			'id'          => 'badge_enabled',
			'type'        => 'checkbox',
			'description' => __( '<i>Display a small red dot badge on the floating button to indicate there is something new or to nudge visitors to open the assistant.</i>', 'ufaqsw' ),
		)
	);

	$cmb->add_field(
		array(
			'name'        => __( 'Header Background Color', 'ufaqsw' ),
			'id'          => 'header_background_color',
			'type'        => 'colorpicker',
			'default'     => '#1a185e',
			'description' => __( '<i>Choose the background color of the FAQ Assistant header. This color is also used as the primary accent throughout the interface (borders, buttons, hover states).</i>', 'ufaqsw' ),
		)
	);

	$cmb->add_field(
		array(
			'name'        => __( 'Header Text Color', 'ufaqsw' ),
			'id'          => 'header_text_color',
			'type'        => 'colorpicker',
			'default'     => '#fff',
			'description' => __( '<i>Select the color of the text and icons in the FAQ Assistant header.</i>', 'ufaqsw' ),
		)
	);

	$cmb->add_field(
		array(
			'name'        => __( 'Loading Animation Color', 'ufaqsw' ),
			'id'          => 'loading_animation_color',
			'type'        => 'colorpicker',
			'default'     => '#222',
			'description' => __( '<i>Choose the color for the animated dots shown while the FAQ Assistant is loading content.</i>', 'ufaqsw' ),
		)
	);

	// -------------------------------------------------------------------------
	// UI LABELS & MESSAGES TAB
	// -------------------------------------------------------------------------

	$cmb->add_field(
		array(
			'name'        => __( 'Floating Button Title', 'ufaqsw' ),
			'id'          => 'floating_button_title',
			'type'        => 'text',
			'description' => __( '<i>The tooltip text displayed when hovering over the floating button.</i>', 'ufaqsw' ),
		)
	);

	$cmb->add_field(
		array(
			'name'        => __( 'Window Headline', 'ufaqsw' ),
			'id'          => 'assistant_window_headline',
			'type'        => 'text',
			'default'     => __( 'Welcome to our Help Center!', 'ufaqsw' ),
			'description' => __( '<i>The main title displayed at the top of the FAQ Assistant window.</i>', 'ufaqsw' ),
		)
	);

	$cmb->add_field(
		array(
			'name'        => __( 'Window Intro Text', 'ufaqsw' ),
			'id'          => 'assistant_window_intro_text',
			'type'        => 'text',
			'default'     => __( 'Explore common questions and answers.', 'ufaqsw' ),
			'description' => __( '<i>A brief subtitle shown below the headline on the home screen of the assistant.</i>', 'ufaqsw' ),
		)
	);

	$cmb->add_field(
		array(
			'name'        => __( 'Back Button Tooltip', 'ufaqsw' ),
			'id'          => 'assistant_back_button_title',
			'type'        => 'text',
			'default'     => __( 'Go back to the previous view', 'ufaqsw' ),
			'description' => __( '<i>Tooltip text for the back navigation button.</i>', 'ufaqsw' ),
		)
	);

	$cmb->add_field(
		array(
			'name'        => __( 'Close Button Tooltip', 'ufaqsw' ),
			'id'          => 'assistant_close_button_title',
			'type'        => 'text',
			'default'     => __( 'Close the window', 'ufaqsw' ),
			'description' => __( '<i>Tooltip text for the close button.</i>', 'ufaqsw' ),
		)
	);

	$cmb->add_field(
		array(
			'name'        => __( 'Preloader Text', 'ufaqsw' ),
			'id'          => 'preloader_text',
			'type'        => 'text',
			'default'     => '',
			'description' => __( '<i>Optional text shown below the loading animation (e.g., "Loading…").</i>', 'ufaqsw' ),
		)
	);

	$cmb->add_field(
		array(
			'name'        => __( 'FAQ Count Text', 'ufaqsw' ),
			'id'          => 'faqs_count_text',
			'type'        => 'text',
			'default'     => __( '[count] Frequently Asked Questions', 'ufaqsw' ),
			'description' => __( '<i>Text template showing the number of FAQs per group. Use <code>[count]</code> as the number placeholder.</i>', 'ufaqsw' ),
		)
	);

	$cmb->add_field(
		array(
			'name'    => __( 'Body Text', 'ufaqsw' ),
			'desc'    => __( '<i>Text displayed above the list of FAQ categories on the home screen of the assistant.</i>', 'ufaqsw' ),
			'default' => __( 'Browse our FAQ categories below to quickly find answers grouped by topic.', 'ufaqsw' ),
			'id'      => 'body_text',
			'type'    => 'wysiwyg',
			'options' => array( 'textarea_rows' => 6 ),
		)
	);

	$cmb->add_field(
		array(
			'name'    => __( 'Bottom Text', 'ufaqsw' ),
			'desc'    => __( '<i>Content shown at the very bottom of the FAQ Assistant window on every screen (except the Ask a Question form). Good for a call-to-action or support link.</i>', 'ufaqsw' ),
			'id'      => 'bottom_text',
			'type'    => 'wysiwyg',
			'options' => array( 'textarea_rows' => 6 ),
		)
	);

	// -------------------------------------------------------------------------
	// SEARCH TAB
	// -------------------------------------------------------------------------

	$cmb->add_field(
		array(
			'name'        => __( 'Enable Search', 'ufaqsw' ),
			'id'          => 'search_enabled',
			'type'        => 'checkbox',
			'description' => __( '<i>Display a live search bar at the top of the FAQ Assistant. Users can type to instantly filter questions across all groups.</i>', 'ufaqsw' ),
		)
	);

	$cmb->add_field(
		array(
			'name'        => __( 'Search Placeholder', 'ufaqsw' ),
			'id'          => 'search_placeholder',
			'type'        => 'text',
			'default'     => __( 'Search FAQs…', 'ufaqsw' ),
			'description' => __( '<i>Placeholder text shown inside the search input when it is empty.</i>', 'ufaqsw' ),
		)
	);

	$cmb->add_field(
		array(
			'name'        => __( 'Search in Answer Content', 'ufaqsw' ),
			'id'          => 'search_in_answers',
			'type'        => 'checkbox',
			'description' => __( '<i>When enabled, search also looks inside the answer body (not just the question title). May show more results but could be less precise.</i>', 'ufaqsw' ),
		)
	);

	$cmb->add_field(
		array(
			'name'        => __( 'No Results Text', 'ufaqsw' ),
			'id'          => 'search_no_results_text',
			'type'        => 'text',
			'default'     => __( 'No results found for "[query]".', 'ufaqsw' ),
			'description' => __( '<i>Message shown when the search returns no results. Use <code>[query]</code> as a placeholder for the user\'s search term.</i>', 'ufaqsw' ),
		)
	);

	// -------------------------------------------------------------------------
	// ASK A QUESTION TAB
	// -------------------------------------------------------------------------

	$cmb->add_field(
		array(
			'name'        => __( 'Enable "Ask a Question"', 'ufaqsw' ),
			'id'          => 'ask_enabled',
			'type'        => 'checkbox',
			'description' => __( '<i>Show an "Ask a Question" button in the FAQ Assistant that opens a contact form. Submitted questions are emailed to the address below.</i>', 'ufaqsw' ),
		)
	);

	$cmb->add_field(
		array(
			'name'        => __( 'Notification Email', 'ufaqsw' ),
			'id'          => 'ask_notification_email',
			'type'        => 'text_email',
			'description' => __( '<i>Email address to receive question submissions. Leave blank to use the site admin email.</i>', 'ufaqsw' ),
		)
	);

	$cmb->add_field(
		array(
			'name'        => __( 'Form Title', 'ufaqsw' ),
			'id'          => 'ask_form_title',
			'type'        => 'text',
			'default'     => __( 'Ask Us a Question', 'ufaqsw' ),
			'description' => __( '<i>The heading displayed at the top of the Ask a Question screen.</i>', 'ufaqsw' ),
		)
	);

	$cmb->add_field(
		array(
			'name'        => __( 'Button Label', 'ufaqsw' ),
			'id'          => 'ask_button_label',
			'type'        => 'text',
			'default'     => __( 'Ask a Question', 'ufaqsw' ),
			'description' => __( '<i>Label for the button that opens the Ask a Question form (shown at the bottom of the FAQ list and on the "no results" screen).</i>', 'ufaqsw' ),
		)
	);

	$cmb->add_field(
		array(
			'name'        => __( 'Name Field Label', 'ufaqsw' ),
			'id'          => 'ask_name_label',
			'type'        => 'text',
			'default'     => __( 'Your Name', 'ufaqsw' ),
		)
	);

	$cmb->add_field(
		array(
			'name'        => __( 'Email Field Label', 'ufaqsw' ),
			'id'          => 'ask_email_label',
			'type'        => 'text',
			'default'     => __( 'Your Email', 'ufaqsw' ),
		)
	);

	$cmb->add_field(
		array(
			'name'        => __( 'Question Field Label', 'ufaqsw' ),
			'id'          => 'ask_question_label',
			'type'        => 'text',
			'default'     => __( 'Your Question', 'ufaqsw' ),
		)
	);

	$cmb->add_field(
		array(
			'name'    => __( 'Submit Button Label', 'ufaqsw' ),
			'id'      => 'ask_submit_label',
			'type'    => 'text',
			'default' => __( 'Send Question', 'ufaqsw' ),
		)
	);

	$cmb->add_field(
		array(
			'name'    => __( 'Cancel Button Label', 'ufaqsw' ),
			'id'      => 'ask_cancel_label',
			'type'    => 'text',
			'default' => __( 'Cancel', 'ufaqsw' ),
		)
	);

	$cmb->add_field(
		array(
			'name'        => __( 'Enable GDPR Consent Checkbox', 'ufaqsw' ),
			'id'          => 'ask_gdpr_enabled',
			'type'        => 'checkbox',
			'description' => __( '<i>Require users to check a consent checkbox before submitting the form. Useful for GDPR compliance.</i>', 'ufaqsw' ),
		)
	);

	$cmb->add_field(
		array(
			'name'        => __( 'GDPR Consent Text', 'ufaqsw' ),
			'id'          => 'ask_gdpr_text',
			'type'        => 'text',
			'default'     => __( 'I agree to the privacy policy and consent to being contacted about my question.', 'ufaqsw' ),
			'description' => __( '<i>Label text next to the consent checkbox. You may include HTML links (e.g., to your privacy policy page).</i>', 'ufaqsw' ),
		)
	);

	$cmb->add_field(
		array(
			'name'        => __( 'GDPR Validation Error', 'ufaqsw' ),
			'id'          => 'ask_gdpr_error',
			'type'        => 'text',
			'default'     => __( 'Please accept the privacy policy to continue.', 'ufaqsw' ),
			'description' => __( '<i>Error message shown if the user tries to submit without checking the consent box.</i>', 'ufaqsw' ),
		)
	);

	$cmb->add_field(
		array(
			'name'    => __( 'Success Screen Title', 'ufaqsw' ),
			'id'      => 'ask_success_title',
			'type'    => 'text',
			'default' => __( 'Question Received!', 'ufaqsw' ),
		)
	);

	$cmb->add_field(
		array(
			'name'    => __( 'Success Screen Message', 'ufaqsw' ),
			'id'      => 'ask_success_message',
			'type'    => 'textarea_small',
			'default' => __( 'Thank you! We\'ll review your question and get back to you as soon as possible.', 'ufaqsw' ),
		)
	);

	$cmb->add_field(
		array(
			'name'    => __( '"Back to FAQs" Button Label', 'ufaqsw' ),
			'id'      => 'ask_back_to_faqs_label',
			'type'    => 'text',
			'default' => __( 'Back to FAQs', 'ufaqsw' ),
		)
	);

	// -------------------------------------------------------------------------
	// FEEDBACK & RATINGS TAB
	// -------------------------------------------------------------------------

	$cmb->add_field(
		array(
			'name'        => __( 'Enable "Was this helpful?" Feedback', 'ufaqsw' ),
			'id'          => 'feedback_enabled',
			'type'        => 'checkbox',
			'description' => __( '<i>Show a thumbs up / thumbs down rating at the bottom of each FAQ answer. Results are stored and visible in the FAQ analytics (see below).</i>', 'ufaqsw' ),
		)
	);

	$cmb->add_field(
		array(
			'name'    => __( 'Feedback Label', 'ufaqsw' ),
			'id'      => 'feedback_label',
			'type'    => 'text',
			'default' => __( 'Was this helpful?', 'ufaqsw' ),
		)
	);

	$cmb->add_field(
		array(
			'name'    => __( '"Helpful" Button Label', 'ufaqsw' ),
			'id'      => 'feedback_helpful_text',
			'type'    => 'text',
			'default' => __( 'Yes, helpful', 'ufaqsw' ),
		)
	);

	$cmb->add_field(
		array(
			'name'    => __( '"Not Helpful" Button Label', 'ufaqsw' ),
			'id'      => 'feedback_not_helpful_text',
			'type'    => 'text',
			'default' => __( 'Not helpful', 'ufaqsw' ),
		)
	);

	$cmb->add_field(
		array(
			'name'    => __( 'Thank You Message', 'ufaqsw' ),
			'id'      => 'feedback_thanks_text',
			'type'    => 'text',
			'default' => __( 'Thank you for your feedback!', 'ufaqsw' ),
		)
	);

	$cmb->add_field(
		array(
			'name'        => __( 'Enable Related Questions', 'ufaqsw' ),
			'id'          => 'related_enabled',
			'type'        => 'checkbox',
			'description' => __( '<i>Show a "Related Questions" section at the bottom of each answer, listing other questions from the same FAQ group.</i>', 'ufaqsw' ),
		)
	);

	$cmb->add_field(
		array(
			'name'    => __( 'Related Questions Section Title', 'ufaqsw' ),
			'id'      => 'related_title',
			'type'    => 'text',
			'default' => __( 'Related Questions', 'ufaqsw' ),
		)
	);

	$cmb->add_field(
		array(
			'name'        => __( 'Number of Related Questions', 'ufaqsw' ),
			'id'          => 'related_count',
			'type'        => 'text_small',
			'default'     => '3',
			'description' => __( '<i>How many related questions to show (max). Default: 3.</i>', 'ufaqsw' ),
			'attributes'  => array(
				'type'    => 'number',
				'min'     => '1',
				'max'     => '10',
			),
		)
	);
}

add_action( 'cmb2_admin_init', 'ufaqsw_register_settings_page' );

/**
 * Output custom HTML before the CMB2 form.
 */
function ufaqsw_add_html_before_cmb2_output( $cmb_id, $object_id, $object_type, $cmb ) {
	if ( 'ufaqsw_settings_page' !== $cmb_id ) {
		return;
	}
	echo '<div style="margin-bottom: 20px;">';
	echo esc_html__(
		'The FAQ Assistant adds an interactive, floating help icon to your website, giving visitors quick access to your FAQs in a sleek, chat-style interface. Use the settings below to enable the assistant and customize its behavior.',
		'ufaqsw'
	);
	echo '<br><a href="https://www.braintum.com/docs/ultimate-faq-solution/faq-assistant/" target="_blank" style="display:inline-block;margin-top:10px;">' . esc_html__( 'Read the FAQ Assistant Documentation.', 'ufaqsw' ) . '</a>';
	echo '</div>';
}
add_action( 'cmb2_before_form', 'ufaqsw_add_html_before_cmb2_output', 1, 4 );

/**
 * Returns all published pages as an ID => title array for select fields.
 */
function ufaqsw_get_all_pages_for_select() {
	$pages   = get_pages( array( 'post_status' => 'publish' ) );
	$options = array();
	foreach ( $pages as $page ) {
		$options[ $page->ID ] = $page->post_title;
	}
	return $options;
}
