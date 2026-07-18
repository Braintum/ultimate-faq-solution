<?php
/**
 * FAQGroupActions.php
 *
 * Handles actions related to FAQ groups, including meta box display and saving appearance settings.
 *
 * @package UltimateFAQSolution
 */

namespace Mahedi\UltimateFaqSolution;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class FAQGroupActions
 *
 * Handles actions related to FAQ groups, such as adding meta boxes for appearance settings,
 * rendering the redesigned appearance selector, the shortcode helper, and post-save nudges.
 *
 * @package UltimateFAQSolution
 */
class FAQGroupActions {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_meta' ) );
		add_action( 'admin_notices', array( $this, 'post_save_nudge' ) );
	}

	/**
	 * Register meta boxes for the FAQ Group edit screen.
	 */
	public function add_meta_boxes() {
		add_meta_box(
			'faq_group_appearance',
			esc_html__( 'FAQ Appearance', 'ufaqsw' ),
			array( $this, 'faq_group_appearance_metabox' ),
			'ufaqsw',
			'side',
			'default'
		);

		add_meta_box(
			'ufaqsw_faq_group_shortcode',
			esc_html__( 'FAQ Shortcode', 'ufaqsw' ),
			array( $this, 'render_shortcode_metabox' ),
			'ufaqsw',
			'side',
			'default'
		);
	}

	/**
	 * Renders the redesigned Appearance selector meta box.
	 *
	 * Shows a design preview swatch for the selected appearance, a warning when
	 * only the default appearance is in use, quick edit/create links, and the
	 * standard dropdown.
	 *
	 * @param \WP_Post $post The current post object.
	 */
	public function faq_group_appearance_metabox( $post ) {
		$explicit_id = (int) get_post_meta( $post->ID, 'linked_faq_appearance_id', true );
		$default_id  = (int) get_option( 'faq_default_appearance_id', 0 );
		$has_explicit = $explicit_id && get_post( $explicit_id );

		$selected_id = $has_explicit ? $explicit_id : $default_id;

		$appearances = get_posts(
			array(
				'post_type'   => 'ufaqsw_appearance',
				'post_status' => 'publish',
				'numberposts' => -1,
				'orderby'     => 'title',
				'order'       => 'ASC',
			)
		);

		wp_nonce_field( 'save_faq_group_appearance', 'faq_group_appearance_nonce' );

		echo '<div class="ufaqsw-appearance-meta-box">';

		// Preview swatch for selected appearance.
		if ( $selected_id && get_post( $selected_id ) ) {
			$template      = get_post_meta( $selected_id, 'ufaqsw_template', true ) ?: 'default';
			$border_color  = get_post_meta( $selected_id, 'ufaqsw_border_color', true ) ?: '#e0e0e0';
			$q_bg          = get_post_meta( $selected_id, 'ufaqsw_question_background_color', true ) ?: '#f6f7f7';
			$a_bg          = get_post_meta( $selected_id, 'ufaqsw_answer_background_color', true ) ?: '#ffffff';

			$template_labels = array(
				'default' => __( 'Default template', 'ufaqsw' ),
				'style-1' => __( 'Style 1 template', 'ufaqsw' ),
				'style-2' => __( 'Style 2 template', 'ufaqsw' ),
			);
			$template_label = $template_labels[ $template ] ?? $template;

			echo '<div class="ufaqsw-appearance-preview">';
			echo '<div class="ufaqsw-appearance-swatch">';
			echo '<div class="ufaqsw-swatch-bar" style="background:' . esc_attr( $border_color ) . ';"></div>';
			echo '<div class="ufaqsw-swatch-bar" style="background:' . esc_attr( $q_bg ) . ';"></div>';
			echo '<div class="ufaqsw-swatch-bar" style="background:' . esc_attr( $a_bg ) . ';"></div>';
			echo '</div>';
			echo '<div class="ufaqsw-appearance-preview-info">';
			echo '<div class="ufaqsw-appearance-preview-name">' . esc_html( get_the_title( $selected_id ) ) . '</div>';
			echo '<div class="ufaqsw-appearance-preview-template">' . esc_html( $template_label ) . '</div>';
			echo '</div>';
			echo '</div>';
		}

		// Warning when falling back to default (no explicit selection).
		if ( ! $has_explicit ) {
			echo '<div class="ufaqsw-appearance-no-link">';
			echo '<span class="dashicons dashicons-info"></span>';
			echo esc_html__( 'Using the default appearance. Select a specific one below to customise this group independently.', 'ufaqsw' );
			echo '</div>';
		}

		// Dropdown.
		echo '<label for="faq_appearance_select">' . esc_html__( 'Select appearance:', 'ufaqsw' ) . '</label>';
		echo '<select name="faq_appearance_select" id="faq_appearance_select">';

		// "Use default" option first.
		$use_default_selected = ! $has_explicit ? 'selected' : '';
		echo '<option value="0" ' . esc_attr( $use_default_selected ) . '>' . esc_html__( '— Use default appearance —', 'ufaqsw' ) . '</option>';

		foreach ( $appearances as $appearance ) {
			$selected = ( $appearance->ID === $selected_id && $has_explicit ) ? 'selected' : '';
			echo '<option value="' . esc_attr( $appearance->ID ) . '" ' . esc_attr( $selected ) . '>' . esc_html( $appearance->post_title ) . '</option>';
		}

		echo '</select>';

		// Quick action links.
		echo '<div class="ufaqsw-appearance-meta-links">';
		if ( $selected_id && get_post( $selected_id ) ) {
			echo '<a href="' . esc_url( get_edit_post_link( $selected_id ) ) . '" target="_blank">' . esc_html__( 'Edit appearance', 'ufaqsw' ) . '</a>';
		}
		echo '<a href="' . esc_url( admin_url( 'post-new.php?post_type=ufaqsw_appearance' ) ) . '" target="_blank">' . esc_html__( 'Create new appearance', 'ufaqsw' ) . '</a>';
		echo '</div>';

		echo '</div><!-- .ufaqsw-appearance-meta-box -->';
	}

	/**
	 * Renders the FAQ Shortcode meta box.
	 *
	 * @param \WP_Post $post The current post object.
	 */
	public function render_shortcode_metabox( $post ) {
		$slug = (int) $post->ID;
		if ( ! $slug ) {
			return;
		}

		echo '<p>' . esc_html__( 'Paste this shortcode into any page or post to display this FAQ group:', 'ufaqsw' ) . '</p>';
		echo '<input class="ufaqsw_admin_faq_shorcode_copy" type="text" readonly value="[ufaqsw id=' . esc_attr( $slug ) . ']" style="width:100%;font-family:monospace;">';
		echo '<p style="margin-top:5px;"><small>' . esc_html__( 'Or use the Gutenberg block — search for "Ultimate FAQ".', 'ufaqsw' ) . '</small></p>';
	}

	/**
	 * Saves the selected appearance for a FAQ group.
	 *
	 * @param int $post_id The ID of the post being saved.
	 */
	public function save_meta( $post_id ) {
		if ( ! isset( $_POST['faq_group_appearance_nonce'] ) ) {
			return;
		}
		if ( ! wp_verify_nonce( $_POST['faq_group_appearance_nonce'], 'save_faq_group_appearance' ) ) { // phpcs:ignore
			return;
		}
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if ( ! isset( $_POST['post_type'] ) || 'ufaqsw' !== $_POST['post_type'] ) {
			return;
		}
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		if ( isset( $_POST['faq_appearance_select'] ) ) {
			$appearance_id = intval( $_POST['faq_appearance_select'] );
			// Store 0 to mean "use default"; helpers will fall back to the default option ID.
			update_post_meta( $post_id, 'linked_faq_appearance_id', $appearance_id );
		}
	}

	/**
	 * Show a post-save nudge reminding users to embed the shortcode after publishing/updating.
	 *
	 * Only shows on the FAQ Group edit screen directly after a save (message=1 or message=6).
	 */
	public function post_save_nudge() {
		$screen = get_current_screen();
		if ( ! $screen || 'ufaqsw' !== $screen->post_type || 'post' !== $screen->base ) {
			return;
		}

		// WordPress sets ?message=1 on first publish, 6 on update-as-published.
		$message = isset( $_GET['message'] ) ? (int) $_GET['message'] : 0; // phpcs:ignore
		if ( ! in_array( $message, array( 1, 4, 6 ), true ) ) {
			return;
		}

		$post_id = isset( $_GET['post'] ) ? (int) $_GET['post'] : 0; // phpcs:ignore
		if ( ! $post_id ) {
			return;
		}

		// Check embed status to tailor the message.
		$embed_map   = ufaqsw_get_embed_status_map();
		$embed_count = $embed_map[ $post_id ] ?? 0;
		$shortcode   = '[ufaqsw id=' . $post_id . ']';
		?>
		<div class="ufaqsw-postsave-nudge">
			<span class="dashicons dashicons-yes-alt"></span>
			<?php if ( $embed_count > 0 ) : ?>
				<span>
					<strong><?php esc_html_e( 'FAQ Group saved.', 'ufaqsw' ); ?></strong>
					<?php
					echo esc_html(
						sprintf(
							/* translators: %d: number of pages this FAQ group is embedded on */
							_n( 'Displayed on %d page.', 'Displayed on %d pages.', $embed_count, 'ufaqsw' ),
							$embed_count
						)
					);
					?>
				</span>
			<?php else : ?>
				<span>
					<strong><?php esc_html_e( 'FAQ Group saved.', 'ufaqsw' ); ?></strong>
					<?php esc_html_e( 'It is not embedded anywhere yet — add it to a page to make it visible.', 'ufaqsw' ); ?>
				</span>
				<div class="ufaqsw-nudge-actions">
					<input
						type="text"
						readonly
						value="<?php echo esc_attr( $shortcode ); ?>"
						class="ufaqsw_admin_faq_shorcode_copy"
						style="font-family:monospace;font-size:12px;width:180px;"
						title="<?php esc_attr_e( 'Click to copy shortcode', 'ufaqsw' ); ?>"
					>
					<a href="<?php echo esc_url( admin_url( 'post-new.php' ) ); ?>" class="button button-secondary button-small">
						<?php esc_html_e( 'Add to a page', 'ufaqsw' ); ?>
					</a>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}
}
