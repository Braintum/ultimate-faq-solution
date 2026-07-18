import React from 'react';

const BackIcon = () => (
	<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
		<polyline points="13 5 7 10 13 15" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" />
	</svg>
);

const CloseIcon = () => (
	<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
		<line x1="5" y1="5" x2="15" y2="15" stroke="currentColor" strokeWidth="2" strokeLinecap="round" />
		<line x1="15" y1="5" x2="5" y2="15" stroke="currentColor" strokeWidth="2" strokeLinecap="round" />
	</svg>
);

const ChevronRight = () => (
	<svg width="10" height="10" viewBox="0 0 20 20" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">
		<polyline points="7 5 13 10 7 15" />
	</svg>
);

export const Header = ({
	onClose,
	onBack,
	showBackButton,
	title,
	intro,
	headerColor,
	textColor,
	back_button_label,
	close_button_label,
	breadcrumbs,
	onBreadcrumbClick,
}) => {
	const headerStyle = {
		...(headerColor ? { backgroundColor: headerColor } : {}),
		...(textColor ? { color: textColor } : {}),
	};
	const btnStyle = textColor ? { color: textColor } : undefined;

	const showBreadcrumbs = breadcrumbs && breadcrumbs.length > 1;

	return (
		<div className="header" style={headerStyle}>
			<div className="chatbot-drag-handle" aria-hidden="true" />
			<div className="header-content">
				{showBackButton && (
					<button
						onClick={onBack}
						className="faq-back-button"
						style={btnStyle}
						aria-label={back_button_label || 'Back'}
						title={back_button_label || 'Back'}
					>
						<BackIcon />
					</button>
				)}
				<div className="chatbot-title-group">
					<span className="chatbot-title">{title}</span>
					{intro && <span className="chatbot-intro">{intro}</span>}
				</div>
				<button
					onClick={onClose}
					className="chatbot-close-button"
					style={btnStyle}
					aria-label={close_button_label || 'Close'}
					title={close_button_label || 'Close'}
				>
					<CloseIcon />
				</button>
			</div>

			{showBreadcrumbs && (
				<nav className="chatbot-breadcrumbs" aria-label="Navigation breadcrumbs">
					{breadcrumbs.map((crumb, i) => (
						<React.Fragment key={i}>
							{i > 0 && <ChevronRight />}
							{crumb.targetView ? (
								<button
									className="breadcrumb-item"
									onClick={() => onBreadcrumbClick && onBreadcrumbClick(crumb.targetView)}
									style={textColor ? { color: textColor } : undefined}
									type="button"
								>
									{crumb.label}
								</button>
							) : (
								<span className="breadcrumb-item breadcrumb-current" style={textColor ? { color: textColor } : undefined}>
									{crumb.label}
								</span>
							)}
						</React.Fragment>
					))}
				</nav>
			)}
		</div>
	);
};
