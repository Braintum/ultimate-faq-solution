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

		if ( is_singular( 'ufaqsw' ) ) {

			$faqs = apply_filters( 'ufaqsw_simplify_faqs', get_post_meta( $post->ID, 'ufaqsw_faq_item01', false ) );

			if ( ! empty( $faqs ) ) {
				$faqs_data = array_merge( $faqs_data, $faqs );
			}
		}

		// Check if post has the FAQ block.
		if ( has_block( 'ultimate-faq-solution/block', $post->post_content ) ) {
			$blocks     = parse_blocks( $post->post_content );
			$faq_blocks = $this->get_blocks_by_name( $blocks, 'ultimate-faq-solution/block' );

			foreach ( $faq_blocks as $value ) {

				$group_id = isset( $value['attrs']['group'] ) ? $value['attrs']['group'] : '';
				if ( '' === $group_id ) {
					continue;
				}

				if ( 'all' === $group_id ) {

					$faq_groups = get_posts(
						array(
							'post_type'      => 'ufaqsw',
							'posts_per_page' => -1,
							'fields'         => 'ids',
							'post__not_in'   => isset( $value['attrs']['exclude'] ) && is_array( $value['attrs']['exclude'] ) ? $value['attrs']['exclude'] : array(),
							'orderby'        => 'menu_order',
							'order'          => 'ASC',
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

		$matched_shortcodes = $this->get_matched_shortcodes( $post->post_content, array( 'ufaqsw-all', 'ufaqsw' ) );

		foreach ( $matched_shortcodes as $shortcode ) {
			$shortcode_tag  = $shortcode['tag'];
			$shortcode_atts = $shortcode['atts'];

			// all faq groups.
			if ( 'ufaqsw-all' === $shortcode_tag ) {

				$exclude_ids = array();
				if ( isset( $shortcode_atts['exclude'] ) ) {
					$exclude_ids = array_filter( array_map( 'absint', array_map( 'trim', explode( ',', $shortcode_atts['exclude'] ) ) ) );
				}

				$faq_groups = get_posts(
					array(
						'post_type'      => 'ufaqsw',
						'posts_per_page' => -1,
						'fields'         => 'ids',
						'post__not_in'   => $exclude_ids,
						'orderby'        => 'menu_order',
						'order'          => 'ASC',
					)
				);

				if ( ! empty( $faq_groups ) ) {
					foreach ( $faq_groups as $faq_group ) {
						$faqs = apply_filters( 'ufaqsw_simplify_faqs', get_post_meta( $faq_group, 'ufaqsw_faq_item01' ) );

						if ( isset( $shortcode_atts['elements_order'] ) && 'DESC' === strtoupper( $shortcode_atts['elements_order'] ) ) {
							$faqs = array_values( array_reverse( $faqs, true ) );
						}

						if ( ! empty( $faqs ) ) {
							$faqs_data = array_merge( $faqs_data, $faqs );
						}
					}
				}
			}

			// Particular faq group.
			if ( 'ufaqsw' === $shortcode_tag && isset( $shortcode_atts['id'] ) && $shortcode_atts['id'] ) {

				$group_post = get_post( $shortcode_atts['id'] );
				if ( ! $group_post ) {
					continue;
				}

				$faqs = apply_filters( 'ufaqsw_simplify_faqs', get_post_meta( $group_post->ID, 'ufaqsw_faq_item01' ) );

				if ( isset( $shortcode_atts['elements_order'] ) && 'DESC' === strtoupper( $shortcode_atts['elements_order'] ) ) {
					$faqs = array_values( array_reverse( $faqs, true ) );
				}

				if ( ! empty( $faqs ) ) {
					$faqs_data = array_merge( $faqs_data, $faqs );
				}
			}
		}

		if ( function_exists( 'is_product' ) && is_product() ) {

			$is_enable = get_post_meta( $post->ID, '_ufaqsw_enable_faq_tab', true );
			$data      = get_post_meta( $post->ID, '_ufaqsw_tab_data', true );

			if ( 'yes' === $is_enable && '' !== $data ) {

				$faqs = apply_filters( 'ufaqsw_simplify_faqs', get_post_meta( get_post( $data )->ID, 'ufaqsw_faq_item01' ) );

				if ( ! empty( $faqs ) ) {
					$faqs_data = array_merge( $faqs_data, $faqs );
				}
			} elseif ( get_option( 'ufaqsw_enable_global_faq' ) === 'on' && get_option( 'ufaqsw_global_faq' ) !== '' ) {

				$faqs = apply_filters( 'ufaqsw_simplify_faqs', get_post_meta( get_post( get_option( 'ufaqsw_global_faq' ) )->ID, 'ufaqsw_faq_item01' ) );

				if ( ! empty( $faqs ) ) {
					$faqs_data = array_merge( $faqs_data, $faqs );
				}
			}
		}

		return $this->get_unique_faqs( $faqs_data );
	}

	/**
	 * Remove duplicate FAQ items by question and answer.
	 *
	 * @param array $faqs FAQ items.
	 *
	 * @return array
	 */
	private function get_unique_faqs( $faqs ) {
		$unique_faqs = array();
		$seen_keys   = array();

		foreach ( $faqs as $faq ) {
			if ( ! is_array( $faq ) ) {
				continue;
			}

			$question = isset( $faq['ufaqsw_faq_question'] ) ? trim( wp_strip_all_tags( (string) $faq['ufaqsw_faq_question'] ) ) : '';
			$answer   = isset( $faq['ufaqsw_faq_answer'] ) ? trim( wp_strip_all_tags( (string) $faq['ufaqsw_faq_answer'] ) ) : '';

			$key = md5( strtolower( $question . '||' . $answer ) );

			if ( isset( $seen_keys[ $key ] ) ) {
				continue;
			}

			$seen_keys[ $key ] = true;
			$unique_faqs[]     = $faq;
		}

		return $unique_faqs;
	}

	/**
	 * Get all matched shortcodes and attributes from post content.
	 *
	 * @param string $content Post content.
	 * @param array  $tags    Allowed shortcode tags.
	 *
	 * @return array
	 */
	private function get_matched_shortcodes( $content, $tags ) {
		$matched_shortcodes = array();

		$shortcode_pattern = get_shortcode_regex( $tags );
		if ( ! preg_match_all( '/' . $shortcode_pattern . '/s', $content, $matches, PREG_SET_ORDER ) ) {
			return $matched_shortcodes;
		}

		foreach ( $matches as $match ) {
			$tag = isset( $match[2] ) ? $match[2] : '';
			if ( '' === $tag ) {
				continue;
			}

			$atts = array();
			if ( isset( $match[3] ) && '' !== trim( $match[3] ) ) {
				$parsed_atts = shortcode_parse_atts( $match[3] );
				if ( is_array( $parsed_atts ) ) {
					$atts = $parsed_atts;
				}
			}

			$matched_shortcodes[] = array(
				'tag'  => $tag,
				'atts' => $atts,
			);
		}

		return $matched_shortcodes;
	}

	/**
	 * Recursively collect blocks by block name.
	 *
	 * @param array  $blocks Parsed blocks.
	 * @param string $name   Block name.
	 *
	 * @return array
	 */
	private function get_blocks_by_name( $blocks, $name ) {
		$matched_blocks = array();

		foreach ( $blocks as $block ) {
			if ( isset( $block['blockName'] ) && $name === $block['blockName'] ) {
				$matched_blocks[] = $block;
			}

			if ( ! empty( $block['innerBlocks'] ) && is_array( $block['innerBlocks'] ) ) {
				$matched_blocks = array_merge( $matched_blocks, $this->get_blocks_by_name( $block['innerBlocks'], $name ) );
			}
		}

		return $matched_blocks;
	}
}
