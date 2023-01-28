<?php

namespace Mahedi\UltimateFaqSolution;

class Custom_Resources {
	
	private $template = '';
	private $configuration = array();
	private $id = 0;
	private $js_handler = '';
	private $css_handler = '';

	public function __construct( $id, $configuration, $template, $js_handler, $css_handler ) {
		$this->template = $template;
		$this->id = $id;
		$this->configuration = $configuration;
		$this->js_handler = $js_handler;
		$this->css_handler = $css_handler;
		return $this;
	}
	
	public function render_css() {

		$custom_css = ( get_option( 'ufaqsw_setting_custom_style' ) !='' ? get_option( 'ufaqsw_setting_custom_style' ) : '' );
        wp_add_inline_style( $this->css_handler, $custom_css );
		
		if ( ! empty( $this->configuration ) ) {
			
			/*
			* Custom styles for default Template
			*/
			if( 'default' === $this->template ){
				extract( $this->configuration );
				/*
				* Need commenting later on
				*/
				$custom_css = "";
				// Title color - from backend.
				if(isset($title_color) && $title_color!=''){
					$custom_css .= ".ufaqsw_faq_title_{$this->id}{ color: {$title_color} !important;}";
				}
				if(isset($title_font_size) && $title_font_size!=''){
					$custom_css .= ".ufaqsw_faq_title_{$this->id}{ font-size: {$title_font_size} !important;}";
				}
				if(isset($question_color) && $question_color!=''){
					$custom_css .= ".ufaqsw-title-name-default_{$this->id}{ color: {$question_color} !important;}";
				}
				if(isset($question_font_size) && $question_font_size!=''){
					$custom_css .= ".ufaqsw-title-name-default_{$this->id}{ font-size: {$question_font_size} !important;}";
				}
				if(isset($question_background_color) && $question_background_color!=''){
					$custom_css .= ".ufaqsw-toggle-title-area-default_{$this->id}{ background-color: {$question_background_color} !important;}";
				}
				if(isset($answer_background_color) && $answer_background_color!=''){
					$custom_css .= ".ufaqsw-toggle-inner-default_{$this->id}{ background-color: {$answer_background_color} !important;}";
				}
				if(isset($answer_color) && $answer_color!=''){
					$custom_css .= ".ufaqsw-toggle-inner-default_{$this->id} *{ color: {$answer_color} !important;}";
				}
				if(isset($answer_font_size) && $answer_font_size!=''){
					$custom_css .= ".ufaqsw-toggle-inner-default_{$this->id} *{ font-size: {$answer_font_size} !important;}";
				}
				if(isset($answer_font_size) && $answer_font_size!=''){
					$custom_css .= ".ufaqsw-toggle-inner-default_{$this->id} *{ font-size: {$answer_font_size} !important;}";
				}
				
				wp_add_inline_style( $this->css_handler, $custom_css );
			}
			if($this->template == 'style-1'){
				/*
				* Need commenting later on
				*/
				extract($this->configuration);
				$custom_css = "";
				// Title color - from backend.
				if(isset($title_color) && $title_color!=''){
					$custom_css .= ".ufaqsw_faq_title_{$this->id}{ color: {$title_color} !important;}";
				}
				if(isset($title_font_size) && $title_font_size!=''){
					$custom_css .= ".ufaqsw_faq_title_{$this->id}{ font-size: {$title_font_size} !important;}";
				}
				if(isset($question_color) && $question_color!=''){
					$custom_css .= ".ufaqsw-title-name-default_{$this->id}{ color: {$question_color} !important;}";
				}
				if(isset($question_font_size) && $question_font_size!=''){
					$custom_css .= ".ufaqsw-title-name-default_{$this->id}{ font-size: {$question_font_size} !important;}";
				}
				if(isset($question_background_color) && $question_background_color!=''){
					$custom_css .= ".ufaqsw-toggle-title-area-default_{$this->id}{ background-color: {$question_background_color} !important;}";
				}
				if(isset($answer_background_color) && $answer_background_color!=''){
					$custom_css .= ".ufaqsw-toggle-inner-default_{$this->id}{ background-color: {$answer_background_color} !important;}";
				}
				
				if(isset($answer_color) && $answer_color!=''){
					$custom_css .= ".ufaqsw-toggle-inner-default_{$this->id} *{ color: {$answer_color} !important;}";
				}
				if(isset($answer_font_size) && $answer_font_size!=''){
					$custom_css .= ".ufaqsw-toggle-inner-default_{$this->id} *{ font-size: {$answer_font_size} !important;}";
				}
				if(isset($answer_font_size) && $answer_font_size!=''){
					$custom_css .= ".ufaqsw-toggle-inner-default_{$this->id} *{ font-size: {$answer_font_size} !important;}";
				}
				
				wp_add_inline_style( $this->css_handler, $custom_css );
			}
			if($this->template == 'style-2'){
				/*
				* Need commenting later on
				*/
				extract($this->configuration);
				$custom_css = "";
				// Title color - from backend.
				if(isset($title_color) && $title_color!=''){
					$custom_css .= ".ufaqsw_faq_title_{$this->id}{ color: {$title_color} !important;}";
				}
				if(isset($title_font_size) && $title_font_size!=''){
					$custom_css .= ".ufaqsw_faq_title_{$this->id}{ font-size: {$title_font_size} !important;}";
				}
				if(isset($question_color) && $question_color!=''){
					$custom_css .= ".ufaqsw-title-name-default_{$this->id}{ color: {$question_color} !important;}";
				}
				if(isset($question_font_size) && $question_font_size!=''){
					$custom_css .= ".ufaqsw-title-name-default_{$this->id}{ font-size: {$question_font_size} !important;}";
				}
				if(isset($question_background_color) && $question_background_color!=''){
					$custom_css .= ".ufaqsw-toggle-title-area-default_{$this->id}{ background-color: {$question_background_color} !important;}";
				}
				if(isset($answer_background_color) && $answer_background_color!=''){
					$custom_css .= ".ufaqsw-toggle-inner-default_{$this->id}{ background-color: {$answer_background_color} !important;}";
				}
				
				if(isset($answer_color) && $answer_color!=''){
					$custom_css .= ".ufaqsw-toggle-inner-default_{$this->id} *{ color: {$answer_color} !important;}";
				}
				if(isset($answer_font_size) && $answer_font_size!=''){
					$custom_css .= ".ufaqsw-toggle-inner-default_{$this->id} *{ font-size: {$answer_font_size} !important;}";
				}
				if(isset($answer_font_size) && $answer_font_size!=''){
					$custom_css .= ".ufaqsw-toggle-inner-default_{$this->id} *{ font-size: {$answer_font_size} !important;}";
				}
				
				wp_add_inline_style( $this->css_handler, $custom_css );
			}
			
		}
		return $this;
	}
	
	public function render_js(){
		/*
		* Script for default Template
		*/
		extract($this->configuration);

		wp_localize_script( $this->js_handler, 'ufaqsw_object_'.str_replace('-','_', $this->template ),
			array(
				'ajax_url' => admin_url('admin-ajax.php'),
				'image_path' => '',
				'showall'	=> $showall,
				'behaviour'	=> $behaviour
			)
		);
		return $this;
	}
}
