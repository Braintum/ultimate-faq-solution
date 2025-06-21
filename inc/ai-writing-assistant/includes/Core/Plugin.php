<?php // phpcs:ignoreFile

namespace BTRefiner\Core;

use BTRefiner\Admin\Ajax;
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

		$ai_settings = get_option( 'ufaqsw_ai_integration_settings', array() );

		if ( isset( $ai_settings['enable_ai_integration'] ) && $ai_settings['enable_ai_integration'] ) {
			new TinyMCEIntegration();
			new Ajax();
		}
	}

}