export function NotificationToast({ notification, setNotification }) {  
	return (
		<div className="fixed bottom-4 left-4 z-[9999] animate-slide-in">
			<div className={`
				px-4 py-3 rounded-lg shadow-lg flex items-center gap-3 min-w-[300px]
				${notification.type === 'success' ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200'}
			`}>
				{notification.type === 'success' ? (
					<svg className="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 13l4 4L19 7"></path>
					</svg>
				) : (
					<svg className="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12"></path>
					</svg>
				)}
				<span className={`text-sm font-medium ${notification.type === 'success' ? 'text-green-800' : 'text-red-800'}`}>
					{notification.message}
				</span>
				<button
					onClick={() => setNotification(null)}
					className="ml-auto text-gray-400 hover:text-gray-600"
				>
					<svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12"></path>
					</svg>
				</button>
			</div>
		</div>
	);
}