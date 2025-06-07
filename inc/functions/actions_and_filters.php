<?php
/**
 * Actions and Filters Utility Functions
 *
 * This file contains utility functions for handling actions and filters
 * used in the Ultimate FAQ Solution plugin.
 *
 * @package UltimateFAQSolution
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Callback function for the title filter.
 *
 * This function is used to modify or filter the title based on specific requirements.
 * It is hooked into a WordPress filter to allow customization of the title.
 *
 * @param string $title The original title.
 * @return string The modified title.
 */
function ufaqsw_the_title( $title ) {
	return $title;
}
add_filter( 'ufaqsw_the_title', 'ufaqsw_the_title' );



/**
 * Callback function for the "Simplify variables" filter.
 *
 * This function is used to process and simplify variables passed through
 * the filter. It is typically hooked into a WordPress filter to modify
 * or sanitize data before it is used elsewhere in the plugin.
 *
 * @param mixed $data The variable to be simplified. The type of this
 *                        parameter depends on the context in which the
 *                        filter is applied.
 * @return mixed The simplified version of the variable.
 */
function ufaqsw_simplify_variables( $data = array() ) {
	$formated_data             = array();
	$formated_data['question'] = ( isset( $data['ufaqsw_faq_question'] ) ? apply_filters( 'ufaqsw_the_title', $data['ufaqsw_faq_question'] ) : '' );
	$formated_data['answer']   = ( isset( $data['ufaqsw_faq_answer'] ) ? apply_filters( 'the_content', $data['ufaqsw_faq_answer'] ) : '' );
	return $formated_data;
}
add_filter( 'ufaqsw_simplify_variables', 'ufaqsw_simplify_variables' );


/**
 * Simplifies and formats configuration variables for a given FAQ post ID.
 *
 * This function retrieves various metadata associated with a FAQ post,
 * processes the data, and returns it in a simplified and formatted array.
 *
 * @param int $id The ID of the FAQ post.
 *
 * @return array An associative array containing the formatted configuration variables:
 *               - 'title_color' (string): The color of the title.
 *               - 'title_font_size' (string): The font size of the title.
 *               - 'question_color' (string): The color of the question text.
 *               - 'answer_color' (string): The color of the answer text.
 *               - 'question_background_color' (string): The background color of the question.
 *               - 'answer_background_color' (string): The background color of the answer.
 *               - 'question_font_size' (string): The font size of the question text.
 *               - 'answer_font_size' (string): The font size of the answer text.
 *               - 'template' (string): The template to use (default: 'default').
 *               - 'showall' (int): Whether to show all answers (1 for true, 0 for false).
 *               - 'hidetitle' (int): Whether to hide the title (1 for true, 0 for false).
 *               - 'normal_icon' (string): The icon to display in normal state.
 *               - 'active_icon' (string): The icon to display in active state.
 *               - 'behaviour' (string): The FAQ behavior (default: 'toggle').
 *
 * @hook filter ufaqsw_simplify_configuration_variables
 */
function ufaqsw_simplify_configuration_variables( $id ) {

	$title_color               = get_post_meta( $id, 'ufaqsw_title_color' );
	$title_font_size           = get_post_meta( $id, 'ufaqsw_title_font_size' );
	$question_color            = get_post_meta( $id, 'ufaqsw_question_color' );
	$answer_color              = get_post_meta( $id, 'ufaqsw_answer_color' );
	$question_background_color = get_post_meta( $id, 'ufaqsw_question_background_color' );
	$answer_background_color   = get_post_meta( $id, 'ufaqsw_answer_background_color' );
	$question_font_size        = get_post_meta( $id, 'ufaqsw_question_font_size' );
	$answer_font_size          = get_post_meta( $id, 'ufaqsw_answer_font_size' );
	$template                  = get_post_meta( $id, 'ufaqsw_template' );
	$showall                   = get_post_meta( $id, 'ufaqsw_answer_showall' );
	$hidetitle                 = get_post_meta( $id, 'ufaqsw_hide_title' );
	$normal_icon               = get_post_meta( $id, 'ufaqsw_normal_icon' );
	$active_icon               = get_post_meta( $id, 'ufaqsw_active_icon' );
	$behaviour                 = get_post_meta( $id, 'ufaqsw_faq_behaviour' );
	$question_bold             = get_post_meta( $id, 'ufaqsw_question_bold' );

	$formated_data                              = array();
	$formated_data['title_color']               = ( isset( $title_color[0] ) ? $title_color[0] : '' );
	$formated_data['title_font_size']           = ( isset( $title_font_size[0] ) ? $title_font_size[0] : '' );
	$formated_data['question_color']            = ( isset( $question_color[0] ) ? $question_color[0] : '' );
	$formated_data['answer_color']              = ( isset( $answer_color[0] ) ? $answer_color[0] : '' );
	$formated_data['question_background_color'] = ( isset( $question_background_color[0] ) ? $question_background_color[0] : '' );
	$formated_data['answer_background_color']   = ( isset( $answer_background_color[0] ) ? $answer_background_color[0] : '' );
	$formated_data['question_font_size']        = ( isset( $question_font_size[0] ) ? $question_font_size[0] : '' );
	$formated_data['answer_font_size']          = ( isset( $answer_font_size[0] ) ? $answer_font_size[0] : '' );
	$formated_data['template']                  = ( isset( $template[0] ) ? $template[0] : 'default' );
	$formated_data['showall']                   = ( isset( $showall[0] ) && 'on' === $showall[0] ? 1 : 0 );
	$formated_data['hidetitle']                 = ( isset( $hidetitle[0] ) && 'on' === $hidetitle[0] ? 1 : 0 );
	$formated_data['normal_icon']               = ( isset( $normal_icon[0] ) ? $normal_icon[0] : '' );
	$formated_data['active_icon']               = ( isset( $active_icon[0] ) ? $active_icon[0] : '' );
	$formated_data['behaviour']                 = ( isset( $behaviour[0] ) ? $behaviour[0] : 'toggle' );
	$formated_data['question_bold']             = ( isset( $question_bold[0] ) && 'on' === $question_bold[0] ? 1 : 0 );
	return $formated_data;
}
add_filter( 'ufaqsw_simplify_configuration_variables', 'ufaqsw_simplify_configuration_variables' );


