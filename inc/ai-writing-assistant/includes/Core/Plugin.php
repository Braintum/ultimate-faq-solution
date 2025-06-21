<?php // phpcs:ignoreFile

namespace BTRefiner\Core;

use BTRefiner\Admin\ACFIntegration;
use BTRefiner\Admin\Ajax;
use BTRefiner\Admin\Settings;
use BTRefiner\Admin\TinyMCEIntegration;

class Plugin {

	/**
	 * Initialize the plugin.
	 */
	public static function init(): void {
		add_action( 'plugins_loaded', array( self::class, 'setup' ) );
	}

	/**
	 * Setup plugin components.
	 */
	public static function setup(): void {
		if ( ! function_exists( 'acf_add_local_field_group' ) ) {
			add_action( 'admin_notices', array( self::class, 'acf_missing_notice' ) );
			return;
		}

		//new Settings();

		if ( get_option( 'options_ufaqsw_ai_status' ) ) {
			//new ACFIntegration();
			new TinyMCEIntegration();
			new Ajax();
		}
	}

	/**
	 * Display an admin notice if ACF is missing.
	 */
	public static function acf_missing_notice(): void {
		echo '<div class="notice notice-error"><p><strong>AI Text Refiner:</strong> Advanced Custom Fields (ACF) plugin is not active. Please install and activate it to use this plugin.</p></div>';
	}
}