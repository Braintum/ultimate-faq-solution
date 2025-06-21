<?php

namespace BTRefiner\Admin;

class Settings {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'load_acf_settings' ), 0 );
		//add_filter( 'acf/load_value/name=ufaqsw_ai_commands', array( $this, 'set_default_ai_commands' ), 10, 3 );
		//add_filter( 'acf/load_field/name=ufaqsw_ai_model', array( $this, 'populate_model_choices' ) );
	}

	/**
	 * Load ACF settings.
	 */
	public function load_acf_settings() {
		// include_once ABSPATH . 'wp-admin/includes/plugin.php';

		// if ( ! is_plugin_active( 'advanced-custom-fields-pro/acf.php' ) ) {
		// 	add_action( 'admin_notices', array( $this, 'show_acf_missing_error' ) );
		// } elseif ( version_compare( get_option( 'acf_version' ), '5.7.3', '<' ) ) {
		// 	add_action( 'admin_notices', array( $this, 'show_acf_outdated_error' ) );
		// } else {
		// 	$this->add_acf_options();
		// 	$this->add_acf_fields();
		// }
	}

	/**
	 * Add ACF options page.
	 */
	private function add_acf_options() {
		if ( function_exists( 'acf_add_options_page' ) ) {
			acf_add_options_sub_page(
				array(
					'page_title'  => 'LOOP AI Writing Assistant',
					'menu_title'  => 'LOOP AI Writing Assistant',
					'parent_slug' => 'options-general.php',
				)
			);
		}
	}

	/**
	 * Add ACF fields.
	 */
	private function add_acf_fields() {
		if ( function_exists( 'acf_add_local_field_group' ) ) :

			acf_add_local_field_group(
				array(
					'key'                   => 'group_5bd6d53f8043d',
					'title'                 => 'LOOP AI Writing Assistant - Settings',
					'fields'                => array(
						array(
							'key'               => 'field_5bd6d53f887bf',
							'label'             => 'Status',
							'name'              => 'ufaqsw_ai_status',
							'type'              => 'true_false',
							'instructions'      => '',
							'required'          => 0,
							'conditional_logic' => 0,
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'message'      => 'Should this plugin be active?',
							'default_value'=> 0,
							'ui'           => 0,
							'ui_on_text'   => '',
							'ui_off_text'  => '',
						),
						array(
							'key'               => 'field_5bd6d53f887e0',
							'label'             => 'ChatGPT API Key',
							'name'              => 'ufaqsw_ai_chatgpt_api_key',
							'type'              => 'password',
							'instructions'      => '',
							'required'          => 1,
							'conditional_logic' => array(
								array(
									array(
										'field'    => 'field_5bd6d53f887bf',
										'operator' => '==',
										'value'    => '1',
									),
								),
							),
							'wrapper'      => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'default_value'=> '',
							'placeholder'  => 'KQ2XXXXXXX',
							'prepend'      => '',
							'append'       => '',
							'maxlength'    => '',
						),
						array(
							'key'               => 'field_ufaqsw_ai_command_language',
							'label'             => 'Language',
							'name'              => 'ufaqsw_ai_language',
							'type'              => 'select',
							'instructions'      => 'Select the language for AI.',
							'required'          => 1,
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'choices'           => array(
								'en' => 'English',
								'es' => 'Spanish',
								'fr' => 'French',
								'de' => 'German',
								'it' => 'Italian',
								'pt' => 'Portuguese',
								'nl' => 'Dutch',
								'pl' => 'Polish',
								// ...add more as needed...
							),
							'conditional_logic' => array(
								array(
									array(
										'field'    => 'field_5bd6d53f887bf',
										'operator' => '==',
										'value'    => '1',
									),
								),
							),
							'default_value' => array( 'en' ),
							'allow_null'    => 0,
							'multiple'      => 0,
							'ui'            => 1,
							'ajax'          => 0,
							'return_format' => 'value',
							'placeholder'   => '',
						),
						array(
							'key'               => 'field_ufaqsw_ai_command_model',
							'label'             => 'Model',
							'name'              => 'ufaqsw_ai_model',
							'type'              => 'select',
							'instructions'      => 'Select the ChatGPT model.',
							'required'          => 1,
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'conditional_logic' => array(
								array(
									array(
										'field'    => 'field_5bd6d53f887bf',
										'operator' => '==',
										'value'    => '1',
									),
								),
							),
							'choices'       => array(),
							'default_value' => 'gpt-4',
							'allow_null'    => 1,
							'multiple'      => 0,
							'ui'            => 1,
							'ajax'          => 0,
							'return_format' => 'value',
							'placeholder'   => 'Select a model',
						),
						array(
							'key'               => 'field_ufaqsw_ai_commands',
							'label'             => 'AI Commands',
							'name'              => 'ufaqsw_ai_commands',
							'type'              => 'repeater',
							'instructions'      => 'Add custom AI commands.',
							'required'          => 1,
							'conditional_logic' => array(
								array(
									array(
										'field'    => 'field_5bd6d53f887bf',
										'operator' => '==',
										'value'    => '1',
									),
								),
							),
							'wrapper'      => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'collapsed'    => '',
							'min'          => 0,
							'max'          => 0,
							'layout'       => 'row',
							'button_label' => 'Add Command',
							'sub_fields'   => array(
								array(
									'key'           => 'field_ufaqsw_ai_command_title',
									'label'         => 'Title',
									'name'          => 'title',
									'type'          => 'text',
									'instructions'  => 'The label shown to users in the editor.',
									'required'      => 1,
									'wrapper'       => array(
										'width' => '',
										'class' => '',
										'id'    => '',
									),
									'default_value' => '',
									'placeholder'   => 'Command Title',
									'prepend'       => '',
									'append'        => '',
									'maxlength'     => '',
								),
								array(
									'key'           => 'field_ufaqsw_ai_command_command',
									'label'         => 'Command',
									'name'          => 'command',
									'type'          => 'text',
									'instructions'  => 'The instruction sent to the AI to perform the action.',
									'required'      => 1,
									'wrapper'       => array(
										'width' => '',
										'class' => '',
										'id'    => '',
									),
									'default_value' => '',
									'placeholder'   => 'Command Text',
									'prepend'       => '',
									'append'        => '',
									'maxlength'     => '',
								),
							),
						),
					),
					'location'              => array(
						array(
							array(
								'param'    => 'options_page',
								'operator' => '==',
								'value'    => 'acf-options-ufaqsw-ai-writing-assistant',
							),
						),
					),
					'menu_order'            => 0,
					'position'              => 'normal',
					'style'                 => 'default',
					'label_placement'       => 'top',
					'instruction_placement' => 'label',
					'hide_on_screen'        => '',
					'active'                => 1,
					'description'           => '',
				)
			);

		endif;
	}

	/**
	 * Set default AI commands.
	 */
	public function set_default_ai_commands( $value, $post_id, $field ) {
		if ( empty( $value ) ) {
			$value = array(
				array(
					'field_ufaqsw_ai_command_title'   => 'Refine the text',
					'field_ufaqsw_ai_command_command' => 'Refine and improve the following text.',
				),
				array(
					'field_ufaqsw_ai_command_title'   => 'Make it longer',
					'field_ufaqsw_ai_command_command' => 'Make the following text longer and more detailed.',
				),
				array(
					'field_ufaqsw_ai_command_title'   => 'Make it shorter',
					'field_ufaqsw_ai_command_command' => 'Make the following text more concise.',
				),
			);
		}
		return $value;
	}

	/**
	 * Populate model choices for the model select field.
	 */
	public function populate_model_choices( $field ) {
		// Fetch available models from ChatGPT API or your own cache/storage.
		// For demonstration, we'll use a static array. Replace with dynamic fetching as needed.
		$models = array(
			'gpt-4'        => 'GPT-4',
			'gpt-4-turbo'  => 'GPT-4 Turbo',
			'gpt-3.5-turbo'=> 'GPT-3.5 Turbo',
			// Add more models as needed...
		);

		$field['choices'] = $models;
		return $field;
	}
}