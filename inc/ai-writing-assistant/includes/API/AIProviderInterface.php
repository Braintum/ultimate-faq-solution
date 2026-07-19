<?php

namespace BTRefiner\API;

interface AIProviderInterface {

	public function set_api_key( string $key ): void;

	public function set_model( string $model ): void;

	public function set_language( string $language ): void;

	/**
	 * Send a completion request to the AI provider.
	 *
	 * @param string $user_prompt   The user message / content to process.
	 * @param string $system_prompt Instructions / system context for the model.
	 * @param array  $history       Prior turns: [['role'=>'user'|'assistant','content'=>'…'], …]
	 * @return string The model's response text.
	 */
	public function complete( string $user_prompt, string $system_prompt = '', array $history = [] ): string;

	public function get_provider_id(): string;

	/** @return array<string,string> model_id => label */
	public function get_available_models(): array;
}
