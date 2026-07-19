<?php
/**
 * Shortcode Builder page template.
 *
 * Variables available:
 *   $faq_groups   – WP_Post[] of ufaqsw posts
 *   $appearances  – WP_Post[] of ufaqsw_appearance posts
 *
 * @package UltimateFAQSolution
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<style>
/* ── Layout ─────────────────────────────────────────────── */
.ufaqsb-wrap {
	max-width: 1100px;
}
.ufaqsb-header {
	margin: 20px 0 24px;
}
.ufaqsb-header h1 {
	font-size: 1.6rem;
	font-weight: 700;
	color: #1d2327;
	margin: 0 0 6px;
	display: flex;
	align-items: center;
	gap: 10px;
}
.ufaqsb-header h1 .dashicons {
	color: #2271b1;
	font-size: 1.5rem;
}
.ufaqsb-header p {
	color: #50575e;
	margin: 0;
	font-size: 14px;
}
.ufaqsb-columns {
	display: grid;
	grid-template-columns: 1fr 380px;
	gap: 24px;
	align-items: start;
}
@media (max-width: 900px) {
	.ufaqsb-columns { grid-template-columns: 1fr; }
}

/* ── Cards ──────────────────────────────────────────────── */
.ufaqsb-card {
	background: #fff;
	border: 1px solid #dcdcde;
	border-radius: 6px;
	padding: 20px 24px;
	margin-bottom: 16px;
}
.ufaqsb-card:last-child {
	margin-bottom: 0;
}
.ufaqsb-card-title {
	font-size: 13px;
	font-weight: 600;
	color: #1d2327;
	text-transform: uppercase;
	letter-spacing: .04em;
	margin: 0 0 16px;
	padding-bottom: 12px;
	border-bottom: 1px solid #f0f0f1;
	display: flex;
	align-items: center;
	gap: 7px;
}
.ufaqsb-card-title .dashicons {
	color: #2271b1;
	font-size: 16px;
}

/* ── Form fields ────────────────────────────────────────── */
.ufaqsb-field {
	margin-bottom: 18px;
}
.ufaqsb-field:last-child {
	margin-bottom: 0;
}
.ufaqsb-field label {
	display: block;
	font-size: 13px;
	font-weight: 600;
	color: #1d2327;
	margin-bottom: 5px;
}
.ufaqsb-field select,
.ufaqsb-field input[type="text"] {
	width: 100%;
	max-width: 420px;
}
.ufaqsb-field .ufaqsb-help {
	display: block;
	font-size: 12px;
	color: #72777c;
	margin-top: 4px;
}
.ufaqsb-checkbox-row {
	display: flex;
	align-items: flex-start;
	gap: 8px;
	margin-bottom: 10px;
}
.ufaqsb-checkbox-row input[type="checkbox"] {
	margin-top: 2px;
	flex-shrink: 0;
}
.ufaqsb-checkbox-row label {
	font-size: 13px;
	color: #1d2327;
	margin: 0;
	cursor: pointer;
}
.ufaqsb-checkbox-row .ufaqsb-help {
	font-size: 12px;
	color: #72777c;
	margin-top: 1px;
	display: block;
}
.ufaqsb-group-checkboxes {
	max-height: 200px;
	overflow-y: auto;
	border: 1px solid #dcdcde;
	border-radius: 4px;
	padding: 8px 12px;
	background: #f9f9f9;
}
.ufaqsb-group-checkboxes .ufaqsb-checkbox-row {
	margin-bottom: 6px;
}
.ufaqsb-group-checkboxes .ufaqsb-checkbox-row:last-child {
	margin-bottom: 0;
}

/* ── Conditional sections ───────────────────────────────── */
.ufaqsb-conditional {
	display: none;
}
.ufaqsb-conditional.ufaqsb-visible {
	display: block;
}

