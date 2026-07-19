<?php

namespace BTRefiner\API;

use BTRefiner\Exceptions\ProviderUnavailableException;
use BTRefiner\Exceptions\RateLimitException;

abstract class AbstractProvider implements AIProviderInterface {

	protected string $api_key  = '';
	protected string $model    = '';
	protected string $language = 'en_US';

	public function set_api_key( string $key ): void {
		$this->api_key = $key;
	}

	public function set_model( string $model ): void {
		$this->model = $model;
	}

	public function set_language( string $language ): void {
		$this->language = $language;
	}

	/**
	 * Appends a language instruction to the system prompt when non-English is selected.
	 */
	protected function apply_language( string $system_prompt ): string {
		if ( $this->language && 'en_US' !== $this->language && 'en' !== $this->language ) {
			$system_prompt .= ' Respond in ' . $this->get_language_name( $this->language ) . '.';
		}
		return $system_prompt;
	}

	/**
	 * Runs $fn, retrying up to $max_retries times on transient network errors.
	 * Rate limits are NOT retried — sleeping 1–2 s server-side does not help when
	 * the provider requires waiting 20–60 s, and each retry just burns more quota.
	 */
	protected function with_retry( callable $fn, int $max_retries = 2 ): string {
		$attempt = 0;
		while ( true ) {
			try {
				return $fn();
			} catch ( RateLimitException $e ) {
				// Fail fast: tell the user to wait rather than wasting quota on doomed retries.
				throw $e;
			} catch ( ProviderUnavailableException $e ) {
				if ( $attempt >= $max_retries ) {
					throw $e;
				}
				// 1 s then 2 s back-off for transient network/server errors.
				usleep( (int) ( pow( 2, $attempt ) * 1000000 ) );
				$attempt++;
			}
		}
	}

	private function get_language_name( string $code ): string {
		if ( ! function_exists( 'wp_get_available_translations' ) ) {
			require_once ABSPATH . 'wp-admin/includes/translation-install.php';
		}

		$translations = wp_get_available_translations();
		$options      = array( 'en_US' => 'English (US)' );
		foreach ( $translations as $lang_code => $translation ) {
			$options[ $lang_code ] = $translation['english_name'] . ' (' . $lang_code . ')';
		}

		return $options[ $code ] ?? $code;
	}
}
