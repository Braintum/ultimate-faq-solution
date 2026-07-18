<?php
/**
 * Default FAQ Template
 *
 * @package UltimateFAQSolution
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
* The following classes are used by the FAQ directory search script:
* ufaqsw_element_group_src, ufaqsw_element_src, ufaqsw_faq_question_src, ufaqsw_faq_answer_src
*/
extract( $designs ); // phpcs:ignore

$is_show_all = 'accordion' !== $behaviour && $showall;
?>
<div class="ufaqsw_container_default ufaqsw_container_default_<?php echo esc_attr( get_the_ID() ); ?> ufaqsw_element_group_src">

	<?php if ( 'yes' !== $title_hide ) : ?>
		<h2 class="ufaqsw_faq_title ufaqsw_faq_title_<?php echo esc_attr( get_the_ID() ); ?>"><?php echo esc_html( get_the_title() ); ?></h2>
	<?php endif; ?>

	<?php if ( isset( $show_expand_all ) && 'yes' === $show_expand_all && 'accordion' !== $behaviour ) : ?>
	<div class="ufaqsw-toggle-all-wrap">
		<button type="button" class="ufaqsw-toggle-all-btn" data-all-expanded="<?php echo $is_show_all ? 'true' : 'false'; ?>" aria-label="<?php esc_attr_e( 'Toggle all FAQ answers', 'ufaqsw' ); ?>">
			<span class="ufaqsw-toggle-all-label-expand" <?php echo $is_show_all ? 'style="display:none"' : ''; ?>><?php esc_html_e( 'Expand All', 'ufaqsw' ); ?></span>
			<span class="ufaqsw-toggle-all-label-collapse" <?php echo $is_show_all ? '' : 'style="display:none"'; ?>><?php esc_html_e( 'Collapse All', 'ufaqsw' ); ?></span>
		</button>
	</div>
	<?php endif; ?>

	<?php
	$c = 1;
	foreach ( $faqs as $faq ) :
		$faq = apply_filters( 'ufaqsw_simplify_variables', $faq );
		extract( $faq ); // phpcs:ignore
		?>
		<div class="ufaqsw_toggle_default ufaqsw_toggle_default_<?php echo esc_attr( get_the_ID() ); ?> ufaqsw_element_src">
			<div class="ufaqsw-toggle-title-area-default ufaqsw-toggle-title-area-default_<?php echo esc_attr( get_the_ID() ); ?>"
				role="button"
				tabindex="0"
				aria-expanded="<?php echo $is_show_all ? 'true' : 'false'; ?>"
				aria-controls="ufaqsw_faq_answer_<?php echo esc_html( $c ); ?>_<?php echo esc_html( get_the_ID() ); ?>"
				aria-label="<?php echo esc_attr( wp_strip_all_tags( $question ) ); ?>"
			>
				<div class="ufaqsw-title-name-default ufaqsw-title-name-default_<?php echo esc_attr( get_the_ID() ); ?>">
					<span class="ufaqsw-default-title ufaqsw_faq_question_src"><?php echo wp_kses_post( $question ); ?></span>
					<span class="ufaqsw-default-icon" aria-hidden="true">
						<i class="fa <?php echo esc_attr( isset( $designs['normal_icon'] ) && '' !== $designs['normal_icon'] ? $designs['normal_icon'] : 'fa-plus' ); ?> ufaqsw-icon-normal"></i>
						<i class="fa <?php echo esc_attr( isset( $designs['active_icon'] ) && '' !== $designs['active_icon'] ? $designs['active_icon'] : 'fa-minus' ); ?> ufaqsw-icon-active"></i>
					</span>
				</div>
			</div>
			<div class="ufaqsw-toggle-inner-default ufaqsw-toggle-inner-default_<?php echo esc_attr( get_the_ID() ); ?> ufaqsw_faq_answer_src"
				id="ufaqsw_faq_answer_<?php echo esc_html( $c ); ?>_<?php echo esc_html( get_the_ID() ); ?>"
				aria-hidden="<?php echo $is_show_all ? 'false' : 'true'; ?>"
				aria-labelledby="ufaqsw_faq_answer_<?php echo esc_html( $c ); ?>_<?php echo esc_html( get_the_ID() ); ?>"
				<?php echo true !== $is_show_all ? 'style="display:none"' : ''; ?>
			>
				<?php echo do_shortcode( $answer ); ?>
			</div>
		</div>
		<?php
		$c++;
	endforeach;
	?>

</div>
