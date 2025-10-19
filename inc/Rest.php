<?php
/**
 * Rest class for generating FAQ schema.
 *
 * @package UltimateFaqSolution
 * @author  Mahedi
 * @license GPL-2.0-or-later
 * @link    https://example.com
 */

namespace Mahedi\UltimateFaqSolution;

/**
 * Class Rest
 *
 * Handles the generation of FAQ schema for Rest purposes.
 */
class Rest {

	/**
	 * Constructor to initialize the Rest class.
	 *
	 * Adds the action to generate FAQ schema in the head section.
	 */
	public function __construct() {
		add_action( 'rest_api_init', array( $this, 'rest_api_init' ) );
		add_action( 'init', array( $this, 'add_rewrite_rules' ) );
		add_action( 'template_redirect', array( $this, 'handle_faq_preview' ) );

		// Flush rewrite rules on plugin activation.
		register_activation_hook( UFAQSW__PLUGIN_FILE, array( $this, 'flush_rewrite_rules' ) );
	}

	/**
	 * Initializes the REST API routes for the FAQ schema.
	 *
	 * Registers a custom REST route for fetching FAQ posts.
	 */
	public function rest_api_init() {
		register_rest_route(
			'wp/v2',
			'/ufaqsw/',
			array(
				'methods'             => 'GET',
				'callback'            => function ( $data ) {
					// Query for your custom post type.
					$args = array(
						'post_type'      => 'ufaqsw',  // Replace 'ufaqsw' with your CPT slug.
						'posts_per_page' => -1,    // Number of posts to fetch.
						'post_status'    => 'publish', // Only fetch published posts.
					);

					$query = new \WP_Query( $args );
					$posts = array();

					// If posts are found, process the data.
					if ( $query->have_posts() ) {
						while ( $query->have_posts() ) {
							$query->the_post();
							$posts[] = array(
								'id'    => get_the_ID(),
								'title' => get_the_title(),
							);
						}
					}

					wp_reset_postdata();
					return new \WP_REST_Response( $posts );
				},
				'permission_callback' => '__return_true', // Make sure the route is accessible publicly.
			)
		);
	}

	/**
	 * Add rewrite rules for FAQ preview.
	 */
	public function add_rewrite_rules() {
		add_rewrite_rule(
			'^ufaqsw-preview/?$',
			'index.php?ufaqsw_preview=1',
			'top'
		);
		add_rewrite_tag( '%ufaqsw_preview%', '([^&]+)' );
	}

	/**
	 * Handle FAQ preview template loading.
	 */
	public function handle_faq_preview() {
		if ( ! get_query_var( 'ufaqsw_preview' ) ) {
			return;
		}

		// Check if user can edit posts for security.
		if ( ! current_user_can( 'edit_posts' ) ) {
			wp_die( esc_html__( 'Access denied.', 'ufaqsw' ) );
		}

		// Get parameters from query string.
		$group          = sanitize_text_field( wp_unslash( $_GET['group'] ?? '' ) );
		$exclude        = sanitize_text_field( wp_unslash( $_GET['exclude'] ?? '' ) );
		$behaviour      = sanitize_text_field( wp_unslash( $_GET['behaviour'] ?? '' ) );
		$elements_order = sanitize_text_field( wp_unslash( $_GET['elements_order'] ?? '' ) );
		$hide_title     = sanitize_text_field( wp_unslash( $_GET['hideTitle'] ?? '0' ) );

		// Store in global for template access.
		global $ufaqsw_preview_data;
		$ufaqsw_preview_data = array(
			'group'          => $group,
			'exclude'        => $exclude,
			'behaviour'      => $behaviour,
			'elements_order' => $elements_order,
			'hide_title'     => $hide_title,
		);

		// Load the preview template.
		$this->load_preview_template();
		exit;
	}

	/**
	 * Load the FAQ preview template.
	 */
	private function load_preview_template() {
		// Prevent any other output.
		if ( ob_get_level() ) {
			ob_end_clean();
		}

		include UFAQSW__PLUGIN_DIR . 'inc/templates/faq-preview.php';
	}

	/**
	 * Flush rewrite rules.
	 */
	public function flush_rewrite_rules() {
		$this->add_rewrite_rules();
		flush_rewrite_rules();
	}
}
