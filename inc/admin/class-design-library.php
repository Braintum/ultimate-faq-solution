<?php
/**
 * Design Library — admin page for browsing and importing bundled design presets.
 *
 * @package UltimateFaqSolution
 */

namespace Mahedi\UltimateFaqSolution;

/**
 * Registers the Design Library submenu and handles preset imports.
 */
class DesignLibrary {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'admin_post_ufaqsw_import_design', array( $this, 'handle_import' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_builder_data' ) );
	}

	/**
	 * Inject preset data into the appearance builder JS context.
	 *
	 * @param string $hook Current admin screen hook.
	 */
	public function enqueue_builder_data( $hook ) {
		$screen = get_current_screen();
		if ( ! $screen || 'ufaqsw_appearance' !== $screen->post_type ) {
			return;
		}
		if ( ! in_array( $screen->base, array( 'post', 'post-new' ), true ) ) {
			return;
		}

		wp_add_inline_script(
			'ufaq-admin-js',
			'window.ufaqDesignLibraryData = ' . wp_json_encode( array( 'presets' => $this->get_presets() ) ) . ';',
			'before'
		);
	}

	/**
	 * Handle design import form submission.
	 */
	public function handle_import() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Unauthorized.', 'ufaqsw' ) );
		}

		$preset_id = sanitize_text_field( wp_unslash( $_POST['preset_id'] ?? '' ) ); // phpcs:ignore
		$nonce     = wp_unslash( $_POST['_wpnonce'] ?? '' ); // phpcs:ignore

		if ( ! wp_verify_nonce( $nonce, 'ufaqsw_import_design_' . $preset_id ) ) {
			wp_die( esc_html__( 'Invalid request.', 'ufaqsw' ) );
		}

		$post_id = $this->import_preset( $preset_id );

		if ( is_wp_error( $post_id ) ) {
			wp_die( esc_html( $post_id->get_error_message() ) );
		}

		wp_redirect(
			add_query_arg(
				array(
					'page'     => 'ufaqsw-design-library',
					'imported' => $post_id,
				),
				admin_url( 'edit.php?post_type=ufaqsw' )
			)
		);
		exit;
	}

	/**
	 * Import a preset by ID: create appearance post, save JSON + individual meta.
	 *
	 * @param string $preset_id  Preset filename without extension.
	 * @return int|\WP_Error     New post ID or WP_Error.
	 */
	public function import_preset( $preset_id ) {
		if ( ! preg_match( '/^[a-z0-9-]+$/', $preset_id ) ) {
			return new \WP_Error( 'invalid_id', __( 'Invalid preset ID.', 'ufaqsw' ) );
		}

		$file = UFAQSW__PLUGIN_DIR . 'inc/design-presets/' . $preset_id . '.json';
		if ( ! file_exists( $file ) ) {
			return new \WP_Error( 'not_found', __( 'Preset file not found.', 'ufaqsw' ) );
		}

		$data = json_decode( file_get_contents( $file ), true ); // phpcs:ignore
		if ( ! $data || ! isset( $data['settings'] ) ) {
			return new \WP_Error( 'bad_json', __( 'Invalid preset data.', 'ufaqsw' ) );
		}

		$post_id = wp_insert_post(
			array(
				'post_type'   => 'ufaqsw_appearance',
				'post_title'  => sanitize_text_field( $data['name'] ?? $preset_id ),
				'post_status' => 'publish',
			)
		);

		if ( is_wp_error( $post_id ) ) {
			return $post_id;
		}

		$rest      = new Rest();
		$sanitized = $rest->sanitize_appearance_settings( $data['settings'] );

		update_post_meta( $post_id, 'ufaqsw_design_settings', wp_json_encode( $sanitized ) );
		$rest->save_individual_meta( $post_id, $sanitized );

		return $post_id;
	}

	/**
	 * Load and parse all preset JSON files.
	 *
	 * @return array
	 */
	public function get_presets() {
		$dir     = UFAQSW__PLUGIN_DIR . 'inc/design-presets/';
		$presets = array();

		foreach ( glob( $dir . '*.json' ) as $file ) {
			$data = json_decode( file_get_contents( $file ), true ); // phpcs:ignore
			if ( is_array( $data ) && ! empty( $data['id'] ) ) {
				$presets[] = $data;
			}
		}

		usort(
			$presets,
			function ( $a, $b ) {
				$cat = strcmp( $a['category'] ?? '', $b['category'] ?? '' );
				return $cat !== 0 ? $cat : strcmp( $a['name'] ?? '', $b['name'] ?? '' );
			}
		);

		return $presets;
	}

	/**
	 * Extract unique category slugs from the preset list.
	 *
	 * @param array $presets
	 * @return array
	 */
	private function get_categories( $presets ) {
		$cats = array();
		foreach ( $presets as $p ) {
			if ( ! empty( $p['category'] ) && ! in_array( $p['category'], $cats, true ) ) {
				$cats[] = $p['category'];
			}
		}
		sort( $cats );
		return $cats;
	}
}
