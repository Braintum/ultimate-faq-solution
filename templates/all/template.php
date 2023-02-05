<div class="ufaqsw_default_all_faq_container">
	<?php if ( get_option( 'ufaqsw_enable_search' ) == 'on' ) : ?>
		<div class="ufaqsw_default_all_faq_header">
			<div class="ufaqsw_default_all_search">
				<input type="text" class="ufaqsw_default_all_search_box" placeholder="<?php echo ( '' != get_option( 'ufaqsw_live_search_text' ) ? esc_html( get_option( 'ufaqsw_live_search_text' ) ) : esc_html__( 'Live Search..', 'ufaqsw' ) ); ?>" />
				<span class="ufaqsw_search_loading">
					<?php echo ( '' != get_option( 'ufaqsw_live_search_loading_text' ) ? esc_html( get_option('ufaqsw_live_search_loading_text' ) ) : esc_html__( 'Loading...', 'ufaqsw' ) ) ?>
				</span>
			</div>
		</div>
	<?php endif; ?>

	<div class="ufaqsw_default_all_faq_content">
		{{content}}
	</div>
	<div class='ufaqsw_search_no_result'>
		<p>
			<?php echo ( '' != get_option( 'ufaqsw_search_result_not_found' ) ? esc_html( get_option( 'ufaqsw_search_result_not_found' ) ): esc_html__( 'No Result Fount!', 'ufaqsw') ); ?>
		</p>
	</div>
</div>