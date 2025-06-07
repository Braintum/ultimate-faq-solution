<?php
/**
 * Handles FAQ group sorting functionality for the Ultimate FAQ Solution plugin.
 *
 * This file defines the FAQ_Group_Sorting class, which manages the ordering and persistence
 * of FAQ groups in the WordPress admin interface.
 *
 * @package Ultimate_FAQ_Solution
 */

defined( 'ABSPATH' ) || exit;

/**
 * Class FAQ_Group_Sorting
 *
 * Handles the sorting functionality for FAQ groups within the Ultimate FAQ Solution plugin.
 * This class provides methods to manage, order, and persist the arrangement of FAQ groups
 * in the WordPress admin interface.
 *
 * @package Ultimate_FAQ_Solution
 */
class FAQ_Group_Sorting {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_filter( 'manage_ufaqsw_posts_columns', array( $this, 'add_sort_column' ) );
		add_action( 'manage_ufaqsw_posts_custom_column', array( $this, 'render_sort_column' ), 10, 2 );
		add_action( 'wp_ajax_ufaqsw_sort', array( $this, 'ajax_sort' ) );
		add_action( 'pre_get_posts', array( $this, 'set_cpt_admin_order' ) );
	}

	/**
	 * Enqueue admin scripts and styles.
	 *
	 * @param string $hook The current admin page.
	 */
	public function enqueue_scripts( $hook ) {
		global $typenow;
		if ( 'ufaqsw' === $typenow && 'edit.php' === $hook ) {
			wp_enqueue_script(
				'ufaqsw-sorting',
				plugins_url( 'admin/assets/js/ufaqsw-sorting.js', dirname( __FILE__ ) ),
				array( 'jquery', 'jquery-ui-sortable' ),
				defined( 'UFAQSW_VERSION' ) ? UFAQSW_VERSION : '1.0.0',
				true
			);
			wp_localize_script(
				'ufaqsw-sorting',
				'ufaqswSorting',
				array(
					'ajax_url' => admin_url( 'admin-ajax.php' ),
					'nonce'    => wp_create_nonce( 'ufaqsw_sort_nonce' ),
				)
			);
			wp_enqueue_style(
				'ufaqsw-sorting-css',
				plugins_url( 'admin/assets/css/ufaqsw-sorting.css', dirname( __FILE__ ) ),
				array(),
				defined( 'UFAQSW_VERSION' ) ? UFAQSW_VERSION : '1.0.0'
			);
		}
	}

	/**
	 * Add custom sort column.
	 *
	 * @param array $columns The columns array.
	 * @return array
	 */
	public function add_sort_column( $columns ) {
		$columns['ufaqsw_sort'] = esc_html__( 'Order', 'ufaqsw' );
		return $columns;
	}

	/**
	 * Render custom sort column.
	 *
	 * @param string $column  The column name.
	 * @param int    $post_id The post ID.
	 */
	public function render_sort_column( $column, $post_id ) {
		if ( 'ufaqsw_sort' === $column ) {
			echo '<span class="ufaqsw-sort-handle" style="cursor:move;">&#9776;</span>';
		}
	}

	/**
	 * Handle AJAX sorting.
	 */
	public function ajax_sort() {
		check_ajax_referer( 'ufaqsw_sort_nonce', 'nonce' );
		if ( ! current_user_can( 'edit_posts' ) ) {
			wp_send_json_error( esc_html__( 'Permission denied', 'ufaqsw' ) );
		}
		$order = isset( $_POST['order'] ) ? wp_unslash( $_POST['order'] ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		if ( is_array( $order ) ) {
			foreach ( $order as $position => $post_id ) {
				wp_update_post(
					array(
						'ID'         => intval( $post_id ),
						'menu_order' => intval( $position ),
					)
				);
			}
			wp_send_json_success();
		}
		wp_send_json_error( esc_html__( 'Invalid data', 'ufaqsw' ) );
	}

	/**
	 * Set custom post type admin order by menu_order.
	 *
	 * Ensures that the FAQ groups are ordered by the 'menu_order' field
	 * in ascending order within the admin list table.
	 *
	 * @param WP_Query $query The current WP_Query instance.
	 */
	public function set_cpt_admin_order( $query ) {
		if ( ! is_admin() ) {
			return;
		}

		global $pagenow;

		if (
			'edit.php' === $pagenow &&
			isset( $_GET['post_type'] ) &&
			'ufaqsw' === sanitize_key( $_GET['post_type'] )
		) {
			$query->set( 'orderby', 'menu_order' );
			$query->set( 'order', 'ASC' );
		}
	}
}

new FAQ_Group_Sorting();

