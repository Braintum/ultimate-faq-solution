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

		if ( $this->is_faq_present() ) {
			wp_register_style( 'ufaqsw_fa_css', UFAQSW__PLUGIN_URL . 'assets/css/font-awesome.min.css', array(), UFAQSW_VERSION, 'all' );
			wp_register_style( 'ufaqsw_styles_css', UFAQSW__PLUGIN_URL . 'assets/css/styles.min.css', array(), UFAQSW_VERSION, 'all' );

			wp_enqueue_style( 'ufaqsw_fa_css' );
			wp_enqueue_style( 'ufaqsw_styles_css' );
		}

		// jQuery for fronend.
		wp_enqueue_script( 'jquery', 'jquery', array(), UFAQSW_VERSION, true );
		wp_register_script( 'ufaqsw-quicksearch-front-js', UFAQSW__PLUGIN_URL . 'assets/js/jquery.quicksearch.js', array( 'jquery' ), UFAQSW_VERSION, true );

	}

	/**
	 * Checks if the FAQ shortcode or block is present in the content.
	 *
	 * This function checks if the FAQ shortcode or block is used in the current post content.
	 * If either is found, it returns true, indicating that the FAQ assets should be enqueued.
	 *
	 * @return bool True if FAQ shortcode or block is present, false otherwise.
	 */
	private function is_faq_present() {

		// Check if FAQ shortcode is present or FAQ block is used.
		global $post;

		$enqueue = false;

		if ( is_a( $post, 'WP_Post' ) ) {
			// Check for shortcode in post content.
			if ( has_shortcode( $post->post_content, 'ufaqsw-all' ) ) {
				$enqueue = true;
			}

			if ( has_shortcode( $post->post_content, 'ufaqsw' ) ) {
				$enqueue = true;
			}
		}

		// Check for FAQ block in the content (for block editor).
		if ( ! $enqueue && function_exists( 'has_block' ) && is_a( $post, 'WP_Post' ) ) {

			if ( has_block( 'ultimate-faq-solution/block', $post ) ) {
				$enqueue = true;
			}
		}

		// Check if FAQ is present in widgets.
		if ( ! $enqueue && $this->is_faq_present_in_widgets() ) {
			$enqueue = true;
		}

		/**
		 * Filter to allow overriding whether FAQ assets should be enqueued.
		 *
		 * @param bool $enqueue Whether to enqueue FAQ assets.
		 * @param WP_Post|null $post The current post object.
		 */
		return apply_filters( 'ufaqsw_should_enqueue_styles', $enqueue, $post );
	}

	/**
	 * Checks if FAQ shortcodes or blocks are present in widgets.
	 *
	 * @return bool True if FAQ is used in any widget, false otherwise.
	 */
	private function is_faq_present_in_widgets() {
		$faq_used = false;

		$sidebars_widgets = wp_get_sidebars_widgets();

		foreach ( $sidebars_widgets as $sidebar_id => $widget_ids ) {
			if ( is_array( $widget_ids ) ) {
				foreach ( $widget_ids as $widget_id ) {
					$widget_instance = get_option( 'widget_' . _get_widget_id_base( $widget_id ) );

					if ( is_array( $widget_instance ) ) {
						foreach ( $widget_instance as $instance ) {
							if ( isset( $instance['content'] ) ) {
								$content = $instance['content'];

								// Check for FAQ shortcode.
								if ( has_shortcode( $content, 'ufaqsw-all' ) || has_shortcode( $content, 'ufaqsw' ) ) {
									$faq_used = true;
								}

								// Check for FAQ block.
								if ( strpos( $content, 'wp:ultimate-faq-solution/block' ) !== false ) {
									$faq_used = true;
								}
							}
						}
					}
				}
			}
		}

		return $faq_used;
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
		wp_enqueue_script( 'ufaqsw-quicksearch-js', UFAQSW__PLUGIN_URL . 'assets/js/jquery.quicksearch.js', array( 'jquery' ), UFAQSW_VERSION, true );
		wp_enqueue_script( 'ufaqsw-cmb2-conditional', UFAQSW_ASSETS_URL . 'js/cmb2-conditional-logic.js', array( 'jquery' ), '1.0.0', true );
	}
}
