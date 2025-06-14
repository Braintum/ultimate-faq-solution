<?php
/**
 * Custom Resources Class
 *
 * This file contains the Custom_Resources class, which is responsible for rendering
 * custom CSS and JavaScript for the Ultimate FAQ Solution plugin.
 *
 * @package UltimateFaqSolution
 * @author  Mahedi
 * @license GPL-2.0-or-later
 * @link    https://example.com
 */

namespace Mahedi\UltimateFaqSolution;

/**
 * Class Custom_Resources
 *
 * Handles rendering of custom CSS and JavaScript for the Ultimate FAQ Solution plugin.
 *
 * @package UltimateFaqSolution
 */
class Custom_Resources {

	/**
	 * Template name for rendering custom styles.
	 *
	 * @var string
	 */
	private $template = '';
	/**
	 * Configuration array for custom styles and scripts.
	 *
	 * @var array
	 */
	private $configuration = array();
	/**
	 * Unique identifier for the resource.
	 *
	 * @var int
	 */
	private $id = 0;
	/**
	 * JavaScript handler for inline scripts.
	 *
	 * @var string
	 */
	private $js_handler = '';
	/**
	 * CSS handler for inline styles.
	 *
	 * @var string
	 */
	private $css_handler = '';

	/**
	 * Constructor for the Custom_Resources class.
	 *
	 * @param int    $id            Unique identifier for the resource.
	 * @param array  $configuration Configuration array for custom styles and scripts.
	 * @param string $template      Template name for rendering custom styles.
	 * @param string $js_handler    JavaScript handler for inline scripts.
	 * @param string $css_handler   CSS handler for inline styles.
	 */
	public function __construct( $id, $configuration, $template, $js_handler, $css_handler ) {
		$this->template      = $template;
		$this->id            = $id;
		$this->configuration = $configuration;
		$this->js_handler    = $js_handler;
		$this->css_handler   = $css_handler;
		return $this;
	}

