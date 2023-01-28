<?php
/**
 * @package   ultimate-faq-solution
 * @link      https://github.com/Braintum/ultimate-faq-solution
 * @author    Md. Mahedi Hasan <mahedi@braintum.com>
 * @copyright 2020-2023 Braintum
 * @license   GPL v2 or later
 * 
 * Plugin Name: Ultimate FAQ Solution
 * Version: 2.0
 * Plugin URI: http://rsfaq.braintum.com/
 * Description: An ultimate FAQ Solution plugin for Wordpress & Woocommerce that lets you create, organize and publicize your FAQs (frequently asked questions) in no time through your WordPress admin panel. Select from multiple responsive FAQ layouts and styles. Also it’s the most comprehensive WooCommerce FAQs solution!
 * Author: Braintum
 * Author URI: https://www.braintum.com
 * Text Domain: ufaqsw
 * Domain Path: /languages/
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/license-list.html#GPLCompatibleLicenses
 * Requires at least: 5.1
 * Tested up to: 6.1.1
 *
 * Requires PHP: 7.2
 */

if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

require_once dirname( __FILE__ ) . '/vendor/autoload.php';

/*
* Define some global constants
*/
define( 'UFAQSW_VERSION', '2.0' );
define( 'UFAQSW_PRFX', 'ufaqsw' );
define( 'UFAQSW_BASE', plugin_basename( __FILE__ ) );
define( 'UFAQSW__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'UFAQSW__PLUGIN_URL', plugin_dir_url(__FILE__) );
define( 'UFAQSW__PLUGIN_TEMPLATE_URL', plugin_dir_url(__FILE__) . 'frontend/templates/' );
define( 'UFAQSW__ASSETS_URL', UFAQSW__PLUGIN_URL . 'assets/' );



if ( is_admin() ) {
	require_once UFAQSW__PLUGIN_DIR . 'admin/class-directory-post-type.php';
}

Mahedi\UltimateFaqSolution\Assets::get_instance();
Mahedi\UltimateFaqSolution\Shortcodes::get_instance();

require_once UFAQSW__PLUGIN_DIR . 'utilities/actions_and_filters.php';
require_once UFAQSW__PLUGIN_DIR . 'utilities/class-product-tab.php';
require_once UFAQSW__PLUGIN_DIR . 'admin/settings/settings.php';
require_once UFAQSW__PLUGIN_DIR . 'admin/icons/class.icons.php';
require_once UFAQSW__PLUGIN_DIR . 'admin/installation.php';
require_once UFAQSW__PLUGIN_DIR . 'bt-rating-feature/rating-class.php';
require_once UFAQSW__PLUGIN_DIR . 'plugin-deactivate-feedback.php';

function ufaqsw_lang_init() {
	load_plugin_textdomain( 'ufaqsw', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', 'ufaqsw_lang_init' );

register_activation_hook( __FILE__, array( 'UFAQSW_installation', 'plugin_activation' ) );
register_deactivation_hook( __FILE__, array( 'UFAQSW_installation', 'plugin_deactivation' ) );

$ufaqsw_feedback = new Ufaqsw_Feedback( __FILE__, '', false, true );
