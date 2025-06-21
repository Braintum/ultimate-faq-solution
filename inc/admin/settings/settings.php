<?php
/**
 * Ultimate FAQ Solution - Settings
 *
 * This file contains the global settings for the Ultimate FAQ Solution plugin.
 *
 * @package UltimateFAQSolution
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class UFAQSW_Global_Settings
 *
 * Handles the global settings for the Ultimate FAQ Solution plugin.
 */
class UFAQSW_Global_Settings {

	/**
	 * Holds the singleton instance of the class.
	 *
	 * @var UFAQSW_Global_Settings
	 */
	private static $instance;

	/**
	 * Retrieve the singleton instance of the class.
	 *
	 * Ensures that only one instance of the class is created and used.
	 *
	 * @return UFAQSW_Global_Settings The singleton instance of the class.
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
	/**
	 * Constructor for the UFAQSW_Global_Settings class.
	 *
	 * Initializes the class by adding actions for displaying the settings page
	 * and registering plugin settings.
	 */
	public function __construct() {
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
		register_setting( 'ufaqsw-plugin-settings-group', 'ufaqsw_global_faq' );
	}

	/**
	 * Callback function to display the settings page.
	 *
	 * This function includes the UI file for the settings page if it exists.
	 */
	public function settings_page_callback_func() {
		if ( file_exists( UFAQSW__PLUGIN_DIR . 'inc/admin/settings/ui.php' ) ) {
			include_once UFAQSW__PLUGIN_DIR . 'inc/admin/settings/ui.php';
		}
	}

	/**
	 * Callback function to render the FAQ export/import page.
	 *
	 * This function includes the UI file for the FAQ export/import page if it exists.
	 */
	public function render_faq_export_import_page() {
		if ( file_exists( UFAQSW__PLUGIN_DIR . 'inc/admin/settings/faq-export-import.php' ) ) {
			include_once UFAQSW__PLUGIN_DIR . 'inc/admin/settings/faq-export-import.php';
		}
	}

	/**
	 * Display admin notices for settings saved.
	 */
	public function display_admin_notices() {
		if ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] ) { //phpcs:ignore
			if ( isset( $_GET['page'] ) && $_GET['page'] === 'ufaqsw-settings' ) { //phpcs:ignore
				add_settings_error( 'ufaqsw_messages', 'ufaqsw_message', __( 'Settings saved successfully.', 'ufaqsw' ), 'updated' );
			}
		}
		settings_errors( 'ufaqsw_messages' );
	}
}

/**
 * Retrieve the global settings instance.
 *
 * This function ensures that the global settings instance is initialized
 * and returned for use throughout the plugin.
 *
 * @return UFAQSW_Global_Settings The global settings instance.
 */
function ufaqsw_global_settings() {
	return UFAQSW_Global_Settings::get_instance();
}
ufaqsw_global_settings();
