<?php

namespace BTRefiner\API;

use BTRefiner\Exceptions\AuthException;
use BTRefiner\Exceptions\ModelNotFoundException;
use BTRefiner\Exceptions\ProviderUnavailableException;
use BTRefiner\Exceptions\RateLimitException;

/**
 * Handles any API endpoint that speaks the OpenAI chat/completions format.
 * OpenAI, Mistral, and Ollama all subclass this.
 */
abstract class OpenAICompatibleProvider extends AbstractProvider {

	/** Base URL, no trailing slash, no /chat/completions suffix. */
	protected string $base_url = '';

	public function complete( string $user_prompt, string $system_prompt = '', array $history = [] ): string {
		return $this->with_retry(
			function () use ( $user_prompt, $system_prompt, $history ) {
				return $this->do_complete( $user_prompt, $system_prompt, $history );
			}
		);
	}

	private function do_complete( string $user_prompt, string $system_prompt, array $history ): string {
		$system_prompt = $this->apply_language( $system_prompt );

		$messages = [];
		if ( $system_prompt ) {
			$messages[] = [ 'role' => 'system', 'content' => $system_prompt ];
		}
		foreach ( $history as $turn ) {
			$messages[] = [ 'role' => $turn['role'], 'content' => $turn['content'] ];
		}
		$messages[] = [ 'role' => 'user', 'content' => $user_prompt ];

		$headers = [ 'Content-Type' => 'application/json' ];
		if ( $this->api_key ) {
			$headers['Authorization'] = 'Bearer ' . $this->api_key;
		}

		$response = wp_remote_post(
			$this->base_url . '/chat/completions',
			[
				'timeout' => 60,
				'headers' => $headers,
				'body'    => wp_json_encode(
					[
						'model'    => $this->model,
						'messages' => $messages,
					]
				),
			]
		);

		if ( is_wp_error( $response ) ) {
			throw new ProviderUnavailableException(
				esc_html__( 'Request failed: ', 'ufaqsw' ) . esc_html( $response->get_error_message() )
			);
		}

		$status = (int) wp_remote_retrieve_response_code( $response );
		$body   = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( 401 === $status ) {
			throw new AuthException( esc_html__( 'Invalid or missing API key. Check your AI Integration settings.', 'ufaqsw' ) );
		}
		if ( 429 === $status ) {
			throw new RateLimitException( esc_html__( 'Your AI provider rate limit was reached. Please wait 30–60 seconds and try again. If this happens often, consider upgrading your API plan.', 'ufaqsw' ) );
		}
		if ( 404 === $status ) {
			throw new ModelNotFoundException(
				sprintf( esc_html__( 'Model "%s" not found on this provider.', 'ufaqsw' ), esc_html( $this->model ) )
			);
		}
		if ( isset( $body['error'] ) ) {
			throw new ProviderUnavailableException(
				esc_html__( 'API error: ', 'ufaqsw' ) . esc_html( $body['error']['message'] ?? 'Unknown error' )
			);
		}

		return $body['choices'][0]['message']['content'] ?? esc_html__( 'No response.', 'ufaqsw' );
	}
}
