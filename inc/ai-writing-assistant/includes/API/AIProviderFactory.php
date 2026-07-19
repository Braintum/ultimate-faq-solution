<?php

namespace BTRefiner\API;

use BTRefiner\Exceptions\AIProviderException;

class AIProviderFactory {

	/**
	 * Build and configure the active AI provider from plugin settings.
	 *
	 * @return AIProviderInterface
	 * @throws AIProviderException If the selected provider is unknown.
	 */
	public static function make(): AIProviderInterface {
		$settings    = get_option( 'ufaqsw_ai_integration_settings', [] );
		$provider_id = $settings['ai_provider'] ?? 'openai';
		$language    = $settings['ai_language'] ?? 'en_US';

		$provider = self::build_provider( $provider_id, $settings );
		$provider->set_language( $language );

		return $provider;
	}

	/**
	 * @param string $id
	 * @param array  $s Settings array.
	 * @return AIProviderInterface
	 */
	private static function build_provider( string $id, array $s ): AIProviderInterface {
		switch ( $id ) {
			case 'anthropic':
				$p = new AnthropicProvider();
				$p->set_api_key( $s['anthropic_api_key'] ?? '' );
				$p->set_model( $s['anthropic_model'] ?? 'claude-sonnet-4-6' );
				return $p;

			case 'gemini':
				$p = new GeminiProvider();
				$p->set_api_key( $s['gemini_api_key'] ?? '' );
				$p->set_model( $s['gemini_model'] ?? 'gemini-2.0-flash' );
				return $p;

			case 'mistral':
				$p = new MistralProvider();
				$p->set_api_key( $s['mistral_api_key'] ?? '' );
				$p->set_model( $s['mistral_model'] ?? 'mistral-small-latest' );
				return $p;

			case 'ollama':
				$endpoint = $s['ollama_endpoint'] ?? 'http://localhost:11434';
				$p        = new OllamaProvider( $endpoint );
				$p->set_model( $s['ollama_model'] ?? 'llama3' );
				return $p;

			case 'openai_compatible':
				$endpoint = $s['openai_compatible_endpoint'] ?? '';
				$p        = new OpenAICompatibleCustomProvider( $endpoint );
				$p->set_api_key( $s['openai_compatible_api_key'] ?? '' );
				$p->set_model( $s['openai_compatible_model'] ?? '' );
				return $p;

			case 'openai':
			default:
				$p = new OpenAIProvider();
				// Backward-compat: fall back to legacy chatgpt_api_key / chatgpt_model keys.
				$p->set_api_key( $s['openai_api_key'] ?? $s['chatgpt_api_key'] ?? '' );
				$p->set_model( $s['openai_model'] ?? $s['chatgpt_model'] ?? 'gpt-4o' );
				return $p;
		}
	}
}
