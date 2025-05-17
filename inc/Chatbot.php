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
	 * Enqueue the React chatbot script.
	 *
	 * @return void
	 */
	public function enqueue_react_chatbot_script() {
		wp_enqueue_script(
			'react-chatbot',
			UFAQSW_ASSETS_URL . 'dist/bundle.js',
			array(),
			fileatime( UFAQSW__PLUGIN_DIR . 'assets/dist/bundle.js' ),
			true
		);

		// Pass data to the React chatbot script.
		wp_localize_script(
			'react-chatbot',
			'chatbotData',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'chatbot_nonce' ),
				'someKey' => 'someValue', // Add your custom data here.
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

		echo '<chatbot-component></chatbot-component>';
	}

}


