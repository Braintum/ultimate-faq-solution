<?php
/**
 * Class Product Tab for Ultimate FAQ Solution
 *
 * This file contains the UFAQSW_product_tab class, which integrates FAQ tabs into WooCommerce products.
 *
 * @package UltimateFAQSolution
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class UFAQSW_Product_Tab
 *
 * This class handles the integration of FAQ tabs into WooCommerce products.
 * It adds a new tab to the product page and allows customization of the tab's content.
 *
 * @package UltimateFAQSolution
 */
class UFAQSW_Product_Tab {

	/**
	 * Instance of UFAQSW_Product_Tab
	 *
	 * @var UFAQSW_Product_Tab
	 */
	private static $instance;

	/**
	 * Get the singleton instance of the UFAQSW_Product_Tab class.
	 *
	 * @return UFAQSW_Product_Tab The instance of the UFAQSW_Product_Tab class.
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
	/**
	 * Constructor for the UFAQSW_Product_Tab class.
	 *
	 * Initializes the class and sets up WooCommerce hooks if the related option is enabled.
	 */
	public function __construct() {
		if ( get_option( 'ufaqsw_enable_woocommerce' ) === 'on' ) {
			add_filter( 'woocommerce_product_tabs', array( $this, 'woo_new_product_tab' ) );
			add_filter( 'woocommerce_product_data_tabs', array( $this, 'render_custom_product_tabs' ), 99, 1 );
			add_action( 'woocommerce_product_data_panels', array( $this, 'product_page_custom_tabs_panel' ) );
			add_action( 'woocommerce_process_product_meta', array( $this, 'woocommerce_process_product_meta_fields_save' ) );
		}

	}

	/**
	 * Adds a new FAQ tab to WooCommerce product tabs.
	 *
	 * @param array $tabs Existing WooCommerce product tabs.
	 * @return array Modified WooCommerce product tabs with the FAQ tab added.
	 */
	public function woo_new_product_tab( $tabs ) {

		global $post;
		$post_id   = $post->ID;
		$is_enable = get_post_meta( $post_id, '_ufaqsw_enable_faq_tab', true );
		$title     = get_post_meta( $post_id, '_ufaqsw_tab_label', true );

		// Global option.
		if ( get_option( 'ufaqsw_enable_global_faq' ) === 'on' ) {
			$tabs['desc_tab'] = array(
				'title'    => get_option( 'ufaqsw_global_faq_label' ),
				'priority' => 50,
				'callback' => array( $this, 'woo_new_product_tab_content' ),
			);
		}

		// Product specific.
		if ( 'yes' === $is_enable ) {
			$tabs['desc_tab'] = array(
				'title'    => esc_html( '' !== $title ? $title : __( 'FAQs', 'ufaqsw' ) ),
				'priority' => 50,
				'callback' => array( $this, 'woo_new_product_tab_content' ),
			);
		}

		return $tabs;
	}

	/**
	 * Outputs the content for the WooCommerce FAQ product tab.
	 *
	 * Retrieves and displays the FAQ content for the product based on the settings and metadata.
	 */
	public function woo_new_product_tab_content() {

		global $post;

		$post_id   = $post->ID;
		$is_enable = get_post_meta( $post_id, '_ufaqsw_enable_faq_tab', true );
		$title     = get_post_meta( $post_id, '_ufaqsw_tab_label', true );
		$data      = get_post_meta( $post_id, '_ufaqsw_tab_data', true );

		// New option FAQ group id.
		if ( '' === $data ) {
			$data = get_post_meta( $post_id, '_ufaqsw_tab_data_id', true );
		}
		$group_title = get_post_meta( $post_id, '_ufaqsw_hide_group_title', true );

		if ( get_option( 'ufaqsw_enable_global_faq' ) === 'on' && get_option( 'ufaqsw_global_faq' ) !== '' ) {

			$shortcode = '[ufaqsw id="' . get_option( 'ufaqsw_global_faq' ) . '"]';

		}
		if ( 'yes' === $is_enable && '' !== $data ) {

			$shortcode = '[ufaqsw id="' . $data . '" title_hide="' . $group_title . '"]';

		}

		echo do_shortcode( $shortcode );
	}

	/**
	 * Adds a custom FAQ tab to the WooCommerce product data tabs.
	 *
	 * @param array $product_data_tabs Existing WooCommerce product data tabs.
	 * @return array Modified WooCommerce product data tabs with the FAQ tab added.
	 */
	public function render_custom_product_tabs( $product_data_tabs ) {

		$product_data_tabs['ufaqsw-faq-tab'] = array(
			'label'  => esc_html__( 'FAQ', 'ufaqsw' ),
			'target' => 'ufaqsw_faq_product_data',
		);
		return $product_data_tabs;

	}

	/**
	 * Retrieves all FAQs as an associative array.
	 *
	 * @return array An array of FAQs with IDs as keys and titles as values.
	 */
	public function get_all_faqs() {

		$args = array(
			'post_type'      => 'ufaqsw',
			'posts_per_page' => -1,
		);

		$allfaqs  = get_posts( $args );
		$faqs     = array();
		$faqs[''] = 'None';
		foreach ( $allfaqs as $faq ) {
			$faqs[ $faq->ID ] = $faq->post_title;
		}
		return $faqs;

	}

