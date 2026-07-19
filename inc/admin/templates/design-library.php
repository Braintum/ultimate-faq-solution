<?php
/**
 * Design Library admin page template.
 *
 * Variables available: $presets, $categories, $active_category, $imported, $edit_url
 *
 * @package UltimateFaqSolution
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$category_labels = array(
	'minimal'      => __( 'Minimal', 'ufaqsw' ),
	'colorful'     => __( 'Colorful', 'ufaqsw' ),
	'dark'         => __( 'Dark', 'ufaqsw' ),
	'professional' => __( 'Professional', 'ufaqsw' ),
);
?>
<div class="wrap ufaqsw-design-library">

	<h1><?php esc_html_e( 'Design Library', 'ufaqsw' ); ?></h1>
	<p class="ufaqsw-dl-intro">
		<?php esc_html_e( 'Import a pre-built design into your Appearance Library. After import you can open it in the visual builder and customise every detail.', 'ufaqsw' ); ?>
	</p>

	<?php if ( $imported && $edit_url ) : ?>
	<div class="notice notice-success is-dismissible ufaqsw-dl-notice">
		<p>
			<?php esc_html_e( 'Design imported successfully.', 'ufaqsw' ); ?>
			<a href="<?php echo esc_url( $edit_url ); ?>"><?php esc_html_e( 'Open in Builder →', 'ufaqsw' ); ?></a>
		</p>
	</div>
	<?php endif; ?>

	<!-- Category tabs -->
	<div class="ufaqsw-dl-tabs">
		<a href="<?php echo esc_url( add_query_arg( 'category', 'all', remove_query_arg( array( 'imported' ) ) ) ); ?>"
		   class="ufaqsw-dl-tab<?php echo 'all' === $active_category ? ' is-active' : ''; ?>">
			<?php esc_html_e( 'All', 'ufaqsw' ); ?>
		</a>
		<?php foreach ( $categories as $cat ) : ?>
		<a href="<?php echo esc_url( add_query_arg( 'category', $cat, remove_query_arg( array( 'imported' ) ) ) ); ?>"
		   class="ufaqsw-dl-tab<?php echo $cat === $active_category ? ' is-active' : ''; ?>">
			<?php echo esc_html( $category_labels[ $cat ] ?? ucfirst( $cat ) ); ?>
		</a>
		<?php endforeach; ?>
	</div>

	<!-- Preset grid -->
	<div class="ufaqsw-dl-grid">
		<?php
		$visible = array_filter(
			$presets,
			function ( $p ) use ( $active_category ) {
				return 'all' === $active_category || ( $p['category'] ?? '' ) === $active_category;
			}
		);

		if ( empty( $visible ) ) :
			?>
			<p class="ufaqsw-dl-empty"><?php esc_html_e( 'No designs found in this category.', 'ufaqsw' ); ?></p>
		<?php else : ?>
			<?php foreach ( $visible as $preset ) : ?>
			<div class="ufaqsw-dl-card">

				<!-- Visual swatch preview -->
				<div class="ufaqsw-dl-swatch"
				     style="background:<?php echo esc_attr( $preset['settings']['question_background_color'] ?? '#f8fafc' ); ?>;border-radius:<?php echo esc_attr( ( $preset['settings']['border_radius'] ?? '4' ) . 'px' ); ?>;">
					<div class="ufaqsw-dl-swatch-bar"
					     style="background:<?php echo esc_attr( $preset['settings']['question_background_color'] ?? '#f8fafc' ); ?>;color:<?php echo esc_attr( $preset['settings']['question_color'] ?? '#111' ); ?>;border-color:<?php echo esc_attr( $preset['settings']['border_color'] ?? '#e2e8f0' ); ?>;">
						<span><?php esc_html_e( 'Question row', 'ufaqsw' ); ?></span>
						<span>+</span>
					</div>
					<div class="ufaqsw-dl-swatch-answer"
					     style="background:<?php echo esc_attr( $preset['settings']['answer_background_color'] ?? '#fff' ); ?>;color:<?php echo esc_attr( $preset['settings']['answer_color'] ?? '#555' ); ?>;border-color:<?php echo esc_attr( $preset['settings']['border_color'] ?? '#e2e8f0' ); ?>;">
						<?php esc_html_e( 'Answer panel preview text.', 'ufaqsw' ); ?>
					</div>
				</div>

				<!-- Meta -->
				<div class="ufaqsw-dl-meta">
					<div class="ufaqsw-dl-name"><?php echo esc_html( $preset['name'] ?? '' ); ?></div>
					<div class="ufaqsw-dl-desc"><?php echo esc_html( $preset['description'] ?? '' ); ?></div>
					<div class="ufaqsw-dl-badges">
						<span class="ufaqsw-dl-badge ufaqsw-dl-badge--layout">
							<?php echo esc_html( ucfirst( $preset['settings']['layout'] ?? 'classic' ) ); ?>
						</span>
						<span class="ufaqsw-dl-badge ufaqsw-dl-badge--cat">
							<?php echo esc_html( $category_labels[ $preset['category'] ?? '' ] ?? ucfirst( $preset['category'] ?? '' ) ); ?>
						</span>
					</div>
				</div>

				<!-- Import form -->
				<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" class="ufaqsw-dl-form">
					<input type="hidden" name="action"    value="ufaqsw_import_design">
					<input type="hidden" name="preset_id" value="<?php echo esc_attr( $preset['id'] ); ?>">
					<?php wp_nonce_field( 'ufaqsw_import_design_' . $preset['id'] ); ?>
					<button type="submit" class="button button-primary ufaqsw-dl-import-btn">
						<?php esc_html_e( 'Import Design', 'ufaqsw' ); ?>
					</button>
				</form>

			</div>
			<?php endforeach; ?>
		<?php endif; ?>
	</div><!-- .ufaqsw-dl-grid -->

</div><!-- .wrap -->
