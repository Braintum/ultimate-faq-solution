<?php

namespace BTRefiner\API;

use BTRefiner\Exceptions\AuthException;
use BTRefiner\Exceptions\ModelNotFoundException;
use BTRefiner\Exceptions\ProviderUnavailableException;
use BTRefiner\Exceptions\RateLimitException;

class GeminiProvider extends AbstractProvider {

	protected string $model = 'gemini-2.0-flash';

	private const API_BASE = 'https://generativelanguage.googleapis.com/v1beta/models';

	public function get_provider_id(): string {
		return 'gemini';
	}

	public function get_available_models(): array {
		return [
			'gemini-2.0-flash'    => 'Gemini 2.0 Flash',
			'gemini-1.5-flash'    => 'Gemini 1.5 Flash',
			'gemini-1.5-pro'      => 'Gemini 1.5 Pro',
		];
	}

	public function complete( string $user_prompt, string $system_prompt = '', array $history = [] ): string {
		return $this->with_retry(
			function () use ( $user_prompt, $system_prompt, $history ) {
				return $this->do_complete( $user_prompt, $system_prompt, $history );
			}
		);
	}

	private function do_complete( string $user_prompt, string $system_prompt, array $history ): string {
		if ( ! $this->api_key ) {
			throw new AuthException( esc_html__( 'Missing Google Gemini API key. Check your AI Integration settings.', 'ufaqsw' ) );
		}

		$system_prompt = $this->apply_language( $system_prompt );

		$contents = [];
		foreach ( $history as $turn ) {
			// Gemini uses 'model' instead of 'assistant'.
			$role       = 'assistant' === $turn['role'] ? 'model' : 'user';
			$contents[] = [ 'role' => $role, 'parts' => [ [ 'text' => $turn['content'] ] ] ];
		}
		$contents[] = [ 'role' => 'user', 'parts' => [ [ 'text' => $user_prompt ] ] ];

		$body = [ 'contents' => $contents ];
		if ( $system_prompt ) {
			$body['system_instruction'] = [ 'parts' => [ [ 'text' => $system_prompt ] ] ];
		}

		$url = self::API_BASE . '/' . rawurlencode( $this->model ) . ':generateContent?key=' . rawurlencode( $this->api_key );

		$response = wp_remote_post(
			$url,
			[
				'timeout' => 60,
				'headers' => [ 'Content-Type' => 'application/json' ],
				'body'    => wp_json_encode( $body ),
			]
		);

		if ( is_wp_error( $response ) ) {
			throw new ProviderUnavailableException(
				esc_html__( 'Request to Gemini failed: ', 'ufaqsw' ) . esc_html( $response->get_error_message() )
			);
		}

		$status      = (int) wp_remote_retrieve_response_code( $response );
		$parsed_body = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( 400 === $status && isset( $parsed_body['error']['status'] ) && 'INVALID_ARGUMENT' === $parsed_body['error']['status'] ) {
			throw new AuthException( esc_html__( 'Invalid Gemini API key or request.', 'ufaqsw' ) );
		}
		if ( 403 === $status ) {
			throw new AuthException( esc_html__( 'Gemini API key is invalid or lacks permissions.', 'ufaqsw' ) );
		}
		if ( 429 === $status ) {
			throw new RateLimitException( esc_html__( 'Gemini rate limit reached. Please wait 30–60 seconds and try again. If this happens often, consider upgrading your API plan.', 'ufaqsw' ) );
		}
		if ( 404 === $status ) {
			throw new ModelNotFoundException(
				sprintf( esc_html__( 'Gemini model "%s" not found.', 'ufaqsw' ), esc_html( $this->model ) )
			);
		}
		if ( isset( $parsed_body['error'] ) ) {
			throw new ProviderUnavailableException(
				esc_html__( 'Gemini API error: ', 'ufaqsw' ) . esc_html( $parsed_body['error']['message'] ?? 'Unknown error' )
			);
		}

		return $parsed_body['candidates'][0]['content']['parts'][0]['text'] ?? esc_html__( 'No response.', 'ufaqsw' );
	}
}
