<?php
/**
 * Global Resource Handler for Ultimate FAQ Solution
 *
 * @package UltimateFAQSolution
 * @author Mahedi Hasan
 * @description Responsible for handling all global resources for Ultimate FAQ Solution
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Global Resource Handler by Braintum
 *
 * @package UltimateFAQSolution
 * @author Mahedi Hasan
 * @description Responsible for handling all global resources for Ultimate FAQ Solution
 * @since 1.0
 */
class UFAQSW_Global_Resources {

	/**
	 * Holds the class instance.
	 *
	 * @var UFAQSW_Global_Resources
	 */
	private static $instance;

	/**
	 * Retrieves the singleton instance of the class.
	 *
	 * @return UFAQSW_Global_Resources The singleton instance of the class.
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Constructor for the UFAQSW_Global_Resources class.
	 *
	 * Initializes the class by adding actions to enqueue frontend and backend resources.
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'render_resources_frontend' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'render_resources_backend' ) );
	}

	/**
	 * Enqueues frontend resources such as styles and scripts.
	 *
	 * Registers and enqueues necessary CSS and JavaScript files for the frontend.
	 */
	public function render_resources_frontend() {
		wp_register_style( 'ufaqsw_fa_css', UFAQSW__PLUGIN_URL . 'assets/css/font-awesome.min.css', array(), UFAQSW_VERSION );
		wp_enqueue_style( 'ufaqsw_fa_css' );
		wp_register_style( 'ufaqsw_styles_css', UFAQSW__PLUGIN_URL . 'assets/css/styles.min.css', array(), UFAQSW_VERSION );

		// jQuery for fronend.
		wp_enqueue_script( 'jquery', 'jquery', array(), UFAQSW_VERSION, true );
		wp_register_script( 'ufaqsw-grid-js', UFAQSW__PLUGIN_URL . 'assets/js/packery.min.js', array( 'jquery' ), UFAQSW_VERSION, true );
		wp_register_script( 'ufaqsw-quicksearch-front-js', UFAQSW__PLUGIN_URL . 'assets/js/jquery.quicksearch.js', array( 'jquery' ), UFAQSW_VERSION, true );
		wp_register_script( 'ufaqsw-script-js', UFAQSW__PLUGIN_URL . 'assets/js/script.min.js', array( 'jquery', 'ufaqsw-grid-js', 'ufaqsw-quicksearch-front-js' ), UFAQSW_VERSION, true );

	}
	/**
	 * Enqueues backend resources such as styles and scripts.
	 *
	 * Registers and enqueues necessary CSS and JavaScript files for the backend.
	 */
	public function render_resources_backend() {

		// jQuery for backend.
		wp_enqueue_media();
		wp_enqueue_script( 'jquery', 'jquery', array(), UFAQSW_VERSION, true );
		wp_register_style( 'ufaqsw_backend_fa_css', UFAQSW__PLUGIN_URL . 'assets/css/font-awesome.min.css', array(), UFAQSW_VERSION );
		wp_enqueue_style( 'ufaqsw_backend_fa_css' );
		wp_register_style( 'ufaqsw_backend_admin_style', UFAQSW__PLUGIN_URL . 'assets/css/admin-style.css', array(), UFAQSW_VERSION );
		wp_enqueue_style( 'ufaqsw_backend_admin_style' );
		wp_register_script( 'ufaqsw-admin-js', UFAQSW__PLUGIN_URL . 'assets/js/admin.js', array( 'jquery' ), UFAQSW_VERSION, true );
		wp_enqueue_script( 'ufaqsw-admin-js' );
		wp_enqueue_script( 'ufaqsw-quicksearch-js', UFAQSW__PLUGIN_URL . 'assets/js/jquery.quicksearch.js', array( 'jquery' ), UFAQSW_VERSION, true );

	}
}

/**
 * Retrieves the singleton instance of the global resources handler.
 *
 * @return UFAQSW_Global_Resources The singleton instance of the global resources handler.
 */
function ufaqsw_global_resources() {
	return UFAQSW_global_resources::get_instance();
}
ufaqsw_global_resources();
