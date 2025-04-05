<?php
/**
 * Template Name: Style 2 FAQ Template
 * Description: Default template by Aussie Team for displaying FAQs in style 2.
 *
 * @package UltimateFAQSolution
 * Author: Aussie Team
 * Version: 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

extract( $designs ); // phpcs:ignore
?>

<div class="ufaqsw_container_style2 ufaqsw_faq_section_style2 ufaqsw_element_group_src">

	<?php
	// Main Title
	// Check if title is enabled.
	if ( '1' !== $hidetitle && 'yes' !== $title_hide ) :
		?>
		<h2 class="ufaqsw_faq_title ufaqsw_faq_title_<?php echo esc_html( get_the_ID() ); ?>"><?php echo esc_html( get_the_title() ); ?></h2>
	<?php endif; ?>

	<?php
	$c = 1;
	foreach ( $faqs as $faq ) :
		/*
		* This is where the looping begans
		* Now it is for simplifing for data variables
		*/
		$faq = apply_filters( 'ufaqsw_simplify_variables', $faq );
		extract( $faq ); // phpcs:ignore

		/*
		* All data variables are extracted. Available variables are
		* $question - Which contains the Question.
		* $answer - Which contains the Answer.
		*/

		?>
	<div class="ufaqsw_FaQ_Each_style2 ufaqsw_toggle_default_<?php echo esc_html( get_the_ID() ); ?> ufaqsw_element_src">
		<div class="ufaqsw_box_style2 ufaqsw-title-name-default_<?php echo esc_html( get_the_ID() ); ?> ufaqsw-toggle-title-area-default_<?php echo esc_html( get_the_ID() ); ?>">
				<span>
					<i class="fa <?php echo esc_attr( isset( $designs['normal_icon'] ) && '' !== $designs['normal_icon'] ? $designs['normal_icon'] : 'fa fa-plus' ); ?>" aria-hidden="true"></i>
					<i class="fa <?php echo esc_attr( isset( $designs['active_icon'] ) && '' !== $designs['active_icon'] ? $designs['active_icon'] : 'fa fa-minus' ); ?>" id="ufaqsw_other_style2" aria-hidden="true"></i>
				</span>
				&nbsp;&nbsp;<span class="ufaqsw_faq_question_src"><?php echo wp_kses_post( $question ); ?></span>
		</div>
		<section class="ufaqsw_draw_style2 ufaqsw-toggle-inner-default_<?php echo esc_html( get_the_ID() ); ?> ufaqsw_faq_answer_src">
			<?php echo wp_kses_post( apply_filters( 'the_content', $answer ) ); ?>
		</section>
	</div>
		<?php
		$c++;
	endforeach;

	?>

</div>
