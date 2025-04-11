<?php
/**
 * Shortcodes class for Ultimate FAQ Solution plugin.
 *
 * This file contains the Shortcodes class, which handles the rendering of
 * shortcodes for displaying FAQs in various formats.
 *
 * @package UltimateFaqSolution
 */

namespace Mahedi\UltimateFaqSolution;

use Mahedi\UltimateFaqSolution\Custom_Resources;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles the rendering of shortcodes for the Ultimate FAQ Solution plugin.
 *
 * This class provides methods to render individual and grouped FAQs using
 * WordPress shortcodes.
 *
 * @package UltimateFaqSolution
 */
class Shortcodes {

	/**
	 * Holds the single instance of the Shortcodes class.
	 *
	 * @var Shortcodes
	 */
	private static $instance;

	/**
	 * Handler for the JavaScript file used in the plugin.
	 *
	 * @var string
	 */
	private static $js_handler = 'ufaqsw-script-js';

	/**
	 * Handler for the CSS file used in the plugin.
	 *
	 * @var string
	 */
	private static $css_handler = 'ufaqsw_styles_css';

	/**
	 * Get the instance of this class
	 *
	 * @return object UFAQSW_shortcode_handler
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Constructor
	 */
	public function __construct() {
		add_shortcode( 'ufaqsw', array( $this, 'render_shortcode' ) );
		add_shortcode( 'ufaqsw-all', array( $this, 'render_all' ) );
	}

	/**
	 * Enqueues the necessary CSS and JavaScript assets for the plugin.
	 *
	 * This function ensures that the required styles and scripts are loaded
	 * for the proper functioning of the plugin's front-end features.
	 */
	private function enqueue_assets() {
		wp_enqueue_style( 'ufaqsw_fa_css' );
		wp_enqueue_style( self::$css_handler, UFAQSW__PLUGIN_URL . 'assets/css/styles.min.css', array(), UFAQSW_VERSION, 'all' );
		wp_enqueue_script( 'ufaqsw-quicksearch-front-js' );
		wp_enqueue_script( self::$js_handler, UFAQSW__PLUGIN_URL . 'assets/js/script.min.js', array( 'jquery', 'ufaqsw-quicksearch-front-js' ), UFAQSW_VERSION, true );
	}

	/**
	 * Renders the shortcode for displaying a single FAQ group.
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string Rendered HTML content for the FAQ group.
	 */
	public function render_shortcode( $atts = array() ) {

		extract( // phpcs:ignore
			shortcode_atts(
				array(
					'id'             => 1,
					'title_hide'     => null,
					'elements_order' => 'asc',
				),
				$atts
			)
		);

		$faq_args  = array(
			'post_type' => 'ufaqsw',
			'p'         => $id,
		);
		$template  = 'default';
		$faq_query = new \WP_Query( $faq_args );

		// load assets.
		$this->enqueue_assets();

		ob_start();

		if ( $faq_query->have_posts() ) {

			while ( $faq_query->have_posts() ) {
				$faq_query->the_post();

				$faqs = get_post_meta( get_the_ID(), 'ufaqsw_faq_item01' );
				$faqs = isset( $faqs[0] ) ? $faqs[0] : $faqs;

				if ( null === $title_hide ) {

					$hide_title = get_post_meta( get_the_ID(), 'ufaqsw_hide_title', true );

					if ( '' !== $hide_title ) {
						$title_hide = 'yes';
					}
				}

				if ( 'desc' === strtolower( $elements_order ) ) {
					$faqs = array_values( array_reverse( $faqs, true ) );
				}

				$designs = apply_filters( 'ufaqsw_simplify_configuration_variables', get_the_ID() );

				$template = apply_filters( 'ufaqsw_template_filter', $designs['template'] );

				( new Custom_Resources(
					get_the_ID(),
					$designs,
					$template,
					self::$js_handler,
					self::$css_handler
				) )->render_css()->render_js();

				if ( file_exists( Template::locate( $template ) ) ) {
					include Template::locate( $template );
				} else {
					// translators: %s is the name of the template that was not found.
					echo sprintf( esc_html__( '%s Template Not Found', 'ufaqsw' ), esc_html( $template ) );
				}
			}
		}
		wp_reset_postdata(); // reseting wp query.

		$content = ob_get_clean();
		return $content;

	}

	/**
	 * Renders the shortcode for displaying all FAQ groups.
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string Rendered HTML content for all FAQ groups.
	 */
	public function render_all( $atts = array() ) {

		extract( // phpcs:ignore
			shortcode_atts(
				array(
					'exclude'        => '', // Coma seperated number string: 88, 86.
					'title_hide'     => 'no',
					'elements_order' => 'asc',
					'behaviour'      => 'toggle',
				),
				$atts
			)
		);

		// generating query.
		$faq_args = array(
			'post_type'      => 'ufaqsw',
			'posts_per_page' => -1,
			'post__not_in'   => explode( ', ', $exclude ),
		);

		// default template.
		$template  = 'default';
		$faq_query = new \WP_Query( $faq_args );

		// load assets.
		$this->enqueue_assets();

		if ( $faq_query->have_posts() ) {

			$all_content = '';

			while ( $faq_query->have_posts() ) {
				$faq_query->the_post();

				$faqs = apply_filters( 'ufaqsw_simplify_faqs', get_post_meta( get_the_ID(), 'ufaqsw_faq_item01' ) );

				if ( 'desc' === strtolower( $elements_order ) ) {
					$faqs = array_values( array_reverse( $faqs, true ) );
				}

				$designs              = apply_filters( 'ufaqsw_simplify_configuration_variables', get_the_ID() );
				$designs['behaviour'] = $behaviour;

				$template = apply_filters( 'ufaqsw_template_filter', $designs['template'] );

				( new Custom_Resources(
					get_the_ID(),
					$designs,
					$template,
					self::$js_handler,
					self::$css_handler
				) )->render_css()->render_js();

				ob_start();
				if ( file_exists( Template::locate( $template ) ) ) {
					include Template::locate( $template );
				} else {
					// translators: %s is the name of the template that was not found.
					echo sprintf( esc_html__( '%s Template Not Found', 'ufaqsw' ), esc_html( ucfirst( $template ) ) );
				}

				$content = ob_get_clean();

				$all_content .= "<div class='ufaqsw_default_all_single_faq' id='ufaqsw_single_faq_" . esc_attr( get_the_ID() ) . "'>" . $content . '</div>';

			}
		}
		wp_reset_postdata(); // reseting wp query.

		ob_start();
		$template = 'all'; // Template for All with search box.
		if ( file_exists( Template::locate( $template ) ) ) {
			include Template::locate( $template );
		} else {
			// translators: %s is the name of the template that was not found.
			echo sprintf( esc_html__( '%s Template Not Found', 'ufaqsw' ), esc_html( ucfirst( $template ) ) );
		}
		$content = ob_get_clean();

		return str_replace( '{{content}}', $all_content, $content );

	}
}