/* ── Output panel ───────────────────────────────────────── */
.ufaqsb-output-sticky {
	position: sticky;
	top: 32px;
}
.ufaqsb-shortcode-block {
	background: #1d2327;
	border-radius: 4px;
	padding: 14px 16px;
	margin-bottom: 12px;
	position: relative;
	min-height: 52px;
}
#ufaqsb-shortcode-text {
	font-family: Consolas, 'Courier New', monospace;
	font-size: 13.5px;
	color: #a8e6cf;
	word-break: break-all;
	line-height: 1.6;
	display: block;
}
.ufaqsb-placeholder {
	color: #72777c;
	font-style: italic;
	font-size: 13px;
	font-family: inherit;
}
.ufaqsb-copy-btn {
	display: flex;
	align-items: center;
	gap: 6px;
	width: 100%;
	justify-content: center;
	padding: 9px 16px;
	background: #2271b1;
	color: #fff;
	border: none;
	border-radius: 4px;
	font-size: 13px;
	font-weight: 600;
	cursor: pointer;
	transition: background .15s;
	margin-bottom: 16px;
}
.ufaqsb-copy-btn:hover {
	background: #135e96;
}
.ufaqsb-copy-btn:disabled {
	background: #8c8f94;
	cursor: default;
}
.ufaqsb-copy-btn.ufaqsb-copied {
	background: #1a7431;
}
.ufaqsb-copy-btn .dashicons {
	font-size: 16px;
}

/* ── Usage guide ────────────────────────────────────────── */
.ufaqsb-usage-guide {
	background: #f6f7f7;
	border: 1px solid #dcdcde;
	border-radius: 6px;
	padding: 16px 18px;
	font-size: 13px;
}
.ufaqsb-usage-guide h3 {
	margin: 0 0 10px;
	font-size: 12px;
	font-weight: 700;
	text-transform: uppercase;
	letter-spacing: .05em;
	color: #50575e;
}
.ufaqsb-usage-guide ul {
	margin: 0;
	padding-left: 18px;
}
.ufaqsb-usage-guide li {
	margin-bottom: 7px;
	color: #3c434a;
	line-height: 1.5;
}
.ufaqsb-usage-guide li:last-child {
	margin-bottom: 0;
}
.ufaqsb-usage-guide code {
	background: #fff;
	border: 1px solid #dcdcde;
	border-radius: 3px;
	padding: 1px 5px;
	font-size: 12px;
}

/* ── Empty state ─────────────────────────────────────────── */
.ufaqsb-empty-notice {
	background: #f0f6fc;
	border: 1px solid #c6d9ed;
	border-radius: 4px;
	padding: 14px 16px;
	color: #0a4a7c;
	font-size: 13px;
	text-align: center;
}
</style>

