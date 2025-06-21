<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName -- File name matches class name.

namespace BTRefiner\API;

/**
 * Class ChatGPT
 *
 * Handles communication with the OpenAI API for text refinement.
 */
class ChatGPT {

	/**
	 * OpenAI API key.
	 *
	 * @var string
	 */
	private string $api_key;
	private string $model = 'gpt-4';
	private string $language = 'en';

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->api_key = defined( 'CHATGPT_API_KEY' ) ? CHATGPT_API_KEY : '';
	}

	/**
	 * Set the API key.
	 *
	 * @param string $api_key
	 */
	public function set_api_key( string $api_key ): void {
		$this->api_key = $api_key;
	}

	/**
	 * Set the model.
	 *
	 * @param string $model
	 */
	public function set_model( string $model ): void {
		$this->model = $model;
	}

	/**
	 * Set the language.
	 *
	 * @param string $language
	 */
	public function set_language( string $language ): void {
		$this->language = $language;
	}

	/**
	 * Refine text using OpenAI API.
	 *
	 * @param string $text        The text to refine.
	 * @param string $instruction The instruction for refinement.
	 * @return string
	 */
	public function refine( string $text, string $instruction = 'Refine the text' ): string {
		if ( ! $this->api_key ) {
			return __( 'Missing OpenAI API key.', 'ufaqsw' );
		}

		// Append language instruction if not English
		if ( $this->language && $this->language !== 'en' ) {
			$instruction .= ' Respond in ' . $this->get_language_name( $this->language ) . '.';
		}

		$response = wp_remote_post(
			'https://api.openai.com/v1/chat/completions',
			array(
				'timeout' => 60, // seconds
				'headers' => array(
					'Authorization' => 'Bearer ' . $this->api_key,
					'Content-Type'  => 'application/json',
				),
				'body'    => wp_json_encode(
					array(
						'model'    => $this->model,
						'messages' => array(
							array(
								'role'    => 'system',
								'content' => $instruction,
							),
							array(
								'role'    => 'user',
								'content' => $text,
							),
						),
					)
				),
			)
		);

		if ( is_wp_error( $response ) ) {
			throw new \RuntimeException( 'Request to OpenAI API failed: ' . $response->get_error_message() );
		}

		$body = json_decode( wp_remote_retrieve_body( $response ), true );
		return $body['choices'][0]['message']['content'] ?? __( 'No response.', 'ufaqsw' );
	}

	/**
	 * Get language name by code.
	 *
	 * @param string $code
	 * @return string
	 */
	private function get_language_name( string $code ): string {
		$languages = array(
			'en' => 'English',
			'es' => 'Spanish',
			'fr' => 'French',
			'de' => 'German',
			'it' => 'Italian',
			'pt' => 'Portuguese',
			'nl' => 'Dutch',
			'pl' => 'Polish',
		);
		return $languages[ $code ] ?? $code;
	}
}
