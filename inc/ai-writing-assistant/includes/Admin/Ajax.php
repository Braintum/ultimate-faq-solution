<?php
/**
 * AJAX handler for the AI Writing Assistant in the Ultimate FAQ Solution plugin.
 *
 * @package Ultimate_FAQ_Solution
 * @subpackage AI_Writing_Assistant
 */

namespace BTRefiner\Admin;

use BTRefiner\API\ChatGPT;

/**
 * Handles AJAX actions for the AI Writing Assistant in the Ultimate FAQ Solution plugin.
 */
class Ajax {


	/**
	 * Register AJAX actions.
	 */
	public function __construct() {
		add_action( 'wp_ajax_refine_text', array( $this, 'handle_refine' ) );
	}

	/**
	 * Handle AJAX request for text refinement.
	 */
	public function handle_refine() {
		check_ajax_referer( 'ufaqsw-ajax-nonce', 'nonce' );

		$text        = isset( $_POST['content'] ) ? sanitize_text_field( wp_unslash( $_POST['content'] ) ) : '';
		$instruction = isset( $_POST['cmd'] ) ? sanitize_text_field( wp_unslash( $_POST['cmd'] ) ) : 'Refine and improve the following text.';

		if ( empty( $text ) ) {
			wp_send_json_error( __( 'No text provided.', 'ufaqsw' ) );
			wp_die();
		}

		try {
			$chatgpt = new ChatGPT();
			$chatgpt->set_api_key( (string) cmb2_get_option( 'ufaqsw_ai_integration_settings', 'chatgpt_api_key' ) );
			$chatgpt->set_model( (string) cmb2_get_option( 'ufaqsw_ai_integration_settings', 'chatgpt_model' ) );
			$chatgpt->set_language( (string) cmb2_get_option( 'ufaqsw_ai_integration_settings', 'ai_language' ) );
			$result = $chatgpt->refine( $text, $instruction );

			wp_send_json_success( $result );

		} catch ( \Exception $e ) {
			wp_send_json(
				array(
					'success' => false,
					'data'    => $e->getMessage(),
				)
			);
			wp_die();
		}
	}
}