<div class="wrap ufaqsb-wrap">

	<div class="ufaqsb-header">
		<h1>
			<span class="dashicons dashicons-shortcode"></span>
			<?php esc_html_e( 'Shortcode Builder', 'ufaqsw' ); ?>
		</h1>
		<p><?php esc_html_e( 'Configure your FAQ shortcode visually and paste it into any page, post, or widget.', 'ufaqsw' ); ?></p>
	</div>

	<?php if ( empty( $faq_groups ) ) : ?>
		<div class="ufaqsb-empty-notice">
			<?php
			printf(
				wp_kses(
					/* translators: %s: link to create a new FAQ group */
					__( 'No FAQ groups found. <a href="%s">Create your first FAQ group</a> to get started.', 'ufaqsw' ),
					array( 'a' => array( 'href' => array() ) )
				),
				esc_url( admin_url( 'post-new.php?post_type=ufaqsw' ) )
			);
			?>
		</div>
	<?php else : ?>

	<div class="ufaqsb-columns">

		<!-- ── Left: Configuration ──────────────────── -->
		<div class="ufaqsb-controls">

			<!-- Group selector -->
			<div class="ufaqsb-card">
				<h2 class="ufaqsb-card-title">
					<span class="dashicons dashicons-editor-help"></span>
					<?php esc_html_e( 'Select FAQ Group', 'ufaqsw' ); ?>
				</h2>

				<div class="ufaqsb-field">
					<label for="ufaqsb-group"><?php esc_html_e( 'FAQ Group', 'ufaqsw' ); ?></label>
					<select id="ufaqsb-group" class="regular-text">
						<option value=""><?php esc_html_e( '— Choose a group —', 'ufaqsw' ); ?></option>
						<option value="all"><?php esc_html_e( 'All FAQ Groups', 'ufaqsw' ); ?></option>
						<?php foreach ( $faq_groups as $group ) : ?>
							<option value="<?php echo esc_attr( $group->ID ); ?>">
								<?php echo esc_html( $group->post_title ); ?>
							</option>
						<?php endforeach; ?>
					</select>
					<span class="ufaqsb-help"><?php esc_html_e( 'Displays a single group or all groups at once.', 'ufaqsw' ); ?></span>
				</div>
			</div>

			<!-- Display options (shared) -->
			<div class="ufaqsb-card ufaqsb-conditional" id="ufaqsb-section-display">
				<h2 class="ufaqsb-card-title">
					<span class="dashicons dashicons-visibility"></span>
					<?php esc_html_e( 'Display Options', 'ufaqsw' ); ?>
				</h2>

				<div class="ufaqsb-field">
					<label for="ufaqsb-order"><?php esc_html_e( 'Item Order', 'ufaqsw' ); ?></label>
					<select id="ufaqsb-order">
						<option value="asc"><?php esc_html_e( 'Ascending (default)', 'ufaqsw' ); ?></option>
						<option value="desc"><?php esc_html_e( 'Descending', 'ufaqsw' ); ?></option>
					</select>
				</div>

				<div class="ufaqsb-checkbox-row">
					<input type="checkbox" id="ufaqsb-hide-title" value="1" />
					<div>
						<label for="ufaqsb-hide-title"><?php esc_html_e( 'Hide group title', 'ufaqsw' ); ?></label>
						<span class="ufaqsb-help"><?php esc_html_e( 'Overrides the per-group title visibility setting.', 'ufaqsw' ); ?></span>
					</div>
				</div>

				<div class="ufaqsb-checkbox-row">
					<input type="checkbox" id="ufaqsb-show-expand-all" value="1" />
					<div>
						<label for="ufaqsb-show-expand-all"><?php esc_html_e( 'Show Expand All button', 'ufaqsw' ); ?></label>
						<span class="ufaqsb-help"><?php esc_html_e( 'Adds an Expand All / Collapse All toggle above the FAQ items.', 'ufaqsw' ); ?></span>
					</div>
				</div>
			</div>

			<!-- All-groups–only options -->
			<div class="ufaqsb-card ufaqsb-conditional" id="ufaqsb-section-all">
				<h2 class="ufaqsb-card-title">
					<span class="dashicons dashicons-list-view"></span>
					<?php esc_html_e( 'All Groups Options', 'ufaqsw' ); ?>
				</h2>

				<div class="ufaqsb-field">
					<label for="ufaqsb-behaviour"><?php esc_html_e( 'Behaviour Override', 'ufaqsw' ); ?></label>
					<select id="ufaqsb-behaviour">
						<option value=""><?php esc_html_e( 'Use group settings (default)', 'ufaqsw' ); ?></option>
						<option value="accordion"><?php esc_html_e( 'Accordion', 'ufaqsw' ); ?></option>
						<option value="toggle"><?php esc_html_e( 'Toggle', 'ufaqsw' ); ?></option>
					</select>
					<span class="ufaqsb-help"><?php esc_html_e( 'Forces the same open/close behaviour on every group.', 'ufaqsw' ); ?></span>
				</div>

				<?php if ( ! empty( $faq_groups ) ) : ?>
				<div class="ufaqsb-field">
					<label><?php esc_html_e( 'Exclude Groups', 'ufaqsw' ); ?></label>
					<div class="ufaqsb-group-checkboxes">
						<?php foreach ( $faq_groups as $group ) : ?>
							<div class="ufaqsb-checkbox-row">
								<input
									type="checkbox"
									class="ufaqsb-exclude-group"
									id="ufaqsb-excl-<?php echo esc_attr( $group->ID ); ?>"
									value="<?php echo esc_attr( $group->ID ); ?>"
								/>
								<label for="ufaqsb-excl-<?php echo esc_attr( $group->ID ); ?>">
									<?php echo esc_html( $group->post_title ); ?>
								</label>
							</div>
						<?php endforeach; ?>
					</div>
					<span class="ufaqsb-help"><?php esc_html_e( 'Checked groups will be hidden from the output.', 'ufaqsw' ); ?></span>
				</div>
				<?php endif; ?>
			</div>

			<!-- Single-group–only options -->
			<div class="ufaqsb-card ufaqsb-conditional" id="ufaqsb-section-single">
				<h2 class="ufaqsb-card-title">
					<span class="dashicons dashicons-filter"></span>
					<?php esc_html_e( 'Single Group Options', 'ufaqsw' ); ?>
				</h2>

				<div class="ufaqsb-field">
					<label for="ufaqsb-exclude-items"><?php esc_html_e( 'Exclude FAQ Items', 'ufaqsw' ); ?></label>
					<input
						type="text"
						id="ufaqsb-exclude-items"
						class="regular-text"
						placeholder="1, 3, 5"
					/>
					<span class="ufaqsb-help"><?php esc_html_e( 'Comma-separated item numbers to hide (1-based position within the group).', 'ufaqsw' ); ?></span>
				</div>
			</div>

			<!-- Appearance override -->
			<div class="ufaqsb-card ufaqsb-conditional" id="ufaqsb-section-appearance">
				<h2 class="ufaqsb-card-title">
					<span class="dashicons dashicons-admin-customizer"></span>
					<?php esc_html_e( 'Appearance', 'ufaqsw' ); ?>
				</h2>

				<?php if ( ! empty( $appearances ) ) : ?>
				<div class="ufaqsb-field">
					<label for="ufaqsb-appearance"><?php esc_html_e( 'Override Appearance', 'ufaqsw' ); ?></label>
					<select id="ufaqsb-appearance">
						<option value=""><?php esc_html_e( 'Use group default', 'ufaqsw' ); ?></option>
						<?php foreach ( $appearances as $appearance ) : ?>
							<option value="<?php echo esc_attr( $appearance->ID ); ?>">
								<?php echo esc_html( $appearance->post_title ); ?>
							</option>
						<?php endforeach; ?>
					</select>
					<span class="ufaqsb-help"><?php esc_html_e( 'Select an appearance preset to use for this embed, overriding the group\'s linked appearance.', 'ufaqsw' ); ?></span>
				</div>
				<?php else : ?>
				<p style="margin:0; font-size:13px; color:#72777c;">
					<?php
					printf(
						wp_kses(
							/* translators: %s: link to create a new appearance */
							__( 'No appearance presets found. <a href="%s" target="_blank">Create an appearance</a> to enable this option.', 'ufaqsw' ),
							array( 'a' => array( 'href' => array(), 'target' => array() ) )
						),
						esc_url( admin_url( 'post-new.php?post_type=ufaqsw_appearance' ) )
					);
					?>
				</p>
				<?php endif; ?>
			</div>

		</div><!-- /.ufaqsb-controls -->

		<!-- ── Right: Output ───────────────────────── -->
		<div class="ufaqsb-output">
			<div class="ufaqsb-output-sticky">

				<div class="ufaqsb-card">
					<h2 class="ufaqsb-card-title">
						<span class="dashicons dashicons-editor-code"></span>
						<?php esc_html_e( 'Generated Shortcode', 'ufaqsw' ); ?>
					</h2>

					<div class="ufaqsb-shortcode-block">
						<code id="ufaqsb-shortcode-text">
							<span class="ufaqsb-placeholder"><?php esc_html_e( 'Select a FAQ group to generate a shortcode…', 'ufaqsw' ); ?></span>
						</code>
					</div>

					<button
						type="button"
						id="ufaqsb-copy-btn"
						class="ufaqsb-copy-btn"
						disabled
					>
						<span class="dashicons dashicons-clipboard"></span>
						<span id="ufaqsb-copy-label"><?php esc_html_e( 'Copy Shortcode', 'ufaqsw' ); ?></span>
					</button>
				</div>

				<div class="ufaqsb-usage-guide">
					<h3><?php esc_html_e( 'Where to use it', 'ufaqsw' ); ?></h3>
					<ul>
						<li><?php esc_html_e( 'Paste into any Classic Editor post or page.', 'ufaqsw' ); ?></li>
						<li>
							<?php
							printf(
								wp_kses(
									/* translators: %s: shortcode block name */
									__( 'In the Block Editor, add a <strong>%s</strong> block and paste the shortcode.', 'ufaqsw' ),
									array( 'strong' => array() )
								),
								esc_html__( 'Shortcode', 'ufaqsw' )
							);
							?>
						</li>
						<li><?php esc_html_e( 'Add to a Text widget in Appearance → Widgets.', 'ufaqsw' ); ?></li>
						<li>
							<?php
							printf(
								wp_kses(
									__( 'Use directly in PHP with <code>echo do_shortcode(…);</code>', 'ufaqsw' ),
									array( 'code' => array() )
								)
							);
							?>
						</li>
					</ul>
				</div>

			</div><!-- /.ufaqsb-output-sticky -->
		</div><!-- /.ufaqsb-output -->

	</div><!-- /.ufaqsb-columns -->

	<?php endif; // empty faq_groups ?>

