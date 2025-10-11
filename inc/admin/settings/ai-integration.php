<?php
/**
 * FAQ Assistant Settings Page and Admin Functionality
 *
 * This file contains the code for registering and rendering the FAQ Assistant settings page
 * in the WordPress admin using CMB2, as well as related helper functions and admin scripts.
 *
 * @package Ultimate_FAQ_Solution
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add the settings page using CMB2.
 */
function ufaqsw_register_ai_settings_page() {

	if ( ! function_exists( 'wp_get_available_translations' ) ) {
		require_once ABSPATH . 'wp-admin/includes/translation-install.php';
	}

	$translations = wp_get_available_translations();
	$traslation_options = array( 'en_US' => 'English (US)' );
	foreach ( $translations as $lang_code => $translation ) {
		$traslation_options[ $lang_code ] = $translation['english_name'] . ' (' . $lang_code . ')';
	}

	$cmb = new_cmb2_box(
		array(
			'id'           => 'ufaqsw_ai_settings_page',
			'title'        => __( 'AI Integration Settings', 'ufaqsw' ),
			'object_types' => array( 'options-page' ),
			'option_key'   => 'ufaqsw_ai_integration_settings', // Option key for storing settings.
			'parent_slug'  => 'edit.php?post_type=ufaqsw', // Parent menu slug.
			'capability'   => 'manage_options', // Capability required to access the page.

			'menu_title'   => __( 'AI Integration', 'ufaqsw' ), // Custom menu title.
		)
	);

	$cmb->add_field(
		array(
			'name'        => __( 'AI Integration', 'ufaqsw' ),
			'id'          => 'enable_ai_integration',
			'type'        => 'checkbox',
			'description' => __( '<i>Enable this option to activate the AI-powered FAQ generation on your website.</i>', 'ufaqsw' ),
		)
	);
	$cmb->add_field(
		array(
			'name'        => __( 'ChatGPT API Key', 'ufaqsw' ),
			'id'          => 'chatgpt_api_key',
			'type'        => 'text',
			'description' => __( '<i>Enter your OpenAI ChatGPT API key to enable AI-powered FAQ generation.</i> <a href="https://platform.openai.com/account/api-keys" target="_blank">Get your API key</a>', 'ufaqsw' ),
			'attributes'  => array(
				'type' => 'password',
				'autocomplete' => 'off',
			),
		)
	);

	$cmb->add_field(
		array(
			'name'        => __( 'ChatGPT Model', 'ufaqsw' ),
			'id'          => 'chatgpt_model',
			'type'        => 'select',
			'default'     => 'GPT-4o',
			'options'     => array(
				'gpt-3.5-turbo' => __( 'GPT-3.5 Turbo', 'ufaqsw' ),
				'gpt-4'         => __( 'GPT-4', 'ufaqsw' ),
				'gpt-4o'        => __( 'GPT-4o', 'ufaqsw' ),
			),
			'description' => __( '<i>Select the ChatGPT model to use for AI-powered FAQ generation.</i>', 'ufaqsw' ),
		)
	);

	$cmb->add_field(
		array(
			'name'        => __( 'Language', 'ufaqsw' ),
			'id'          => 'ai_language',
			'type'        => 'select',
			'default'     => get_option( 'WPLANG', 'en_US' ),
			'options'     => $traslation_options,
			'description' => __( '<i>Select the language to use for AI-powered FAQ generation.</i>', 'ufaqsw' ),
		)
	);

	$cmb->add_field(
		array(
			'id'          => 'ai_commands',
			'type'        => 'group',
			'name'        => __( 'AI Commands', 'ufaqsw' ),
			'description' => __( 'Add custom AI commands for the FAQ Generation. These commands will be available as a dropdown in the TinyMCE editor for the answer field.', 'ufaqsw' ),
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
			'attributes' => array(
				'rows' => 2,
			),
		)
	);
}

add_action( 'cmb2_admin_init', 'ufaqsw_register_ai_settings_page' );

/**
 * Output custom HTML before the CMB2 form.
 *
 * @param string $cmb_id      The CMB2 metabox ID.
 * @param int    $object_id   The object ID.
 * @param string $object_type The object type.
 * @param object $cmb         The CMB2 object.
 */
function ufaqsw_ai_settings_before_cmb2_output( $cmb_id, $object_id, $object_type, $cmb ) {

	// Only output above the ufaqsw_settings_page metabox.
	if ( 'ufaqsw_ai_settings_page' !== $cmb_id ) {
		return;
	}

	echo '<div style="margin-bottom: 20px;">';
	echo esc_html__(
		'Configure AI integration to automatically generate and enhance FAQ answers using OpenAI\'s ChatGPT. Enable AI-powered features, set your API key, choose a model, and define custom commands to streamline FAQ creation and editing. These settings let you leverage AI to improve the quality and efficiency of your FAQ content.',
		'ufaqsw'
	);
	echo '<br><a href="https://www.braintum.com/docs/ultimate-faq-solution/ai-integration/" target="_blank" style="display:inline-block;margin-top:10px;">' . esc_html__( 'Read the AI Integration Documentation', 'ufaqsw' ) . '</a>';
	echo '</div>';
}
add_action( 'cmb2_before_form', 'ufaqsw_ai_settings_before_cmb2_output', 1, 4 );

add_action( 'admin_init', 'ufaqsw_prepopulate_ai_settings_defaults' );
/**
 * Prepopulate AI settings defaults if not set.
 */
function ufaqsw_prepopulate_ai_settings_defaults() {
	$option_key = 'ufaqsw_ai_integration_settings';
	$field_id   = 'ai_commands';

	$options = get_option( $option_key );

	// Only populate if empty or not set.
	if ( empty( $options ) || empty( $options[ $field_id ] ) ) {

		if ( ! is_array( $options ) ) {
			$options = array();
		}

		// Default repeater values.
		$options[ $field_id ] = array(
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
