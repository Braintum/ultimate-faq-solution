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
		// Existing FAQ route
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

		// Appearance settings save endpoint.
		register_rest_route(
			'ufaqsw/v1',
			'/appearance/save',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'save_appearance_settings' ),
				'permission_callback' => function () {
					return current_user_can( 'edit_posts' );
				},
			)
		);
	}

	/**
	 * Save appearance settings via REST API.
	 *
	 * @param \WP_REST_Request $request The REST request object.
	 * @return \WP_REST_Response|\WP_Error Response object or error.
	 */
	public function save_appearance_settings( $request ) {
		$post_id  = $request->get_param( 'post_id' );
		$settings = $request->get_param( 'settings' );

		if ( ! $post_id ) {
			return new \WP_Error( 'no_post_id', 'Post ID is required', array( 'status' => 400 ) );
		}

		// Verify user has permission to edit this post.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return new \WP_Error( 'unauthorized', 'You do not have permission to edit this post', array( 'status' => 403 ) );
		}

		// Sanitize settings.
		$sanitized_settings = $this->sanitize_appearance_settings( $settings );

		// Save to post meta.

		if ( isset( $sanitized_settings['title_color'] ) && ! empty( $sanitized_settings['title_color'] ) ) {
			update_post_meta( $post_id, 'ufaqsw_title_color', $sanitized_settings['title_color'] );
		}

		if ( isset( $sanitized_settings['title_font_size'] ) && ! empty( $sanitized_settings['title_font_size'] ) ) {
			update_post_meta( $post_id, 'ufaqsw_title_font_size', $sanitized_settings['title_font_size'] );
		}

		if ( isset( $sanitized_settings['question_color'] ) && ! empty( $sanitized_settings['question_color'] ) ) {
			update_post_meta( $post_id, 'ufaqsw_question_color', $sanitized_settings['question_color'] );
		}

		if ( isset( $sanitized_settings['answer_color'] ) && ! empty( $sanitized_settings['answer_color'] ) ) {
			update_post_meta( $post_id, 'ufaqsw_answer_color', $sanitized_settings['answer_color'] );
		}

		if ( isset( $sanitized_settings['question_background_color'] ) && ! empty( $sanitized_settings['question_background_color'] ) ) {
			update_post_meta( $post_id, 'ufaqsw_question_background_color', $sanitized_settings['question_background_color'] );
		}

		if ( isset( $sanitized_settings['answer_background_color'] ) && ! empty( $sanitized_settings['answer_background_color'] ) ) {
			update_post_meta( $post_id, 'ufaqsw_answer_background_color', $sanitized_settings['answer_background_color'] );
		}

		if ( isset( $sanitized_settings['border_color'] ) && ! empty( $sanitized_settings['border_color'] ) ) {
			update_post_meta( $post_id, 'ufaqsw_border_color', $sanitized_settings['border_color'] );
		}

		if ( isset( $sanitized_settings['question_font_size'] ) && ! empty( $sanitized_settings['question_font_size'] ) ) {
			update_post_meta( $post_id, 'ufaqsw_question_font_size', $sanitized_settings['question_font_size'] );
		}

		if ( isset( $sanitized_settings['answer_font_size'] ) && ! empty( $sanitized_settings['answer_font_size'] ) ) {
			update_post_meta( $post_id, 'ufaqsw_answer_font_size', $sanitized_settings['answer_font_size'] );
		}

		if ( isset( $sanitized_settings['template'] ) && ! empty( $sanitized_settings['template'] ) ) {
			update_post_meta( $post_id, 'ufaqsw_template', $sanitized_settings['template'] );
		}

		if ( isset( $sanitized_settings['showall'] ) && $sanitized_settings['showall'] ) {
			update_post_meta( $post_id, 'ufaqsw_answer_showall', 1 );
		} else {
			delete_post_meta( $post_id, 'ufaqsw_answer_showall' );
		}

		if ( isset( $sanitized_settings['hidetitle'] ) && $sanitized_settings['hidetitle'] ) {
			update_post_meta( $post_id, 'ufaqsw_hide_title', 1 );
		} else {
			delete_post_meta( $post_id, 'ufaqsw_hide_title' );
		}

		if ( isset( $sanitized_settings['normal_icon'] ) && ! empty( $sanitized_settings['normal_icon'] ) ) {
			update_post_meta( $post_id, 'ufaqsw_normal_icon', $sanitized_settings['normal_icon'] );
		}

		if ( isset( $sanitized_settings['active_icon'] ) && ! empty( $sanitized_settings['active_icon'] ) ) {
			update_post_meta( $post_id, 'ufaqsw_active_icon', $sanitized_settings['active_icon'] );
		}

		if ( isset( $sanitized_settings['behaviour'] ) && ! empty( $sanitized_settings['behaviour'] ) ) {
			update_post_meta( $post_id, 'ufaqsw_behaviour', $sanitized_settings['behaviour'] );
		}

		if ( isset( $sanitized_settings['question_bold'] ) && $sanitized_settings['question_bold'] ) {
			update_post_meta( $post_id, 'ufaqsw_question_bold', 1 );
		} else {
			delete_post_meta( $post_id, 'ufaqsw_question_bold' );
		}

		return rest_ensure_response(
			array(
				'success'  => true,
				'message'  => 'Settings saved successfully',
				'settings' => $sanitized_settings,
			)
		);
	}

	/**
	 * Sanitize appearance settings.
	 *
	 * @param array $settings The settings array to sanitize.
	 * @return array Sanitized settings.
	 */
	private function sanitize_appearance_settings( $settings ) {
		if ( ! is_array( $settings ) ) {
			return array();
		}

		$sanitized = array();

		// Sanitize each field based on its type.
		$text_fields = array( 'template', 'behaviour', 'normal_icon', 'active_icon' );
		foreach ( $text_fields as $field ) {
			if ( isset( $settings[ $field ] ) ) {
				$sanitized[ $field ] = sanitize_text_field( $settings[ $field ] );
			}
		}

		$color_fields = array( 'border_color', 'title_color', 'question_color', 'question_background_color', 'answer_color', 'answer_background_color' );
		foreach ( $color_fields as $field ) {
			if ( isset( $settings[ $field ] ) ) {
				$sanitized[ $field ] = sanitize_hex_color( $settings[ $field ] );
			}
		}

		$number_fields = array( 'title_font_size', 'question_font_size', 'answer_font_size' );
		foreach ( $number_fields as $field ) {
			if ( isset( $settings[ $field ] ) ) {
				$sanitized[ $field ] = is_numeric( $settings[ $field ] ) ? intval( $settings[ $field ] ) : '';
			}
		}

		$boolean_fields = array( 'hidetitle', 'showall', 'question_bold' );
		foreach ( $boolean_fields as $field ) {
			if ( isset( $settings[ $field ] ) ) {
				$sanitized[ $field ] = (bool) $settings[ $field ];
			}
		}

		return $sanitized;
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
