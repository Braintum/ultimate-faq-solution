<?php
/**
 * Shortcode Builder admin page for Ultimate FAQ Solution.
 *
 * @package UltimateFAQSolution
 */

namespace Mahedi\UltimateFaqSolution\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers and renders the Shortcode Builder submenu page.
 */
class ShortcodeBuilder {

	/**
	 * Constructor – hooks into admin_menu.
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'register_page' ) );
	}

	/**
	 * Register the submenu page under the FAQ Groups CPT menu.
	 */
	public function register_page() {
		add_submenu_page(
			'edit.php?post_type=ufaqsw',
			__( 'Shortcode Builder – Ultimate FAQ Solution', 'ufaqsw' ),
			__( 'Shortcode Builder', 'ufaqsw' ),
			'edit_posts',
			'ufaqsw-shortcode-builder',
			array( $this, 'render_page' )
		);
	}

	/**
	 * Render the page – collect data and load the template.
	 */
	public function render_page() {
		$faq_groups = get_posts(
			array(
				'post_type'      => 'ufaqsw',
				'posts_per_page' => -1,
				'post_status'    => 'publish',
				'orderby'        => 'menu_order',
				'order'          => 'ASC',
			)
		);

		$appearances = get_posts(
			array(
				'post_type'      => 'ufaqsw_appearance',
				'posts_per_page' => -1,
				'post_status'    => 'publish',
				'orderby'        => 'title',
				'order'          => 'ASC',
			)
		);

		include __DIR__ . '/templates/shortcode-builder.php';
	}
}
