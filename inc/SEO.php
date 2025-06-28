<?php
/**
 * SEO class for generating FAQ schema.
 *
 * @package UltimateFaqSolution
 * @author  Mahedi
 * @license GPL-2.0-or-later
 * @link    https://example.com
 */

namespace Mahedi\UltimateFaqSolution;

/**
 * Class SEO
 *
 * Handles the generation of FAQ schema for SEO purposes.
 */
class SEO {

	/**
	 * Constructor to initialize the SEO class.
	 *
	 * Adds the action to generate FAQ schema in the head section.
	 */
	public function __construct() {
		add_action( 'wp_head', array( $this, 'generate_faq_schema' ) );
	}

	/**
	 * Generate the faq schema for the current page.
	 *
	 * @return void
	 */
	public function generate_faq_schema() {

		$faqs = $this->get_current_faqs();

		if ( empty( $faqs ) ) {
			return;
		}

		$faq_data = array(
			'@context'   => 'https://schema.org',
			'@type'      => 'FAQPage',
			'mainEntity' => array(),
		);

		foreach ( $faqs as $faq ) {
			$faq_data['mainEntity'][] = array(
				'@type'          => 'Question',
				'name'           => esc_html( wp_strip_all_tags( $faq['ufaqsw_faq_question'] ) ),
				'acceptedAnswer' => array(
					'@type' => 'Answer',
					'text'  => esc_html( wp_strip_all_tags( $faq['ufaqsw_faq_answer'] ) ),
				),
			);
		}

		echo '<!--Ultimate FAQ Solution Schema-->' . "\n";
		echo '<script type="application/ld+json">' . wp_json_encode( $faq_data, JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
		echo '<!--Ultimate FAQ Solution Schema-->' . "\n";
	}

	/**
	 * Get faq items from current page.
	 *
	 * @return array $faq_data
	 */
	private function get_current_faqs() {
		global $post;

		$faqs_data = array();

		if ( empty( $post ) ) {
			return $faqs_data;
		}

		// Check if post has the FAQ block.
		if ( has_block( 'ultimate-faq-solution/block', $post->post_content ) ) {
			$block = parse_blocks( $post->post_content );

			foreach ( $block as $key => $value ) {
				if ( 'ultimate-faq-solution/block' === $value['blockName'] ) {

					$group_id = $value['attrs']['group'];
					if ( 'all' === $group_id ) {

						$faq_groups = get_posts(
							array(
								'post_type'      => 'ufaqsw',
								'posts_per_page' => -1,
								'fields'         => 'ids',
								'post__not_in'   => isset( $value['attrs']['exclude'] ) && is_array( $value['attrs']['exclude'] ) ? $value['attrs']['exclude'] : array(),
							)
						);

						if ( ! empty( $faq_groups ) ) {
							foreach ( $faq_groups as $faq_group ) {
								$faqs = apply_filters( 'ufaqsw_simplify_faqs', get_post_meta( $faq_group, 'ufaqsw_faq_item01' ) );
								if ( ! empty( $faqs ) ) {
									$faqs_data = array_merge( $faqs_data, $faqs );
								}
							}
						}
					} else {
						$faqs = apply_filters( 'ufaqsw_simplify_faqs', get_post_meta( $group_id, 'ufaqsw_faq_item01' ) );

						if ( isset( $value['attrs']['elements_order'] ) && 'desc' === strtolower( $value['attrs']['elements_order'] ) ) {
							$faqs = array_values( array_reverse( $faqs, true ) );
						}

						if ( ! empty( $faqs ) ) {
							$faqs_data = array_merge( $faqs_data, $faqs );
						}
					}
				}
			}
		}

		// all faq groups.
		if ( has_shortcode( $post->post_content, 'ufaqsw-all' ) ) {

			// Extract shortcode parameters.
			$shortcode_pattern = get_shortcode_regex();

			$shortcode_atts = array();
			if ( preg_match( '/' . $shortcode_pattern . '/s', $post->post_content, $matches ) && in_array( 'ufaqsw', $matches, true ) ) {
				$shortcode_atts = shortcode_parse_atts( $matches[3] ); // Extract attributes.
			}

			$faq_groups = get_posts(
				array(
					'post_type'      => 'ufaqsw',
					'posts_per_page' => -1,
					'fields'         => 'ids',
					'post__not_in'   => isset( $shortcode_atts['exclude'] ) ? explode( ', ', $shortcode_atts['exclude'] ) : array(),
				)
			);

			if ( ! empty( $faq_groups ) ) {
				foreach ( $faq_groups as $faq_group ) {
					$faqs = apply_filters( 'ufaqsw_simplify_faqs', get_post_meta( $faq_group, 'ufaqsw_faq_item01' ) );

					if ( isset( $shortcode_atts['elements_order'] ) && 'DESC' === $shortcode_atts['elements_order'] ) {
						$faqs = array_values( array_reverse( $faqs, true ) );
					}

					if ( ! empty( $faqs ) ) {
						$faqs_data = array_merge( $faqs_data, $faqs );
					}
				}
			}
		}

		// Particular faq group.
		if ( has_shortcode( $post->post_content, 'ufaqsw' ) ) {

			// Extract shortcode parameters.
			$shortcode_pattern = get_shortcode_regex();

			$shortcode_atts = array();
			if ( preg_match( '/' . $shortcode_pattern . '/s', $post->post_content, $matches ) && in_array( 'ufaqsw', $matches, true ) ) {
				$shortcode_atts = shortcode_parse_atts( $matches[3] ); // Extract attributes.
			}

			if ( isset( $shortcode_atts['id'] ) && $shortcode_atts['id'] ) {

				$faqs = apply_filters( 'ufaqsw_simplify_faqs', get_post_meta( get_post( $shortcode_atts['id'] )->ID, 'ufaqsw_faq_item01' ) );

				if ( isset( $shortcode_atts['elements_order'] ) && 'DESC' === $shortcode_atts['elements_order'] ) {
					$faqs = array_values( array_reverse( $faqs, true ) );
				}

				if ( ! empty( $faqs ) ) {
					$faqs_data = array_merge( $faqs_data, $faqs );
				}
			}
		}

		if ( function_exists( 'is_product' ) && is_product() ) {

			$is_enable = get_post_meta( $post->ID, '_ufaqsw_enable_faq_tab', true );
			$title     = get_option( 'ufaqsw_global_faq_label' ) ?? esc_html__( 'FAQs', 'ufaqsw' );
			$data      = get_post_meta( $post->ID, '_ufaqsw_tab_data', true );

			if ( get_option( 'ufaqsw_enable_global_faq' ) === 'on' && get_option( 'ufaqsw_global_faq' ) !== '' ) {

				$faqs = apply_filters( 'ufaqsw_simplify_faqs', get_post_meta( get_post( get_option( 'ufaqsw_global_faq' ) )->ID, 'ufaqsw_faq_item01' ) );

				if ( ! empty( $faqs ) ) {
					$faqs_data = array_merge( $faqs_data, $faqs );
				}
			}
			if ( 'yes' === $is_enable && '' !== $data ) {

				$faqs = apply_filters( 'ufaqsw_simplify_faqs', get_post_meta( get_post( $data )->ID, 'ufaqsw_faq_item01' ) );

				if ( ! empty( $faqs ) ) {
					$faqs_data = array_merge( $faqs_data, $faqs );
				}
			}
		}

		return $faqs_data;
	}

}
