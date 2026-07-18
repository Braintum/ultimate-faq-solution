<?php
/**
 * GettingStarted.php
 *
 * Handles the Getting Started page, onboarding banners, and empty states
 * for both the FAQ Groups and FAQ Appearances admin sections.
 *
 * @package UltimateFAQSolution
 */

namespace Mahedi\UltimateFaqSolution;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class GettingStarted
 *
 * Provides first-run guidance: a visual 3-step workflow page, dismissible
 * "how it works" banners on the list tables, and empty-state screens when
 * no posts exist yet.
 */
class GettingStarted {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_submenu' ) );
		add_action( 'admin_init', array( $this, 'maybe_redirect' ) );
		add_action( 'wp_ajax_ufaqsw_dismiss_banner', array( $this, 'ajax_dismiss_banner' ) );
		add_action( 'admin_notices', array( $this, 'render_banners' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'admin_footer', array( $this, 'render_empty_states' ) );
		add_filter( 'plugin_row_meta', array( $this, 'add_plugin_row_meta' ), 10, 2 );
	}

	/**
	 * Add "Getting Started" submenu page.
	 */
	public function add_submenu() {
		add_submenu_page(
			'edit.php?post_type=ufaqsw',
			__( 'Getting Started — Ultimate FAQ Solution', 'ufaqsw' ),
			__( 'Getting Started', 'ufaqsw' ),
			'manage_options',
			'ufaqsw-getting-started',
			array( $this, 'render_getting_started_page' )
		);
	}

	/**
	 * Redirect to Getting Started page on first activation.
	 */
	public function maybe_redirect() {
		if ( ! get_transient( 'ufaqsw_activation_redirect' ) ) {
			return;
		}
		delete_transient( 'ufaqsw_activation_redirect' );
		if ( is_multisite() || ! current_user_can( 'manage_options' ) ) {
			return;
		}
		wp_safe_redirect( admin_url( 'edit.php?post_type=ufaqsw&page=ufaqsw-getting-started' ) );
		exit;
	}

	/**
	 * AJAX handler: persist banner dismiss per user.
	 */
	public function ajax_dismiss_banner() {
		check_ajax_referer( 'ufaqsw_dismiss_banner', 'nonce' );
		$banner = sanitize_key( isset( $_POST['banner'] ) ? $_POST['banner'] : '' );
		if ( $banner ) {
			update_user_meta( get_current_user_id(), 'ufaqsw_banner_dismissed_' . $banner, 1 );
		}
		wp_send_json_success();
	}

	/**
	 * Enqueue admin general styles on all plugin admin screens.
	 */
	public function enqueue_styles() {
		$screen = get_current_screen();
		if ( ! $screen ) {
			return;
		}

		$plugin_screens = array(
			'edit-ufaqsw',
			'ufaqsw',
			'edit-ufaqsw_appearance',
			'ufaqsw_appearance',
			'ufaqsw_page_ufaqsw-getting-started',
		);

		if ( ! in_array( $screen->id, $plugin_screens, true ) ) {
			return;
		}

		wp_enqueue_style(
			'ufaqsw-admin-general',
			UFAQSW__PLUGIN_URL . 'assets/css/ufaqsw-admin-general.css',
			array(),
			UFAQSW_VERSION
		);

		// Inline JS for banner dismiss (piggybacks on jQuery which WP always loads in admin).
		$nonce = wp_create_nonce( 'ufaqsw_dismiss_banner' );
		$js    = "
		jQuery(function($){
			$(document).on('click','.ufaqsw-banner-dismiss',function(){
				var banner=$(this).data('banner');
				$(this).closest('.ufaqsw-how-it-works-banner').slideUp(200);
				$.post(ajaxurl,{action:'ufaqsw_dismiss_banner',banner:banner,nonce:'" . esc_js( $nonce ) . "'});
			});
		});
		";
		wp_add_inline_script( 'jquery', $js );
	}

	// -----------------------------------------------------------------------
	// Banners
	// -----------------------------------------------------------------------

	/**
	 * Render "How it works" banners on the relevant list-table screens.
	 */
	public function render_banners() {
		$screen  = get_current_screen();
		if ( ! $screen ) {
			return;
		}
		$user_id = get_current_user_id();

		if ( 'edit-ufaqsw' === $screen->id ) {
			$this->render_faq_groups_banner( $user_id );
			$this->render_faq_assistant_banner( $user_id );
		}

		if ( 'edit-ufaqsw_appearance' === $screen->id ) {
			$this->render_appearances_banner( $user_id );
		}
	}

