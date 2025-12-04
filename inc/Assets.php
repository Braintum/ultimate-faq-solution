<?php
/**
 * Assets class for managing frontend and backend resources.
 *
 * @package UltimateFaqSolution
 * @author  Mahedi
 * @license GPL-2.0-or-later
 * @link    https://example.com
 */

namespace Mahedi\UltimateFaqSolution;

/**
 * Class Assets
 *
 * Handles the registration and enqueueing of frontend and backend assets.
 *
 * @package UltimateFaqSolution
 */
class Assets {

	/**
	 * Holds the class instance.
	 *
	 * @var Assets
	 */
	private static $instance;

	/**
	 * Retrieves the singleton instance of the Assets class.
	 *
	 * @return Assets The singleton instance of the Assets class.
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
	/**
	 * Constructor for the Assets class.
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
	 * @return void
	 */
	public function render_resources_frontend() {
		wp_register_style( 'ufaqsw_fa_css', UFAQSW__PLUGIN_URL . 'assets/css/font-awesome.min.css', array(), UFAQSW_VERSION, 'all' );
		wp_register_style( 'ufaqsw_styles_css', UFAQSW__PLUGIN_URL . 'assets/css/styles.min.css', array(), UFAQSW_VERSION, 'all' );
		wp_enqueue_style( 'ufaqsw_styles_css' );

		// jQuery for fronend.
		wp_enqueue_script( 'jquery', 'jquery', array(), UFAQSW_VERSION, true );
		wp_register_script( 'ufaqsw-quicksearch-front-js', UFAQSW__PLUGIN_URL . 'assets/js/jquery.quicksearch.js', array( 'jquery' ), UFAQSW_VERSION, true );
	}

	/**
	 * Enqueues backend resources such as styles and scripts.
	 *
	 * @return void
	 */
	public function render_resources_backend() {

		wp_enqueue_media();
		wp_enqueue_script( 'jquery', 'jquery', array(), UFAQSW_VERSION, true );
		wp_register_style( 'ufaqsw_backend_fa_css', UFAQSW__PLUGIN_URL . 'assets/css/font-awesome.min.css', array(), UFAQSW_VERSION );
		wp_enqueue_style( 'ufaqsw_backend_fa_css' );
		wp_register_style( 'ufaqsw_backend_admin_style', UFAQSW__PLUGIN_URL . 'assets/css/admin-style.css', array(), UFAQSW_VERSION );
		wp_enqueue_style( 'ufaqsw_backend_admin_style' );
		wp_register_script( 'ufaqsw-admin-js', UFAQSW__PLUGIN_URL . 'assets/js/admin.js', array( 'jquery' ), UFAQSW_VERSION, true );
		wp_enqueue_script( 'ufaqsw-admin-js' );
		wp_enqueue_script( 'ufaqsw-cmb2-conditional', UFAQSW_ASSETS_URL . 'js/cmb2-conditional-logic.js', array( 'jquery' ), '1.0.0', true );
	}
}
