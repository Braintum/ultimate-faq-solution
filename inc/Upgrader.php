<?php
/**
 * Upgrader class.
 *
 * @package UltimateFaqSolution
 * @author  Mahedi
 * @license GPL-2.0-or-later
 * @link    https://www.braintum.com
 */

namespace Mahedi\UltimateFaqSolution;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Upgrader
 *
 * Handles the upgrade of FAQ plugin.
 */
class Upgrader {

	/**
	 * Map of plugin versions to upgrade callbacks.
	 *
	 * @var array $map
	 */
	private $map = array();

	/**
	 * Constructor for the Upgrader class.
	 *
	 * Initializes the upgrade map and starts the upgrader process.
	 */
	public function __construct() {

		$this->setup_map();
		$this->upgrader_init();
	}

	/**
	 * Initializes the upgrade map with version-specific upgrade callbacks.
	 *
	 * This method sets up an associative array mapping plugin versions to their
	 * corresponding upgrade callback functions. The map is used to determine which
	 * upgrade routines should be executed when updating the plugin to a new version.
	 *
	 * @access private
	 */
	private function setup_map() {
		$this->map = array(
			'1.6.3' => array(
				'callback' => 'upgrade_to_1_6_3',
			),
		);
	}

	/**
	 * Initializes the plugin upgrader process.
	 *
	 * Checks if the plugin version stored in the database is older than the current version.
	 * If so, iterates through the upgrade map and includes the necessary upgrade callback files
	 * for each version that is newer than the current version.
	 *
	 * @return void
	 */
	public function upgrader_init() {
		// Check if the plugin is being upgraded.
		if ( ! get_option( 'ufaqsw_version' ) || version_compare( get_option( 'ufaqsw_version' ), UFAQSW_VERSION, '<' ) ) {
			$current_version = get_option( 'ufaqsw_version', '0.0.0' );
			// Loop through the map to find the appropriate upgrade callback.
			foreach ( $this->map as $version => $upgrade_info ) {
				if ( version_compare( $current_version, $version, '<' ) ) {
					if ( isset( $upgrade_info['callback'] ) && file_exists( UFAQSW__PLUGIN_DIR . 'inc/upgrades/' . $upgrade_info['callback'] . '.php' ) ) {
						require_once UFAQSW__PLUGIN_DIR . 'inc/upgrades/' . $upgrade_info['callback'] . '.php';
					}
				}
			}

			update_option( 'ufaqsw_version', UFAQSW_VERSION );
		}
	}
}
