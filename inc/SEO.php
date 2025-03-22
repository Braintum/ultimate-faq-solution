<?php

namespace Mahedi\UltimateFaqSolution;

class SEO {

	public function __construct() {
		add_action( 'wp_head', [ $this, 'generate_faq_schema' ] );
	}

	/**
	 * Generate the faq schema for the current page.
	 *
	 * @return void
	 */
	public function generate_faq_schema() {

		$faqs = $this->get_current_faqs();

		if (empty($faqs)) {
			return;
		}
	
		$faq_data = [
			"@context" => "https://schema.org",
			"@type" => "FAQPage",
			"mainEntity" => []
		];
	
		foreach ( $faqs as $faq ) {
			$faq_data["mainEntity"][] = [
				"@type" => "Question",
				"name" => esc_html( strip_tags( $faq["ufaqsw_faq_question"] ) ),
				"acceptedAnswer" => [
					"@type" => "Answer",
					"text" => esc_html( strip_tags( $faq["ufaqsw_faq_answer"] ) )
				]
			];
		}
	
		echo '<!--Ultimate FAQ Solution Schema-->' . "\n";
		echo '<script type="application/ld+json">' . json_encode( $faq_data, JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
		echo '<!--Ultimate FAQ Solution Schema-->' . "\n";
	}

	/**
	 * Get faq items from current page.
	 *
	 * @return array $faq_data
	 */
	private function get_current_faqs() {
		global $post;

		$faqs_data = [];

		if ( is_page() ) {

			// all faq groups.
			if ( has_shortcode( $post->post_content, 'ufaqsw-all') ) {

				// Extract shortcode parameters
				$shortcode_pattern = get_shortcode_regex();

				$shortcode_atts = [];
				if (preg_match( '/' . $shortcode_pattern . '/s', $post->post_content, $matches ) && in_array( 'ufaqsw', $matches ) ) {
					$shortcode_atts = shortcode_parse_atts( $matches[3] ); // Extract attributes
				}

				$faq_groups = get_posts([
					'post_type' => 'ufaqsw',
					'posts_per_page'   => -1,
					'fields' => 'ids',
					'post__not_in' => isset( $shortcode_atts['exclude'] ) ? explode( ', ', $shortcode_atts['exclude'] ) : [],
				]);

				if ( ! empty( $faq_groups ) ) {
					foreach ( $faq_groups as $faq_group ) {
						$faqs = apply_filters('ufaqsw_simplify_faqs', get_post_meta( $faq_group, 'ufaqsw_faq_item01' ));

						if( isset( $shortcode_atts['elements_order'] ) && 'DESC' === $shortcode_atts['elements_order'] ){
							$faqs = array_values( array_reverse( $faqs, true ) );
						}

						if ( ! empty( $faqs ) ) {
							$faqs_data = array_merge( $faqs_data, $faqs );
						}
					}
				}
			}

			// Particular faq group.
			if ( has_shortcode( $post->post_content, 'ufaqsw') ) {

				// Extract shortcode parameters
				$shortcode_pattern = get_shortcode_regex();

				$shortcode_atts = [];
				if (preg_match( '/' . $shortcode_pattern . '/s', $post->post_content, $matches ) && in_array( 'ufaqsw', $matches ) ) {
					$shortcode_atts = shortcode_parse_atts( $matches[3] ); // Extract attributes
				}

				if ( isset( $shortcode_atts['id'] ) && $shortcode_atts['id'] ) {

					$faqs = apply_filters('ufaqsw_simplify_faqs', get_post_meta( get_post( $shortcode_atts['id'] )->ID, 'ufaqsw_faq_item01' ));

					if( isset( $shortcode_atts['elements_order'] ) && 'DESC' === $shortcode_atts['elements_order'] ){
						$faqs = array_values( array_reverse( $faqs, true ) );
					}

					if ( ! empty( $faqs ) ) {
						$faqs_data = array_merge( $faqs_data, $faqs );
					}
					
				}

			}

		}

		if ( function_exists('is_product') && is_product() ) {

			$is_enable = get_post_meta( $post->ID, '_ufaqsw_enable_faq_tab', true );
			$title = get_post_meta( $post->ID, '_ufaqsw_tab_label', true );
			$data = get_post_meta( $post->ID, '_ufaqsw_tab_data', true );

			//New option FAQ group id
			if( $data == '' ){
				$data = get_post_meta( $post->ID, '_ufaqsw_tab_data_id', true );
			}

			if ( get_option( 'ufaqsw_enable_global_faq' ) == 'on' && get_option( 'ufaqsw_global_faq' ) !== '' ) {

				$faqs = apply_filters('ufaqsw_simplify_faqs', get_post_meta( get_post( get_option( 'ufaqsw_global_faq' ) )->ID, 'ufaqsw_faq_item01' ));

				if ( ! empty( $faqs ) ) {
					$faqs_data = array_merge( $faqs_data, $faqs );
				}
			}
			if( 'yes' === $is_enable && '' !== $data ){

				$faqs = apply_filters('ufaqsw_simplify_faqs', get_post_meta( get_post( $data )->ID, 'ufaqsw_faq_item01' ));

				if ( ! empty( $faqs ) ) {
					$faqs_data = array_merge( $faqs_data, $faqs );
				}
			}
		}

		return $faqs_data;
	}

}
