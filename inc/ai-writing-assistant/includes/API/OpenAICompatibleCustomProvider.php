<?php

namespace BTRefiner\API;

/**
 * Generic adapter for any OpenAI-compatible third-party endpoint
 * (e.g. LM Studio, vLLM, Together AI, Groq, etc.).
 */
class OpenAICompatibleCustomProvider extends OpenAICompatibleProvider {

	protected string $model = '';

	public function __construct( string $endpoint ) {
		$this->base_url = rtrim( $endpoint, '/' );
	}

	public function get_provider_id(): string {
		return 'openai_compatible';
	}

	public function get_available_models(): array {
		return [];
	}
}