/**
 * Simplifies the FAQs array by returning the first element if it exists and is not empty.
 *
 * This function is hooked to the 'ufaqsw_simplify_faqs' filter.
 *
 * @param array $faqs The array of FAQs to simplify.
 *
 * @return array The simplified FAQs array. Returns the first element if it exists and is not empty,
 *               otherwise returns an empty array.
 */
function ufaqsw_simplify_faqs( $faqs ) {
	if ( isset( $faqs[0] ) && ! empty( $faqs[0] ) ) {
		return $faqs[0];
	} else {
		return array();
	}
}
add_filter( 'ufaqsw_simplify_faqs', 'ufaqsw_simplify_faqs' );

/**
 * Filters the template used by the plugin.
 *
 * This function is hooked to the 'ufaqsw_template_filter' filter and allows
 * modification of the template being used.
 *
 * @param string $template The current template being used.
 * @return string The filtered template.
 */
function ufaqsw_template_filter( $template ) {
	return $template;
}
add_filter( 'ufaqsw_template_filter', 'ufaqsw_template_filter' );

/**
 * Defines a list of allowed HTML tags and their attributes for use in the plugin.
 *
 * This function returns an array of HTML tags and their respective allowed attributes
 * that can be safely used within the plugin's context. It is typically used to sanitize
 * or whitelist HTML content.
 *
 * @return array An associative array where the keys are HTML tag names and the values
 *               are arrays of allowed attributes for each tag.
 *
 * Example structure:
 * [
 *     'a'    => [
 *         'href'   => [], // Allows the 'href' attribute for <a> tags.
 *         'class'  => [], // Allows the 'class' attribute for <a> tags.
 *         'target' => [], // Allows the 'target' attribute for <a> tags.
 *     ],
 *     'b'    => [
 *         'title' => [], // Allows the 'title' attribute for <b> tags.
 *     ],
 *     ...
 * ]
 */
function ufaqsw_wses_allowed_menu_html() {
	return array(
		'a'    => array(
			'href'   => array(),
			'class'  => array(),
			'target' => array(),
		),
		'b'    => array(
			'title' => array(),
		),
		'p'    => array( 'title' => array() ),
		'u'    => array( 'title' => array() ),
		'i'    => array( 'title' => array() ),
		'span' => array(),
		'br'   => array(),
	);
}

/**
 * Filters the allowed HTML tags for a given context to include additional attributes for the <iframe> tag.
 *
 * This function modifies the allowed HTML tags for the 'post' context by adding support for the <iframe> tag
 * with specific attributes such as 'src', 'height', 'width', 'frameborder', and 'allowfullscreen'.
 *
 * @param array  $tags    An array of allowed HTML tags and their attributes.
 * @param string $context The context for which HTML tags are being filtered. Default is 'post'.
 *
 * @return array The modified array of allowed HTML tags and attributes.
 *
 * @hook wp_kses_allowed_html
 */
