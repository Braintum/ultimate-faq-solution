<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class UFAQSW_product_tab
{
	// class instance
	static $instance;

	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
	public function __construct() {
		if(get_option('ufaqsw_enable_woocommerce')=='on'){
			add_filter( 'woocommerce_product_tabs', array($this, 'woo_new_product_tab') );
			add_filter( 'woocommerce_product_data_tabs', array( $this, 'render_custom_product_tabs' ) , 99 , 1 );
			add_action( 'woocommerce_product_data_panels', array( $this, 'product_page_custom_tabs_panel' ) );
			add_action( 'woocommerce_process_product_meta', array($this, 'woocommerce_process_product_meta_fields_save') );
		}

	}
	public function woo_new_product_tab($tabs){
		
		global $post;
		$post_id = $post->ID;
		$is_enable = get_post_meta($post_id, '_ufaqsw_enable_faq_tab', true);
		$title = get_post_meta($post_id, '_ufaqsw_tab_label', true);
		
		if($is_enable=='yes'){
			$tabs['desc_tab'] = array(
				'title'     => esc_html($title!=''?$title:'FAQs'),
				'priority'  => 50,
				'callback'  => array($this, 'woo_new_product_tab_content')
			);
		}
		
		
		return $tabs;
	}
	
	public function woo_new_product_tab_content(){
		
		global $post;
		
		$post_id = $post->ID;
		$is_enable = get_post_meta($post_id, '_ufaqsw_enable_faq_tab', true);
		$title = get_post_meta($post_id, '_ufaqsw_tab_label', true);
		$data = get_post_meta($post_id, '_ufaqsw_tab_data', true);

		//New option FAQ group id
		if( $data == '' ){
			$data = get_post_meta($post_id, '_ufaqsw_tab_data_id', true);
		}

		$group_title = get_post_meta($post_id, '_ufaqsw_hide_group_title', true);
		if( $is_enable=='yes' && $data !== '' ){
			echo do_shortcode( '[ufaqsw id="'.$data.'" title_hide="'.$group_title.'"]' );
		}
				
	}
	
	public function render_custom_product_tabs($product_data_tabs){
		
		
		$product_data_tabs['ufaqsw-faq-tab'] = array(
			'label' => esc_html__( 'FAQ', 'ufaqsw' ),
			'target' => 'ufaqsw_faq_product_data',
		);
		return $product_data_tabs;
		
	}
	public function get_all_faqs(){
		
		$args = array(
		  'post_type'   => 'ufaqsw',
		  'posts_per_page' => -1
		);
		 
		$allfaqs = get_posts( $args );
		$faqs = array();
		$faqs[''] = 'None';
		foreach($allfaqs as $faq){
			$faqs[$faq->ID] = $faq->post_title;
		}
		return $faqs;
		
	}
	public function product_page_custom_tabs_panel(){
		global $woocommerce, $post;
		$faqs = $this->get_all_faqs();
		$faqdata = get_post_meta( $post->ID, '_ufaqsw_tab_data', true );
		?>
		<div id="ufaqsw_faq_product_data" class="panel woocommerce_options_panel">
			
			<?php woocommerce_wp_checkbox( array( 
				'id'            => '_ufaqsw_enable_faq_tab', 
				'wrapper_class' => 'show_if_simple', 
				'label'         => esc_html__( 'Enable FAQ Tab', 'ufaqsw' ),
				'description'   => esc_html__( 'Check this field if you want to enable faq tab for this product', 'ufaqsw' ),
				'default'       => '0',
				'desc_tip'      => true,
			) ); 
			?>
			
			<?php woocommerce_wp_text_input(array('id' => '_ufaqsw_tab_label', 'label' => esc_html__('FAQ Tab Label', 'ufaqsw'), 'placeholder' => __('FAQs', 'ufaqsw'), 'desc_tip' => true, 'description' => esc_html__('Enter the tab label. Default will be FAQs.', 'ufaqsw'), 'wrapper_class' => 'hide_if_variable')); ?>
			
			<div class="bt_ufaqsw_faq_id" style="background:#eee; padding: 3px 0px">
			<?php 
			woocommerce_wp_select(array('id' => '_ufaqsw_tab_data', 
						'options' => $faqs, 
						'label' => esc_html('Select A FAQ Group'),'desc_tip' => true, 'description' => esc_html__('Please select a FAQ Group from dropdown.', 'ufaqsw'), 'value' => $faqdata));
			?>

			<?php woocommerce_wp_text_input(array('id' => '_ufaqsw_tab_data_id', 'label' => esc_html__('Or Add FAQ Group ID', 'ufaqsw'), 'placeholder' => __('ex: 10', 'ufaqsw'), 'desc_tip' => true, 'description' => esc_html__('Or add a FAQ ID here. This will help for multilangual site built with WPML', 'ufaqsw'), 'wrapper_class' => 'hide_if_variable')); ?>
			</div>

			<?php woocommerce_wp_checkbox( array( 
				'id'            => '_ufaqsw_hide_group_title', 
				'wrapper_class' => 'show_if_simple', 
				'label'         => esc_html__( 'Hide FAQ Group Title', 'ufaqsw' ),
				'description'   => esc_html__( 'Check this field if you want to hide FAQ Group Title.', 'ufaqsw' ),
				'default'       => '0',
				'desc_tip'      => true,
			) ); 
			?>
			
		</div>
	<?php
	}
	
	public function woocommerce_process_product_meta_fields_save($post_id){
		$woo_data = isset( $_POST['_ufaqsw_tab_label'] ) ? sanitize_text_field($_POST['_ufaqsw_tab_label']) : sanitize_text_field('FAQs');
		update_post_meta( $post_id, '_ufaqsw_tab_label', $woo_data );
		update_post_meta( $post_id, '_ufaqsw_tab_data', sanitize_text_field($_POST['_ufaqsw_tab_data']) );
		update_post_meta( $post_id, '_ufaqsw_tab_data_id', sanitize_text_field($_POST['_ufaqsw_tab_data_id']) );
		$woo_checkbox = isset( $_POST['_ufaqsw_enable_faq_tab'] ) ? sanitize_text_field('yes') : sanitize_text_field('no');
		update_post_meta( $post_id, '_ufaqsw_enable_faq_tab', $woo_checkbox );
		$woo_hide_title = isset( $_POST['_ufaqsw_hide_group_title'] ) ? sanitize_text_field('yes') : sanitize_text_field('no');
		update_post_meta( $post_id, '_ufaqsw_hide_group_title', $woo_hide_title );
		
	}
	
}

function UFAQSW_product_tab(){
	return UFAQSW_product_tab::get_instance();
}

UFAQSW_product_tab();


