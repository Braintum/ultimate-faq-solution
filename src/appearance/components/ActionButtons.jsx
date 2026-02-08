import { __ } from '@wordpress/i18n';

export function ActionButtons({ onReset, onSave, isSaving = false }) {
	return (
		<div className="flex gap-2 justify-between">
			<button
				type="button"
				onClick={onReset}
				disabled={isSaving}
				className="px-3 py-1 rounded border text-sm disabled:opacity-50 disabled:cursor-not-allowed"
			>
				{ __('Reset', 'ufaqsw') }
			</button>
			<button
				type="button"
				onClick={onSave}
				disabled={isSaving}
				className="px-3 py-1 rounded bg-blue-600 text-white text-sm disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
			>
				{isSaving && (
					<svg className="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
					<circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
					<path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
					</svg>
				)}
				{isSaving ? __('Saving...', 'ufaqsw') : __('Save', 'ufaqsw')}
			</button>
		</div>
	);
}