import React, { useState, useEffect } from 'react';
import '../styles/floatingButton.css';

export const FloatingButton = ({ onClick }) => {
	const [title, setTitle] = useState('');
	const [icon, setIcon] = useState('');
	const [pulseEnabled, setPulseEnabled] = useState(false);
	const [badgeEnabled, setBadgeEnabled] = useState(false);
	const [pulseColor, setPulseColor] = useState('#1a185e');

	useEffect(() => {
		if (window.chatbotData) {
			setTitle(window.chatbotData.floating_button_title || '');
			setIcon(window.chatbotData.floating_button_icon || '');
			setPulseEnabled(!!window.chatbotData.pulse_enabled);
			setBadgeEnabled(!!window.chatbotData.badge_enabled);
			setPulseColor(window.chatbotData.header_background_color || '#1a185e');
		}
	}, []);

	return (
		<div className="ufaqsw_floating-chat-wrapper">
			{badgeEnabled && <span className="ufaqsw_badge" aria-hidden="true" />}
			<button
				onClick={onClick}
				className="ufaqsw_floating-chat-button"
				title={title}
				aria-label={title || 'Open FAQ Assistant'}
				style={{
					backgroundImage: icon ? `url('${icon}')` : undefined,
				}}
			/>
			{pulseEnabled && (
				<span
					className="ufaqsw_pulse-ring"
					style={{ borderColor: pulseColor }}
					aria-hidden="true"
				/>
			)}
		</div>
	);
};