	/**
	 * "How it works" banner for the FAQ Groups list screen.
	 *
	 * @param int $user_id Current user ID.
	 */
	private function render_faq_groups_banner( $user_id ) {
		if ( get_user_meta( $user_id, 'ufaqsw_banner_dismissed_faq_groups', true ) ) {
			return;
		}
		?>
		<div class="ufaqsw-how-it-works-banner">
			<div class="ufaqsw-banner-content">
				<div class="ufaqsw-banner-icon">
					<span class="dashicons dashicons-editor-help"></span>
				</div>
				<div class="ufaqsw-banner-text">
					<strong><?php esc_html_e( 'What is a FAQ Group?', 'ufaqsw' ); ?></strong>
					<p><?php esc_html_e( 'A FAQ Group holds your Q&A content. Create a group, add questions and answers, then embed it on any page using the shortcode from the table or the Gutenberg block. Pair each group with an Appearance to control how it looks.', 'ufaqsw' ); ?></p>
				</div>
				<div class="ufaqsw-banner-actions">
					<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=ufaqsw&page=ufaqsw-getting-started' ) ); ?>" class="button button-secondary">
						<?php esc_html_e( 'View Guide', 'ufaqsw' ); ?>
					</a>
					<button type="button" class="ufaqsw-banner-dismiss" data-banner="faq_groups" aria-label="<?php esc_attr_e( 'Dismiss this notice', 'ufaqsw' ); ?>">
						<span class="dashicons dashicons-no-alt"></span>
					</button>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * "How it works" banner for the FAQ Appearances list screen.
	 *
	 * @param int $user_id Current user ID.
	 */
	private function render_appearances_banner( $user_id ) {
		if ( get_user_meta( $user_id, 'ufaqsw_banner_dismissed_appearances', true ) ) {
			return;
		}
		?>
		<div class="ufaqsw-how-it-works-banner ufaqsw-banner-design">
			<div class="ufaqsw-banner-content">
				<div class="ufaqsw-banner-icon">
					<span class="dashicons dashicons-admin-customizer"></span>
				</div>
				<div class="ufaqsw-banner-text">
					<strong><?php esc_html_e( 'What is an Appearance?', 'ufaqsw' ); ?></strong>
					<p><?php esc_html_e( 'An Appearance is a reusable design preset — choose a template, set colors, fonts, and icons. One Appearance can be shared across multiple FAQ Groups. Any changes here update everywhere it is applied.', 'ufaqsw' ); ?></p>
				</div>
				<div class="ufaqsw-banner-actions">
					<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=ufaqsw&page=ufaqsw-getting-started' ) ); ?>" class="button button-secondary">
						<?php esc_html_e( 'View Guide', 'ufaqsw' ); ?>
					</a>
					<button type="button" class="ufaqsw-banner-dismiss" data-banner="appearances" aria-label="<?php esc_attr_e( 'Dismiss this notice', 'ufaqsw' ); ?>">
						<span class="dashicons dashicons-no-alt"></span>
					</button>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * FAQ Assistant spotlight banner — shown on the FAQ Groups screen when the
	 * assistant has not been enabled yet. Dismissed per-user.
	 *
	 * @param int $user_id Current user ID.
	 */
	private function render_faq_assistant_banner( $user_id ) {
		if ( get_user_meta( $user_id, 'ufaqsw_banner_dismissed_faq_assistant', true ) ) {
			return;
		}
		if ( cmb2_get_option( 'ufaqsw_chatbot_settings', 'enable_chatbot' ) ) {
			return; // Already enabled — no need to promote it.
		}
		$settings_url = admin_url( 'edit.php?post_type=ufaqsw&page=ufaqsw_settings_page' );
		?>
		<div class="ufaqsw-how-it-works-banner ufaqsw-banner-assistant">
			<div class="ufaqsw-banner-content">
				<div class="ufaqsw-banner-icon">
					<span class="dashicons dashicons-format-chat"></span>
				</div>
				<div class="ufaqsw-banner-text">
					<strong><?php esc_html_e( '✨ New: FAQ Assistant — a floating chatbot for your visitors', 'ufaqsw' ); ?></strong>
					<p><?php esc_html_e( 'Let visitors search and browse your FAQs without leaving the page. The FAQ Assistant adds a floating chat button to any page — no coding needed. It supports live search, "Ask a Question" submissions, and helpful/not-helpful ratings.', 'ufaqsw' ); ?></p>
				</div>
				<div class="ufaqsw-banner-actions">
					<a href="<?php echo esc_url( $settings_url ); ?>" class="button button-primary">
						<?php esc_html_e( 'Enable FAQ Assistant', 'ufaqsw' ); ?>
					</a>
					<button type="button" class="ufaqsw-banner-dismiss" data-banner="faq_assistant" aria-label="<?php esc_attr_e( 'Dismiss this notice', 'ufaqsw' ); ?>">
						<span class="dashicons dashicons-no-alt"></span>
					</button>
				</div>
			</div>
		</div>
		<?php
	}

	// -----------------------------------------------------------------------
	// Empty States (rendered via admin_footer so they can hide the WP table)
	// -----------------------------------------------------------------------

	/**
	 * Inject empty-state screens when a CPT has no posts.
	 */
	public function render_empty_states() {
		$screen = get_current_screen();
		if ( ! $screen ) {
			return;
		}

		if ( 'edit-ufaqsw' === $screen->id ) {
			$this->render_faq_groups_empty_state();
		}

		if ( 'edit-ufaqsw_appearance' === $screen->id ) {
			$this->render_appearances_empty_state();
		}
	}

	/**
	 * Empty state for the FAQ Groups list when no groups exist.
	 */
	private function render_faq_groups_empty_state() {
		// Skip when a search or status filter is active — the user wants the native "not found" feedback.
		if ( ! empty( $_GET['s'] ) || ( ! empty( $_GET['post_status'] ) && 'all' !== $_GET['post_status'] ) ) { // phpcs:ignore
			return;
		}

		$count = wp_count_posts( 'ufaqsw' );
		$total = ( $count->publish ?? 0 ) + ( $count->draft ?? 0 ) + ( $count->pending ?? 0 ) + ( $count->private ?? 0 );
		if ( $total > 0 ) {
			return;
		}
		?>
		<style>.wp-list-table,.tablenav{display:none!important}</style>
		<div class="ufaqsw-empty-state">
			<div class="ufaqsw-empty-state-inner">
				<div class="ufaqsw-empty-state-icon">
					<span class="dashicons dashicons-editor-help"></span>
				</div>
				<h2><?php esc_html_e( 'No FAQ Groups yet', 'ufaqsw' ); ?></h2>
				<p><?php esc_html_e( 'A FAQ Group is a set of questions and answers. Create your first group, add your content, and embed it on any page with a shortcode or Gutenberg block.', 'ufaqsw' ); ?></p>
				<div class="ufaqsw-empty-state-actions">
					<a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=ufaqsw' ) ); ?>" class="button button-primary button-hero">
						<?php esc_html_e( 'Add Your First FAQ Group', 'ufaqsw' ); ?>
					</a>
					<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=ufaqsw&page=ufaqsw-getting-started' ) ); ?>" class="button button-secondary button-hero">
						<?php esc_html_e( 'View Getting Started Guide', 'ufaqsw' ); ?>
					</a>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Empty state for the FAQ Appearances list when no appearances exist.
	 */
	private function render_appearances_empty_state() {
		if ( ! empty( $_GET['s'] ) || ( ! empty( $_GET['post_status'] ) && 'all' !== $_GET['post_status'] ) ) { // phpcs:ignore
			return;
		}

		$count = wp_count_posts( 'ufaqsw_appearance' );
		$total = ( $count->publish ?? 0 ) + ( $count->draft ?? 0 ) + ( $count->pending ?? 0 ) + ( $count->private ?? 0 );
		if ( $total > 0 ) {
			return;
		}
		?>
		<style>.wp-list-table,.tablenav{display:none!important}</style>
		<div class="ufaqsw-empty-state">
			<div class="ufaqsw-empty-state-inner">
				<div class="ufaqsw-empty-state-icon">
					<span class="dashicons dashicons-admin-customizer"></span>
				</div>
				<h2><?php esc_html_e( 'No Appearances yet', 'ufaqsw' ); ?></h2>
				<p><?php esc_html_e( 'An Appearance controls how your FAQs look — pick a template and customise colors, fonts, and icons. Create one and apply it to any FAQ Group.', 'ufaqsw' ); ?></p>
				<div class="ufaqsw-empty-state-actions">
					<a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=ufaqsw_appearance' ) ); ?>" class="button button-primary button-hero">
						<?php esc_html_e( 'Create Your First Appearance', 'ufaqsw' ); ?>
					</a>
					<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=ufaqsw&page=ufaqsw-getting-started' ) ); ?>" class="button button-secondary button-hero">
						<?php esc_html_e( 'View Getting Started Guide', 'ufaqsw' ); ?>
					</a>
				</div>
			</div>
		</div>
		<?php
	}

	// -----------------------------------------------------------------------
	// Getting Started Page
	// -----------------------------------------------------------------------

	/**
	 * Render the Getting Started admin page.
	 */
	public function render_getting_started_page() {
		$new_group_url      = admin_url( 'post-new.php?post_type=ufaqsw' );
		$new_appearance_url = admin_url( 'post-new.php?post_type=ufaqsw_appearance' );
		$docs_url           = 'https://www.braintum.com/docs/ultimate-faq-solution/';
		?>
		<div class="wrap ufaqsw-getting-started">

			<div class="ufaqsw-gs-header">
				<h1><?php esc_html_e( 'Getting Started with Ultimate FAQ Solution', 'ufaqsw' ); ?></h1>
				<p><?php esc_html_e( 'Three steps to get your FAQs live on your site.', 'ufaqsw' ); ?></p>
			</div>

			<!-- 3-step workflow -->
			<div class="ufaqsw-gs-steps">

				<div class="ufaqsw-gs-step">
					<div class="ufaqsw-gs-step-number">1</div>
					<div class="ufaqsw-gs-step-icon">
						<span class="dashicons dashicons-editor-help"></span>
					</div>
					<h3><?php esc_html_e( 'Add Your Content', 'ufaqsw' ); ?></h3>
					<p><?php esc_html_e( 'Create a FAQ Group and add your questions and answers. You can have multiple groups for different topics or page sections.', 'ufaqsw' ); ?></p>
					<a href="<?php echo esc_url( $new_group_url ); ?>" class="button button-primary">
						<?php esc_html_e( 'Create FAQ Group', 'ufaqsw' ); ?>
					</a>
					<div class="ufaqsw-gs-step-hint">
						<span class="dashicons dashicons-lightbulb"></span>
						<?php esc_html_e( 'Each group gets its own shortcode so you can embed them independently.', 'ufaqsw' ); ?>
					</div>
				</div>

				<div class="ufaqsw-gs-arrow">
					<span class="dashicons dashicons-arrow-right-alt"></span>
				</div>

				<div class="ufaqsw-gs-step">
					<div class="ufaqsw-gs-step-number">2</div>
					<div class="ufaqsw-gs-step-icon">
						<span class="dashicons dashicons-admin-customizer"></span>
					</div>
					<h3><?php esc_html_e( 'Choose a Design', 'ufaqsw' ); ?></h3>
					<p><?php esc_html_e( 'Pick from 3 templates and customise colors, fonts, and icons using the live Appearance builder.', 'ufaqsw' ); ?></p>
					<a href="<?php echo esc_url( $new_appearance_url ); ?>" class="button button-secondary">
						<?php esc_html_e( 'Customise Appearance', 'ufaqsw' ); ?>
					</a>
					<div class="ufaqsw-gs-step-hint">
						<span class="dashicons dashicons-lightbulb"></span>
						<?php esc_html_e( 'One Appearance can be shared across multiple FAQ Groups.', 'ufaqsw' ); ?>
					</div>
				</div>

				<div class="ufaqsw-gs-arrow">
					<span class="dashicons dashicons-arrow-right-alt"></span>
				</div>

				<div class="ufaqsw-gs-step">
					<div class="ufaqsw-gs-step-number">3</div>
					<div class="ufaqsw-gs-step-icon">
						<span class="dashicons dashicons-welcome-view-site"></span>
					</div>
					<h3><?php esc_html_e( 'Display on Your Site', 'ufaqsw' ); ?></h3>
					<p><?php esc_html_e( 'Copy the shortcode from your FAQ Group and paste it into any page or post — or use the Gutenberg block.', 'ufaqsw' ); ?></p>
					<a href="<?php echo esc_url( $docs_url ); ?>" class="button button-secondary" target="_blank" rel="noopener noreferrer">
						<?php esc_html_e( 'View Documentation', 'ufaqsw' ); ?>
					</a>
					<div class="ufaqsw-gs-step-hint">
						<span class="dashicons dashicons-lightbulb"></span>
						<?php esc_html_e( 'The shortcode looks like: [ufaqsw id="123"]', 'ufaqsw' ); ?>
					</div>
				</div>

			</div><!-- /.ufaqsw-gs-steps -->

			<!-- Explainer: the two sections -->
			<div class="ufaqsw-gs-explainer">
				<h2><?php esc_html_e( 'Understanding the Two Sections', 'ufaqsw' ); ?></h2>
				<div class="ufaqsw-gs-explainer-grid">

					<div class="ufaqsw-gs-explainer-card ufaqsw-card-data">
						<div class="ufaqsw-gs-explainer-card-header">
							<span class="dashicons dashicons-database"></span>
							<h3><?php esc_html_e( 'FAQ Groups — Content', 'ufaqsw' ); ?></h3>
						</div>
						<p><?php esc_html_e( 'This is where you manage your Q&A content. Think of each group as a folder of related FAQs. Create one per topic, product, or page section.', 'ufaqsw' ); ?></p>
						<ul>
							<li><?php esc_html_e( 'Add, edit, and drag-to-reorder Q&A pairs', 'ufaqsw' ); ?></li>
							<li><?php esc_html_e( 'Each group has a unique shortcode for embedding', 'ufaqsw' ); ?></li>
							<li><?php esc_html_e( 'Link each group to an Appearance for styling', 'ufaqsw' ); ?></li>
						</ul>
						<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=ufaqsw' ) ); ?>" class="button button-secondary">
							<?php esc_html_e( 'View All FAQ Groups', 'ufaqsw' ); ?>
						</a>
					</div>

					<div class="ufaqsw-gs-explainer-card ufaqsw-card-design">
						<div class="ufaqsw-gs-explainer-card-header">
							<span class="dashicons dashicons-admin-customizer"></span>
							<h3><?php esc_html_e( 'FAQ Appearances — Design', 'ufaqsw' ); ?></h3>
						</div>
						<p><?php esc_html_e( 'This is where you control how your FAQs look. An Appearance is a reusable design preset — create one and apply it to as many groups as you like.', 'ufaqsw' ); ?></p>
						<ul>
							<li><?php esc_html_e( 'Choose from 3 pre-built templates', 'ufaqsw' ); ?></li>
							<li><?php esc_html_e( 'Customise colors, fonts, and icons live', 'ufaqsw' ); ?></li>
							<li><?php esc_html_e( 'One Appearance, multiple FAQ Groups', 'ufaqsw' ); ?></li>
						</ul>
						<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=ufaqsw_appearance' ) ); ?>" class="button button-secondary">
							<?php esc_html_e( 'View All Appearances', 'ufaqsw' ); ?>
						</a>
					</div>

				</div>
			</div><!-- /.ufaqsw-gs-explainer -->

			<!-- FAQ Assistant spotlight -->
			<div class="ufaqsw-gs-explainer ufaqsw-gs-assistant-spotlight">
				<div class="ufaqsw-gs-assistant-spotlight-inner">
					<div class="ufaqsw-gs-assistant-spotlight-icon">
						<span class="dashicons dashicons-format-chat"></span>
					</div>
					<div class="ufaqsw-gs-assistant-spotlight-body">
						<h2><?php esc_html_e( '✨ FAQ Assistant — floating chatbot', 'ufaqsw' ); ?></h2>
						<p><?php esc_html_e( 'The FAQ Assistant is a floating chat button that lets visitors search and browse your FAQs right on the page — without navigating away. Enable it once and it appears automatically on the pages you choose.', 'ufaqsw' ); ?></p>
						<ul class="ufaqsw-gs-assistant-features">
							<li><span class="dashicons dashicons-search"></span> <?php esc_html_e( 'Live search across all your FAQ groups', 'ufaqsw' ); ?></li>
							<li><span class="dashicons dashicons-email-alt"></span> <?php esc_html_e( '"Ask a Question" form — submissions land in your inbox', 'ufaqsw' ); ?></li>
							<li><span class="dashicons dashicons-thumbs-up"></span> <?php esc_html_e( '"Was this helpful?" ratings with an analytics dashboard', 'ufaqsw' ); ?></li>
							<li><span class="dashicons dashicons-admin-customizer"></span> <?php esc_html_e( 'Fully customisable colors, labels, and behaviour', 'ufaqsw' ); ?></li>
						</ul>
						<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=ufaqsw&page=ufaqsw_settings_page' ) ); ?>" class="button button-primary">
							<?php esc_html_e( 'Set Up FAQ Assistant', 'ufaqsw' ); ?>
						</a>
					</div>
				</div>
			</div><!-- /.ufaqsw-gs-assistant-spotlight -->

		</div><!-- /.ufaqsw-getting-started -->
		<?php
	}

	/**
	 * Add a "FAQ Assistant" quick link in the plugin row on the Plugins page.
	 *
	 * @param string[] $links Existing meta links.
	 * @param string   $file  Plugin basename.
	 * @return string[]
	 */
	public function add_plugin_row_meta( $links, $file ) {
		if ( $file !== UFAQSW_BASE ) {
			return $links;
		}
		$links[] = sprintf(
			'<a href="%s">%s</a>',
			esc_url( admin_url( 'edit.php?post_type=ufaqsw&page=ufaqsw_settings_page' ) ),
			esc_html__( 'FAQ Assistant', 'ufaqsw' )
		);
		return $links;
	}
}
