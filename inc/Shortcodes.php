<?php

namespace Mahedi\UltimateFaqSolution;

use Mahedi\UltimateFaqSolution\Custom_Resources;

if ( ! defined( 'ABSPATH' ) ) exit;

class Shortcodes {

	// class instance
	static $instance;

	private static $js_handler = 'ufaqsw-script-js';

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
		add_shortcode('ufaqsw-all', array( $this, 'render_all'));
	}
	
	private function enqueue_assets() {
		wp_enqueue_style( 'ufaqsw_fa_css' );
		wp_enqueue_style(  self::$css_handler , UFAQSW__PLUGIN_URL . 'assets/css/styles.min.css', array(), UFAQSW_VERSION, 'all' );
		wp_enqueue_script( 'ufaqsw-grid-js' );
		wp_enqueue_script( 'ufaqsw-quicksearch-front-js' );
		wp_enqueue_script( self::$js_handler, UFAQSW__PLUGIN_URL . 'assets/js/script.min.js', array( 'jquery', 'ufaqsw-grid-js', 'ufaqsw-quicksearch-front-js' ), UFAQSW_VERSION, true );
	}

	/**
	 * Render shortcodes
	 *
	 * @param array $atts
	 * @return string
	 */
	public function render_shortcode( $atts = [] ) {

		extract( shortcode_atts(
			array(
				'id' => 1,
				'title_hide'=> 'no',
				'elements_order' => 'ASC'
			), $atts
		));

		$faq_args = array(
			'post_type' => 'ufaqsw',
			'p' => $id,
		);
		$template = 'default';
		$faq_query = new \WP_Query( $faq_args );

		//load assets		
		$this->enqueue_assets();

		ob_start();
		
		if ( $faq_query->have_posts() ) {

			while ( $faq_query->have_posts() ) {
				$faq_query->the_post();
				
				$faqs = get_post_meta( get_the_ID(), 'ufaqsw_faq_item01' );

				$faqs = isset( $faqs[0] ) ? $faqs[0] : $faqs;
				
				if ( $elements_order == 'DESC' ) {
					$faqs = array_values( array_reverse( $faqs, true ) );
				}

				$designs = apply_filters( 'ufaqsw_simplify_configuration_variables', get_the_ID() );

				$template = apply_filters( 'ufaqsw_template_filter', $designs['template'] );
				
				(new Custom_Resources( 
					get_the_ID(), 
					$designs, 
					$template, 
					self::$js_handler,
					self::$css_handler 
				))->render_css()->render_js();

				if ( file_exists( Template::locate( $template ) ) ){
					include Template::locate( $template );
				} else {
					echo sprintf( esc_html__( '%s Template Not Found', 'ufaqsw' ), $template );
				}
				
				
			}
		}
		wp_reset_query(); //reseting wp query
		
		$content = ob_get_clean();
		return $content;
		
	}
	
	/*
	* @Method - Directory Shortcode by Braintum
	* @Author - Mahedi Hasan
	* @Description - Responsible for rendering single group shorcode for RS FAQ
	* @Since 1.0
	*/
	public function render_all( $atts = [] ) {
		
		extract( shortcode_atts(
			array(
				'exclude' 			=> '', //Coma seperated number string: 88, 86.
				'column' 			=> 1, // column parameter.
				'title_hide'		=> 'no',
				'elements_order' 	=> 'ASC',
				'behaviour'			=> 'toggle'
			), $atts
		) );
		
		//generating query
		$faq_args = array(
			'post_type' => 'ufaqsw',
			'posts_per_page'   => -1,
			'post__not_in' => explode( ', ', $exclude ),
		);
		//default template
		$template = 'default';
		$faq_query = new \WP_Query( $faq_args );

		//load assets		
		$this->enqueue_assets();

		if ( $faq_query->have_posts() ) {

			$all_content = '';
			
			while ( $faq_query->have_posts() ) {
				$faq_query->the_post();
				
				$faqs = apply_filters('ufaqsw_simplify_faqs', get_post_meta( get_the_ID(), 'ufaqsw_faq_item01' ));
				
				if( $elements_order == 'DESC' ){
					$faqs = array_values( array_reverse( $faqs, true ) );
				}

				$designs = apply_filters('ufaqsw_simplify_configuration_variables', get_the_ID());
				$designs['behaviour'] = $behaviour;
				
				$template = apply_filters('ufaqsw_template_filter', $designs['template']);
				
				(new Custom_Resources( 
					get_the_ID(), 
					$designs, 
					$template, 
					self::$js_handler,
					self::$css_handler 
				))->render_css()->render_js();
				
				ob_start();
				if ( file_exists( Template::locate( $template ) ) ) {					
					include Template::locate( $template );
				} else {
					echo sprintf( esc_html__( '%s Template Not Found', 'ufaqsw' ), ucfirst( $template ) );
				}
				
				$content = ob_get_clean();
				
				$all_content .="<div class='ufaqsw_default_all_single_faq ufaqsw_default_single_".esc_attr($column)."' id='ufaqsw_single_faq_".esc_attr(get_the_ID())."'>".$content."</div>";
				
			}
			
		}
		wp_reset_query(); //reseting wp query
		
		ob_start();
		$template = 'all'; // Template for All with search box.
		if ( file_exists( Template::locate( $template ) ) ) {					
			include Template::locate( $template );
		} else {
			echo sprintf( esc_html__( '%s Template Not Found', 'ufaqsw' ), ucfirst( $template ) );
		}
		$content = ob_get_clean();

		return str_replace( '{{content}}', $all_content, $content );
		
	}
}
