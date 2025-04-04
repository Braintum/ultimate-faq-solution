<?php
/**
 * Ultimate FAQ Solution Plugin for WordPress.
 *
 * @package   ultimate-faq-solution
 * @link      https://github.com/Braintum/ultimate-faq-solution
 * @author    Md. Mahedi Hasan <mahedihasannoman@gmail.com>
 * @copyright 2020-2025 SolrEngine
 * @license   GPL v2 or later
 *
 * Plugin Name: Ultimate FAQ Solution
 * Version: 1.4.7
 * Plugin URI: http://www.solrengine.com
 * Description: An ultimate FAQ Solution plugin for WordPress & WooCommerce that lets you create, organize and publicize your FAQs (frequently asked questions) in no time through your WordPress admin panel. Select from multiple responsive FAQ layouts and styles. Also it’s the most comprehensive WooCommerce FAQs solution!
 * Author: braintum
 * Author URI: https://www.solrengine.com
 * Text Domain: ufaqsw
 * Domain Path: /languages/
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/license-list.html#GPLCompatibleLicenses
 * Requires at least: 5.1
 * Tested up to: 6.7.2
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
define( 'UFAQSW_VERSION', '1.4.7' );
define( 'UFAQSW_PRFX', 'ufaqsw' );
define( 'UFAQSW_BASE', plugin_basename( __FILE__ ) );
define( 'UFAQSW__PLUGIN_DIR', __DIR__ . '/' );
define( 'UFAQSW__PLUGIN_URL', plugins_url( '/', __FILE__ ) );
define( 'UFAQSW_ASSETS_URL', UFAQSW__PLUGIN_URL . 'assets/' );

// Autoload dependencies only when needed.
if ( file_exists( UFAQSW__PLUGIN_DIR . 'vendor/autoload.php' ) ) {
	require_once UFAQSW__PLUGIN_DIR . 'vendor/autoload.php';
}

// Load admin-specific files only in the admin area.
if ( is_admin() ) {
	include_once UFAQSW__PLUGIN_DIR . 'admin/class-directory-post-type.php';
	include_once UFAQSW__PLUGIN_DIR . 'admin/settings/settings.php';
	include_once UFAQSW__PLUGIN_DIR . 'admin/icons/class.icons.php';
	include_once UFAQSW__PLUGIN_DIR . 'admin/installation.php';
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

		load_plugin_textdomain( 'ufaqsw', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}
);

// Load utility files only when necessary.
require_once UFAQSW__PLUGIN_DIR . 'inc/functions/actions_and_filters.php';

// Register activation and deactivation hooks.
register_activation_hook( __FILE__, array( 'UFAQSW_installation', 'plugin_activation' ) );
register_deactivation_hook( __FILE__, array( 'UFAQSW_installation', 'plugin_deactivation' ) );

