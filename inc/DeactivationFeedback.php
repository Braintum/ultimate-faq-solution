<?php
/**
 * Deactivation Feedback handler.
 *
 * @package UltimateFaqSolution
 */

namespace Mahedi\UltimateFaqSolution;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * DeactivationFeedback class handles plugin deactivation feedback collection.
 */
class DeactivationFeedback {

	/**
	 * Plugin slug.
	 *
	 * @var string
	 */
	private $plugin_slug = 'ultimate-faq-solution/init.php';

	/**
	 * REST API namespace.
	 *
	 * @var string
	 */
	private $rest_namespace = 'ufs/v1';

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'wp_ajax_ufs_submit_feedback', array( $this, 'handle_feedback_submission' ) );
	}

	/**
	 * Enqueue admin JS for the deactivation modal.
	 *
	 * @param string $hook Current admin page hook.
	 */
	public function enqueue_scripts( $hook ) {
		if ( 'plugins.php' !== $hook ) {
			return;
		}

		wp_enqueue_style( 'ufs-feedback-style', UFAQSW__PLUGIN_URL . 'inc/admin/assets/css/ufs-feedback.css', array(), UFAQSW_VERSION );
		wp_enqueue_script(
			'ufs-feedback-script',
			UFAQSW__PLUGIN_URL . 'inc/admin/assets/js/ufs-feedback.js',
			array( 'jquery' ),
			UFAQSW_VERSION,
			true
		);

		wp_localize_script(
			'ufs-feedback-script',
			'UFS_FEEDBACK',
			array(
				'ajax_url'    => admin_url( 'admin-ajax.php' ),
				'rest_url'    => esc_url_raw( rest_url( $this->rest_namespace . '/deactivation-feedback' ) ),
				'plugin_slug' => $this->plugin_slug,
				'nonce'       => wp_create_nonce( 'ufs_feedback_nonce' ),
			)
		);
	}

	/**
	 * Handle AJAX submission (for fallback).
	 */
	public function handle_feedback_submission() {
		check_ajax_referer( 'ufs_feedback_nonce', 'security' );

		$data = array(
			'reason'  => sanitize_text_field( $_POST['reason'] ?? '' ), // phpcs:ignore
			'details' => sanitize_textarea_field( $_POST['details'] ?? '' ), // phpcs:ignore
			'plugin'  => 'Ultimate FAQ Solution - ' . UFAQSW_VERSION,
			'site'    => get_site_url(),
		);

		// send data wherever you want (e.g. to your API, email, log file).
		$this->send_feedback( $data );

		deactivate_plugins( $this->plugin_slug );

		wp_send_json_success( array( 'message' => 'Feedback submitted and plugin deactivated.' ) );
	}

	/**
	 * Send feedback to remote API endpoint.
	 *
	 * @param array $data Feedback data to send.
	 * @return bool True on success, false on failure.
	 */
	private function send_feedback( $data ) {
		// API endpoint URL.
		$api_url = 'https://feedback.ultimatefaqsolution.com/api/feedback';

		// Bearer token for authentication.
		$bearer_token = '1|3zoRtlTjklYI6q1p0rQyRWRK01vpUQwpDeFnGLMud26be7ea';

		// Prepare the request arguments.
		$args = array(
			'method'      => 'POST',
			'timeout'     => 10,
			'redirection' => 5,
			'httpversion' => '1.0',
			'blocking'    => true,
			'headers'     => array(
				'Content-Type'  => 'application/json',
				'Authorization' => 'Bearer ' . $bearer_token,
			),
			'body'        => wp_json_encode( $data ),
		);

		// Send the request using WordPress HTTP API.
		$response = wp_remote_post( $api_url, $args );

		// Check for errors.
		if ( is_wp_error( $response ) ) {
			return false;
		}

		// Get response code.
		$response_code = wp_remote_retrieve_response_code( $response );

		// Check if request was successful.
		if ( $response_code >= 200 && $response_code < 300 ) {
			return true;
		} else {
			return false;
		}
	}
}
