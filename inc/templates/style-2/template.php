<?php
/**
 * Template Name: Style 2 FAQ Template
 *
 * @package UltimateFAQSolution
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

extract( $designs ); // phpcs:ignore

$is_show_all = 'accordion' !== $behaviour && $showall;
?>

<div class="ufaqsw_container_style2 ufaqsw_faq_section_style2 ufaqsw_element_group_src">

	<?php if ( 'yes' !== $title_hide ) : ?>
		<h2 class="ufaqsw_faq_title ufaqsw_faq_title_<?php echo esc_html( get_the_ID() ); ?>"><?php echo esc_html( get_the_title() ); ?></h2>
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
	<div class="ufaqsw_faq_style2 ufaqsw_toggle_default_<?php echo esc_html( get_the_ID() ); ?> ufaqsw_element_src">
		<div class="ufaqsw_box_style2 ufaqsw-title-name-default_<?php echo esc_html( get_the_ID() ); ?> ufaqsw-toggle-title-area-default_<?php echo esc_html( get_the_ID() ); ?>"
			role="button"
			tabindex="0"
			aria-expanded="<?php echo $is_show_all ? 'true' : 'false'; ?>"
			aria-controls="ufaqsw_faq_answer_<?php echo esc_html( $c ); ?>_<?php echo esc_html( get_the_ID() ); ?>"
			aria-label="<?php echo esc_attr( wp_strip_all_tags( $question ) ); ?>"
		>
			<span aria-hidden="true">
				<i class="fa <?php echo esc_attr( isset( $designs['normal_icon'] ) && '' !== $designs['normal_icon'] ? $designs['normal_icon'] : 'fa-plus' ); ?> ufaqsw-icon-normal" <?php echo true !== $is_show_all ? 'style="display:inline"' : 'style="display:none"'; ?>></i>
				<i class="fa <?php echo esc_attr( isset( $designs['active_icon'] ) && '' !== $designs['active_icon'] ? $designs['active_icon'] : 'fa-minus' ); ?> ufaqsw-icon-active" <?php echo true === $is_show_all ? 'style="display:inline"' : 'style="display:none"'; ?>></i>
			</span>
			&nbsp;&nbsp;<span class="ufaqsw_faq_question_src"><?php echo wp_kses_post( $question ); ?></span>
		</div>
		<section class="ufaqsw_draw_style2 ufaqsw-toggle-inner-default_<?php echo esc_html( get_the_ID() ); ?> ufaqsw_faq_answer_src"
			id="ufaqsw_faq_answer_<?php echo esc_html( $c ); ?>_<?php echo esc_html( get_the_ID() ); ?>"
			aria-hidden="<?php echo $is_show_all ? 'false' : 'true'; ?>"
			aria-labelledby="ufaqsw_faq_answer_<?php echo esc_html( $c ); ?>_<?php echo esc_html( get_the_ID() ); ?>"
			<?php echo true === $is_show_all ? 'style="display: block;"' : 'style="display: none;"'; ?>
		>
			<?php echo do_shortcode( $answer ); ?>
		</section>
	</div>
		<?php
		$c++;
	endforeach;
	?>

</div>
