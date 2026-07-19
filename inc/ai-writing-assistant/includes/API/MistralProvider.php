<?php

namespace BTRefiner\API;

class MistralProvider extends OpenAICompatibleProvider {

	protected string $base_url = 'https://api.mistral.ai/v1';
	protected string $model    = 'mistral-small-latest';

	public function get_provider_id(): string {
		return 'mistral';
	}

	public function get_available_models(): array {
		return [
			'mistral-small-latest'  => 'Mistral Small',
			'mistral-medium-latest' => 'Mistral Medium',
			'mistral-large-latest'  => 'Mistral Large',
			'codestral-latest'      => 'Codestral',
		];
	}
}
