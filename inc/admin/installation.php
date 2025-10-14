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

		ufaqsw_create_default_appearance();
	}

	/**
	 * Handles tasks to perform during plugin deactivation.
	 */
	public static function plugin_deactivation() {
		// No actions needed on deactivation currently.
	}
}