	/**
	 * Outputs the custom FAQ tab panel in the WooCommerce product edit screen.
	 *
	 * Displays fields for enabling the FAQ tab, setting the tab label, selecting a FAQ group,
	 * and other related options in the WooCommerce product data section.
	 */
	public function product_page_custom_tabs_panel() {
		global $woocommerce, $post;
		$faqs    = $this->get_all_faqs();
		$faqdata = get_post_meta( $post->ID, '_ufaqsw_tab_data', true );
		?>
		<div id="ufaqsw_faq_product_data" class="panel woocommerce_options_panel">

			<?php
			woocommerce_wp_checkbox(
				array(
					'id'            => '_ufaqsw_enable_faq_tab',
					'wrapper_class' => 'ufaqsw_enable_faq',
					'label'         => esc_html__( 'Enable FAQ Tab', 'ufaqsw' ),
					'description'   => esc_html__( 'Check this field if you want to enable faq tab for this product', 'ufaqsw' ),
					'default'       => '0',
					'desc_tip'      => true,
				)
			);
			?>

			<?php
			woocommerce_wp_text_input(
				array(
					'id'          => '_ufaqsw_tab_label',
					'label'       => esc_html__( 'FAQ Tab Label', 'ufaqsw' ),
					'placeholder' => __( 'FAQs', 'ufaqsw' ),
					'desc_tip'    => true,
					'description' => esc_html__(
						'Enter the tab label. Default will be FAQs.',
						'ufaqsw'
					),
				)
			);
			?>

			<div class="bt_ufaqsw_faq_id" style="background:#eee; padding: 3px 0px">
			<?php
			woocommerce_wp_select(
				array(
					'id'          => '_ufaqsw_tab_data',
					'options'     => $faqs,
					'label'       => esc_html( 'Select A FAQ Group' ),
					'desc_tip'    => true,
					'description' => esc_html__(
						'Please select a FAQ Group from dropdown.',
						'ufaqsw'
					),
					'value'       => $faqdata,
				)
			);
			?>

			<?php
			woocommerce_wp_text_input(
				array(
					'id'          => '_ufaqsw_tab_data_id',
					'label'       => esc_html__( 'Or Add FAQ Group ID', 'ufaqsw' ),
					'placeholder' => __( 'ex: 10', 'ufaqsw' ),
					'desc_tip'    => true,
					'description' => esc_html__(
						'Or add a FAQ ID here. This will help for multilangual site built with WPML',
						'ufaqsw'
					),
				)
			);
			?>
			</div>

			<?php
			woocommerce_wp_checkbox(
				array(
					'id'            => '_ufaqsw_hide_group_title',
					'wrapper_class' => 'ufaqsw_hide_group_title',
					'label'         => esc_html__( 'Hide FAQ Group Title', 'ufaqsw' ),
					'description'   => esc_html__( 'Check this field if you want to hide FAQ Group Title.', 'ufaqsw' ),
					'default'       => '0',
					'desc_tip'      => true,
				)
			);
			?>

		</div>
		<?php
	}

	/**
	 * Saves custom meta fields for WooCommerce products.
	 *
	 * Handles saving of FAQ-related metadata when a product is updated in the WooCommerce admin.
	 *
	 * @param int $post_id The ID of the product being saved.
	 */
	public function woocommerce_process_product_meta_fields_save( $post_id ) {
		$woo_data = isset( $_POST['_ufaqsw_tab_label'] ) ? sanitize_text_field( $_POST['_ufaqsw_tab_label'] ) : sanitize_text_field( 'FAQs' ); // phpcs:ignore
		update_post_meta( $post_id, '_ufaqsw_tab_label', $woo_data );
		update_post_meta( $post_id, '_ufaqsw_tab_data', sanitize_text_field( $_POST['_ufaqsw_tab_data'] ) ); // phpcs:ignore
		update_post_meta( $post_id, '_ufaqsw_tab_data_id', sanitize_text_field( $_POST['_ufaqsw_tab_data_id'] ) ); // phpcs:ignore
		$woo_checkbox = isset( $_POST['_ufaqsw_enable_faq_tab'] ) ? sanitize_text_field( 'yes' ) : sanitize_text_field( 'no' ); // phpcs:ignore
		update_post_meta( $post_id, '_ufaqsw_enable_faq_tab', $woo_checkbox );
		$woo_hide_title = isset( $_POST['_ufaqsw_hide_group_title'] ) ? sanitize_text_field( 'yes' ) : sanitize_text_field( 'no' ); // phpcs:ignore
		update_post_meta( $post_id, '_ufaqsw_hide_group_title', $woo_hide_title );

	}

}

/**
 * Initialize the UFAQSW_Product_Tab class instance.
 *
 * @return UFAQSW_Product_Tab The instance of the UFAQSW_Product_Tab class.
 */
function u_f_a_q_s_w_product_tab() {
	return UFAQSW_Product_Tab::get_instance();
}

u_f_a_q_s_w_product_tab();


