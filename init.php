<?php
/**
 * Ultimate FAQ Solution Plugin for WordPress.
 *
 * @package   ultimate-faq-solution
 * @link      https://github.com/Braintum/ultimate-faq-solution
 * @author    Md. Mahedi Hasan <mahedihasannoman@gmail.com>
 * @copyright 2020-2025 Braintum
 * @license   GPL v2 or later
 *
 * Plugin Name: Ultimate FAQ Solution
 * Version: 1.7.5
 * Plugin URI: https://www.braintum.com/ultimate-faq-solution/
 * Description: A WordPress plugin to create, organize, and display FAQs with responsive layouts and styles.
 * Author: braintum
 * Author URI: https://www.braintum.com
 * Text Domain: ufaqsw
 * Domain Path: /languages/
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/license-list.html#GPLCompatibleLicenses
 * Requires at least: 5.1
 * Tested up to: 6.8.3
 *
 * Requires PHP: 7.4
 */

if ( ! function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

/*
* Define some global constants
* Use `plugin_dir_path` and `plugin_dir_url` only when necessary to reduce overhead.
*/
define( 'UFAQSW_VERSION', '1.7.5' );
define( 'UFAQSW_PRFX', 'ufaqsw' );
define( 'UFAQSW_BASE', plugin_basename( __FILE__ ) );
define( 'UFAQSW__PLUGIN_DIR', __DIR__ . '/' );
define( 'UFAQSW__PLUGIN_URL', plugins_url( '/', __FILE__ ) );
define( 'UFAQSW_ASSETS_URL', UFAQSW__PLUGIN_URL . 'assets/' );

// Autoload dependencies only when needed.
if ( file_exists( UFAQSW__PLUGIN_DIR . 'vendor/autoload.php' ) ) {
	require_once UFAQSW__PLUGIN_DIR . 'vendor/autoload.php';
}

// Load general functions and utilities.
require_once UFAQSW__PLUGIN_DIR . 'inc/functions/general.php';

// Load cpt.
require_once UFAQSW__PLUGIN_DIR . 'inc/admin/class-directory-post-type.php';

// Load admin-specific files only in the admin area.
if ( is_admin() ) {
	include_once UFAQSW__PLUGIN_DIR . 'inc/admin/class-faq-group-sorting.php';
	include_once UFAQSW__PLUGIN_DIR . 'inc/admin/settings/settings.php';
	include_once UFAQSW__PLUGIN_DIR . 'inc/admin/chatbot.php';
	include_once UFAQSW__PLUGIN_DIR . 'inc/admin/icons/class.icons.php';
	include_once UFAQSW__PLUGIN_DIR . 'inc/admin/installation.php';
	include_once UFAQSW__PLUGIN_DIR . 'inc/ai-writing-assistant/init.php';
	include_once UFAQSW__PLUGIN_DIR . 'inc/ExportImport/bootstrap.php';

	Mahedi\UltimateFaqSolution\ExportImport\bootstrap();
}

// Lazy-load classes to improve performance.
add_action(
	'plugins_loaded',
	function () {
		Mahedi\UltimateFaqSolution\Assets::get_instance();
		Mahedi\UltimateFaqSolution\Shortcodes::get_instance();
		Mahedi\UltimateFaqSolution\Product_Tab::get_instance();

		// Structured data support for FAQs.
		new Mahedi\UltimateFaqSolution\SEO();

		// REST API.
		new Mahedi\UltimateFaqSolution\Rest();

		new Mahedi\UltimateFaqSolution\Chatbot();

		// REST API.
		new Mahedi\UltimateFaqSolution\Upgrader();

		// Appearance actions for custom post type.
		new Mahedi\UltimateFaqSolution\AppearanceActions();

		// FAQ group actions for custom post type.
		new Mahedi\UltimateFaqSolution\FAQGroupActions();

		load_plugin_textdomain( 'ufaqsw', false, dirname( plugin_basename( __FILE__ ) ) . '/inc/languages' );

		// Deactivation feedback.
		new Mahedi\UltimateFaqSolution\DeactivationFeedback();
	}
);

// Load utility files only when necessary.
require_once UFAQSW__PLUGIN_DIR . 'inc/functions/actions_and_filters.php';
require_once UFAQSW__PLUGIN_DIR . 'block/block.php';

// Register activation and deactivation hooks.
register_activation_hook( __FILE__, array( 'UFAQSW_installation', 'plugin_activation' ) );
register_deactivation_hook( __FILE__, array( 'UFAQSW_installation', 'plugin_deactivation' ) );
