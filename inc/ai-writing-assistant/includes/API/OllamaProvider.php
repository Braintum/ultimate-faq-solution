<?php

namespace BTRefiner\API;

class OllamaProvider extends OpenAICompatibleProvider {

	protected string $model = 'llama3';

	public function __construct( string $endpoint = 'http://localhost:11434' ) {
		$this->base_url = rtrim( $endpoint, '/' ) . '/v1';
	}

	public function get_provider_id(): string {
		return 'ollama';
	}

	/** Ollama has no fixed model list — models are whatever the user has pulled. */
	public function get_available_models(): array {
		return [];
	}
}
