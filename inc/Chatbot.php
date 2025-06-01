<?php
/**
 * Chatbot class
 *
 * @package UltimateFaqSolution
 * @author  Mahedi
 * @license GPL-2.0-or-later
 * @link    https://example.com
 */

namespace Mahedi\UltimateFaqSolution;

/**
 * Class Chatbot
 *
 * Handles the generation of FAQ schema for Rest purposes.
 */
class Chatbot {

	/**
	 * Constructor to initialize the Rest class.
	 *
	 * Adds the action to generate FAQ schema in the head section.
	 */
	public function __construct() {

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_react_chatbot_script' ) );
		add_action( 'wp_ajax_ufaqsw_get_faqs', array( $this, 'handle_get_faqs' ) );
		add_action( 'wp_ajax_nopriv_ufaqsw_get_faqs', array( $this, 'handle_get_faqs' ) );

		add_action( 'wp_footer', array( $this, 'bot_integration' ) );
	}

	/**
	 * Checks if the assistant (chatbot) feature is enabled based on plugin settings.
	 *
	 * Retrieves the chatbot enablement status from the plugin options. If enabled,
	 * further checks if the chatbot should be displayed on the current page, either
	 * globally or on specific pages as defined in the settings.
	 *
	 * @since 1.0.0
	 *
	 * @return bool True if the assistant is enabled for the current context, false otherwise.
	 */
	public function is_assistant_enabled() {

		$enable_chatbot = cmb2_get_option( 'ufaqsw_chatbot_settings', 'enable_chatbot' );
		if ( ! $enable_chatbot ) {
			return false;
		}

		$display_on = cmb2_get_option( 'ufaqsw_chatbot_settings', 'display_on' );
		if ( 'on' !== $display_on ) {
			$specific_pages = cmb2_get_option( 'ufaqsw_chatbot_settings', 'display_on_pages' );
			if ( ! is_array( $specific_pages ) || empty( $specific_pages ) || ! in_array( get_the_ID(), $specific_pages ) ) {
				return false;
			}

		}

		return $enable_chatbot;
	}

	/**
	 * Enqueue the React chatbot script.
	 *
	 * @return void
	 */
	public function enqueue_react_chatbot_script() {

		if ( ! $this->is_assistant_enabled() ) {
			return;
		}

		wp_enqueue_script(
			'react-chatbot',
			UFAQSW_ASSETS_URL . 'dist/bundle.js',
			array(),
			fileatime( UFAQSW__PLUGIN_DIR . 'assets/dist/bundle.js' ),
			true
		);

		$floating_button_icon_url = cmb2_get_option( 'ufaqsw_chatbot_settings', 'floating_button_icon' );
		if ( ! $floating_button_icon_url ) {
			$floating_button_icon_url = UFAQSW_ASSETS_URL . 'images/faq-bot-icon.svg'; // Default icon URL.
		}

		// Pass data to the React chatbot script.
		wp_localize_script(
			'react-chatbot',
			'chatbotData',
			array(
				'ajaxUrl'                => admin_url( 'admin-ajax.php' ),
				'nonce'                  => wp_create_nonce( 'chatbot_nonce' ),
				'floating_button_icon'   => $floating_button_icon_url,
				'floating_button_title'  => cmb2_get_option( 'ufaqsw_chatbot_settings', 'floating_button_title' ),
				'assistant_window_headline'  => cmb2_get_option( 'ufaqsw_chatbot_settings', 'assistant_window_headline' ),
				'assistant_window_intro_text'  => cmb2_get_option( 'ufaqsw_chatbot_settings', 'assistant_window_intro_text' ),
				'header_background_color'  => cmb2_get_option( 'ufaqsw_chatbot_settings', 'header_background_color' ),
				'header_text_color'  => cmb2_get_option( 'ufaqsw_chatbot_settings', 'header_text_color' ),
				'body_text'  => cmb2_get_option( 'ufaqsw_chatbot_settings', 'body_text' ),

			)
		);
	}

	/**
	 * Handle the AJAX request to fetch FAQs.
	 */
	public function handle_get_faqs() {

		// Verify the nonce for security.
		check_ajax_referer( 'chatbot_nonce', 'nonce' );

		$faq_args = array(
			'post_type'      => 'ufaqsw',
			'fields'         => 'ids',
			'posts_per_page' => -1,
			'post_status'    => 'publish',
			'orderby'        => 'date',
			'order'          => 'DESC',
			'meta_query'     => array(
				array(
					'key'     => 'ufaqsw_faq_item01',
					'value'   => '',
					'compare' => '!=',
				),
			),
		);

		$faq_groups = cmb2_get_option( 'ufaqsw_chatbot_settings', 'faq_groups' );
		if ( ! empty( $faq_groups ) ) {
			$faq_args['post__in'] = $faq_groups; // Filter by selected FAQ groups.
		}

		$faq_data = array();

		$faq_groups = get_posts( $faq_args );
		if ( ! $faq_groups ) {
			wp_send_json_error( __( 'No FAQs found.', 'ufaqsw' ) );
			return;
		}

		foreach ( $faq_groups as $faq_group ) {
			$faq_items = get_post_meta( $faq_group, 'ufaqsw_faq_item01' );
			$faq_items = isset( $faq_items[0] ) ? $faq_items[0] : $faq_items;
			$faq_items = array_map(
				function ( $item ) {
					return array(
						'question' => $item['ufaqsw_faq_question'],
						'answer'   => $item['ufaqsw_faq_answer'],
					);
				},
				$faq_items
			);

			$faq_data[] = array(
				'group' => get_the_title( $faq_group ),
				'items' => $faq_items,
			);
		}

		// Return the FAQ data as a JSON response.
		wp_send_json_success( $faq_data );
		wp_die(); // Always die in functions echoing AJAX content.
	}

	/**
	 * Chatbot component.
	 *
	 * @return void
	 */
	public function bot_integration() {

		if ( is_admin() ) {
			return;
		}

		if ( ! $this->is_assistant_enabled() ) {
			return;
		}

		echo '<chatbot-component></chatbot-component>';
	}

}


