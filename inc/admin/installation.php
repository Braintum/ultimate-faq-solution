<?php
/**
 * Plugin Name: Ultimate FAQ Solution - Installation
 * Description: Handles the necessary setup during plugin installation.
 * Author: Mahedi Hasan
 * Version: 1.0
 *
 * @package UltimateFAQSolution
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class UFAQSW_installation
 *
 * Handles the installation and activation of the Ultimate FAQ Solution plugin.
 *
 * @package UltimateFAQSolution
 */
class UFAQSW_Installation {

	/**
	 * Constructor for the UFAQSW_Installation class.
	 *
	 * Adds the activation redirect action.
	 */
	public function __construct() {
		add_action( 'activated_plugin', array( &$this, 'activation_redirect' ) );
	}

	/**
	 * Redirects to the settings page upon plugin activation.
	 *
	 * @param string $plugin The plugin being activated.
	 */
	public function activation_redirect( $plugin ) {
		if ( UFAQSW_BASE === $plugin ) {
			if ( 'cli' !== php_sapi_name() ) {
				wp_safe_redirect( esc_url( admin_url( 'edit.php?post_type=ufaqsw&page=ufaqsw-settings#getting_started' ) ) );
				exit;
			}
		}
	}

	/**
	 * Handles tasks to perform during plugin activation.
	 *
	 * This function sets default options for the plugin upon activation.
	 */
	public static function plugin_activation() {
		if ( ! get_option( 'ufaqsw_enable_search' ) ) {
			update_option( 'ufaqsw_enable_search', 'on' );
		}
		if ( ! get_option( 'ufaqsw_live_search_loading_text' ) ) {
			update_option( 'ufaqsw_live_search_loading_text', 'Loading...' );
		}
		if ( ! get_option( 'ufaqsw_search_result_not_found' ) ) {
			update_option( 'ufaqsw_search_result_not_found', 'No result Found!' );
		}
		if ( ! get_option( 'ufaqsw_live_search_text' ) ) {
			update_option( 'ufaqsw_live_search_text', 'Live Search..' );
		}
	}

	/**
	 * Handles tasks to perform during plugin deactivation.
	 */
	public static function plugin_deactivation() {

	}
}

new UFAQSW_installation();
