import { __ } from '@wordpress/i18n';

export function PreviewPanel({
	iframeLoaded,
	iframeSrc,
	iframeTitle,
	iframeRef,
	setIframeLoaded
}) {
  return (
		<div className="flex-1 flex flex-col gap-2">
			<div className="flex items-center justify-between">
				<div className="text-sm text-gray-600">{__('Live Preview', 'ufaqsw')}</div>
				<div className="text-xs text-gray-500"></div>
			</div>

			<div className="flex-1 border rounded overflow-hidden relative">
				{/* Loading overlay for iframe */}
				{!iframeLoaded && (
					<div className="absolute inset-0 bg-gray-50 flex items-center justify-center z-10">
						<div className="text-center">
							<svg className="animate-spin h-12 w-12 text-blue-600 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
								<circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
								<path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
							</svg>
							<p className="text-gray-600 text-sm">{__('Loading preview...', 'ufaqsw')}</p>
						</div>
					</div>
				)}
				
				<iframe
					title={iframeTitle}
					src={iframeSrc}
					ref={iframeRef}
					onLoad={() => setIframeLoaded(true)}
					className="w-full h-[100%]"
				/>
			</div>
		</div>
	);
}