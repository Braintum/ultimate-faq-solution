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
}