	/**
	 * Renders custom CSS for the FAQ solution.
	 *
	 * This function generates and adds inline CSS based on the configuration
	 * and template provided for the FAQ solution.
	 *
	 * @return $this
	 */
	public function render_css() {

		$custom_css = ( get_option( 'ufaqsw_setting_custom_style' ) !== '' ? get_option( 'ufaqsw_setting_custom_style' ) : '' );
		wp_add_inline_style( $this->css_handler, $custom_css );

		if ( ! empty( $this->configuration ) ) {

			if ( 'default' === $this->template ) {
				extract( $this->configuration ); // phpcs:ignore

				/*
				* Need commenting later on
				*/
				$custom_css = '';
				// Title color - from backend.
				if ( isset( $title_color ) && '' !== $title_color ) {
					$custom_css .= ".ufaqsw_faq_title_{$this->id}{ color: {$title_color} !important;}";
				}
				if ( isset( $title_font_size ) && '' !== $title_font_size ) {
					$custom_css .= ".ufaqsw_faq_title_{$this->id}{ font-size: {$title_font_size} !important;}";
				}

				if ( isset( $question_bold ) && $question_bold ) {
					$custom_css .= ".ufaqsw-title-name-default_{$this->id}{ font-weight: bold !important;}";
				}

				if ( isset( $question_color ) && '' !== $question_color ) {
					$custom_css .= ".ufaqsw-title-name-default_{$this->id}{ color: {$question_color} !important;}";
				}
				if ( isset( $question_font_size ) && '' !== $question_font_size ) {
					$custom_css .= ".ufaqsw-title-name-default_{$this->id}{ font-size: {$question_font_size} !important;}";
				}
				if ( isset( $question_background_color ) && '' !== $question_background_color ) {
					$custom_css .= ".ufaqsw-toggle-title-area-default_{$this->id}{ background-color: {$question_background_color} !important;}";
				}
				if ( isset( $answer_background_color ) && '' !== $answer_background_color ) {
					$custom_css .= ".ufaqsw-toggle-inner-default_{$this->id}{ background-color: {$answer_background_color} !important;}";
				}
				if ( isset( $answer_color ) && '' !== $answer_color ) {
					$custom_css .= ".ufaqsw-toggle-inner-default_{$this->id} *{ color: {$answer_color} !important;}";
				}
				if ( isset( $answer_font_size ) && '' !== $answer_font_size ) {
					$custom_css .= ".ufaqsw-toggle-inner-default_{$this->id} *{ font-size: {$answer_font_size} !important;}";
				}
				if ( isset( $answer_font_size ) && '' !== $answer_font_size ) {
					$custom_css .= ".ufaqsw-toggle-inner-default_{$this->id} *{ font-size: {$answer_font_size} !important;}";
				}

				wp_add_inline_style( $this->css_handler, $custom_css );
			}
			if ( 'style-1' === $this->template ) {
				/*
				* Need commenting later on
				*/
				extract( $this->configuration ); // phpcs:ignore
				$custom_css = '';
				// Title color - from backend.
				if ( isset( $title_color ) && '' !== $title_color ) {
					$custom_css .= ".ufaqsw_faq_title_{$this->id}{ color: {$title_color} !important;}";
				}
				if ( isset( $title_font_size ) && '' !== $title_font_size ) {
					$custom_css .= ".ufaqsw_faq_title_{$this->id}{ font-size: {$title_font_size} !important;}";
				}

				if ( isset( $question_bold ) && $question_bold ) {
					$custom_css .= ".ufaqsw-title-name-default_{$this->id}{ font-weight: bold !important;}";
				}

				if ( isset( $question_color ) && '' !== $question_color ) {
					$custom_css .= ".ufaqsw-title-name-default_{$this->id}{ color: {$question_color} !important;}";
				}
				if ( isset( $question_font_size ) && '' !== $question_font_size ) {
					$custom_css .= ".ufaqsw-title-name-default_{$this->id}{ font-size: {$question_font_size} !important;}";
				}
				if ( isset( $question_background_color ) && '' !== $question_background_color ) {
					$custom_css .= ".ufaqsw-toggle-title-area-default_{$this->id}{ background-color: {$question_background_color} !important;}";
				}
				if ( isset( $answer_background_color ) && '' !== $answer_background_color ) {
					$custom_css .= ".ufaqsw-toggle-inner-default_{$this->id}{ background-color: {$answer_background_color} !important;}";
				}

				if ( isset( $answer_color ) && '' !== $answer_color ) {
					$custom_css .= ".ufaqsw-toggle-inner-default_{$this->id} *{ color: {$answer_color} !important;}";
				}
				if ( isset( $answer_font_size ) && '' !== $answer_font_size ) {
					$custom_css .= ".ufaqsw-toggle-inner-default_{$this->id} *{ font-size: {$answer_font_size} !important;}";
				}
				if ( isset( $answer_font_size ) && '' !== $answer_font_size ) {
					$custom_css .= ".ufaqsw-toggle-inner-default_{$this->id} *{ font-size: {$answer_font_size} !important;}";
				}

				wp_add_inline_style( $this->css_handler, $custom_css );
			}
			if ( 'style-2' === $this->template ) {
				/*
				* Need commenting later on
				*/
				extract( $this->configuration ); // phpcs:ignore
				$custom_css = '';
				// Title color - from backend.
				if ( isset( $title_color ) && '' !== $title_color ) {
					$custom_css .= ".ufaqsw_faq_title_{$this->id}{ color: {$title_color} !important;}";
				}

				if ( isset( $title_font_size ) && '' !== $title_font_size ) {
					$custom_css .= ".ufaqsw_faq_title_{$this->id}{ font-size: {$title_font_size} !important;}";
				}

				if ( isset( $question_bold ) && $question_bold ) {
					$custom_css .= ".ufaqsw-title-name-default_{$this->id}{ font-weight: bold !important;}";
				}

				if ( isset( $question_color ) && '' !== $question_color ) {
					$custom_css .= ".ufaqsw-title-name-default_{$this->id}{ color: {$question_color} !important;}";
				}
				if ( isset( $question_font_size ) && '' !== $question_font_size ) {
					$custom_css .= ".ufaqsw-title-name-default_{$this->id}{ font-size: {$question_font_size} !important;}";
				}
				if ( isset( $question_background_color ) && '' !== $question_background_color ) {
					$custom_css .= ".ufaqsw-toggle-title-area-default_{$this->id}{ background-color: {$question_background_color} !important;}";
				}
				if ( isset( $answer_background_color ) && '' !== $answer_background_color ) {
					$custom_css .= ".ufaqsw-toggle-inner-default_{$this->id}{ background-color: {$answer_background_color} !important;}";
				}

				if ( isset( $answer_color ) && '' !== $answer_color ) {
					$custom_css .= ".ufaqsw-toggle-inner-default_{$this->id} *{ color: {$answer_color} !important;}";
				}
				if ( isset( $answer_font_size ) && '' !== $answer_font_size ) {
					$custom_css .= ".ufaqsw-toggle-inner-default_{$this->id} *{ font-size: {$answer_font_size} !important;}";
				}
				if ( isset( $answer_font_size ) && '' !== $answer_font_size ) {
					$custom_css .= ".ufaqsw-toggle-inner-default_{$this->id} *{ font-size: {$answer_font_size} !important;}";
				}

				if ( isset( $border_color ) && '' !== $border_color ) {
					$custom_css .= ".ufaqsw-toggle-title-area-default_{$this->id}{ border-color: {$border_color} !important;}";
					$custom_css .= ".ufaqsw-toggle-inner-default_{$this->id}{ border-color: {$border_color} !important;}";
				}

				wp_add_inline_style( $this->css_handler, $custom_css );
			}
		}
		return $this;
	}

	/**
	 * Renders custom JavaScript for the FAQ solution.
	 *
	 * This function generates and localizes JavaScript variables based on the configuration
	 * and template provided for the FAQ solution.
	 *
	 * @return $this
	 */
	public function render_js() {
		/*
		* Script for default Template
		*/
		extract( $this->configuration ); // phpcs:ignore

		wp_localize_script(
			$this->js_handler,
			'ufaqsw_object_' . str_replace( '-', '_', $this->template ),
			array(
				'ajax_url'   => admin_url( 'admin-ajax.php' ),
				'image_path' => '',
				'showall'    => $showall,
				'behaviour'  => $behaviour,
			)
		);
		return $this;
	}
}
