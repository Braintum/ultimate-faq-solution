<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Enqueue styles and scripts
 */
class UFAQSW_enqueue_resources
{
	private $template = '';
	private $configuration = array();
	private $id = 0;

	public function __construct( $id, $configuration, $template )
	{
		$this->template = $template;
		$this->id = $id;
		$this->configuration = $configuration;
	}
	
	public function render_css(){

		$handle = UFAQSW_PRFX . $this->template . '_style';			
		wp_enqueue_style( $handle, UFAQSW__PLUGIN_TEMPLATE_URL . $this->template . '/style.css', array(), UFAQSW_VERSION, 'all' );		
		$custom_css = ( get_option( 'ufaqsw_setting_custom_style' ) !='' ? get_option( 'ufaqsw_setting_custom_style' ) : '' );
        wp_add_inline_style( $handle, $custom_css );
		
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
				
				wp_add_inline_style( $handle, $custom_css );
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
				
				wp_add_inline_style( $handle, $custom_css );
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
				
				wp_add_inline_style( $handle, $custom_css );
			}
			
		}
	}
	
	public function render_js(){
		/*
		* Script for default Template
		*/
		extract($this->configuration);
		
		$handle = UFAQSW_PRFX.$this->template.'_script';
			
		wp_enqueue_script( $handle, UFAQSW__PLUGIN_TEMPLATE_URL.$this->template.'/script.js', array('jquery'), UFAQSW_VERSION, true ); 
		
		wp_localize_script($handle, 'ufaqsw_object_'.str_replace('-','_', $this->template),
			array(
				'ajax_url' => admin_url('admin-ajax.php'),
				'image_path' => '',
				'showall'	=> $showall,
				'behaviour'	=> $behaviour
			)
		);
		
		
	}
	
}