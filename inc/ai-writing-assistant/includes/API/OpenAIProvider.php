<?php

namespace BTRefiner\API;

class OpenAIProvider extends OpenAICompatibleProvider {

	protected string $base_url = 'https://api.openai.com/v1';
	protected string $model    = 'gpt-4o';

	public function get_provider_id(): string {
		return 'openai';
	}

	public function get_available_models(): array {
		return [
			'gpt-4o-mini' => 'GPT-4o Mini',
			'gpt-4o'      => 'GPT-4o',
			'gpt-4'       => 'GPT-4',
			'o1-mini'     => 'o1 Mini',
			'o1'          => 'o1',
		];
	}
}
