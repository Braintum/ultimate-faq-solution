<?php
/**
 * Template for displaying all FAQs.
 *
 * This template is used to render the FAQ section with optional search functionality.
 *
 * @package UltimateFAQSolution
 */

?>
<div class="ufaqsw_default_all_faq_container">
	<?php if ( get_option( 'ufaqsw_enable_search' ) === 'on' ) : ?>
		<div class="ufaqsw_default_all_faq_header">
			<label for="ufaqsw-faq-search" class="screen-reader-text"><?php echo esc_html__( 'Search FAQs', 'ufaqsw' ); ?></label>
			<input type="text" 
				class="ufaqsw_default_all_search_box" 
				placeholder="<?php echo ( '' !== get_option( 'ufaqsw_live_search_text' ) ? esc_html( get_option( 'ufaqsw_live_search_text' ) ) : esc_html__( 'Live Search..', 'ufaqsw' ) ); ?>" 
				id="ufaqsw-faq-search"
				role="searchbox"
				aria-label="<?php echo esc_html__( 'Search FAQs', 'ufaqsw' ); ?>"
				autocomplete="off"
			/>
			<span class="ufaqsw_search_loading"
				role="status"
				aria-live="polite"
			>
				<?php echo ( '' !== get_option( 'ufaqsw_live_search_loading_text' ) ? esc_html( get_option( 'ufaqsw_live_search_loading_text' ) ) : esc_html__( 'Loading...', 'ufaqsw' ) ); ?>
			</span>

		</div>
	<?php endif; ?>

	<?php if ( ! empty( $filter_groups ) ) : ?>
	<div class="faq_navigation filter-list filter-list--sticky">
		<div class="faq__navigation__inner filter-list__items">
			<?php foreach ( $filter_groups as $key => $value ) : ?>
				<div class="faq__navigation__tab filter-list__item" data-index="<?php echo esc_attr( 'ufaqsw_single_faq_' . $key ); ?>">
					<?php echo esc_html( $value ); ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
	<?php endif; ?>
	<div class="ufaqsw_default_all_faq_content">
		{{content}}
	</div>
	<div class='ufaqsw_search_no_result'>
		<p>
			<?php echo ( '' !== get_option( 'ufaqsw_search_result_not_found' ) ? esc_html( get_option( 'ufaqsw_search_result_not_found' ) ) : esc_html__( 'No Result Fount!', 'ufaqsw' ) ); ?>
		</p>
	</div>
</div>