</div><!-- /.wrap -->

<script>
( function () {
	'use strict';

	var groupEl        = document.getElementById( 'ufaqsb-group' );
	var orderEl        = document.getElementById( 'ufaqsb-order' );
	var hideTitleEl    = document.getElementById( 'ufaqsb-hide-title' );
	var expandAllEl    = document.getElementById( 'ufaqsb-show-expand-all' );
	var behaviourEl    = document.getElementById( 'ufaqsb-behaviour' );
	var excludeItemsEl = document.getElementById( 'ufaqsb-exclude-items' );
	var appearanceEl   = document.getElementById( 'ufaqsb-appearance' );
	var outputEl       = document.getElementById( 'ufaqsb-shortcode-text' );
	var copyBtn        = document.getElementById( 'ufaqsb-copy-btn' );
	var copyLabel      = document.getElementById( 'ufaqsb-copy-label' );

	var sectionDisplay    = document.getElementById( 'ufaqsb-section-display' );
	var sectionAll        = document.getElementById( 'ufaqsb-section-all' );
	var sectionSingle     = document.getElementById( 'ufaqsb-section-single' );
	var sectionAppearance = document.getElementById( 'ufaqsb-section-appearance' );

	if ( ! groupEl ) return;

	function show( el ) { if ( el ) el.classList.add( 'ufaqsb-visible' ); }
	function hide( el ) { if ( el ) el.classList.remove( 'ufaqsb-visible' ); }

	function buildShortcode() {
		var group = groupEl ? groupEl.value : '';
		if ( ! group ) return '';

		var sc = '';

		if ( group === 'all' ) {
			sc = 'ufaqsw-all';

			// Excluded groups.
			var excludeChecked = [];
			document.querySelectorAll( '.ufaqsb-exclude-group:checked' ).forEach( function ( el ) {
				excludeChecked.push( el.value );
			} );
			if ( excludeChecked.length ) {
				sc += ' exclude="' + excludeChecked.join( ',' ) + '"';
			}

			// Behaviour.
			if ( behaviourEl && behaviourEl.value ) {
				sc += ' behaviour="' + behaviourEl.value + '"';
			}
		} else {
			sc = 'ufaqsw id="' + group + '"';

			// Exclude individual items.
			if ( excludeItemsEl ) {
				var items = excludeItemsEl.value.replace( /\s/g, '' );
				if ( items ) sc += ' exclude_items="' + items + '"';
			}
		}

		// Shared options.
		if ( orderEl && orderEl.value === 'desc' ) {
			sc += ' elements_order="desc"';
		}
		if ( hideTitleEl && hideTitleEl.checked ) {
			sc += ' title_hide="yes"';
		}
		if ( expandAllEl && expandAllEl.checked ) {
			sc += ' show_expand_all="yes"';
		}
		if ( appearanceEl && appearanceEl.value ) {
			sc += ' appearance_id="' + appearanceEl.value + '"';
		}

		return '[' + sc + ']';
	}

	function updateOutput() {
		var group = groupEl ? groupEl.value : '';
		var sc    = buildShortcode();

		// Toggle conditional sections.
		if ( group ) {
			show( sectionDisplay );
			show( sectionAppearance );
		} else {
			hide( sectionDisplay );
			hide( sectionAppearance );
		}

		if ( group === 'all' ) {
			show( sectionAll );
			hide( sectionSingle );
		} else if ( group ) {
			hide( sectionAll );
			show( sectionSingle );
		} else {
			hide( sectionAll );
			hide( sectionSingle );
		}

		// Update output.
		if ( sc ) {
			outputEl.innerHTML = '';
			outputEl.textContent = sc;
			copyBtn.disabled = false;
		} else {
			outputEl.innerHTML = '<span class="ufaqsb-placeholder"><?php echo esc_js( __( 'Select a FAQ group to generate a shortcode…', 'ufaqsw' ) ); ?></span>';
			copyBtn.disabled = true;
		}

		// Reset copy button if user changes options.
		copyBtn.classList.remove( 'ufaqsb-copied' );
		copyLabel.textContent = '<?php echo esc_js( __( 'Copy Shortcode', 'ufaqsw' ) ); ?>';
	}

	// Wire up all inputs.
	var inputs = document.querySelectorAll(
		'#ufaqsb-group, #ufaqsb-order, #ufaqsb-hide-title, #ufaqsb-show-expand-all, ' +
		'#ufaqsb-behaviour, #ufaqsb-exclude-items, #ufaqsb-appearance, .ufaqsb-exclude-group'
	);
	inputs.forEach( function ( el ) {
		el.addEventListener( 'change', updateOutput );
		if ( el.tagName === 'INPUT' && el.type === 'text' ) {
			el.addEventListener( 'input', updateOutput );
		}
	} );

	// Copy button.
	if ( copyBtn ) {
		copyBtn.addEventListener( 'click', function () {
			var sc = buildShortcode();
			if ( ! sc ) return;

			if ( navigator.clipboard && navigator.clipboard.writeText ) {
				navigator.clipboard.writeText( sc ).then( function () {
					onCopied();
				} );
			} else {
				// Fallback for older browsers.
				var ta = document.createElement( 'textarea' );
				ta.value = sc;
				ta.style.position = 'fixed';
				ta.style.opacity  = '0';
				document.body.appendChild( ta );
				ta.select();
				document.execCommand( 'copy' );
				document.body.removeChild( ta );
				onCopied();
			}
		} );
	}

	function onCopied() {
		copyBtn.classList.add( 'ufaqsb-copied' );
		copyLabel.textContent = '<?php echo esc_js( __( 'Copied!', 'ufaqsw' ) ); ?>';
		setTimeout( function () {
			copyBtn.classList.remove( 'ufaqsb-copied' );
			copyLabel.textContent = '<?php echo esc_js( __( 'Copy Shortcode', 'ufaqsw' ) ); ?>';
		}, 2000 );
	}

} )();
</script>
