<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName -- File name matches class name.
/**
 * TinyMCEIntegration class integration for AI Text Refiner plugin.
 *
 * Handles ACF field integration and AJAX refinement.
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
            add_action( 'init', array(  $this, 'setup_plugin' ) );
			add_action( 'admin_print_footer_scripts', array(  $this, 'tiny_custom_script' ) );
			add_filter( 'acf/fields/wysiwyg/toolbars', array(  $this, 'custom_acf_wsyiwyg_toolbar' ), 9999 );
        }
	}

	public function custom_acf_wsyiwyg_toolbar( $toolbars ) {
		foreach ( $toolbars as $toolbar_name => &$toolbar ) {

			if ( 'Full' === $toolbar_name ) continue;

			if ( isset( $toolbar[1] ) && is_array( $toolbar[1] ) ) {
				$toolbar[1][] = 'ufaqsw_ai_assistant_menu';
				$toolbar[1][] = 'ufaqsw_ai_assistant_undo';
			}
		}
		return $toolbars;
	}

	/**
	 * Outputs custom script for TinyMCE integration.
	 */

	public function tiny_custom_script() {

        $selected_options_detailed = array();
        
		$commands = get_field( 'ufaqsw_ai_commands', 'options' );
		if ( ! empty( $commands ) && is_array( $commands ) ) {
			foreach ( $commands as $command ) {
				if ( isset( $command['title'] ) && isset( $command['command'] ) ) {
					$selected_options_detailed[] = array(
						"name"  => $command['command'],
						"title" => $command['title']
					);
				}
			}
		}

        echo "<script>" ;
        echo "var ufaqsw_tiny_plugin_url = '" . esc_js( UFAQSW_TEXT_REFINER_URL ) . "';" ;
        echo "var ufaqsw_tiny_plugin_options = '" . wp_json_encode($selected_options_detailed) . "';" ;
        echo "var ufaqsw_tiny_ajax_nonce = '" . esc_js(wp_create_nonce('ufaqsw-ajax-nonce')) . "';" ;
        echo "var ufaqsw_tiny_mode = '" . esc_js('prod') . "';" ;
        echo 'let ufaqsw_tiny_plugin_texts = {ufaqsw_tiny_text_ai_tools:"' . esc_html__('✨ AI Assistant', 'loop') . '", ufaqsw_tiny_text_available_ai_tools:"' . esc_html__('Available AI Commands - you can set these on settings page', 'loop') . '", ufaqsw_tiny_text_undo:"' . esc_html__('Undo last AI text modification', 'loop') . '"}';        
        echo "</script>";
    }

	public function setup_plugin() {
		add_filter( 'mce_external_plugins', array( $this, 'add_tiny_plugin' ), 1, 2 );
		add_filter( 'mce_buttons', array( $this, 'add_tiny_toolbar_button' ) );
	}

	/**
    * Adds a TinyMCE plugin compatible JS file to the TinyMCE / Visual Editor instance
    *
    * @param array $plugin_array Array of registered TinyMCE Plugins
    * @return array Modified array of registered TinyMCE Plugins
    */
    public function add_tiny_plugin( $plugin_array ) {
    
        $plugin_array['ufaqsw_ai_assistant_menu'] = UFAQSW_TEXT_REFINER_URL . 'assets/js/tiny.js';
		$plugin_array['loadingoverlay'] = UFAQSW_TEXT_REFINER_URL . 'assets/js/loadingoverlay/loadingoverlay.min.js';
        return $plugin_array;
    }     


    /**
    * Adds a button to the main TinyMCE toolbar row.
    *
    * @param array $buttons Array of registered TinyMCE Buttons
    * @return array Modified array of registered TinyMCE Buttons
    */
    public function add_tiny_toolbar_button( $buttons ) {
        array_push( $buttons, 'ufaqsw_ai_assistant_menu', 'ufaqsw_ai_assistant_undo' );
        return $buttons;
    }
}
