<?php
/**
 * FAQ Assistant – Feedback Analytics admin page.
 *
 * Displays a sortable table of all FAQ questions with their helpful / not-helpful
 * vote counts collected by the chatbot "Was this helpful?" widget.
 *
 * @package Ultimate_FAQ_Solution
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the "Feedback Analytics" submenu page under the FAQ Groups post type.
 */
function ufaqsw_register_feedback_analytics_page() {
	if ( ! cmb2_get_option( 'ufaqsw_chatbot_settings', 'feedback_enabled' ) && get_option( 'ufaqsw_faq_feedback_enabled' ) !== 'on' ) {
		return;
	}

	add_submenu_page(
		'edit.php?post_type=ufaqsw',
		__( 'Feedback Analytics', 'ufaqsw' ),
		__( 'Feedback Analytics', 'ufaqsw' ),
		'manage_options',
		'ufaqsw-feedback-analytics',
		'ufaqsw_render_feedback_analytics_page'
	);
}
add_action( 'admin_menu', 'ufaqsw_register_feedback_analytics_page' );

/**
 * Render the Feedback Analytics page.
 */
function ufaqsw_render_feedback_analytics_page() {

	// --- Collect data -------------------------------------------------------

	$faq_groups = get_posts(
		array(
			'post_type'      => 'ufaqsw',
			'posts_per_page' => -1,
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
			'post_status'    => 'publish',
			'fields'         => 'ids',
		)
	);

	$rows = array();

	foreach ( $faq_groups as $group_id ) {
		$group_title = get_the_title( $group_id );
		$faq_items   = get_post_meta( $group_id, 'ufaqsw_faq_item01' );
		$faq_items   = isset( $faq_items[0] ) ? $faq_items[0] : array();

		foreach ( $faq_items as $item ) {
			$question    = html_entity_decode( $item['ufaqsw_faq_question'] ?? '' );
			$option_key  = 'ufaqsw_feedback_' . md5( $question );
			$feedback    = get_option( $option_key, array( 'helpful' => 0, 'not_helpful' => 0 ) );

			$helpful     = (int) ( $feedback['helpful']     ?? 0 );
			$not_helpful = (int) ( $feedback['not_helpful'] ?? 0 );
			$total       = $helpful + $not_helpful;
			$score       = $total > 0 ? round( ( $helpful / $total ) * 100 ) : null;

			$rows[] = array(
				'group'       => $group_title,
				'question'    => $question,
				'helpful'     => $helpful,
				'not_helpful' => $not_helpful,
				'total'       => $total,
				'score'       => $score,
			);
		}
	}

	// Sort by total votes descending so the most-viewed FAQs appear first.
	usort( $rows, function ( $a, $b ) {
		return $b['total'] - $a['total'];
	} );

	$total_helpful     = array_sum( array_column( $rows, 'helpful' ) );
	$total_not_helpful = array_sum( array_column( $rows, 'not_helpful' ) );
	$total_votes       = $total_helpful + $total_not_helpful;
	$rated_rows        = array_filter( $rows, fn( $r ) => $r['total'] > 0 );

	// --- Handle reset action ------------------------------------------------

	if (
		isset( $_POST['ufaqsw_reset_feedback'] ) &&
		check_admin_referer( 'ufaqsw_reset_feedback_nonce', 'ufaqsw_reset_feedback_nonce' )
	) {
		$question_to_reset = sanitize_text_field( wp_unslash( $_POST['ufaqsw_reset_question'] ?? '' ) );
		if ( $question_to_reset ) {
			delete_option( 'ufaqsw_feedback_' . md5( $question_to_reset ) );
			echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'Feedback reset successfully.', 'ufaqsw' ) . '</p></div>';
			// Refresh data after reset.
			foreach ( $rows as &$row ) {
				if ( $row['question'] === $question_to_reset ) {
					$row['helpful'] = $row['not_helpful'] = $row['total'] = 0;
					$row['score']   = null;
				}
			}
			unset( $row );
		}
	}

	// --- Render HTML --------------------------------------------------------
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'FAQ Feedback Analytics', 'ufaqsw' ); ?></h1>
		<p><?php esc_html_e( 'Vote counts collected by the "Was this helpful?" widget in the FAQ Assistant.', 'ufaqsw' ); ?></p>

		<!-- Summary cards -->
		<div style="display:flex;gap:16px;margin:20px 0;flex-wrap:wrap;">
			<?php
			$cards = array(
				array(
					'label' => __( 'Total Votes', 'ufaqsw' ),
					'value' => number_format_i18n( $total_votes ),
					'color' => '#1a185e',
				),
				array(
					'label' => __( 'Helpful', 'ufaqsw' ),
					'value' => number_format_i18n( $total_helpful ),
					'color' => '#16a34a',
				),
				array(
					'label' => __( 'Not Helpful', 'ufaqsw' ),
					'value' => number_format_i18n( $total_not_helpful ),
					'color' => '#dc2626',
				),
				array(
					'label' => __( 'FAQs with Votes', 'ufaqsw' ),
					'value' => number_format_i18n( count( $rated_rows ) ),
					'color' => '#0284c7',
				),
			);
			foreach ( $cards as $card ) {
				printf(
					'<div style="background:#fff;border:1px solid #e0e0e0;border-radius:8px;padding:16px 22px;min-width:130px;">
						<div style="font-size:11px;text-transform:uppercase;letter-spacing:.05em;color:#888;margin-bottom:6px;">%s</div>
						<div style="font-size:28px;font-weight:700;color:%s;">%s</div>
					</div>',
					esc_html( $card['label'] ),
					esc_attr( $card['color'] ),
					esc_html( $card['value'] )
				);
			}
			?>
		</div>

		<?php if ( empty( $rows ) ) : ?>
			<p><?php esc_html_e( 'No FAQ groups found.', 'ufaqsw' ); ?></p>
			<?php return; ?>
		<?php endif; ?>

		<!-- Table -->
		<table class="wp-list-table widefat fixed striped" style="margin-top:10px;">
			<thead>
				<tr>
					<th style="width:22%;"><?php esc_html_e( 'Group', 'ufaqsw' ); ?></th>
					<th><?php esc_html_e( 'Question', 'ufaqsw' ); ?></th>
					<th style="width:9%;text-align:center;">
						<span style="color:#16a34a;" title="<?php esc_attr_e( 'Helpful votes', 'ufaqsw' ); ?>">&#128077;</span>
					</th>
					<th style="width:9%;text-align:center;">
						<span style="color:#dc2626;" title="<?php esc_attr_e( 'Not helpful votes', 'ufaqsw' ); ?>">&#128078;</span>
					</th>
					<th style="width:9%;text-align:center;"><?php esc_html_e( 'Total', 'ufaqsw' ); ?></th>
					<th style="width:13%;text-align:center;"><?php esc_html_e( 'Score', 'ufaqsw' ); ?></th>
					<th style="width:6%;text-align:center;"><?php esc_html_e( 'Reset', 'ufaqsw' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ( $rows as $row ) : ?>
					<tr>
						<td><em><?php echo esc_html( $row['group'] ); ?></em></td>
						<td><?php echo esc_html( $row['question'] ); ?></td>
						<td style="text-align:center;color:#16a34a;font-weight:600;">
							<?php echo $row['helpful'] > 0 ? esc_html( number_format_i18n( $row['helpful'] ) ) : '<span style="color:#ccc;">—</span>'; ?>
						</td>
						<td style="text-align:center;color:#dc2626;font-weight:600;">
							<?php echo $row['not_helpful'] > 0 ? esc_html( number_format_i18n( $row['not_helpful'] ) ) : '<span style="color:#ccc;">—</span>'; ?>
						</td>
						<td style="text-align:center;">
							<?php echo $row['total'] > 0 ? esc_html( number_format_i18n( $row['total'] ) ) : '<span style="color:#ccc;">—</span>'; ?>
						</td>
						<td style="text-align:center;">
							<?php if ( null !== $row['score'] ) : ?>
								<?php
								$color = $row['score'] >= 70 ? '#16a34a' : ( $row['score'] >= 40 ? '#d97706' : '#dc2626' );
								printf(
									'<strong style="color:%s;">%d%%</strong>',
									esc_attr( $color ),
									(int) $row['score']
								);
								?>
							<?php else : ?>
								<span style="color:#ccc;">—</span>
							<?php endif; ?>
						</td>
						<td style="text-align:center;">
							<?php if ( $row['total'] > 0 ) : ?>
								<form method="post" style="margin:0;" onsubmit="return confirm('<?php esc_attr_e( 'Reset votes for this question?', 'ufaqsw' ); ?>');">
									<?php wp_nonce_field( 'ufaqsw_reset_feedback_nonce', 'ufaqsw_reset_feedback_nonce' ); ?>
									<input type="hidden" name="ufaqsw_reset_question" value="<?php echo esc_attr( $row['question'] ); ?>">
									<button type="submit" name="ufaqsw_reset_feedback" class="button button-small">
										<?php esc_html_e( 'Reset', 'ufaqsw' ); ?>
									</button>
								</form>
							<?php else : ?>
								<span style="color:#ccc;">—</span>
							<?php endif; ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>

		<p style="margin-top:14px;color:#888;font-size:12px;">
			<?php esc_html_e( 'Score = percentage of "helpful" votes out of total votes. Green ≥ 70 %, yellow ≥ 40 %, red < 40 %.', 'ufaqsw' ); ?>
		</p>
	</div>
	<?php
}