function ufaqsw_wpkses_post_tags( $tags, $context ) {
	if ( 'post' === $context ) {
		$tags['iframe'] = array(
			'src'             => true,
			'height'          => true,
			'width'           => true,
			'frameborder'     => true,
			'allowfullscreen' => true,
		);
	}

	return $tags;
}

add_filter( 'wp_kses_allowed_html', 'ufaqsw_wpkses_post_tags', 10, 2 );

add_filter(
	'template_include',
	function ( $template ) {

		if ( isset( $_GET['ufaqsw-preview'] ) && ! empty( $_GET['ufaqsw-preview'] ) ) {
			if ( isset( $_GET['_wpnonce'] ) && wp_verify_nonce( $_GET['_wpnonce'], 'ufaqsw_preview_nonce' ) ) { //phpcs:ignore
				return UFAQSW__PLUGIN_DIR . '/inc/templates/single-faq-preview.php';
			} else {
				wp_die( esc_html__( 'Invalid nonce verification.', 'ufaqsw' ) );
			}
		}

		return $template;
	}
);

add_action(
	'post_submitbox_misc_actions',
	function () {
		global $post;

		if ( 'ufaqsw' !== $post->post_type ) {
			return;
		}
		if ( ! current_user_can( 'edit_post', $post->ID ) ) {
			return;
		}

		$preview_url = add_query_arg(
			array(
				'ufaqsw-preview' => $post->ID,
				'preview'        => true,
				'_wpnonce'       => wp_create_nonce( 'ufaqsw_preview_nonce' ),
			),
			home_url()
		);

		echo '<div class="misc-pub-section">
			<a href="' . esc_url( $preview_url ) . '" class="button" target="_blank">' . esc_html__( 'Preview FAQ Group', 'ufaqsw' ) . '</a>
			<p class="description">' . esc_html__( 'Click the button to preview the FAQ on the front end. Make sure to save/update the FAQ group first.', 'ufaqsw' ) . '</p>
		</div>';

		echo '<div class="misc-pub-section">';
		echo '<strong>' . esc_html__( 'Note:', 'ufaqsw' ) . '</strong> ' . esc_html__( 'Creating an FAQ Group does not mean it will automatically appear on your website. ', 'ufaqsw' );
		echo esc_html__( 'You need to display the group using the shortcode or the Gutenberg block on a page or post.', 'ufaqsw' );
		echo '</div>';
	}
);

add_action( 'admin_menu', 'ufaqsw_reorder_submenu', 999 );

/**
 * Reorders the submenu items under the FAQ Groups menu.
 */
function ufaqsw_reorder_submenu() {
	global $submenu;

	$parent_slug = 'edit.php?post_type=ufaqsw';

	if ( ! isset( $submenu[ $parent_slug ] ) ) {
		return;
	}

	$desired_order = array(
		'edit.php?post_type=ufaqsw',        // All FAQ Groups.
		'post-new.php?post_type=ufaqsw',    // Add New FAQ Group.
		'edit.php?post_type=ufaqsw_appearance', // Appearance (move this to 2nd).
		'ufaqsw_chatbot_settings',          // FAQ Assistant (move this to 3rd).
		'ufaqsw-settings',                  // Settings & Help.
		'faq-export-import',                // Export/Import.
	);

	$new_submenu = array();

	// Build new submenu array in desired order.
	foreach ( $desired_order as $slug ) {
		foreach ( $submenu[ $parent_slug ] as $item ) {
			if ( $item[2] === $slug ) {
				$new_submenu[] = $item;
				break;
			}
		}
	}

	// Append any submenu items not in the desired order list, keep original order.
	foreach ( $submenu[ $parent_slug ] as $item ) {
		if ( ! in_array( $item[2], $desired_order, true ) ) {
			$new_submenu[] = $item;
		}
	}

	// Replace original submenu with reordered one.
	$submenu[ $parent_slug ] = $new_submenu; //phpcs:ignore.
}

add_filter( 'parent_file', 'ufaqsw_appearance_set_menu_parent' );
add_filter( 'submenu_file', 'ufaqsw_appearance_set_submenu_file' );

/**
 * Sets the parent menu as active for the Appearance post type.
 *
 * @param string $parent_file The parent file.
 * @return string The modified parent file.
 */
function ufaqsw_appearance_set_menu_parent( $parent_file ) {
	global $current_screen;

	if ( isset( $current_screen->post_type ) && 'ufaqsw_appearance' === $current_screen->post_type ) {
		return 'edit.php?post_type=ufaqsw'; // Set the FAQ Group menu as active.
	}

	return $parent_file;
}

/**
 * Sets the submenu highlight for the Appearance post type.
 *
 * @param string $submenu_file The submenu file.
 * @return string The modified submenu file.
 */
