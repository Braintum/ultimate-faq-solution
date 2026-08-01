<?php
/**
 * Chatbot class
 *
 * @package UltimateFaqSolution
 * @author  Mahedi
 * @license GPL-2.0-or-later
 */

namespace Mahedi\UltimateFaqSolution;

/**
 * Class Chatbot
 *
 * Manages the FAQ Assistant: script enqueueing, data localisation, AJAX handlers.
 */
class Chatbot {

	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_react_chatbot_script' ) );
		add_action( 'wp_footer',          array( $this, 'bot_integration' ) );

		// Public AJAX actions (logged-in and guest users).
		foreach ( array( 'ufaqsw_get_faqs', 'ufaqsw_submit_question', 'ufaqsw_faq_feedback' ) as $action ) {
			add_action( 'wp_ajax_' . $action,        array( $this, 'dispatch_' . $action ) );
			add_action( 'wp_ajax_nopriv_' . $action, array( $this, 'dispatch_' . $action ) );
		}
	}

	// -------------------------------------------------------------------------
	// AJAX dispatcher shims (keeps action names tidy)
	// -------------------------------------------------------------------------

	public function dispatch_ufaqsw_get_faqs()        { $this->handle_get_faqs(); }
	public function dispatch_ufaqsw_submit_question()  { $this->handle_submit_question(); }
	public function dispatch_ufaqsw_faq_feedback()     { $this->handle_faq_feedback(); }

	// -------------------------------------------------------------------------
	// Assistant visibility check
	// -------------------------------------------------------------------------

	/**
	 * Returns true if the assistant should be shown on the current page.
	 */
	public function is_assistant_enabled() {
		global $ufaqsw_preview_data;

		$enable_chatbot = cmb2_get_option( 'ufaqsw_chatbot_settings', 'enable_chatbot' );
		if ( ! $enable_chatbot ) {
			return false;
		}

		$display_on = cmb2_get_option( 'ufaqsw_chatbot_settings', 'display_on' );
		if ( 'all' !== $display_on ) {
			$specific_pages = cmb2_get_option( 'ufaqsw_chatbot_settings', 'display_on_pages' );
			if ( ! is_array( $specific_pages ) || empty( $specific_pages ) || ! in_array( (int) get_queried_object_id(), array_map( 'intval', $specific_pages ), true ) ) {
				return false;
			}
		}

		if ( isset( $ufaqsw_preview_data ) && ! empty( $ufaqsw_preview_data ) ) {
			return false;
		}

		return true;
	}

	// -------------------------------------------------------------------------
	// Script / style enqueueing
	// -------------------------------------------------------------------------

	public function enqueue_react_chatbot_script() {
		if ( ! $this->is_assistant_enabled() ) {
			return;
		}

		wp_enqueue_script(
			'react-chatbot',
			UFAQSW_ASSETS_URL . 'dist/bundle.js',
			array(),
			fileatime( UFAQSW__PLUGIN_DIR . 'assets/dist/bundle.js' ),
			true
		);

		$floating_button_icon_url = cmb2_get_option( 'ufaqsw_chatbot_settings', 'floating_button_icon' );
		if ( ! $floating_button_icon_url ) {
			$floating_button_icon_url = UFAQSW_ASSETS_URL . 'images/faq-bot-icon.svg';
		}

		// Helper: fetch a string setting or return the default.
		$opt = function ( $key, $default = '' ) {
			$val = cmb2_get_option( 'ufaqsw_chatbot_settings', $key );
			return ( $val !== false && $val !== null && $val !== '' ) ? $val : $default;
		};

		// Helper: fetch a boolean (checkbox) setting.
		$bool = function ( $key ) {
			return (bool) cmb2_get_option( 'ufaqsw_chatbot_settings', $key );
		};

		wp_localize_script(
			'react-chatbot',
			'chatbotData',
			array(
				// Core
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'chatbot_nonce' ),

				// Floating button
				'floating_button_icon'  => $floating_button_icon_url,
				'floating_button_title' => $opt( 'floating_button_title' ),
				'pulse_enabled'         => $bool( 'pulse_enabled' ),
				'badge_enabled'         => $bool( 'badge_enabled' ),

				// Header / appearance
				'header_background_color' => $opt( 'header_background_color', '#1a185e' ),
				'header_text_color'       => $opt( 'header_text_color', '#fff' ),
				'loading_animation_color' => $opt( 'loading_animation_color', '#222' ),

				// Window labels
				'assistant_window_headline'   => $opt( 'assistant_window_headline', __( 'Welcome to our Help Center!', 'ufaqsw' ) ),
				'assistant_window_intro_text' => $opt( 'assistant_window_intro_text', __( 'Explore common questions and answers.', 'ufaqsw' ) ),
				'back_button_label'           => $opt( 'assistant_back_button_title', __( 'Go back', 'ufaqsw' ) ),
				'close_button_label'          => $opt( 'assistant_close_button_title', __( 'Close the window', 'ufaqsw' ) ),
				'preloader_text'              => $opt( 'preloader_text' ),
				'faqs_count_text'             => $opt( 'faqs_count_text', __( '[count] Frequently Asked Questions', 'ufaqsw' ) ),
				'body_text'                   => wpautop( $opt( 'body_text' ) ),
				'bottom_text'                 => $opt( 'bottom_text' ),

				// Search
				'search_enabled'       => $bool( 'search_enabled' ),
				'search_placeholder'   => $opt( 'search_placeholder', __( 'Search FAQs…', 'ufaqsw' ) ),
				'search_in_answers'    => $bool( 'search_in_answers' ),
				'search_no_results_text' => $opt( 'search_no_results_text', __( 'No results found for "[query]".', 'ufaqsw' ) ),

				// Ask a Question (always email mode)
				'ask_enabled'          => $bool( 'ask_enabled' ),
				'ask_form_title'       => $opt( 'ask_form_title',   __( 'Ask Us a Question', 'ufaqsw' ) ),
				'ask_button_label'     => $opt( 'ask_button_label', __( 'Ask a Question', 'ufaqsw' ) ),
				'ask_name_label'       => $opt( 'ask_name_label',   __( 'Your Name', 'ufaqsw' ) ),
				'ask_email_label'      => $opt( 'ask_email_label',  __( 'Your Email', 'ufaqsw' ) ),
				'ask_question_label'   => $opt( 'ask_question_label', __( 'Your Question', 'ufaqsw' ) ),
				'ask_submit_label'     => $opt( 'ask_submit_label', __( 'Send Question', 'ufaqsw' ) ),
				'ask_cancel_label'     => $opt( 'ask_cancel_label', __( 'Cancel', 'ufaqsw' ) ),
				'ask_gdpr_enabled'     => $bool( 'ask_gdpr_enabled' ),
				'ask_gdpr_text'        => $opt( 'ask_gdpr_text', __( 'I agree to the privacy policy.', 'ufaqsw' ) ),
				'ask_gdpr_error'       => $opt( 'ask_gdpr_error', __( 'Please accept the privacy policy to continue.', 'ufaqsw' ) ),
				'ask_success_title'    => $opt( 'ask_success_title',   __( 'Question Received!', 'ufaqsw' ) ),
				'ask_success_message'  => $opt( 'ask_success_message', __( "Thank you! We'll get back to you as soon as possible.", 'ufaqsw' ) ),
				'ask_back_to_faqs_label' => $opt( 'ask_back_to_faqs_label', __( 'Back to FAQs', 'ufaqsw' ) ),

				// Feedback & Ratings
				'feedback_enabled'      => $bool( 'feedback_enabled' ),
				'feedback_label'        => $opt( 'feedback_label',       __( 'Was this helpful?', 'ufaqsw' ) ),
				'feedback_helpful_text' => $opt( 'feedback_helpful_text', __( 'Yes, helpful', 'ufaqsw' ) ),
				'feedback_not_helpful_text' => $opt( 'feedback_not_helpful_text', __( 'Not helpful', 'ufaqsw' ) ),
				'feedback_thanks_text'  => $opt( 'feedback_thanks_text', __( 'Thank you for your feedback!', 'ufaqsw' ) ),
				'related_enabled'       => $bool( 'related_enabled' ),
				'related_title'         => $opt( 'related_title', __( 'Related Questions', 'ufaqsw' ) ),
				'related_count'         => $opt( 'related_count', '3' ),
			)
		);
	}

	// -------------------------------------------------------------------------
	// AJAX: fetch all FAQ groups and their items
	// -------------------------------------------------------------------------

	public function handle_get_faqs() {
		check_ajax_referer( 'chatbot_nonce', 'nonce' );

		$faq_args = array(
			'post_type'      => 'ufaqsw',
			'fields'         => 'ids',
			'posts_per_page' => -1,
			'post_status'    => 'publish',
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
			'meta_query'     => array(
				array(
					'key'     => 'ufaqsw_faq_item01',
					'value'   => '',
					'compare' => '!=',
				),
			),
		);

		$selected_groups = cmb2_get_option( 'ufaqsw_chatbot_settings', 'faq_groups' );
		if ( ! empty( $selected_groups ) ) {
			$faq_args['post__in'] = $selected_groups;
		}

		$faq_group_ids = get_posts( $faq_args );
		if ( ! $faq_group_ids ) {
			wp_send_json_error( __( 'No FAQs found.', 'ufaqsw' ) );
			return;
		}

		$faq_data = array();
		foreach ( $faq_group_ids as $faq_group ) {
			$faq_items         = get_post_meta( $faq_group, 'ufaqsw_faq_item01' );
			$group_description = get_post_meta( $faq_group, 'group_short_desc', true );

			$faq_items = isset( $faq_items[0] ) ? $faq_items[0] : $faq_items;
			$faq_items = array_map(
				function ( $item ) {
					return array(
						'question' => html_entity_decode( $item['ufaqsw_faq_question'] ),
						'answer'   => do_shortcode( $item['ufaqsw_faq_answer'] ),
					);
				},
				$faq_items
			);

			$entry = array(
				'group' => html_entity_decode( get_the_title( $faq_group ) ),
				'items' => $faq_items,
			);

			if ( ! empty( $group_description ) ) {
				$entry['description'] = wp_kses_post( apply_filters( 'the_content', $group_description ) );
			}

			$faq_data[] = $entry;
		}

		wp_send_json_success( $faq_data );
		wp_die();
	}

	// -------------------------------------------------------------------------
	// AJAX: submit a visitor question
	// -------------------------------------------------------------------------

	public function handle_submit_question() {
		check_ajax_referer( 'chatbot_nonce', 'nonce' );

		// Honeypot check — bots fill the hidden "website" field.
		if ( ! empty( $_POST['website'] ) ) {
			wp_send_json_success(); // Silent success for bots.
			return;
		}

		$name     = sanitize_text_field( wp_unslash( $_POST['name']     ?? '' ) );
		$email    = sanitize_email( wp_unslash( $_POST['email']         ?? '' ) );
		$question = sanitize_textarea_field( wp_unslash( $_POST['question'] ?? '' ) );
		$page_url = esc_url_raw( wp_unslash( $_POST['page_url'] ?? '' ) );

		if ( ! $name || ! $email || ! $question || ! is_email( $email ) ) {
			wp_send_json_error( array( 'message' => __( 'Please fill in all required fields with valid data.', 'ufaqsw' ) ) );
			return;
		}

		// Rate limit: one submission per IP per hour.
		$ip            = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : 'unknown';
		$transient_key = 'ufaqsw_ask_' . md5( $ip );
		if ( get_transient( $transient_key ) ) {
			wp_send_json_error( array( 'message' => __( 'You have already submitted a question recently. Please wait a while before trying again.', 'ufaqsw' ) ) );
			return;
		}

		$to      = cmb2_get_option( 'ufaqsw_chatbot_settings', 'ask_notification_email' );
		$to      = ( $to && is_email( $to ) ) ? $to : get_option( 'admin_email' );
		$subject = sprintf( __( 'New FAQ Question from %s', 'ufaqsw' ), $name );
		/* translators: 1: name, 2: email, 3: question, 4: page URL */
		$body = sprintf( "Name: %s\nEmail: %s\n\nQuestion:\n%s\n\n---\nSubmitted from: %s", $name, $email, $question, $page_url );
		wp_mail( $to, $subject, $body );

		// Set rate-limit transient (1 hour).
		set_transient( $transient_key, 1, HOUR_IN_SECONDS );

		wp_send_json_success();
		wp_die();
	}

	// -------------------------------------------------------------------------
	// AJAX: record "was this helpful?" feedback
	// -------------------------------------------------------------------------

	public function handle_faq_feedback() {
		check_ajax_referer( 'chatbot_nonce', 'nonce' );

		$question = sanitize_text_field( wp_unslash( $_POST['question'] ?? '' ) );
		$vote     = sanitize_key( wp_unslash( $_POST['vote']            ?? '' ) );

		if ( ! $question || ! in_array( $vote, array( 'helpful', 'not_helpful' ), true ) ) {
			wp_send_json_error();
			return;
		}

		// Store feedback counts in a dedicated wp_option, keyed by question hash.
		$option_key = 'ufaqsw_feedback_' . md5( $question );
		$feedback   = get_option( $option_key, array( 'helpful' => 0, 'not_helpful' => 0 ) );
		$feedback[ $vote ] = intval( $feedback[ $vote ] ) + 1;
		update_option( $option_key, $feedback, false ); // autoload = false.

		wp_send_json_success();
		wp_die();
	}

	// -------------------------------------------------------------------------
	// Footer: output the custom element
	// -------------------------------------------------------------------------

	public function bot_integration() {
		if ( is_admin() ) {
			return;
		}

		if ( ! $this->is_assistant_enabled() ) {
			return;
		}

		wp_enqueue_style(
			'chatbot-floating-button',
			UFAQSW_ASSETS_URL . 'css/floatingbutton.css',
			array(),
			filemtime( UFAQSW__PLUGIN_DIR . 'assets/css/floatingbutton.css' )
		);

		echo '<chatbot-component></chatbot-component>';
	}
}
