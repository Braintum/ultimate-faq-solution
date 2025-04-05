<?php
/**
 * Ultimate FAQ Solution Block Plugin
 *
 * This file contains the block registration and rendering logic for the Ultimate FAQ Solution plugin.
 *
 * @package UltimateFAQSolution
 */

/**
 * Registers block assets for the Ultimate FAQ Solution plugin.
 *
 * This function registers the JavaScript and CSS assets required for the block editor
 * and associates them with the block type.
 */
function ufaq_register_block_assets() {

	// Load the asset file.
	$asset_file = include UFAQSW__PLUGIN_DIR . 'block/build/index.asset.php';

	// Editor script.
	wp_register_script(
		'ufaqs-block-editor',
		UFAQSW__PLUGIN_URL . 'block/build/index.js',
		$asset_file['dependencies'],
		$asset_file['version'],
		true
	);

	// Editor styles.
	wp_register_style(
		'ufaqs-block-editor-style',
		UFAQSW__PLUGIN_URL . 'block/editor.css',
		array( 'wp-edit-blocks' ),
		filemtime( UFAQSW__PLUGIN_DIR . 'block/editor.css' )
	);

	// Register block.
	register_block_type(
		'ultimate-faq-solution/block',
		array(
			'editor_script'   => 'ufaqs-block-editor',
			'editor_style'    => 'ufaqs-block-editor-style',
			'render_callback' => 'ufaq_render_block_callback',
			'attributes'      => array(
				'group'          => array(
					'type'    => 'string',
					'default' => '',
				),
				'exclude'        => array(
					'type'    => 'array',
					'default' => array(),
					'items'   => array(
						'type' => 'string',
					),
				),
				'column'         => array(
					'type'    => 'string',
					'default' => '',
				),
				'behaviour'      => array(
					'type'    => 'string',
					'default' => '',
				),
				'elements_order' => array(
					'type'    => 'string',
					'default' => '',
				),
				'hideTitle'      => array(
					'type'    => 'boolean',
					'default' => false,
				),
			),
		)
	);

}
add_action( 'init', 'ufaq_register_block_assets' );

/**
 * Callback function to render the FAQ block.
 *
 * @param array $attributes The block attributes.
 * @return string The rendered block content.
 */
function ufaq_render_block_callback( $attributes ) {

	$group          = $attributes['group'] ?? '';
	$exclude        = ! empty( $attributes['exclude'] ) ? implode( ',', $attributes['exclude'] ) : '';
	$column         = $attributes['column'] ?? '';
	$behaviour      = $attributes['behaviour'] ?? '';
	$elements_order = $attributes['elements_order'] ?? '';
	$hide_title     = $attributes['hideTitle'] ?? false;

	if ( empty( $group ) ) {
		return '<p>No FAQ group selected.</p>';
	}

	// Build shortcode.
	if ( 'all' === $group ) {
		$shortcode = '[ufaqsw-all';

		if ( ! empty( $exclude ) ) {
			$shortcode .= ' exclude="' . esc_attr( $exclude ) . '"';
		}
		if ( ! empty( $column ) ) {
			$shortcode .= ' column="' . esc_attr( $column ) . '"';
		}
		if ( ! empty( $behaviour ) ) {
			$shortcode .= ' behaviour="' . esc_attr( $behaviour ) . '"';
		}
	} else {
		$shortcode = '[ufaqsw id="' . esc_attr( $group ) . '"';
	}

	if ( ! empty( $elements_order ) ) {
		$shortcode .= ' elements_order="' . esc_attr( $elements_order ) . '"';
	}

	if ( $hide_title ) {
		$shortcode .= ' title_hide="yes"';
	}

	$shortcode .= ']';

	return do_shortcode( $shortcode );
}
