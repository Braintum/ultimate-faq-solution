<?php

namespace BTRefiner\Admin;

use BTRefiner\API\ChatGPT;

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

		$text        = sanitize_text_field( $_POST['content'] ?? '' );
		$instruction = sanitize_text_field( $_POST['cmd'] ?? 'Refine and improve the following text.' );

		if ( ! $text ) {
			wp_send_json_error( 'No text provided.' );
			exit;
		}

		try {
			$chatgpt = new ChatGPT();
			$chatgpt->set_api_key( get_field( 'ufaqsw_ai_chatgpt_api_key', 'options' ) );
			$chatgpt->set_model( get_field( 'ufaqsw_ai_model', 'options' ) );
			$chatgpt->set_language( get_field( 'ufaqsw_ai_language', 'options' ) );
			$result  = $chatgpt->refine( $text, $instruction );
			
			wp_send_json_success( $result );

		} catch ( \Exception $e ) {
			wp_send_json( array(
				'success'  => false,
				'data' => $e->getMessage(),
			) );
			exit;
		}
	}
}
