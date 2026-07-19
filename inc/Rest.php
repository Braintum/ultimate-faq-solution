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

		// Design import endpoint.
		register_rest_route(
			'ufaqsw/v1',
			'/designs/import',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'import_design_preset' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			)
		);

		// AI design generation endpoint.
		register_rest_route(
			'ufaqsw/v1',
			'/ai/generate-design',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'generate_ai_design' ),
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

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return new \WP_Error( 'unauthorized', 'You do not have permission to edit this post', array( 'status' => 403 ) );
		}

		$sanitized = self::sanitize_appearance_settings( $settings );

		// Phase 4: save canonical JSON blob.
		update_post_meta( $post_id, 'ufaqsw_design_settings', wp_json_encode( $sanitized ) );

		// Also persist individual meta keys for backward compatibility with legacy templates.
		$this->save_individual_meta( $post_id, $sanitized );

		return rest_ensure_response(
			array(
				'success'  => true,
				'message'  => 'Settings saved successfully',
				'settings' => $sanitized,
			)
		);
	}

	/**
	 * Generate appearance settings from a natural-language prompt via the configured AI provider.
	 *
	 * @param \WP_REST_Request $request The REST request object.
	 * @return \WP_REST_Response|\WP_Error
	 */
	public function generate_ai_design( $request ) {
		$prompt           = sanitize_textarea_field( $request->get_param( 'prompt' ) ?? '' );
		$current_settings = (array) ( $request->get_param( 'current_settings' ) ?? array() );

		if ( '' === trim( $prompt ) ) {
			return new \WP_Error( 'no_prompt', __( 'Prompt is required.', 'ufaqsw' ), array( 'status' => 400 ) );
		}

		// Rate limiting: 10 requests per hour per user.
		$user_id   = get_current_user_id();
		$cache_key = 'ufaqsw_ai_design_rl_' . $user_id;
		$count     = (int) get_transient( $cache_key );

		if ( $count >= 10 ) {
			return new \WP_Error(
				'rate_limited',
				__( 'Limit of 10 AI design generations per hour reached. Please try again later.', 'ufaqsw' ),
				array( 'status' => 429 )
			);
		}

		try {
			$generator = new AiDesignGenerator();
			$settings  = $generator->generate( $prompt, $current_settings );

			set_transient( $cache_key, $count + 1, HOUR_IN_SECONDS );

			return rest_ensure_response(
				array(
					'success'  => true,
					'settings' => $settings,
				)
			);
		} catch ( \BTRefiner\Exceptions\AIProviderException $e ) {
			return new \WP_Error( 'ai_provider_error', $e->getMessage(), array( 'status' => 502 ) );
		} catch ( \RuntimeException $e ) {
			return new \WP_Error( 'generation_error', $e->getMessage(), array( 'status' => 500 ) );
		}
	}

	/**
	 * Import a bundled design preset and create a new ufaqsw_appearance post.
	 *
	 * @param \WP_REST_Request $request The REST request object.
	 * @return \WP_REST_Response|\WP_Error
	 */
	public function import_design_preset( $request ) {
		$preset_id = sanitize_text_field( $request->get_param( 'preset_id' ) );

		if ( ! preg_match( '/^[a-z0-9-]+$/', $preset_id ) ) {
			return new \WP_Error( 'invalid_preset', 'Invalid preset ID', array( 'status' => 400 ) );
		}

		$file = UFAQSW__PLUGIN_DIR . 'inc/design-presets/' . $preset_id . '.json';
		if ( ! file_exists( $file ) ) {
			return new \WP_Error( 'not_found', 'Preset not found', array( 'status' => 404 ) );
		}

		$data = json_decode( file_get_contents( $file ), true ); // phpcs:ignore
		if ( ! $data || ! isset( $data['settings'] ) ) {
			return new \WP_Error( 'invalid_json', 'Invalid preset file', array( 'status' => 500 ) );
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

		$sanitized = self::sanitize_appearance_settings( $data['settings'] );
		update_post_meta( $post_id, 'ufaqsw_design_settings', wp_json_encode( $sanitized ) );
		$this->save_individual_meta( $post_id, $sanitized );

		return rest_ensure_response(
			array(
				'success'  => true,
				'post_id'  => $post_id,
				'edit_url' => get_edit_post_link( $post_id, 'raw' ),
			)
		);
	}

	/**
	 * Save individual post meta keys for backward compat with legacy templates.
	 *
	 * @param int   $post_id   Appearance post ID.
	 * @param array $settings  Sanitized settings array.
	 */
	public function save_individual_meta( $post_id, $settings ) {
		$simple_map = array(
			'template'                  => 'ufaqsw_template',
			'behaviour'                 => 'ufaqsw_faq_behaviour',
			'title_color'               => 'ufaqsw_title_color',
			'title_font_size'           => 'ufaqsw_title_font_size',
			'question_color'            => 'ufaqsw_question_color',
			'question_background_color' => 'ufaqsw_question_background_color',
			'answer_color'              => 'ufaqsw_answer_color',
			'answer_background_color'   => 'ufaqsw_answer_background_color',
			'border_color'              => 'ufaqsw_border_color',
			'question_font_size'        => 'ufaqsw_question_font_size',
			'answer_font_size'          => 'ufaqsw_answer_font_size',
			'normal_icon'               => 'ufaqsw_normal_icon',
			'active_icon'               => 'ufaqsw_active_icon',
		);

		foreach ( $simple_map as $key => $meta_key ) {
			if ( isset( $settings[ $key ] ) && '' !== $settings[ $key ] ) {
				update_post_meta( $post_id, $meta_key, $settings[ $key ] );
			}
		}

		// Boolean fields.
		foreach ( array( 'showall' => 'ufaqsw_answer_showall', 'hidetitle' => 'ufaqsw_hide_title', 'question_bold' => 'ufaqsw_question_bold' ) as $key => $meta_key ) {
			if ( ! empty( $settings[ $key ] ) ) {
				update_post_meta( $post_id, $meta_key, 1 );
			} else {
				delete_post_meta( $post_id, $meta_key );
			}
		}
	}

	/**
	 * Sanitize appearance settings.
	 *
	 * Static so it can be called from AiDesignGenerator without re-instantiating Rest.
	 *
	 * @param array $settings The settings array to sanitize.
	 * @return array Sanitized settings.
	 */
	public static function sanitize_appearance_settings( $settings ) {
		if ( ! is_array( $settings ) ) {
			return array();
		}

		$sanitized = array();

		$text_fields = array(
			'template', 'layout', 'behaviour', 'animation', 'normal_icon', 'active_icon',
			'border_style', 'question_font_weight', 'answer_line_height',
			// Universal-only
			'item_border_position', 'shadow_style', 'question_text_transform', 'title_font_weight',
			'icon_position',
		);
		$text_allowlists = array(
			'border_style'            => array( 'solid', 'dashed', 'dotted' ),
			'item_border_position'    => array( 'all', 'bottom-only', 'left-accent' ),
			'shadow_style'            => array( 'none', 'subtle', 'medium', 'strong' ),
			'question_text_transform' => array( '', 'uppercase', 'capitalize' ),
			'title_font_weight'       => array( '', '400', '500', '600', '700' ),
			'question_font_weight'    => array( '', '400', '500', '600', '700' ),
			'icon_position'           => array( 'right', 'left' ),
		);
		foreach ( $text_fields as $field ) {
			if ( isset( $settings[ $field ] ) ) {
				$val = sanitize_text_field( $settings[ $field ] );
				if ( isset( $text_allowlists[ $field ] ) && ! in_array( $val, $text_allowlists[ $field ], true ) ) {
					$val = '';
				}
				$sanitized[ $field ] = $val;
			}
		}

		$color_fields = array(
			'border_color', 'title_color', 'question_color', 'question_background_color',
			'answer_color', 'answer_background_color',
			// Universal-only
			'active_question_color', 'active_question_bg', 'icon_color', 'active_icon_color',
			'title_bg_color', 'active_border_color',
		);
		foreach ( $color_fields as $field ) {
			if ( isset( $settings[ $field ] ) ) {
				$sanitized[ $field ] = sanitize_hex_color( $settings[ $field ] ) ?? '';
			}
		}

		$number_fields = array( 'title_font_size', 'question_font_size', 'answer_font_size', 'border_radius', 'border_width', 'item_padding', 'item_gap', 'icon_size', 'container_max_width' );
		foreach ( $number_fields as $field ) {
			if ( isset( $settings[ $field ] ) ) {
				$sanitized[ $field ] = is_numeric( $settings[ $field ] ) ? intval( $settings[ $field ] ) : '';
			}
		}

		// Float number fields (step < 1).
		$float_fields = array( 'question_letter_spacing' );
		foreach ( $float_fields as $field ) {
			if ( isset( $settings[ $field ] ) ) {
				$sanitized[ $field ] = is_numeric( $settings[ $field ] ) ? floatval( $settings[ $field ] ) : '';
			}
		}

		$boolean_fields = array( 'hidetitle', 'showall', 'question_bold' );
		foreach ( $boolean_fields as $field ) {
			if ( isset( $settings[ $field ] ) ) {
				$sanitized[ $field ] = (bool) $settings[ $field ];
			}
		}

		if ( isset( $settings['custom_css'] ) ) {
			$sanitized['custom_css'] = wp_strip_all_tags( $settings['custom_css'] );
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
