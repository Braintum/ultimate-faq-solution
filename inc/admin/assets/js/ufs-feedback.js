const { __ } = wp.i18n;

jQuery(document).ready(function($) {
	const pluginSlug = UFS_FEEDBACK.plugin_slug;
	const deactivateLink = $('tr[data-slug="ultimate-faq-solution"] .deactivate a');

	if (!deactivateLink.length) return;

	deactivateLink.on('click', function(e) {
		e.preventDefault();
		showFeedbackModal($(this).attr('href'));
	});

	function showFeedbackModal(deactivateUrl) {
		const modal = $(
			`<div id="ufs-feedback-modal" class="ufs-modal" style="position:fixed;z-index:9999;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.32);display:flex;align-items:center;justify-content:center;">
				<div class="ufs-modal-content" style="position:relative;z-index:2;">
					<h2>${__('Quick Feedback', 'ufaqsw')}</h2>
					<p>${__('Please let us know why you\'re deactivating the plugin:', 'ufaqsw')}</p>
					<p><em>${__('Your feedback helps us improve the plugin and provide better features for all users.', 'ufaqsw')}</em></p>
					<form id="ufs-feedback-form">
						<label><input type="radio" name="reason" value="no_longer_needed"> ${__('I no longer need the plugin', 'ufaqsw')}</label><br>

						<label>
							<input type="radio" name="reason" value="switching_plugin"> ${__('I\'m switching to a different plugin', 'ufaqsw')}
						</label>
						<input type="text" name="switching_details" class="ufs-extra-input" placeholder="${__('Which plugin are you switching to?', 'ufaqsw')}" style="display:none;width:100%;margin:5px 0 10px 0;"><br>

						<label>
							<input type="radio" name="reason" value="couldnt_work"> ${__('I couldn\'t get the plugin to work', 'ufaqsw')}
						</label>
						<input type="text" name="couldnt_work_details" class="ufs-extra-input" placeholder="${__('What issue did you face?', 'ufaqsw')}" style="display:none;width:100%;margin:5px 0 10px 0;"><br>

						<label><input type="radio" name="reason" value="temporary"> ${__('It\'s a temporary deactivation', 'ufaqsw')}</label><br>

						<label><input type="radio" name="reason" value="other"> ${__('Other', 'ufaqsw')}</label>
						<textarea name="details" placeholder="${__('Please share more details...', 'ufaqsw')}" style="display:none;margin-top:8px;width:100%;"></textarea>

						<div class="ufs-buttons">
							<button type="button" class="button button-primary" id="ufs-submit">${__('Deactivate', 'ufaqsw')}</button>
							<button type="button" class="button" id="ufs-skip">${__('Skip and Deactivate', 'ufaqsw')}</button>
						</div>
					</form>
				</div>
			</div>
		`);

		$('body').append(modal);

		// Overlay click handler (close if click outside modal content)
		$('#ufs-feedback-modal').on('mousedown', function(e) {
			if ($(e.target).is('#ufs-feedback-modal')) {
				$('#ufs-feedback-modal').remove();
				$('#ufs-loading-overlay').remove();
			}
		});

		// Loading overlay (hidden initially)
		const loader = $(`
			<div id="ufs-loading-overlay" class="ufs-loading-overlay" style="display:none;">
				<div class="ufs-spinner"></div>
				<p>${__('Deactivating...', 'ufaqsw')}</p>
			</div>
		`);
		$('body').append(loader);

		$('input[name="reason"]').on('change', function() {
			const val = $(this).val();
			$('.ufs-extra-input').hide();
			$('textarea[name="details"]').hide();

			if (val === 'switching_plugin') {
				$('input[name="switching_details"]').show().prop('required', true).focus();
			} else if (val === 'couldnt_work') {
				$('input[name="couldnt_work_details"]').show().prop('required', true).focus();
			} else if (val === 'other') {
				$('textarea[name="details"]').show().prop('required', true).focus();
			}
		});

		$('#ufs-skip').on('click', function() {
			window.location.href = deactivateUrl;
		});

		$('#ufs-submit').on('click', function(e) {
			e.preventDefault();
			const reason = $('input[name="reason"]:checked').val();
			if (!reason) {
				alert(__('Please select a reason. Your feedback helps us improve the plugin.', 'ufaqsw'));
				return;
			}

			let details = '';
			let required = false;

			if (reason === 'switching_plugin') {
				details = $('input[name="switching_details"]').val();
				required = true;
			} else if (reason === 'couldnt_work') {
				details = $('input[name="couldnt_work_details"]').val();
				required = true;
			} else if (reason === 'other') {
				details = $('textarea[name="details"]').val();
				required = true;
			}

			if (required && !details) {
				alert(__('Please tell us a bit more before before deactivating.', 'ufaqsw'));
				return;
			}

			// Show loading overlay
			$('#ufs-loading-overlay').fadeIn(200);
			
			$.ajax({
				url: UFS_FEEDBACK.ajax_url,
				type: 'POST',
				data: {
					action: 'ufs_submit_feedback',
					security: UFS_FEEDBACK.nonce,
					reason: reason,
					details: details
				},
				success: function () {
					window.location.href = deactivateUrl;
				},
				error: function () {
					alert(t.alert_error);
					window.location.href = deactivateUrl;
				}
			});
		});
	}
});
