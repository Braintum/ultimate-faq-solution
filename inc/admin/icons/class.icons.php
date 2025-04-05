<?php
/**
 * Icons Settings Class
 *
 * @package UltimateFAQSolution
 * @author Mahedi Hasan
 * @description Responsible for icon choosing modal for RS FAQ
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class UFAQSW_Icon_Settings
 *
 * @package UltimateFAQSolution
 * @since 1.0
 */
class UFAQSW_Icon_Settings {

	/**
	 * Holds the single instance of the class.
	 *
	 * @var UFAQSW_Icon_Settings
	 */
	private static $instance;

	/**
	 * Retrieves the single instance of the UFAQSW_Icon_Settings class.
	 *
	 * @return UFAQSW_Icon_Settings The instance of the class.
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
	/**
	 * Constructor for the UFAQSW_Icon_Settings class.
	 *
	 * Adds the action to include the icon modal in the admin footer.
	 */
	public function __construct() {
		add_action( 'admin_footer', array( &$this, 'ufaqsw_modal_fa' ) );
	}

	/**
	 * Outputs the icon modal in the admin footer for the 'ufaqsw' post type.
	 *
	 * Reads icon data from a file and includes the UI for selecting icons.
	 */
	public function ufaqsw_modal_fa() {

		global $post, $typenow, $current_screen;

		if ( $typenow && 'ufaqsw' === $typenow ) {
			$data  = file( UFAQSW__PLUGIN_DIR . 'assets/data/fa-data.txt' ); // file in to an array.
			$icons = array();
			foreach ( $data as $key => $val ) {
				$val   = explode( '=>', $val );
				$title = $val[0];
				$class = explode( ',', $val[1] );
				foreach ( $class as $v => $k ) {
					if ( strlen( $k ) > 2 ) {
						$icons[ $title ][] = trim( $k );
					}
				}
			}
			include_once UFAQSW__PLUGIN_DIR . 'inc/admin/icons/ui.php';
		}

	}

}

/**
 * Initialize the UFAQSW_Icon_Settings class instance.
 *
 * @return UFAQSW_Icon_Settings The instance of the class.
 */
function ufaqsw_icons_setting() {
	return UFAQSW_icon_settings::get_instance();
}
ufaqsw_icons_setting();