function ufaqsw_appearance_set_submenu_file( $submenu_file ) {
	global $current_screen;

	if ( isset( $current_screen->post_type ) && 'ufaqsw_appearance' === $current_screen->post_type ) {
		return 'edit.php?post_type=ufaqsw_appearance'; // Set the submenu highlight.
	}

	return $submenu_file;
}

add_action( 'add_meta_boxes', 'ufaqsw_add_faq_group_appearance_metabox' );

/**
 * Adds the FAQ Group Appearance metabox to the FAQ Group post type.
 */
function ufaqsw_add_faq_group_appearance_metabox() {
	add_meta_box(
		'faq_group_appearance',
		esc_html__( 'FAQ Appearance', 'ufaqsw' ),
		'ufaqsw_render_faq_group_appearance_metabox',
		'ufaqsw',
		'side',
		'default'
	);
}

/**
 * Renders the FAQ Group Appearance metabox.
 *
 * This metabox allows users to select an appearance for the FAQ group.
 *
 * @param WP_Post $post The current post object.
 */
function ufaqsw_render_faq_group_appearance_metabox( $post ) {
	$selected_id = get_post_meta( $post->ID, 'linked_faq_appearance_id', true );

	if ( empty( $selected_id ) || ! get_post( $selected_id ) ) {
		// If no valid appearance is linked, use the default appearance ID.
		$selected_id = get_option( 'faq_default_appearance_id', 0 );
	}

	$appearances = get_posts(
		array(
			'post_type'   => 'ufaqsw_appearance',
			'post_status' => 'publish',
			'numberposts' => -1,
		)
	);

	wp_nonce_field( 'save_faq_group_appearance', 'faq_group_appearance_nonce' );

	echo '<label for="faq_appearance_select">' . esc_html__( 'Select an appearance:', 'ufaqsw' ) . '</label>';
	echo '<select name="faq_appearance_select" id="faq_appearance_select" style="width:100%;">';

	foreach ( $appearances as $appearance ) {
		$selected = ( $appearance->ID === $selected_id ) ? 'selected' : '';
		echo '<option value="' . esc_attr( $appearance->ID ) . '" ' . esc_attr( $selected ) . '>' . esc_html( $appearance->post_title ) . '</option>';
	}

	echo '</select>';

	// Add edit link if a valid appearance is selected.
	if ( $selected_id && get_post( $selected_id ) ) {
		$edit_url = get_edit_post_link( $selected_id );
		if ( $edit_url ) {
			echo '<p style="margin-top:8px;"><a href="' . esc_url( $edit_url ) . '" target="_blank">' . esc_html__( 'Edit Appearance', 'ufaqsw' ) . '</a></p>';
		}
	}
}

add_filter( 'post_updated_messages', 'ufaqsw_faq_group_updated_messages' );

/**
 * Adds custom post updated messages for the FAQ Group post type.
 *
 * This function customizes the messages displayed in the WordPress admin
 * when a FAQ Group post is updated, published, or otherwise modified.
 *
 * @param array $messages The existing post updated messages.
 * @return array The modified post updated messages.
 */
function ufaqsw_faq_group_updated_messages( $messages ) {
	global $post;

	$post_type = get_post_type( $post );
	if ( 'ufaqsw' !== $post_type ) {
		return $messages;
	}

	$messages['ufaqsw'] = array(
		0  => '', // Unused.
		1  => __( 'FAQ Group published.', 'ufaqsw' ),
		2  => __( 'Custom field updated.', 'ufaqsw' ),
		3  => __( 'Custom field deleted.', 'ufaqsw' ),
		4  => __( 'FAQ Group updated.', 'ufaqsw' ),
		5  => isset( $_GET['revision'] ) ? sprintf( // phpcs:ignore
			/* translators: %s: Date and time of the revision */
			__( 'FAQ Group restored to revision from %s.', 'ufaqsw' ),
			wp_post_revision_title( (int) $_GET['revision'], false ) // phpcs:ignore
		) : false,
		6  => __( 'FAQ Group published.', 'ufaqsw' ),
		7  => __( 'FAQ Group saved.', 'ufaqsw' ),
		8  => __( 'FAQ Group submitted.', 'ufaqsw' ),
		9  => sprintf(
			/* translators: %s: Date and time the FAQ Group is scheduled for */
			__( 'FAQ Group scheduled for: <strong>%s</strong>.', 'ufaqsw' ),
			date_i18n( 'M j, Y @ G:i', strtotime( $post->post_date ) )
		),
		10 => __( 'FAQ Group draft updated.', 'ufaqsw' ),
	);

	return $messages;
}
