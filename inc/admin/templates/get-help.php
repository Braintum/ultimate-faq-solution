<?php
/**
 * Get Help page for Ultimate FAQ Solution
 *
 * @package UltimateFaqSolution
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="ufs-help-wrap">
	<div class="ufs-help-header">
		<div class="ufs-help-logo">
			<img src="<?php echo esc_url( UFAQSW__PLUGIN_URL . 'assets/images/logo.png' ); ?>" alt="Ultimate FAQ Solution Logo" />
		</div>
		<div class="ufs-header-text">
			<h1 class="ufs-help-title"><?php esc_html_e( 'Get Help', 'ufaqsw' ); ?></h1>
			<p class="ufs-help-subtitle"><?php esc_html_e( 'Support, documentation, and troubleshooting for Ultimate FAQ Solution.', 'ufaqsw' ); ?></p>
		</div>
	</div>
	<div class="ufs-help-cards">
		<div class="ufs-help-card">
			<div class="ufs-card-icon">📚</div>
			<h3><?php esc_html_e( 'Documentation', 'ufaqsw' ); ?></h3>
			<p><?php esc_html_e( 'Read the full plugin documentation and feature guides.', 'ufaqsw' ); ?></p>
			<a class="ufs-btn" href="https://www.braintum.com/docs/ultimate-faq-solution/" target="_blank">View Docs</a>
		</div>
		<div class="ufs-help-card">
			<div class="ufs-card-icon">💬</div>
			<h3><?php esc_html_e( 'Get Support', 'ufaqsw' ); ?></h3>
			<p><?php esc_html_e( 'Contact our team for help or to report a bug.', 'ufaqsw' ); ?></p>
			<a class="ufs-btn" target="_blank" href="https://www.braintum.com/support/">Contact Us</a>
		</div>
		<div class="ufs-help-card">
			<div class="ufs-card-icon">
				<!-- GitHub SVG icon -->
				<svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
					<path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.021c0 4.428 2.865 8.184 6.839 9.504.5.092.682-.217.682-.483 0-.237-.009-.868-.014-1.703-2.782.605-3.369-1.342-3.369-1.342-.454-1.155-1.11-1.463-1.11-1.463-.908-.62.069-.608.069-.608 1.004.07 1.532 1.032 1.532 1.032.892 1.53 2.341 1.088 2.91.832.091-.647.35-1.088.636-1.339-2.221-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.025A9.564 9.564 0 0 1 12 6.844c.85.004 1.705.115 2.504.337 1.909-1.295 2.748-1.025 2.748-1.025.546 1.378.202 2.397.1 2.65.64.7 1.028 1.595 1.028 2.688 0 3.847-2.337 4.695-4.566 4.944.359.309.678.919.678 1.853 0 1.337-.012 2.419-.012 2.749 0 .268.18.579.688.481C19.138 20.2 22 16.447 22 12.021 22 6.484 17.523 2 12 2z" fill="#181717"/>
				</svg>
			</div>
			<h3><?php esc_html_e( 'GitHub', 'ufaqsw' ); ?></h3>
			<p><?php esc_html_e( 'View the source code, report issues, or contribute on GitHub.', 'ufaqsw' ); ?></p>
			<a class="ufs-btn" target="_blank" href="https://github.com/Braintum/ultimate-faq-solution">View on GitHub</a>
		</div>
	</div>

	<div class="ufs-section" id="support">
		<h2>💡 <?php esc_html_e( 'Need More Help?', 'ufaqsw' ); ?></h2>
		<div class="ufs-support">
			<p><strong><?php esc_html_e( 'We’re here to help!', 'ufaqsw' ); ?></strong> <?php esc_html_e( 'Contact us for fast, friendly support:', 'ufaqsw' ); ?></p>
			<ul>
				<li>✉️ <a href="mailto:support@ultimatefaqsolution.com">support@ultimatefaqsolution.com</a></li>
				<li>🧾 <a href="https://www.braintum.com/support/" target="_blank">Open a support ticket</a></li>
			</ul>
			<p><?php esc_html_e( 'We typically respond within 1 business day.', 'ufaqsw' ); ?></p>
		</div>
	</div>
	<div class="ufs-help-footer">
		<?php esc_html_e( 'Ultimate FAQ Solution by ', 'ufaqsw' ); ?> <a href="https://www.braintum.com/" target="_blank">Braintum</a>
	</div>
</div>