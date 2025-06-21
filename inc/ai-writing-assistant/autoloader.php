<?php
/**
 * Autoloader for AI Writing Assistant plugin.
 *
 * This file is responsible for loading the necessary classes for the plugin.
 *
 * @package BTRefiner
 */

defined( 'ABSPATH' ) || exit;

spl_autoload_register(
	function ( $class ) {
		if ( 0 === strpos( $class, 'BTRefiner\\' ) ) {
			$class_path = str_replace( '\\', '/', substr( $class, strlen( 'BTRefiner\\' ) ) );
			$file       = __DIR__ . '/includes/' . $class_path . '.php';
			if ( file_exists( $file ) ) {
				require_once $file;
			}
		}
	}
);
