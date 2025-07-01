<?php
/**
 * FAQGroupActions.php
 *
 * Handles actions related to FAQ groups, including meta box display and saving appearance settings.
 *
 * @package UltimateFAQSolution
 */

namespace Mahedi\UltimateFaqSolution\Functions;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class FAQGroupActions
 *
 * Handles actions related to FAQ groups, such as adding meta boxes for appearance settings
 * and saving the selected appearance for a FAQ group.
 *
 * @package UltimateFAQSolution
 */
class FAQGroupActions {

	/**
	 * Constructor for the class.
	 *
	 * Initializes any required properties or sets up hooks for the class.
	 */
	public function __construct() {

		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_meta' ) );
	}

	/**
	 * Adds a meta box for selecting the appearance of a FAQ group.
	 *
	 * This method registers a meta box that allows users to select an appearance for a FAQ group
	 * in the WordPress admin interface.
	 */
	public function add_meta_boxes() {
		add_meta_box(
			'faq_group_appearance',
			esc_html__( 'FAQ Appearance', 'ufaqsw' ),
			array( $this, 'faq_group_appearance_metabox' ),
			'ufaqsw',
			'side',
			'default'
		);

		add_meta_box(
			'ufaqsw_faq_group_shortcode',
			esc_html__( 'FAQ Shortcode', 'ufaqsw' ),
			array( $this, 'render_shortcode_metabox' ),
			'ufaqsw',
			'side',
			'default'
		);
	}

	/**
	 * Displays the meta box for selecting the appearance of a FAQ group.
	 *
	 * This method outputs the HTML for the meta box, allowing users to select an appearance
	 * for the FAQ group. It retrieves available appearances and displays them in a dropdown.
	 *
	 * @param WP_Post $post The current post object.
	 */
	public function faq_group_appearance_metabox( $post ) {

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

		echo '<div class="description" style="margin-bottom:8px;">';
		echo '<p>' . esc_html__( 'Select an appearance for this FAQ group. This will determine how the FAQs are displayed on the front end.', 'ufaqsw' ) . '</p>';
		echo '<p style="margin-bottom:8px;"><a href="https://www.braintum.com/docs/ultimate-faq-solution/displaying-faqs/appearance-settings/" target="_blank" rel="noopener noreferrer">' . esc_html__( 'Learn more about Appearance Settings', 'ufaqsw' ) . '</a></p>';
		echo '</div>';
		echo '</hr>';
		echo '<label for="faq_appearance_select">' . esc_html__( 'Select an appearance:', 'ufaqsw' ) . '</label>';
		echo '<select name="faq_appearance_select" id="faq_appearance_select" style="width:100%;">';

		foreach ( $appearances as $appearance ) {
			$selected = ( $appearance->ID === (int) $selected_id ) ? 'selected' : '';
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

	/**
	 * Renders the FAQ Shortcode metabox content.
	 *
	 * Displays the shortcode for the current FAQ group, allowing users to copy it.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Post $post The current post object.
	 */
	public function render_shortcode_metabox( $post ) {
		$slug = $post->ID;
		if ( ! $slug ) {
			return;
		}
		$slug = (int) $slug; // Ensure the slug is an integer.

		echo '<p>' . esc_html__( 'Use the shortcode below to display this FAQ group in a page or post:', 'ufaqsw' ) . '</p>';
		echo '<input class="ufaqsw_admin_faq_shorcode_copy" type="text" readonly value="[ufaqsw id=' . esc_attr( $slug ) . ']" style="width:100%; font-family:monospace;">';
		echo '<p style="margin-top:5px;"><small>' . esc_html__( 'Copy and paste this into any post, page, or widget.', 'ufaqsw' ) . '</small></p>';
	}

	/**
	 * Saves the selected appearance for a FAQ group.
	 *
	 * This method processes the form submission from the meta box and saves the selected appearance
	 * for the FAQ group in the post meta.
	 *
	 * @param int $post_id The ID of the post being saved.
	 */
	public function save_meta( $post_id ) {

		// Check if our nonce is set.
		if ( ! isset( $_POST['faq_group_appearance_nonce'] ) ) {
			return;
		}

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $_POST['faq_group_appearance_nonce'], 'save_faq_group_appearance' ) ) { // phpcs:ignore
			return;
		}

		// Don't autosave.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Check the user's permissions.
		if ( isset( $_POST['post_type'] ) && 'ufaqsw' === $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}
		} else {
			return;
		}

		// Save the selected appearance.
		if ( isset( $_POST['faq_appearance_select'] ) ) {
			$appearance_id = intval( $_POST['faq_appearance_select'] );
			update_post_meta( $post_id, 'linked_faq_appearance_id', $appearance_id );
		}
	}
}
