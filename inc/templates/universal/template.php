<?php
/**
 * Universal FAQ Template
 *
 * All styling is driven by CSS custom properties set inline on the container.
 * Layout variants (classic/card/minimal/boxed) are controlled via data-layout.
 *
 * @package UltimateFaqSolution
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * Source-search classes used by the directory search script:
 * ufaqsw_element_group_src, ufaqsw_element_src, ufaqsw_faq_question_src, ufaqsw_faq_answer_src
 */
extract( $designs ); // phpcs:ignore

$layout          = isset( $layout )               && ! empty( $layout )               ? $layout               : 'classic';
$animation       = isset( $animation )            && ! empty( $animation )            ? $animation            : 'none';
$border_position = isset( $item_border_position ) && ! empty( $item_border_position ) ? $item_border_position : 'all';
$icon_position   = isset( $icon_position )        && ! empty( $icon_position )        ? $icon_position        : 'right';
$is_show_all     = 'accordion' !== $behaviour && $showall;

$css_vars = ufaqsw_build_css_vars( $designs );
?>
<div
	class="ufaqsw-faq ufaqsw-faq-<?php echo esc_attr( get_the_ID() ); ?> ufaqsw_element_group_src"
	data-layout="<?php echo esc_attr( $layout ); ?>"
	data-behaviour="<?php echo esc_attr( isset( $behaviour ) ? $behaviour : 'accordion' ); ?>"
	data-animation="<?php echo esc_attr( $animation ); ?>"
	data-border="<?php echo esc_attr( $border_position ); ?>"
	data-icon-position="<?php echo esc_attr( $icon_position ); ?>"
	<?php if ( $css_vars ) : ?>
	style="<?php echo esc_attr( $css_vars ); ?>"
	<?php endif; ?>
>

	<?php if ( 'yes' !== $title_hide ) : ?>
		<h2 class="ufaqsw-faq__title ufaqsw_faq_title ufaqsw_faq_title_<?php echo esc_attr( get_the_ID() ); ?>">
			<?php echo esc_html( get_the_title() ); ?>
		</h2>
	<?php endif; ?>

	<?php
	$c = 1;
	foreach ( $faqs as $faq ) :
		$faq = apply_filters( 'ufaqsw_simplify_variables', $faq );
		extract( $faq ); // phpcs:ignore
		$answer_id = 'ufaqsw-answer-' . esc_attr( $c ) . '-' . esc_attr( get_the_ID() );
		?>
		<div class="ufaqsw-faq__item ufaqsw_element_src">
			<div
				class="ufaqsw-faq__question"
				role="button"
				tabindex="0"
				aria-expanded="<?php echo $is_show_all ? 'true' : 'false'; ?>"
				aria-controls="<?php echo esc_attr( $answer_id ); ?>"
				aria-label="<?php echo esc_attr( wp_strip_all_tags( $question ) ); ?>"
			>
				<span class="ufaqsw-faq__question-text ufaqsw_faq_question_src">
					<?php echo wp_kses_post( $question ); ?>
				</span>
				<span class="ufaqsw-faq__icon" aria-hidden="true">
					<i class="fa <?php echo esc_attr( isset( $designs['normal_icon'] ) && '' !== $designs['normal_icon'] ? $designs['normal_icon'] : 'fa-plus' ); ?> ufaqsw-icon-normal"></i>
					<i class="fa <?php echo esc_attr( isset( $designs['active_icon'] ) && '' !== $designs['active_icon'] ? $designs['active_icon'] : 'fa-minus' ); ?> ufaqsw-icon-active"></i>
				</span>
			</div>
			<div
				class="ufaqsw-faq__answer ufaqsw_faq_answer_src<?php echo $is_show_all ? ' ufaqsw-open' : ''; ?>"
				id="<?php echo esc_attr( $answer_id ); ?>"
				aria-hidden="<?php echo $is_show_all ? 'false' : 'true'; ?>"
				<?php if ( 'slide' !== $animation && 'fade' !== $animation && ! $is_show_all ) : ?>
				style="display:none"
				<?php endif; ?>
			>
				<div class="ufaqsw-faq__answer-inner">
					<?php echo do_shortcode( $answer ); ?>
				</div>
			</div>
		</div>
		<?php
		$c++;
	endforeach;
	?>
</div>

<?php if ( ! empty( $designs['custom_css'] ) ) : ?>
<style>
<?php echo wp_strip_all_tags( $designs['custom_css'] ); // phpcs:ignore ?>
</style>
<?php endif; ?>

<script>
(function () {
	var containers = document.querySelectorAll('.ufaqsw-faq-<?php echo esc_js( (string) get_the_ID() ); ?>');
	containers.forEach(function (container) {
		var behaviour  = container.dataset.behaviour  || 'accordion';
		var animation  = container.dataset.animation  || 'none';

		function openItem(btn, answer) {
			btn.setAttribute('aria-expanded', 'true');
			answer.setAttribute('aria-hidden', 'false');
			if (animation === 'slide' || animation === 'fade') {
				answer.classList.add('ufaqsw-open');
			} else {
				answer.style.display = '';
			}
		}

		function closeItem(btn, answer) {
			btn.setAttribute('aria-expanded', 'false');
			answer.setAttribute('aria-hidden', 'true');
			if (animation === 'slide' || animation === 'fade') {
				answer.classList.remove('ufaqsw-open');
			} else {
				answer.style.display = 'none';
			}
		}

		container.querySelectorAll('.ufaqsw-faq__question').forEach(function (btn) {
			btn.addEventListener('click', function () {
				var answerId = btn.getAttribute('aria-controls');
				var answer   = document.getElementById(answerId);
				var expanded = btn.getAttribute('aria-expanded') === 'true';

				if (behaviour === 'accordion' && !expanded) {
					container.querySelectorAll('.ufaqsw-faq__question[aria-expanded="true"]').forEach(function (other) {
						var otherId     = other.getAttribute('aria-controls');
						var otherAnswer = document.getElementById(otherId);
						if (otherAnswer) {
							closeItem(other, otherAnswer);
						}
					});
				}

				if (expanded) {
					closeItem(btn, answer);
				} else {
					openItem(btn, answer);
				}
			});

			btn.addEventListener('keydown', function (e) {
				if (e.key === 'Enter' || e.key === ' ') {
					e.preventDefault();
					btn.click();
				}
			});
		});
	});
}());
</script>
