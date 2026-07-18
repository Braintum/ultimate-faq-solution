import React, { useState, useRef } from 'react';
import submitQuestion from '../models/submitQuestion';

const AskQuestion = ({ config, ajaxUrl, nonce, onCancel, onSuccess }) => {
	const [name, setName] = useState('');
	const [email, setEmail] = useState('');
	const [question, setQuestion] = useState('');
	const [gdprChecked, setGdprChecked] = useState(false);
	const [submitting, setSubmitting] = useState(false);
	const [submitted, setSubmitted] = useState(false);
	const [error, setError] = useState('');
	const honeypotRef = useRef(null);

	const handleSubmit = async (e) => {
		e.preventDefault();
		setError('');

		if (config.gdpr_enabled && !gdprChecked) {
			setError(config.gdpr_error || 'Please accept the privacy policy to continue.');
			return;
		}

		setSubmitting(true);
		const result = await submitQuestion({
			ajaxUrl,
			nonce,
			name: name.trim(),
			email: email.trim(),
			question: question.trim(),
			honeypot: honeypotRef.current?.value || '',
		});
		setSubmitting(false);

		if (result && result.success) {
			setSubmitted(true);
		} else {
			const msg = result?.data?.message || 'Something went wrong. Please try again.';
			setError(msg);
		}
	};

	if (submitted) {
		return (
			<div className="ask-success">
				<div className="success-icon">
					<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">
						<polyline points="20 6 9 17 4 12" />
					</svg>
				</div>
				<h3>{config.success_title || 'Question Received!'}</h3>
				<p>{config.success_message || 'Thank you! We\'ll get back to you as soon as possible.'}</p>
				<button className="btn-back-to-faqs" type="button" onClick={onSuccess}>
					{config.back_to_faqs_label || 'Back to FAQs'}
				</button>
			</div>
		);
	}

	return (
		<div className="ask-question-form">
			<form onSubmit={handleSubmit} noValidate>
				<div className="form-group">
					<label htmlFor="ufaqsw-ask-name">
						{config.name_label || 'Your Name'} <span className="required" aria-hidden="true">*</span>
					</label>
					<input
						id="ufaqsw-ask-name"
						type="text"
						value={name}
						onChange={(e) => setName(e.target.value)}
						required
						autoComplete="name"
					/>
				</div>

				<div className="form-group">
					<label htmlFor="ufaqsw-ask-email">
						{config.email_label || 'Your Email'} <span className="required" aria-hidden="true">*</span>
					</label>
					<input
						id="ufaqsw-ask-email"
						type="email"
						value={email}
						onChange={(e) => setEmail(e.target.value)}
						required
						autoComplete="email"
					/>
				</div>

				<div className="form-group">
					<label htmlFor="ufaqsw-ask-question">
						{config.question_label || 'Your Question'} <span className="required" aria-hidden="true">*</span>
					</label>
					<textarea
						id="ufaqsw-ask-question"
						value={question}
						onChange={(e) => setQuestion(e.target.value)}
						required
						rows={4}
					/>
				</div>

				{config.gdpr_enabled && (
					<div className="form-group form-gdpr">
						<label>
							<input
								type="checkbox"
								checked={gdprChecked}
								onChange={(e) => setGdprChecked(e.target.checked)}
							/>
							<span dangerouslySetInnerHTML={{ __html: config.gdpr_text || 'I agree to the privacy policy.' }} />
						</label>
					</div>
				)}

				{/* Honeypot field — hidden from real users */}
				<div style={{ position: 'absolute', left: '-9999px', top: 'auto', width: '1px', height: '1px', overflow: 'hidden' }} aria-hidden="true">
					<input
						type="text"
						name="website"
						ref={honeypotRef}
						tabIndex={-1}
						autoComplete="off"
					/>
				</div>

				{error && <div className="form-error" role="alert">{error}</div>}

				<div className="form-actions">
					<button type="button" className="btn-cancel" onClick={onCancel}>
						{config.cancel_label || 'Cancel'}
					</button>
					<button type="submit" className="btn-submit" disabled={submitting}>
						{submitting ? '…' : (config.submit_label || 'Send Question')}
					</button>
				</div>
			</form>
		</div>
	);
};

export default AskQuestion;
