<?php
/**
 * FAQ Preview Template
 *
 * This template is used to render FAQ content in the block editor preview iframe.
 *
 * @package UltimateFaqSolution
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $ufaqsw_preview_data;
global $ufaqsw_appearance_data;

// Get FAQ preview data.
$group          = $ufaqsw_preview_data['group'];
$exclude        = $ufaqsw_preview_data['exclude'] ?? '';
$behaviour      = $ufaqsw_preview_data['behaviour'] ?? '';
$elements_order = $ufaqsw_preview_data['elements_order'] ?? '';
$hide_title     = $ufaqsw_preview_data['hide_title'] ?? '0';

if ( isset( $_GET['appearance'] ) ) {
	$appearance_data = sanitize_text_field( wp_unslash( $_GET['appearance'] ) );
	$data = json_decode( base64_decode( $appearance_data ), true );
	$ufaqsw_appearance_data = $data;

	if ( empty( $group ) ) {
		$faq_groups = get_posts(
			array(
				'post_type'      => 'ufaqsw',
				'posts_per_page' => 1,
				'fields'          => 'ids',
			)
		);
		if ( ! empty( $faq_groups ) ) {
			$group = $faq_groups[0];
		}
	}
}

// Build shortcode.
if ( empty( $group ) ) {
	$content = '<div class="ufaqsw-editor-notice">' . esc_html__( 'No FAQ group to preview. Please create one.', 'ufaqsw' ) . '</div>';
} else {
	$shortcode_atts = array();

	if ( 'all' === $group ) {
		$shortcode_name = 'ufaqsw-all';

		if ( ! empty( $exclude ) ) {
			$shortcode_atts['exclude'] = $exclude;
		}
		if ( ! empty( $behaviour ) ) {
			$shortcode_atts['behaviour'] = $behaviour;
		}
	} else {
		$shortcode_name       = 'ufaqsw';
		$shortcode_atts['id'] = $group;
	}

	if ( ! empty( $elements_order ) ) {
		$shortcode_atts['elements_order'] = $elements_order;
	}

	if ( '1' === $hide_title ) {
		$shortcode_atts['title_hide'] = 'yes';
	}

	// Build shortcode string.
	$shortcode = '[' . $shortcode_name;
	foreach ( $shortcode_atts as $key => $value ) {
		$shortcode .= ' ' . $key . '="' . esc_attr( $value ) . '"';
	}
	$shortcode .= ']';
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> style="margin: 0 !important;">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php esc_html_e( 'FAQ Preview', 'ufaqsw' ); ?></title>
	<?php wp_head(); ?>
	<style>
		body {
			margin: 0;
			padding: 20px;
			background: #fff;
		}
		.ufaqsw-editor-notice {
			padding: 16px;
			border-radius: 4px;
			font-style: italic;
			text-align: center;
			background-color: #f0f6fc;
			border: 1px solid #c6d9ed;
			color: #0969da;
		}
		.ufaqsw-editor-error {
			padding: 16px;
			border-radius: 4px;
			font-style: italic;
			text-align: center;
			background-color: #fdf2f2;
			border: 1px solid #f5c6cb;
			color: #721c24;
		}
		/* Override some theme styles that might interfere */
		.admin-bar {
			margin-top: 0 !important;
		}
		#wpadminbar {
			display: none !important;
		}
	</style>
</head>
<body <?php body_class( 'ufaqsw-preview' ); ?>>
	<?php
	// Output the FAQ content.
	echo do_shortcode( $shortcode );
	echo isset( $content ) && ! empty( $content ) ? $content : '';
	?>
	<?php wp_footer(); ?>
</body>
</html>