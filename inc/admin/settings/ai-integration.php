<?php
/**
 * AI Integration Settings Page
 *
 * @package Ultimate_FAQ_Solution
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the AI settings CMB2 options page and all fields.
 */
function ufaqsw_register_ai_settings_page() {

	if ( ! function_exists( 'wp_get_available_translations' ) ) {
		require_once ABSPATH . 'wp-admin/includes/translation-install.php';
	}

	$translations       = wp_get_available_translations();
	$language_options   = array( 'en_US' => 'English (US)' );
	foreach ( $translations as $lang_code => $translation ) {
		$language_options[ $lang_code ] = $translation['english_name'] . ' (' . $lang_code . ')';
	}

	$cmb = new_cmb2_box(
		array(
			'id'           => 'ufaqsw_ai_settings_page',
			'title'        => __( 'AI Integration Settings', 'ufaqsw' ),
			'object_types' => array( 'options-page' ),
			'option_key'   => 'ufaqsw_ai_integration_settings',
			'parent_slug'  => 'edit.php?post_type=ufaqsw',
			'capability'   => 'manage_options',
			'menu_title'   => __( 'AI Integration', 'ufaqsw' ),
		)
	);

	// ── Enable toggle ────────────────────────────────────────────────────────────

	$cmb->add_field(
		array(
			'name'        => __( 'Enable AI Integration', 'ufaqsw' ),
			'id'          => 'enable_ai_integration',
			'type'        => 'checkbox',
			'description' => __( '<i>Enable AI-powered FAQ generation and text refinement.</i>', 'ufaqsw' ),
		)
	);

	// ── Provider selector ────────────────────────────────────────────────────────

	$cmb->add_field(
		array(
			'name'        => __( 'AI Provider', 'ufaqsw' ),
			'id'          => 'ai_provider',
			'type'        => 'select',
			'default'     => 'openai',
			'options'     => array(
				'openai'            => __( 'OpenAI (ChatGPT)', 'ufaqsw' ),
				'anthropic'         => __( 'Anthropic (Claude)', 'ufaqsw' ),
				'gemini'            => __( 'Google Gemini', 'ufaqsw' ),
				'mistral'           => __( 'Mistral AI', 'ufaqsw' ),
				'ollama'            => __( 'Ollama (Local)', 'ufaqsw' ),
				'openai_compatible' => __( 'OpenAI-Compatible (Custom)', 'ufaqsw' ),
			),
			'description' => __( '<i>Select which AI service to use. Configure the corresponding API key below.</i>', 'ufaqsw' ),
		)
	);

	// ── OpenAI fields ────────────────────────────────────────────────────────────

	$cmb->add_field(
		array(
			'name'        => __( 'OpenAI API Key', 'ufaqsw' ),
			'id'          => 'openai_api_key',
			'type'        => 'text',
			'description' => __( '<i>Your OpenAI API key.</i> <a href="https://platform.openai.com/account/api-keys" target="_blank">Get API key</a>', 'ufaqsw' ),
			'attributes'  => array(
				'type'                   => 'password',
				'autocomplete'           => 'off',
				'data-conditional-id'    => 'ai_provider',
				'data-conditional-value' => 'openai',
			),
		)
	);

	$cmb->add_field(
		array(
			'name'        => __( 'OpenAI Model', 'ufaqsw' ),
			'id'          => 'openai_model',
			'type'        => 'select',
			'default'     => 'gpt-4o',
			'options'     => array(
				'gpt-4o-mini' => 'GPT-4o Mini',
				'gpt-4o'      => 'GPT-4o',
				'gpt-4'       => 'GPT-4',
				'o1-mini'     => 'o1 Mini',
				'o1'          => 'o1',
			),
			'attributes'  => array(
				'data-conditional-id'    => 'ai_provider',
				'data-conditional-value' => 'openai',
			),
		)
	);

	// ── Anthropic fields ─────────────────────────────────────────────────────────

	$cmb->add_field(
		array(
			'name'        => __( 'Anthropic API Key', 'ufaqsw' ),
			'id'          => 'anthropic_api_key',
			'type'        => 'text',
			'description' => __( '<i>Your Anthropic API key.</i> <a href="https://console.anthropic.com/settings/keys" target="_blank">Get API key</a>', 'ufaqsw' ),
			'attributes'  => array(
				'type'                   => 'password',
				'autocomplete'           => 'off',
				'data-conditional-id'    => 'ai_provider',
				'data-conditional-value' => 'anthropic',
			),
		)
	);

	$cmb->add_field(
		array(
			'name'        => __( 'Claude Model', 'ufaqsw' ),
			'id'          => 'anthropic_model',
			'type'        => 'select',
			'default'     => 'claude-sonnet-4-6',
			'options'     => array(
				'claude-haiku-4-5-20251001'  => 'Claude Haiku 4.5',
				'claude-sonnet-4-6'          => 'Claude Sonnet 4.6',
				'claude-opus-4-8'            => 'Claude Opus 4.8',
				'claude-3-5-haiku-20241022'  => 'Claude 3.5 Haiku',
				'claude-3-5-sonnet-20241022' => 'Claude 3.5 Sonnet',
			),
			'attributes'  => array(
				'data-conditional-id'    => 'ai_provider',
				'data-conditional-value' => 'anthropic',
			),
		)
	);

	// ── Google Gemini fields ─────────────────────────────────────────────────────

	$cmb->add_field(
		array(
			'name'        => __( 'Google Gemini API Key', 'ufaqsw' ),
			'id'          => 'gemini_api_key',
			'type'        => 'text',
			'description' => __( '<i>Your Google AI Studio API key.</i> <a href="https://aistudio.google.com/app/apikey" target="_blank">Get API key</a>', 'ufaqsw' ),
			'attributes'  => array(
				'type'                   => 'password',
				'autocomplete'           => 'off',
				'data-conditional-id'    => 'ai_provider',
				'data-conditional-value' => 'gemini',
			),
		)
	);

	$cmb->add_field(
		array(
			'name'        => __( 'Gemini Model', 'ufaqsw' ),
			'id'          => 'gemini_model',
			'type'        => 'select',
			'default'     => 'gemini-2.0-flash',
			'options'     => array(
				'gemini-2.0-flash' => 'Gemini 2.0 Flash',
				'gemini-1.5-flash' => 'Gemini 1.5 Flash',
				'gemini-1.5-pro'   => 'Gemini 1.5 Pro',
			),
			'attributes'  => array(
				'data-conditional-id'    => 'ai_provider',
				'data-conditional-value' => 'gemini',
			),
		)
	);

	// ── Mistral fields ───────────────────────────────────────────────────────────

	$cmb->add_field(
		array(
			'name'        => __( 'Mistral API Key', 'ufaqsw' ),
			'id'          => 'mistral_api_key',
			'type'        => 'text',
			'description' => __( '<i>Your Mistral AI API key.</i> <a href="https://console.mistral.ai/api-keys/" target="_blank">Get API key</a>', 'ufaqsw' ),
			'attributes'  => array(
				'type'                   => 'password',
				'autocomplete'           => 'off',
				'data-conditional-id'    => 'ai_provider',
				'data-conditional-value' => 'mistral',
			),
		)
	);

	$cmb->add_field(
		array(
			'name'        => __( 'Mistral Model', 'ufaqsw' ),
			'id'          => 'mistral_model',
			'type'        => 'select',
			'default'     => 'mistral-small-latest',
			'options'     => array(
				'mistral-small-latest'  => 'Mistral Small',
				'mistral-medium-latest' => 'Mistral Medium',
				'mistral-large-latest'  => 'Mistral Large',
				'codestral-latest'      => 'Codestral',
			),
			'attributes'  => array(
				'data-conditional-id'    => 'ai_provider',
				'data-conditional-value' => 'mistral',
			),
		)
	);

	// ── Ollama fields ────────────────────────────────────────────────────────────

	$cmb->add_field(
		array(
			'name'        => __( 'Ollama Endpoint', 'ufaqsw' ),
			'id'          => 'ollama_endpoint',
			'type'        => 'text',
			'default'     => 'http://localhost:11434',
			'description' => __( '<i>Base URL of your local Ollama instance.</i>', 'ufaqsw' ),
			'attributes'  => array(
				'data-conditional-id'    => 'ai_provider',
				'data-conditional-value' => 'ollama',
			),
		)
	);

	$cmb->add_field(
		array(
			'name'        => __( 'Ollama Model', 'ufaqsw' ),
			'id'          => 'ollama_model',
			'type'        => 'text',
			'default'     => 'llama3',
			'description' => __( '<i>Name of the locally installed Ollama model, e.g. <code>llama3</code>, <code>mistral</code>, <code>phi3</code>.</i>', 'ufaqsw' ),
			'attributes'  => array(
				'data-conditional-id'    => 'ai_provider',
				'data-conditional-value' => 'ollama',
			),
		)
	);

	// ── OpenAI-Compatible (Custom) fields ────────────────────────────────────────

	$cmb->add_field(
		array(
			'name'        => __( 'Custom API Endpoint', 'ufaqsw' ),
			'id'          => 'openai_compatible_endpoint',
			'type'        => 'text',
			'description' => __( '<i>Base URL of an OpenAI-compatible API (e.g. <code>https://api.groq.com/openai/v1</code>). The <code>/chat/completions</code> path is appended automatically.</i>', 'ufaqsw' ),
			'attributes'  => array(
				'data-conditional-id'    => 'ai_provider',
				'data-conditional-value' => 'openai_compatible',
			),
		)
	);

	$cmb->add_field(
		array(
			'name'        => __( 'Custom API Key', 'ufaqsw' ),
			'id'          => 'openai_compatible_api_key',
			'type'        => 'text',
			'description' => __( '<i>API key for the custom endpoint (leave blank if not required).</i>', 'ufaqsw' ),
			'attributes'  => array(
				'type'                   => 'password',
				'autocomplete'           => 'off',
				'data-conditional-id'    => 'ai_provider',
				'data-conditional-value' => 'openai_compatible',
			),
		)
	);

	$cmb->add_field(
		array(
			'name'        => __( 'Custom Model Name', 'ufaqsw' ),
			'id'          => 'openai_compatible_model',
			'type'        => 'text',
			'description' => __( '<i>Model identifier to send in the request, e.g. <code>llama-3.3-70b-versatile</code>.</i>', 'ufaqsw' ),
			'attributes'  => array(
				'data-conditional-id'    => 'ai_provider',
				'data-conditional-value' => 'openai_compatible',
			),
		)
	);

	// ── Shared settings ──────────────────────────────────────────────────────────

	$cmb->add_field(
		array(
			'name'        => __( 'Language', 'ufaqsw' ),
			'id'          => 'ai_language',
			'type'        => 'select',
			'default'     => get_option( 'WPLANG', 'en_US' ),
			'options'     => $language_options,
			'description' => __( '<i>Language for AI-generated content.</i>', 'ufaqsw' ),
		)
	);

	$cmb->add_field(
		array(
			'id'          => 'ai_commands',
			'type'        => 'group',
			'name'        => __( 'AI Commands', 'ufaqsw' ),
			'description' => __( 'Custom commands available in the TinyMCE AI Assistant dropdown.', 'ufaqsw' ),
			'options'     => array(
				'group_title'   => __( 'Command {#}', 'ufaqsw' ),
				'add_button'    => __( 'Add Command', 'ufaqsw' ),
				'remove_button' => __( 'Remove Command', 'ufaqsw' ),
			),
		)
	);

	$cmb->add_group_field(
		'ai_commands',
		array(
			'name' => __( 'Title', 'ufaqsw' ),
			'id'   => 'title',
			'type' => 'text',
		)
	);

	$cmb->add_group_field(
		'ai_commands',
		array(
			'name'       => __( 'Commands', 'ufaqsw' ),
			'id'         => 'commands',
			'type'       => 'textarea_small',
			'attributes' => array( 'rows' => 2 ),
		)
	);
}
add_action( 'cmb2_admin_init', 'ufaqsw_register_ai_settings_page' );

