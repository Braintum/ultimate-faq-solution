<?php
/**
 * Ultimate FAQ Solution - Settings
 *
 * This file contains the global settings for the Ultimate FAQ Solution plugin.
 *
 * @package UltimateFAQSolution
 */

namespace Mahedi\UltimateFaqSolution\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class RegisterAdminPages
 *
 * Handles the global settings for the Ultimate FAQ Solution plugin.
 */
class RegisterAdminPages {

	/**
	 * Holds the singleton instance of the class.
	 *
	 * @var RegisterAdminPages
	 */
	private static $instance;

	/**
	 * Retrieve the singleton instance of the class.
	 *
	 * Ensures that only one instance of the class is created and used.
	 *
	 * @return RegisterAdminPages The singleton instance of the class.
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
	/**
	 * Constructor for the RegisterAdminPages class.
	 *
	 * Initializes the class by adding actions for displaying the settings page
	 * and registering plugin settings.
	 */
	public function __construct() {

		// Include AI integration settings.
		require_once UFAQSW__PLUGIN_DIR . 'inc/admin/settings/ai-integration.php';

		add_action( 'admin_menu', array( $this, 'show_settings_page_callback_func' ) );
		add_action( 'admin_init', array( $this, 'register_plugin_settings' ) );
		add_action( 'admin_notices', array( $this, 'display_admin_notices' ) ); // Add this line.
	}

	/**
	 * Add a submenu page for the plugin settings.
	 *
	 * This function adds a submenu page under the custom post type
	 * for managing the plugin's settings and help documentation.
	 */
	public function show_settings_page_callback_func() {

		add_submenu_page(
			'edit.php?post_type=ufaqsw',
			'Settings - Ultimate FAQs',
			'Settings',
			'manage_options',
			'ufaqsw-settings',
			array( $this, 'settings_page_callback_func' )
		);

		add_submenu_page(
			'edit.php?post_type=ufaqsw',
			'Get Help - Ultimate FAQ Solution',
			'Get Help',
			'manage_options',
			'ufaqsw-get-help',
			array( $this, 'get_help_page_callback_func' )
		);
	}

	/**
	 * Register plugin settings.
	 *
	 * This function registers all the settings used by the plugin
	 * to manage its functionality and customization options.
	 */
	public function register_plugin_settings() {
		register_setting( 'ufaqsw-plugin-settings-group', 'ufaqsw_enable_woocommerce' );
		register_setting( 'ufaqsw-plugin-settings-group', 'ufaqsw_enable_search' );
		register_setting( 'ufaqsw-plugin-settings-group', 'ufaqsw_enable_filter' );
		register_setting( 'ufaqsw-plugin-settings-group', 'ufaqsw_live_search_text' );
		register_setting( 'ufaqsw-plugin-settings-group', 'ufaqsw_live_search_loading_text' );
		register_setting( 'ufaqsw-plugin-settings-group', 'ufaqsw_search_result_not_found' );
		register_setting( 'ufaqsw-plugin-settings-group', 'ufaqsw_setting_custom_style' );

		register_setting( 'ufaqsw-plugin-settings-group', 'ufaqsw_enable_global_faq' );
		register_setting( 'ufaqsw-plugin-settings-group', 'ufaqsw_global_faq_label' );
		register_setting( 'ufaqsw-plugin-settings-group', 'ufaqsw_product_hide_group_title' );
		register_setting( 'ufaqsw-plugin-settings-group', 'ufaqsw_global_faq' );

		register_setting( 'ufaqsw-plugin-settings-group', 'ufaqsw_detail_page_slug' );
		register_setting( 'ufaqsw-plugin-settings-group', 'ufaqsw_enable_group_detail_page' );
	}

	/**
	 * Callback function to display the settings page.
	 *
	 * This function includes the UI file for the settings page if it exists.
	 */
	public function settings_page_callback_func() {
		if ( file_exists( UFAQSW__PLUGIN_DIR . 'inc/admin/templates/settings.php' ) ) {
			include_once UFAQSW__PLUGIN_DIR . 'inc/admin/templates/settings.php';
		}
	}

	/**
	 * Callback function to display the help page.
	 *
	 * This function includes the help template file if it exists.
	 */
	public function get_help_page_callback_func() {
		if ( file_exists( UFAQSW__PLUGIN_DIR . 'inc/admin/templates/get-help.php' ) ) {
			include_once UFAQSW__PLUGIN_DIR . 'inc/admin/templates/get-help.php';
		}
	}

	/**
	 * Display admin notices for settings saved.
	 */
	public function display_admin_notices() {
		if ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] ) { //phpcs:ignore
			if ( isset( $_GET['page'] ) && $_GET['page'] === 'ufaqsw-settings' ) { //phpcs:ignore
				$message = __( 'Settings saved successfully.', 'ufaqsw' );

				// Flush rewrite rules only when group detail page is enabled.
				if ( get_option( 'ufaqsw_enable_group_detail_page' ) === 'on' ) {
					flush_rewrite_rules();
					$message = __( 'Settings saved successfully. Permalinks have been refreshed.', 'ufaqsw' );
				}

				add_settings_error( 'ufaqsw_messages', 'ufaqsw_message', $message, 'updated' );
			}
		}
		settings_errors( 'ufaqsw_messages' );
	}
}
