<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName -- File name matches class name.
/**
 * ACFIntegration class integration for AI Text Refiner plugin.
 *
 * Handles ACF field integration and AJAX refinement.
 *
 * @package BTRefiner\Admin
 */

namespace BTRefiner\Admin;

/**
 * Class ACFIntegration
 *
 * Integrates ACF fields with the AI Text Refiner plugin, adding UI elements and handling AJAX requests for text refinement.
 */
class ACFIntegration {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'acf/render_field/type=textarea', array( $this, 'add_refine_dropdown' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
	}

	/**
	 * Add refine dropdown to ACF fields.
	 *
	 * @param array $field The ACF field array.
	 */
	public function add_refine_dropdown( $field ) {

		$field_id = esc_attr( $field['id'] );

		$commands = get_field( 'ufaqsw_ai_commands', 'options' );
		?>
		<div class="chatgpt-refine-dropdown" data-target="<?php echo esc_attr( $field_id ); ?>" >
			<button type="button" class="button">✨ AI Tools</button>
			<ul>
				<?php
				if ( ! empty( $commands ) && is_array( $commands ) ) {
					foreach ( $commands as $command ) {
						if ( isset( $command['title'] ) && isset( $command['command'] ) ) {
							echo '<li data-action="' . $command['command'] . '">' . $command['title'] . '</li>';
						}
					}
				}
				?>
			</ul>
		</div>
		<?php
	}

	/**
	 * Enqueue admin assets.
	 */
	public function enqueue_assets(): void {

		wp_enqueue_script('loading-overlay-js', UFAQSW_TEXT_REFINER_URL . 'assets/js/loadingoverlay/loadingoverlay.min.js', ['jquery'], null, true);
		wp_enqueue_script('refine-text-js', UFAQSW_TEXT_REFINER_URL . 'assets/js/refine-text.js', ['jquery'], null, true);
        wp_localize_script('refine-text-js', 'RefinerData', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('ufaqsw-ajax-nonce'),
        ]);

		wp_enqueue_style('refine-text-css', UFAQSW_TEXT_REFINER_URL . 'assets/css/refine-text.css', [], null);
	}
}