/**
 * Output intro text above the CMB2 form.
 */
function ufaqsw_ai_settings_before_cmb2_output( $cmb_id ) {
	if ( 'ufaqsw_ai_settings_page' !== $cmb_id ) {
		return;
	}

	echo '<div style="margin-bottom:20px;">';
	echo esc_html__( 'Connect any major AI service to power FAQ generation and text refinement. Select your provider, enter your API key, and pick a model.', 'ufaqsw' );
	echo '<br><a href="https://www.braintum.com/docs/ultimate-faq-solution/ai-integration/" target="_blank" style="display:inline-block;margin-top:10px;">';
	echo esc_html__( 'Read the AI Integration Documentation', 'ufaqsw' );
	echo '</a></div>';
}
add_action( 'cmb2_before_form', 'ufaqsw_ai_settings_before_cmb2_output', 1, 4 );

/**
 * One-time migration: copy legacy chatgpt_* keys to the new openai_* keys so existing
 * users see their API key already filled in after the update.
 */
add_action( 'admin_init', 'ufaqsw_migrate_legacy_ai_settings' );

function ufaqsw_migrate_legacy_ai_settings() {
	$option_key = 'ufaqsw_ai_integration_settings';
	$options    = get_option( $option_key, array() );

	// Nothing to migrate if the new fields are already set, or no legacy key exists.
	if ( ! empty( $options['openai_api_key'] ) || empty( $options['chatgpt_api_key'] ) ) {
		return;
	}

	$options['openai_api_key'] = $options['chatgpt_api_key'];

	if ( ! empty( $options['chatgpt_model'] ) && empty( $options['openai_model'] ) ) {
		$options['openai_model'] = $options['chatgpt_model'];
	}

	if ( empty( $options['ai_provider'] ) ) {
		$options['ai_provider'] = 'openai';
	}

	update_option( $option_key, $options );
}

/**
 * Populate default AI commands on first install.
 */
add_action( 'admin_init', 'ufaqsw_prepopulate_ai_settings_defaults' );

function ufaqsw_prepopulate_ai_settings_defaults() {
	$option_key = 'ufaqsw_ai_integration_settings';
	$options    = get_option( $option_key );

	if ( empty( $options ) || empty( $options['ai_commands'] ) ) {
		if ( ! is_array( $options ) ) {
			$options = array();
		}

		$options['ai_commands'] = array(
			array(
				'title'    => __( 'Refine the text', 'ufaqsw' ),
				'commands' => __( 'Refine and improve the following text.', 'ufaqsw' ),
			),
			array(
				'title'    => __( 'Make it longer', 'ufaqsw' ),
				'commands' => __( 'Make the following text longer and more detailed.', 'ufaqsw' ),
			),
			array(
				'title'    => __( 'Make it shorter', 'ufaqsw' ),
				'commands' => __( 'Make the following text more concise.', 'ufaqsw' ),
			),
			array(
				'title'    => __( 'Execute as command', 'ufaqsw' ),
				'commands' => __( 'Execute this text as prompt.', 'ufaqsw' ),
			),
		);

		update_option( $option_key, $options );
	}
}
