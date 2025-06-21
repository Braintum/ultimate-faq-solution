<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName -- File name matches class name.
/**
 * TinyMCEIntegration class integration for AI Text Refiner plugin.
 *
 * @package BTRefiner\Admin
 */

namespace BTRefiner\Admin;

/**
 * Class TinyMCEIntegration
 *
 * Integrates ACF fields with the AI Text Refiner plugin, adding UI elements and handling AJAX requests for text refinement.
 */
class TinyMCEIntegration {

	/**
	 * Constructor.
	 */
	public function __construct() {
		if ( is_admin() ) {
			add_action( 'init', array( $this, 'setup_plugin' ) );
			add_action( 'admin_print_footer_scripts', array( $this, 'tiny_custom_script' ) );
		}
	}

	/**
	 * Outputs custom script for TinyMCE integration.
	 */
	public function tiny_custom_script() {
		$selected_options_detailed = array();

		$commands = cmb2_get_option( 'ufaqsw_ai_integration_settings', 'ai_commands' );
		if ( ! empty( $commands ) && is_array( $commands ) ) {
			foreach ( $commands as $command ) {
				if ( isset( $command['title'] ) && isset( $command['commands'] ) ) {
					$selected_options_detailed[] = array(
						'name'  => $command['commands'],
						'title' => $command['title'],
					);
				}
			}
		}

		echo '<script>';
		echo "var ufaqsw_tiny_plugin_url = '" . esc_js( UFAQSW_TEXT_REFINER_URL ) . "';";
		echo 'var ufaqsw_tiny_plugin_options = \'' . wp_json_encode( $selected_options_detailed ) . '\';';
		echo "var ufaqsw_tiny_ajax_nonce = '" . esc_js( wp_create_nonce( 'ufaqsw-ajax-nonce' ) ) . "';";
		echo "var ufaqsw_tiny_mode = '" . esc_js( 'prod' ) . "';";
		echo 'let ufaqsw_tiny_plugin_texts = {ufaqsw_tiny_text_ai_tools:"' . esc_html__( '✨ AI Assistant', 'ufaqsw' ) . '", ufaqsw_tiny_text_available_ai_tools:"' . esc_html__( 'Available AI Commands - you can set these on settings page', 'ufaqsw' ) . '", ufaqsw_tiny_text_undo:"' . esc_html__( 'Undo last AI text modification', 'ufaqsw' ) . '"};';
		echo '</script>';
	}

	/**
	 * Setup plugin hooks.
	 */
	public function setup_plugin() {
		add_filter( 'mce_external_plugins', array( $this, 'add_tiny_plugin' ), 1, 2 );
		add_filter( 'mce_buttons', array( $this, 'add_tiny_toolbar_button' ) );
	}

	/**
	 * Adds a TinyMCE plugin compatible JS file to the TinyMCE / Visual Editor instance.
	 *
	 * @param array $plugin_array Array of registered TinyMCE Plugins.
	 * @return array Modified array of registered TinyMCE Plugins.
	 */
	public function add_tiny_plugin( $plugin_array ) {
		$plugin_array['ufaqsw_ai_assistant_menu'] = UFAQSW_TEXT_REFINER_URL . 'assets/js/tiny.js';
		$plugin_array['loadingoverlay']           = UFAQSW_TEXT_REFINER_URL . 'assets/js/loadingoverlay/loadingoverlay.min.js';
		return $plugin_array;
	}

	/**
	 * Adds a button to the main TinyMCE toolbar row.
	 *
	 * @param array $buttons Array of registered TinyMCE Buttons.
	 * @return array Modified array of registered TinyMCE Buttons.
	 */
	public function add_tiny_toolbar_button( $buttons ) {
		array_push( $buttons, 'ufaqsw_ai_assistant_menu', 'ufaqsw_ai_assistant_undo' );
		return $buttons;
	}
}
