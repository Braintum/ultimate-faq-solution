<?php
/**
 * AppearanceActions.php
 *
 * Handles appearance-related actions and functionalities for the Ultimate FAQ Solution plugin.
 *
 * @package UltimateFAQSolution
 */

namespace Mahedi\UltimateFaqSolution\Functions;

/**
 * Class AppearanceActions
 *
 * Handles appearance-related actions and functionalities for the Ultimate FAQ Solution plugin.
 *
 * This class may include methods for customizing, updating, or managing the visual aspects
 * of the plugin's frontend or backend appearance.
 *
 * @package UltimateFAQSolution
 */
class AppearanceActions {

	/**
	 * Constructor for the class.
	 *
	 * Initializes any required properties or sets up hooks for the class.
	 */
	public function __construct() {
		add_filter( 'manage_ufaqsw_appearance_posts_columns', array( $this, 'columns_head' ) );
		add_action( 'manage_ufaqsw_appearance_posts_custom_column', array( $this, 'columns_content' ), 10, 2 );
		add_action( 'admin_menu', array( $this, 'add_submenu' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_linked_faqs_metabox' ) );
		add_action( 'admin_post_ufaqsw_detach_group', array( $this, 'handle_detach_group' ) );
		add_action( 'save_post_ufaqsw_appearance', array( $this, 'handle_apply_appearance_to_all' ) );
	}

	/**
	 * Handles applying the current appearance to all FAQ groups.
	 *
	 * This method processes the form submission from the metabox to apply the selected appearance
	 * to all FAQ groups. It verifies the nonce, updates all FAQ groups, and redirects back with a success message.
	 *
	 * @param int $post_id The ID of the appearance post being saved.
	 * @return void
	 */
	public function handle_apply_appearance_to_all( $post_id ) {
		if (
			! isset( $_POST['ufaqsw_apply_appearance_to_all_nonce'], $_POST['ufaqsw_appearance_id'], $_POST['ufaqsw_apply_appearance_to_all'] )
			|| ! wp_verify_nonce( $_POST['ufaqsw_apply_appearance_to_all_nonce'], 'ufaqsw_apply_appearance_to_all_' . $post_id )
		) {
			return;
		}

		$appearance_id = intval( $_POST['ufaqsw_appearance_id'] );
		$faq_groups    = get_posts(
			array(
				'post_type'      => 'ufaqsw',
				'posts_per_page' => -1,
				'fields'         => 'ids',
				'post_status'    => 'any',
			)
		);

		if ( ! empty( $faq_groups ) ) {
			foreach ( $faq_groups as $group_id ) {
				update_post_meta( $group_id, 'linked_faq_appearance_id', $appearance_id );
			}
		}

		// Redirect back to the edit screen with a success message.
		wp_redirect( add_query_arg( 'ufaqsw_applied_all', '1', get_edit_post_link( $appearance_id, 'url' ) ) );
		exit;
	}

	/**
	 * Handles the detachment of a FAQ group from an appearance.
	 *
	 * This method processes the request to detach a FAQ group from an appearance
	 * and redirects back to the appearance edit page with a success message.
	 *
	 * @return void
	 */
	public function handle_detach_group() {
		if ( ! isset( $_GET['appearance'], $_GET['group'], $_GET['_wpnonce'] ) || ! wp_verify_nonce( $_GET['_wpnonce'], 'ufaqsw_detach_group_' . $_GET['appearance'] . '_' . $_GET['group'] ) ) { //phpcs:ignore
			wp_die( esc_html__( 'Invalid request.', 'ufaqsw' ), esc_html__( 'Error', 'ufaqsw' ), array( 'response' => 403 ) );
		}

		$appearance_id = intval( $_GET['appearance'] );
		$group_id      = intval( $_GET['group'] );

		if ( ufaqsw_detach_group_from_appearance( $appearance_id, $group_id ) ) {
			wp_redirect( add_query_arg( 'message', 'detached', get_edit_post_link( $appearance_id ) ) );
			exit;
		} else {
			wp_die( esc_html__( 'Failed to detach the group.', 'ufaqsw' ), esc_html__( 'Error', 'ufaqsw' ), array( 'response' => 500 ) );
		}
	}

	/**
	 * Adds a metabox to the 'ufaqsw_appearance' post type for linking FAQs.
	 *
	 * This method registers a metabox that allows users to link FAQs to a specific appearance.
	 *
	 * @return void
	 */
	public function add_linked_faqs_metabox() {
		add_meta_box(
			'ufaqsw_linked_faqs',
			__( 'Linked FAQ Groups', 'ufaqsw' ),
			array( $this, 'render_linked_faqs_metabox' ),
			'ufaqsw_appearance',
			'side',
			'low'
		);
	}
	/**
	 * Renders the content of the 'Linked FAQs' metabox.
	 *
	 * This method outputs the HTML for the metabox that allows users to link FAQs to the appearance.
	 *
	 * @param \WP_Post $post The current post object.
	 * @return void
	 */
	public function render_linked_faqs_metabox( $post ) {
		$faq_groups = ufaqsw_get_group_ids_by_appearance( get_the_ID( $post ) );

		if ( ! empty( $faq_groups ) ) {
			foreach ( $faq_groups as $group ) {
				echo '<a href="' . esc_url( get_edit_post_link( $group ) ) . '">' . esc_html( get_the_title( $group ) ) . '</a>';
				echo ' <a href="' . esc_url(
					add_query_arg(
						array(
							'action'      => 'ufaqsw_detach_group',
							'appearance'  => $post->ID,
							'group'       => $group,
							'_wpnonce'    => wp_create_nonce( 'ufaqsw_detach_group_' . $post->ID . '_' . $group ),
						),
						admin_url( 'admin-post.php' )
					)
				) . '" style="color:red;" onclick="return confirm(\'Are you sure you want to detach this group?\');">' . esc_html__( 'Detach', 'ufaqsw' ) . '</a><br>';
			}
		} else {
			echo esc_html__( 'No connected FAQ groups', 'ufaqsw' );
		}

		echo '<hr>';
		echo '<form method="post" action="">';
		wp_nonce_field( 'ufaqsw_apply_appearance_to_all_' . $post->ID, 'ufaqsw_apply_appearance_to_all_nonce' );
		echo '<input type="hidden" name="ufaqsw_appearance_id" value="' . esc_attr( $post->ID ) . '">';
		echo '<button type="submit" name="ufaqsw_apply_appearance_to_all" class="button button-secondary" style="width:100%;" onclick="return confirm(\'Are you sure? This action may override the existing appearance of FAQ group.\');">' . esc_html__( 'Apply to all FAQ groups', 'ufaqsw' ) . '</button>';
		echo '</form>';

		// Show success message if redirected after applying appearance.
		if ( isset( $_GET['ufaqsw_applied_all'] ) && '1' === $_GET['ufaqsw_applied_all'] ) {
			echo '<div class="notice notice-success inline"><p>' . esc_html__( 'Appearance applied to all FAQ groups.', 'ufaqsw' ) . '</p></div>';
		}
	}

	/**
	 * Adds a submenu page to the WordPress admin menu for the plugin.
	 *
	 * This method is responsible for registering a submenu under the plugin's main menu
	 * in the WordPress admin dashboard. It defines the submenu's title, capability requirements,
	 * menu slug, and the callback function that renders the submenu page.
	 *
	 * @return void
	 */
	public function add_submenu() {
		add_submenu_page(
			'edit.php?post_type=ufaqsw',         // Parent slug.
			'FAQ Appearances',                   // Page title.
			'FAQ Appearances',                   // Menu title.
			'manage_options',                    // Capability.
			'edit.php?post_type=ufaqsw_appearance' // Target link.
		);
	}

	/**
	 * Modifies the columns displayed in the admin list table for the 'ufaqsw_appearance' post type.
	 *
	 * @param array $defaults The default columns.
	 * @return array Modified columns with additional custom columns.
	 */
	public function columns_head( $defaults ) {
		$new_columns = array();

		$new_columns['cb']                = '<input type="checkbox" />';
		$new_columns['title']             = __( 'Title', 'ufaqsw' );
		$new_columns['ufaqsw_faq_groups'] = __( 'Connected FAQ Groups', 'ufaqsw' );
		$new_columns['date']              = __( 'Date', 'ufaqsw' );

		return $new_columns;
	}

	/**
	 * Outputs custom column content for the 'ufaqsw_appearance' post type in the admin list table.
	 *
	 * @param string $column_name The name of the column to display content for.
	 * @param int    $post_ID     The ID of the current post.
	 */
	public function columns_content( $column_name, $post_ID ) {
		if ( 'ufaqsw_faq_groups' === $column_name ) {
			$faq_groups = ufaqsw_get_group_ids_by_appearance( $post_ID );

			if ( ! empty( $faq_groups ) ) {
				foreach ( $faq_groups as $group ) {
					echo '<a href="' . esc_url( get_edit_post_link( $group ) ) . '">' . esc_html( get_the_title( $group ) ) . '</a><br>';
				}
			} else {
				echo esc_html__( 'No connected FAQ groups', 'ufaqsw' );
			}
		}
	}

}
