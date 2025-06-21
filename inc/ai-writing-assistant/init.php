<?php
/**
 * AI Writing Assistant main plugin file.
 *
 * @package UltimateFAQSolution
 */

defined( 'ABSPATH' ) || exit;

require_once __DIR__ . '/autoloader.php';

define( 'UFAQSW_TEXT_REFINER_URL', UFAQSW__PLUGIN_URL );
define( 'UFAQSW_TEXT_REFINER_PATH', UFAQSW__PLUGIN_DIR );

use BTRefiner\Core\Plugin;

Plugin::init();
