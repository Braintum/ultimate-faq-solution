<?php

namespace BTRefiner\API;

use BTRefiner\Exceptions\AuthException;
use BTRefiner\Exceptions\ModelNotFoundException;
use BTRefiner\Exceptions\ProviderUnavailableException;
use BTRefiner\Exceptions\RateLimitException;

class AnthropicProvider extends AbstractProvider {

	protected string $model = 'claude-sonnet-4-6';

	private const API_URL     = 'https://api.anthropic.com/v1/messages';
	private const API_VERSION = '2023-06-01';
	private const MAX_TOKENS  = 4096;

	public function get_provider_id(): string {
		return 'anthropic';
	}

	public function get_available_models(): array {
		return [
			'claude-haiku-4-5-20251001'    => 'Claude Haiku 4.5',
			'claude-sonnet-4-6'            => 'Claude Sonnet 4.6',
			'claude-opus-4-8'              => 'Claude Opus 4.8',
			'claude-3-5-haiku-20241022'    => 'Claude 3.5 Haiku',
			'claude-3-5-sonnet-20241022'   => 'Claude 3.5 Sonnet',
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
			throw new AuthException( esc_html__( 'Missing Anthropic API key. Check your AI Integration settings.', 'ufaqsw' ) );
		}

		$system_prompt = $this->apply_language( $system_prompt );

		$messages = [];
		foreach ( $history as $turn ) {
			$messages[] = [ 'role' => $turn['role'], 'content' => $turn['content'] ];
		}
		$messages[] = [ 'role' => 'user', 'content' => $user_prompt ];

		$body = [
			'model'      => $this->model,
			'max_tokens' => self::MAX_TOKENS,
			'messages'   => $messages,
		];
		if ( $system_prompt ) {
			$body['system'] = $system_prompt;
		}

		$response = wp_remote_post(
			self::API_URL,
			[
				'timeout' => 60,
				'headers' => [
					'x-api-key'         => $this->api_key,
					'anthropic-version'  => self::API_VERSION,
					'Content-Type'       => 'application/json',
				],
				'body'    => wp_json_encode( $body ),
			]
		);

		if ( is_wp_error( $response ) ) {
			throw new ProviderUnavailableException(
				esc_html__( 'Request to Anthropic failed: ', 'ufaqsw' ) . esc_html( $response->get_error_message() )
			);
		}

		$status      = (int) wp_remote_retrieve_response_code( $response );
		$parsed_body = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( 401 === $status ) {
			throw new AuthException( esc_html__( 'Invalid Anthropic API key.', 'ufaqsw' ) );
		}
		if ( 429 === $status ) {
			throw new RateLimitException( esc_html__( 'Anthropic rate limit reached. Please wait 30–60 seconds and try again. If this happens often, consider upgrading your API plan.', 'ufaqsw' ) );
		}
		if ( 404 === $status ) {
			throw new ModelNotFoundException(
				sprintf( esc_html__( 'Model "%s" not found on Anthropic.', 'ufaqsw' ), esc_html( $this->model ) )
			);
		}
		if ( isset( $parsed_body['error'] ) ) {
			throw new ProviderUnavailableException(
				esc_html__( 'Anthropic API error: ', 'ufaqsw' ) . esc_html( $parsed_body['error']['message'] ?? 'Unknown error' )
			);
		}

		return $parsed_body['content'][0]['text'] ?? esc_html__( 'No response.', 'ufaqsw' );
	}
}
