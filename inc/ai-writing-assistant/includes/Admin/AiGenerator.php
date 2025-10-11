<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName -- File name matches class name.
/**
 * AiGenerator class integration for AI Text Refiner plugin.
 *
 * @package BTRefiner\Admin
 */

namespace BTRefiner\Admin;

use BTRefiner\API\ChatGPT;

/**
 * Class AiGenerator
 *
 * Integrates ACF fields with the AI Text Refiner plugin, adding UI elements and handling AJAX requests for text refinement.
 */
class AiGenerator {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'admin_footer-edit.php', array( $this, 'add_ai_button_popup' ) );
		add_action( 'wp_ajax_generate_faq_group_with_ai', array( $this, 'ajax_generate_faq_group' ) );
		add_action( 'current_screen', array( $this, 'maybe_add_ai_button' ) );
		add_filter( 'display_post_states', array( $this, 'add_ai_status_label' ), 10, 2 );
	}

	/**
	 * Add "Created with AI" label to FAQ Group titles in admin list.
	 *
	 * @param array    $states Existing post states.
	 * @param \WP_Post $post   Current post object.
	 * @return array
	 */
	public function add_ai_status_label( array $states, \WP_Post $post ): array {
		if ( 'ufaqsw' !== $post->post_type ) {
			return $states;
		}

		$is_ai = get_post_meta( $post->ID, '_created_with_ai', true );
		if ( $is_ai ) {
			$states['created_with_ai'] = __( 'Created with AI', 'ufaqsw' );
		}

		return $states;
	}

	/**
	 * Conditionally add the "Create with AI" button on the FAQ Group list page.
	 *
	 * @param \WP_Screen $screen Current screen object.
	 */
	public function maybe_add_ai_button( $screen ) {
		if ( 'edit-ufaqsw' === $screen->id ) {
			add_action( 'in_admin_header', array( $this, 'add_ai_button' ) );
		}
	}

	/**
	 * Add the "Create with AI" button next to the "Add New" button on the FAQ Group list page.
	 */
	public function add_ai_button() {
		// Output only for the FAQ Group list page.
		if ( isset( $_GET['post_type'] ) && 'ufaqsw' === $_GET['post_type'] ) { ?>
		<script type="text/javascript">
			jQuery(document).ready(function ($) {
				// Add our "Create with AI" button next to the Add New button
				const addNewBtn = $('.page-title-action').first();
				if (addNewBtn.length && !$('#create-faq-ai-btn').length) {
					$('<a id="create-faq-ai-btn" href="#TB_inline?width=600&height=400&inlineId=create-faq-ai-popup" class="page-title-action thickbox" title="<?php echo esc_attr__( 'AI FAQ Group Generator - Ultimate FAQ Solution', 'ufaqsw' ); ?>">🤖 <?php echo esc_html__( 'Create with AI', 'ufaqsw' ); ?></a>').insertAfter(addNewBtn);
				}
			});
		</script>
			<?php
		}
	}

	/**
	 * Enqueue scripts and localize data
	 *
	 * @param string $hook Current admin page hook.
	 */
	public function enqueue_scripts( $hook ) {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Checking GET parameter for admin context only.
		if ( 'edit.php' !== $hook || ! isset( $_GET['post_type'] ) || 'ufaqsw' !== $_GET['post_type'] ) {
			return;
		}

		wp_enqueue_script(
			'ultimate-faq-ai-generator',
			UFAQSW__PLUGIN_URL . 'inc/admin/assets/js/ultimate-faq-ai-generator.js',
			array( 'jquery' ),
			UFAQSW_VERSION,
			true
		);

		wp_localize_script(
			'ultimate-faq-ai-generator',
			'UFAQ_AI',
			array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( 'ufaq_ai_nonce' ),
			)
		);

		wp_enqueue_style( 'thickbox' );
		add_thickbox();
	}

	/**
	 * Add the "Create with AI" button and popup modal HTML
	 */
	public function add_ai_button_popup() {

		$screen = get_current_screen();
		if ( 'ufaqsw' !== $screen->post_type ) {
			return;
		}

		$ai_settings = get_option( 'ufaqsw_ai_integration_settings', array() );
		if ( isset( $ai_settings['enable_ai_integration'] ) && $ai_settings['enable_ai_integration'] ) {
			?>
		<div id="create-faq-ai-popup" style="display:none;">
			<h2>Create FAQ Group with AI</h2>
			<form id="ufaq-ai-form">
				<p>
					<label><strong>FAQ Group Title:</strong></label><br>
					<input type="text" name="faq_title" class="regular-text" placeholder="e.g. Shipping & Delivery" required /><br>
					<small class="description">Give your FAQ group a short, descriptive title. For example, "Shipping & Delivery", "Product Returns", or "Account Setup".</small>
				</p>

				<p>
					<label><strong>Context (page link or text):</strong></label><br>
					<textarea name="faq_context" rows="4" class="large-text" placeholder="Paste a page link or short description..." required></textarea>
					<small class="description">
						Provide the content or context that the AI will use to generate relevant FAQs.<br>
						You can paste a page URL, a paragraph of text, or product/service details.
					</small>
				</p>

				<p>
					<label><strong>Number of FAQ items:</strong></label><br>
					<input type="number" name="faq_count" value="5" min="1" max="20" /><br>
					<small class="description">Select how many FAQs you want the AI to generate for this group (1–20).</small>
				</p>

				<p>
					<label><strong>Tone / Style:</strong></label><br>
					<select name="faq_tone" class="regular-text">
						<option value="neutral" selected>Neutral / Informative</option>
						<option value="friendly">Friendly & Conversational</option>
						<option value="professional">Professional & Formal</option>
						<option value="technical">Technical & Detailed</option>
						<option value="marketing">Persuasive / Marketing Style</option>
					</select><br>
					<small class="description">Choose the style in which answers should be written.</small>
				</p>

				<p>
					<button type="submit" class="button button-primary">Generate FAQ Group</button>
				</p>
			</form>

			<div id="ufaq-ai-result" style="margin-top:15px;">
				<div id="ufaq-ai-success" style="display:none;">
					<div style="background:#d4edda; border:1px solid #c3e6cb; color:#155724; padding:15px; border-radius:5px; margin-bottom:15px;">
						<h4 style="margin:0 0 10px 0; display:flex; align-items:center;">
							<span style="color:#28a745; margin-right:8px;">✓</span>
							Success!
						</h4>
						<p id="ufaq-success-message" style="margin:0;"></p>
					</div>
					<div style="text-align:center;">
						<a id="ufaq-edit-link" href="#" class="button button-primary" style="margin-right:10px;">Edit FAQ Group</a>
						<button type="button" class="button" onclick="tb_remove();">Close</button>
					</div>
				</div>
				
				<div id="ufaq-ai-error" style="display:none;">
					<div style="background:#f8d7da; border:1px solid #f5c6cb; color:#721c24; padding:15px; border-radius:5px; margin-bottom:15px;">
						<h4 style="margin:0 0 10px 0; display:flex; align-items:center;">
							<span style="color:#dc3545; margin-right:8px;">✗</span>
							Error
						</h4>
						<p id="ufaq-error-message" style="margin:0;"></p>
					</div>
					<div style="text-align:center;">
						<button type="button" class="button button-primary" onclick="jQuery('#ufaq-ai-result > div').hide(); jQuery('#ufaq-ai-form').show();">Try Again</button>
					</div>
				</div>
			</div>

			<style>
				@keyframes spin {
					0% { transform: rotate(0deg); }
					100% { transform: rotate(360deg); }
				}
				#ufaq-ai-form.processing {
					opacity: 0.6;
					pointer-events: none;
				}
			</style>
		</div>
			<?php
		} else {
			?>
		<div id="create-faq-ai-popup" style="display:none;">
			<h2>AI Integration Not Enabled</h2>
			<p>Please enable AI Integration in the <a href="<?php echo esc_url( admin_url( 'admin.php?page=ufaqsw_ai_integration_settings' ) ); ?>">AI Integration Settings</a> to use this feature.</p>
		</div>
			<?php
		}
	}

	/**
	 * Handle AJAX request to generate FAQ group with AI
	 */
	/**
	 * Handles AJAX request to generate FAQ group using AI.
	 *
	 * This method processes an AJAX request to create a new FAQ group with AI-generated
	 * questions and answers based on user-provided context and parameters.
	 *
	 * @since 1.0.0
	 *
	 * @throws \Exception When any error occurs during processing.
	 *
	 * @return void Sends JSON response and terminates execution.
	 *
	 * Expected POST parameters:
	 * - faq_title (string): Title for the FAQ group
	 * - faq_context (string): Context/topic for generating FAQs
	 * - faq_count (int): Number of FAQ items to generate
	 * - faq_tone (string): Tone/style for the generated content
	 * - security (string): Nonce for security verification
	 *
	 * Response format:
	 * - On success: {'success': true, 'data': {'message': 'Success message'}}
	 * - On error: {'success': false, 'data': {'message': 'Error message'}}
	 *
	 * Creates a new WordPress post of type 'ufaqsw' and stores the generated
	 * FAQ data in post meta field 'ufaqsw_faq_item01'.
	 */
	public function ajax_generate_faq_group() {
		check_ajax_referer( 'ufaq_ai_nonce', 'security' );

		$title   = sanitize_text_field( $_POST['faq_title'] ); // phpcs:ignore.
		$context = sanitize_textarea_field( $_POST['faq_context'] ); // phpcs:ignore.
		$count   = intval( $_POST['faq_count'] ); // phpcs:ignore.
		$tone    = sanitize_text_field( $_POST['faq_tone'] ); // phpcs:ignore.

		$instruction = <<<PROMPT
			You are an assistant that creates FAQ groups in structured JSON format.

			Generate exactly {$count} FAQs based on the following context:
			{$context}

			Return only valid JSON with this structure — do not include any extra text:

			{
			"group_title": "string",
			"group_description": "string",
			"faqs": [
				{
				"ufaqsw_faq_question": "string",
				"ufaqsw_faq_answer": "string"
				}
			]
			}

			Rules:
			- The group description should summarize the main topic of the context.
			- Each question should be concise and natural.
			- Each answer should be clear, factual, and relevant.
			- Generate exactly {$count} FAQ items.
			- Output only JSON, no Markdown, no extra commentary.
			- Use the following tone/style: {$tone}
			PROMPT;

		$user_text = "Create a FAQ group with {$count} items based on group title: {$title}";
		try {
			$chatgpt = new ChatGPT();
			$chatgpt->set_api_key( (string) cmb2_get_option( 'ufaqsw_ai_integration_settings', 'chatgpt_api_key' ) );
			$chatgpt->set_model( (string) cmb2_get_option( 'ufaqsw_ai_integration_settings', 'chatgpt_model' ) );
			$chatgpt->set_language( (string) cmb2_get_option( 'ufaqsw_ai_integration_settings', 'ai_language' ) );
			$result = $chatgpt->refine( $user_text, $instruction );

			$faq_data = json_decode( $result, true );

			if ( ! $faq_data || empty( $faq_data['faqs'] ) ) {
				throw new \Exception( 'Invalid response format from AI.' );
			}

			// Create FAQ Group.
			$group_id = wp_insert_post(
				array(
					'post_type'   => 'ufaqsw',
					'post_title'  => sanitize_text_field( $faq_data['group_title'] ),
					'post_status' => 'publish',
				)
			);

			update_post_meta( $group_id, 'group_short_desc', sanitize_text_field( $faq_data['group_description'] ) );
			update_post_meta( $group_id, 'ufaqsw_faq_item01', $faq_data['faqs'] );
			update_post_meta( $group_id, '_created_with_ai', 1 );

			wp_send_json(
				array(
					'success' => true,
					'data'    => array(
						'message' => "FAQ Group '{$title}' created successfully with {$count} items.",
						'return_link' => get_edit_post_link( $group_id ),
					),
				)
			);
			wp_die();

		} catch ( \Exception $e ) {
			wp_send_json(
				array(
					'success' => false,
					'data'    => array(
						'message' => $e->getMessage(),
					),
				)
			);
			wp_die();
		}
	}
}
