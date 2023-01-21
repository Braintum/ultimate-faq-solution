<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Shortcode handler class
 */
class UFAQSW_shortcode_handler
{
	// class instance
	static $instance;


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
		add_shortcode('ufaqsw', array($this, 'render_shortcode'));
		add_shortcode('ufaqsw-all', array($this, 'render_all'));
	}
	
	/**
	 * Render shortcodes
	 *
	 * @param array $atts
	 * @return string
	 */
	public function render_shortcode($atts = array()){

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
		$faq_query = new WP_Query($faq_args);

		ob_start();
		
		if ( $faq_query->have_posts() ) {

			while ( $faq_query->have_posts() ) {
				$faq_query->the_post();
				
				$faqs = get_post_meta( get_the_ID(), 'ufaqsw_faq_item01' );

				$faqs = isset($faqs[0])?$faqs[0]:$faqs;
				
				if( $elements_order == 'DESC' ){
					$faqs = array_values( array_reverse( $faqs, true ) );
				}

				$designs = apply_filters('ufaqsw_simplify_configuration_variables', get_the_ID());

				$template = apply_filters('ufaqsw_template_filter', $designs['template']);
				
				$enqueue_resource = new UFAQSW_enqueue_resources();	
				$enqueue_resource->id = get_the_ID();				
				$enqueue_resource->configuration = $designs;
				$enqueue_resource->render_css($template);
				$enqueue_resource->render_js($template);
				
				if(file_exists(UFAQSW__PLUGIN_DIR.'frontend/templates/' . $template . '/template.php')){

					include UFAQSW__PLUGIN_DIR.'frontend/templates/' . $template . '/template.php';
				}else{
					echo esc_html($template.' Template Not Found');
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
	public function render_all($atts = array()){
		
		extract( shortcode_atts(
			array(
				'exclude' 			=> '', //Coma seperated number string: 88, 86
				'column' 			=> 1, // column parameter
				'title_hide'		=> 'no',
				'elements_order' 	=> 'ASC',
				'behaviour'			=> 'toggle'
			), $atts
		));
		
		
		//generating query
		$faq_args = array(
			'post_type' => 'ufaqsw',
			'posts_per_page'   => -1,
			'post__not_in' => explode(', ', $exclude),
		);
		//default template
		$template = 'default';
		$faq_query = new WP_Query($faq_args);		

		//loading resources		
		wp_enqueue_style('ufaqsw_fa_default_all_css');
		wp_enqueue_script('ufaqsw-grid-js');
		wp_enqueue_script('ufaqsw-quicksearch-front-js');
		wp_enqueue_script('ufaqsw-default-all-js');

		if ( $faq_query->have_posts() ) 
		{
			// Main container
			$all_content = '<div class="ufaqsw_default_all_faq_container">';
			if(get_option('ufaqsw_enable_search')=='on'){
				// Hearder area for search section
				$all_content .= '<div class="ufaqsw_default_all_faq_header">';
					// Quick search box container
					$all_content .= '<div class="ufaqsw_default_all_search">';
						//quick search box
						$all_content .= '<input type="text" class="ufaqsw_default_all_search_box" placeholder="'.(get_option('ufaqsw_live_search_text')!=''?esc_html(get_option('ufaqsw_live_search_text')):esc_html__('Live Search..','ufaqsw')).'" />'; // Search Box
						$all_content .='<span class="ufaqsw_search_loading">'.(get_option('ufaqsw_live_search_loading_text')!=''?esc_html(get_option('ufaqsw_live_search_loading_text')):esc_html__('Loading...','ufaqsw')).'</span>';
					$all_content .= '</div>'; // Closing box container
				$all_content .= '</div>'; //Closing Hearder area for search section
			}
			
			// Content Area
			$all_content .= '<div class="ufaqsw_default_all_faq_content">';
			
			
			while ( $faq_query->have_posts() ) {
				$faq_query->the_post();
				
				$faqs = apply_filters('ufaqsw_simplify_faqs', get_post_meta( get_the_ID(), 'ufaqsw_faq_item01' ));
				
				if( $elements_order == 'DESC' ){
					$faqs = array_values( array_reverse( $faqs, true ) );
				}

				$designs = apply_filters('ufaqsw_simplify_configuration_variables', get_the_ID());
				$designs['behaviour'] = $behaviour;
				
				$template = apply_filters('ufaqsw_template_filter', $designs['template']);
				
				$enqueue_resource = new UFAQSW_enqueue_resources();	
				
				$enqueue_resource->id = get_the_ID();
				
				$enqueue_resource->configuration = $designs;
				$enqueue_resource->render_css($template);
				$enqueue_resource->render_js($template);

				
				ob_start();
				if(file_exists(UFAQSW__PLUGIN_DIR.'/frontend/templates/'.$template.'/template.php')){					
					include UFAQSW__PLUGIN_DIR.'/frontend/templates/'.$template.'/template.php';
				}else{
					echo '<p>'.esc_html($template).' - Template Not Found</p>';
				}
				
				$content = ob_get_clean();
				
				$all_content .="<div class='ufaqsw_default_all_single_faq ufaqsw_default_single_".esc_attr($column)."' id='ufaqsw_single_faq_".esc_attr(get_the_ID())."'>".$content."</div>";
				
				
			}
			$all_content .="</div>"; // Closing Content
			$all_content .="<div class='ufaqsw_search_no_result'>"; // No result div
				$all_content .="<p>".(get_option('ufaqsw_search_result_not_found')!=''?esc_html(get_option('ufaqsw_search_result_not_found')):esc_html__('No Result Fount!', 'ufaqsw'))."</p>";
			$all_content .="</div>"; // Closing No Result			
			$all_content .="</div>"; // Closing Main Container
			
		}
		wp_reset_query(); //reseting wp query
		
		return $all_content;
		
	}
	
}

// Shortcode Handler Start function
function ufaqsw_shortcode_handler(){
	return UFAQSW_shortcode_handler::get_instance();
}
//Start Shortcode handler
ufaqsw_shortcode_handler();