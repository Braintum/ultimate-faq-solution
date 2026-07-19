<?php
/**
 * AppearanceActions.php
 *
 * Handles appearance-related actions and functionalities for the Ultimate FAQ Solution plugin.
 *
 * @package UltimateFAQSolution
 */

namespace Mahedi\UltimateFaqSolution;

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

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
		add_action( 'edit_form_after_title', array( $this, 'builder_root' ) );
	}

	/**
	 * Renders the appearance builder root element.
	 *
	 * This method outputs the root div element for the appearance builder
	 * on the appearance post type edit screen.
	 *
	 * @return void
	 */
	public function builder_root() {
		$screen = get_current_screen();
		if ( ! $screen ) {
			return;
		}
		if ( 'ufaqsw_appearance' !== $screen->post_type ) {
			return;
		}

		echo '<div id="ufaq-appearance-builder-root"></div>';
	}

	/**
	 * Enqueue admin scripts and styles for the appearance editor.
	 *
	 * @param string $hook The current admin page hook.
	 * @return void
	 */
	public function enqueue_admin_scripts( $hook ) {
		// Only load on edit/add screen for 'ufaqsw_appearance' CPT.
		$screen = get_current_screen();
		if ( ! $screen ) {
			return;
		}
		if ( 'ufaqsw_appearance' !== $screen->post_type ) {
			return;
		}
		if ( ! in_array( $screen->base, array( 'post', 'post-new' ), true ) ) {
			return;
		}

		// Adjust path if plugin folder differs.
		$dist_url = UFAQSW__PLUGIN_URL . 'assets/';

		wp_enqueue_style( 'ufaq-admin-css', $dist_url . 'css/admin.css', array(), '1.0' );
		wp_enqueue_script( 'ufaq-admin-js', $dist_url . 'dist/admin.js', array( 'wp-element' ), '1.0', true );

		// Pass initial values (fetch from meta or provide defaults).
		$appearance_meta = ufaqsw_simplify_configuration_variables( get_the_ID() );
		if ( ! $appearance_meta ) {
			$appearance_meta = array();
		}

		$data  = file( UFAQSW__PLUGIN_DIR . 'assets/data/fa-data.txt' ); // file in to an array.
		$icons = array();
		foreach ( $data as $key => $val ) {
			$val   = explode( '=>', $val );
			$title = $val[0];
			$class = explode( ',', $val[1] );
			foreach ( $class as $v => $k ) {
				if ( strlen( $k ) > 2 ) {
					$icons[ $title ][] = trim( $k );
				}
			}
		}
		// Define available FontAwesome icons.
		$fontawesome_icons = array(
			array(
				'value' => '',
				'label' => 'Default',
				'icon'  => '',
			),
		);

		foreach ( $icons as $icon_category => $icon_items ) {

			foreach ( $icon_items as $icon ) {
				$icon_name = str_replace( 'fa-', '', $icon );
				$fontawesome_icons[] = array(
					'value' => $icon,
					'label' => ucwords( str_replace( '-', ' ', $icon_name ) ),
					'icon'  => $icon,
				);
			}
		}

		$ai_settings = get_option( 'ufaqsw_ai_integration_settings', array() );

		// Build linked groups for the React "Applied to X groups" indicator.
		$post_id        = get_the_ID();
		$linked_ids     = $post_id ? ufaqsw_get_group_ids_by_appearance( $post_id ) : array();
		$linked_groups  = array_map(
			function ( $group_id ) {
				return array(
					'id'      => $group_id,
					'title'   => get_the_title( $group_id ),
					'editUrl' => get_edit_post_link( $group_id, 'raw' ),
				);
			},
			$linked_ids
		);

		wp_localize_script(
			'ufaq-admin-js',
			'ufaqAppearanceData',
			array(
				'initialValues'  => $appearance_meta,
				'previewBaseUrl' => add_query_arg( 'preview', 'ufaq', home_url( '/ufaqsw-preview/' ) ),
				'postId'         => $post_id,
				'saveEndpoint'   => rest_url( 'ufaqsw/v1/appearance/save' ),
				'nonce'          => wp_create_nonce( 'wp_rest' ),
				'icons'              => $fontawesome_icons,
				'linkedGroups'       => array_values( $linked_groups ),
				'aiGenerateEndpoint' => rest_url( 'ufaqsw/v1/ai/generate-design' ),
				'aiEnabled'          => ! empty( $ai_settings['enable_ai_integration'] ),
			)
		);
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
			|| ! wp_verify_nonce( $_POST['ufaqsw_apply_appearance_to_all_nonce'], 'ufaqsw_apply_appearance_to_all_' . $post_id ) // phpcs:ignore
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
	 * Renders the redesigned "Linked FAQ Groups" meta box.
	 *
	 * @param \WP_Post $post The current post object.
	 * @return void
	 */
	public function render_linked_faqs_metabox( $post ) {
		$faq_groups = ufaqsw_get_group_ids_by_appearance( get_the_ID( $post ) );
		$count      = count( $faq_groups );

		// Applied-to summary line.
		echo '<p class="ufaqsw-linked-groups-header">';
		if ( $count > 0 ) {
			echo esc_html(
				sprintf(
					/* translators: %d: number of FAQ groups using this appearance */
					_n( 'Applied to %d FAQ Group', 'Applied to %d FAQ Groups', $count, 'ufaqsw' ),
					$count
				)
			);
		} else {
			echo esc_html__( 'Not applied to any FAQ Group yet.', 'ufaqsw' );
		}
		echo '</p>';

		if ( ! empty( $faq_groups ) ) {
			foreach ( $faq_groups as $group ) {
				$detach_url = add_query_arg(
					array(
						'action'     => 'ufaqsw_detach_group',
						'appearance' => $post->ID,
						'group'      => $group,
						'_wpnonce'   => wp_create_nonce( 'ufaqsw_detach_group_' . $post->ID . '_' . $group ),
					),
					admin_url( 'admin-post.php' )
				);

				echo '<div class="ufaqsw-linked-group-item">';
				echo '<a href="' . esc_url( get_edit_post_link( $group ) ) . '">' . esc_html( get_the_title( $group ) ) . '</a>';
				echo '<a href="' . esc_url( $detach_url ) . '" class="ufaqsw-detach-link" onclick="return confirm(\'' . esc_js( __( 'Remove this group from the appearance?', 'ufaqsw' ) ) . '\');">' . esc_html__( 'Detach', 'ufaqsw' ) . '</a>';
				echo '</div>';
			}
		} else {
			echo '<p class="ufaqsw-no-groups-msg">';
			echo esc_html__( 'Go to a FAQ Group and select this appearance from the sidebar to link it here.', 'ufaqsw' );
			echo '</p>';
		}

		echo '<hr style="margin:12px 0;">';

		// Apply to all.
		echo '<form method="post" action="">';
		wp_nonce_field( 'ufaqsw_apply_appearance_to_all_' . $post->ID, 'ufaqsw_apply_appearance_to_all_nonce' );
		echo '<input type="hidden" name="ufaqsw_appearance_id" value="' . esc_attr( $post->ID ) . '">';
		echo '<button type="submit" name="ufaqsw_apply_appearance_to_all" class="button button-secondary" style="width:100%;" onclick="return confirm(\'' . esc_js( __( 'Apply this appearance to ALL FAQ Groups? This will override any individual group settings.', 'ufaqsw' ) ) . '\');">';
		echo esc_html__( 'Apply to all FAQ Groups', 'ufaqsw' );
		echo '</button>';
		echo '</form>';

		if ( isset( $_GET['ufaqsw_applied_all'] ) && '1' === $_GET['ufaqsw_applied_all'] ) { // phpcs:ignore
			echo '<div class="notice notice-success is-dismissible" style="margin:8px 0 0;"><p>' . esc_html__( 'Appearance applied to all FAQ Groups.', 'ufaqsw' ) . '</p></div>';
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

		$new_columns['cb']                    = '<input type="checkbox" />';
		$new_columns['title']                 = __( 'Title', 'ufaqsw' );
		$new_columns['ufaqsw_template_chip']  = __( 'Template', 'ufaqsw' );
		$new_columns['ufaqsw_faq_groups']     = __( 'Connected FAQ Groups', 'ufaqsw' );
		$new_columns['date']                  = __( 'Date', 'ufaqsw' );

		return $new_columns;
	}

	/**
	 * Outputs custom column content for the 'ufaqsw_appearance' post type in the admin list table.
	 *
	 * @param string $column_name The name of the column to display content for.
	 * @param int    $post_ID     The ID of the current post.
	 */
	public function columns_content( $column_name, $post_ID ) {
		if ( 'ufaqsw_template_chip' === $column_name ) {
			$template = get_post_meta( $post_ID, 'ufaqsw_template', true );
			if ( ! $template ) {
				$template = 'default';
			}

			$labels = array(
				'default'   => __( 'Default', 'ufaqsw' ),
				'style-1'   => __( 'Style 1', 'ufaqsw' ),
				'style-2'   => __( 'Style 2', 'ufaqsw' ),
				'universal' => __( 'Universal', 'ufaqsw' ),
			);
			$css_class_map = array(
				'default'   => 'chip-default',
				'style-1'   => 'chip-style-1',
				'style-2'   => 'chip-style-2',
				'universal' => 'chip-universal',
			);
			$label     = $labels[ $template ] ?? $template;
			$css_class = $css_class_map[ $template ] ?? 'chip-default';

			echo '<span class="ufaqsw-template-chip ' . esc_attr( $css_class ) . '">' . esc_html( $label ) . '</span>';
		}

		if ( 'ufaqsw_faq_groups' === $column_name ) {
			$faq_groups = ufaqsw_get_group_ids_by_appearance( $post_ID );
			$count      = count( $faq_groups );

			if ( $count > 0 ) {
				$names = array_map( 'get_the_title', array_slice( $faq_groups, 0, 3 ) );
				foreach ( array_slice( $faq_groups, 0, 3 ) as $group ) {
					echo '<a href="' . esc_url( get_edit_post_link( $group ) ) . '">' . esc_html( get_the_title( $group ) ) . '</a><br>';
				}
				if ( $count > 3 ) {
					echo '<span class="ufaqsw-groups-count">+' . esc_html( $count - 3 ) . ' ' . esc_html__( 'more', 'ufaqsw' ) . '</span>';
				}
			} else {
				echo '<span style="color:#72777c;font-size:12px;">' . esc_html__( 'None', 'ufaqsw' ) . '</span>';
			}
		}
	}
}
