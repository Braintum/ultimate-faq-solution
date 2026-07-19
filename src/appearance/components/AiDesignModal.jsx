import React, { useState } from 'react';
import { createPortal } from 'react-dom';
import { __ } from '@wordpress/i18n';

const QUICK_TAGS = [
    { label: 'Minimal',      text: 'clean and minimal' },
    { label: 'Dark',         text: 'dark mode' },
    { label: 'Colorful',     text: 'colorful and vibrant' },
    { label: 'Professional', text: 'professional corporate' },
    { label: 'Card layout',  text: 'card-style layout with shadows' },
    { label: 'Left accent',  text: 'left accent border' },
];

// ── Swatch preview ──────────────────────────────────────────────────────────

function ResultSwatch({ settings: s }) {
    const radius = ( s.border_radius ?? '4' ) + 'px';

    return (
        <div style={{ border: '1px solid #e5e7eb', borderRadius: '8px', overflow: 'hidden' }}>
            <div style={{
                display: 'flex', alignItems: 'center', justifyContent: 'space-between',
                padding: '10px 14px', fontSize: '12px',
                fontWeight: s.question_font_weight || '500',
                background: s.question_background_color || '#f8fafc',
                color: s.question_color || '#111827',
                borderBottom: '1px solid ' + ( s.border_color || '#e2e8f0' ),
                borderRadius: `${radius} ${radius} 0 0`,
            }}>
                <span>{__( 'Question row', 'ufaqsw' )}</span>
                <span style={{ color: s.icon_color || s.question_color || '#6b7280' }}>+</span>
            </div>
            <div style={{
                padding: '10px 14px', fontSize: '12px',
                background: s.answer_background_color || '#ffffff',
                color: s.answer_color || '#374151',
            }}>
                {__( 'Answer content area', 'ufaqsw' )}
            </div>
        </div>
    );
}

// ── Main modal ──────────────────────────────────────────────────────────────

export function AiDesignModal({ isOpen, onClose, onApply, currentValues }) {
    const [prompt, setPrompt] = useState( '' );
    const [useCurrentAsBase, setUseCurrentAsBase] = useState( true );
    const [status, setStatus] = useState( 'idle' ); // idle | loading | success | error
    const [result, setResult] = useState( null );
    const [errorMsg, setErrorMsg] = useState( '' );

    const data     = window.ufaqAppearanceData || {};
    const aiEnabled  = data.aiEnabled;
    const endpoint   = data.aiGenerateEndpoint || '/wp-json/ufaqsw/v1/ai/generate-design';

    if ( !isOpen ) return null;

    async function handleGenerate() {
        if ( !prompt.trim() ) return;
        setStatus( 'loading' );
        setErrorMsg( '' );
        try {
            const res  = await fetch( endpoint, {
                method:  'POST',
                headers: { 'Content-Type': 'application/json', 'X-WP-Nonce': data.nonce || '' },
                body:    JSON.stringify( {
                    prompt,
                    current_settings: useCurrentAsBase ? currentValues : {},
                } ),
            } );
            const body = await res.json();
            if ( !res.ok ) {
                throw new Error( body.message || __( 'Generation failed.', 'ufaqsw' ) );
            }
            if ( !body.success ) {
                throw new Error( __( 'Generation failed.', 'ufaqsw' ) );
            }
            setResult( body.settings );
            setStatus( 'success' );
        } catch ( e ) {
            setErrorMsg( e.message || __( 'Something went wrong. Please try again.', 'ufaqsw' ) );
            setStatus( 'error' );
        }
    }

    function handleApply() {
        if ( result ) {
            onApply( null, result );
            onClose();
        }
    }

    function handleRegenerate() {
        setStatus( 'idle' );
        setResult( null );
        setErrorMsg( '' );
    }

    function appendTag( text ) {
        setPrompt( p => p ? p.trimEnd() + ', ' + text : text );
    }

    const modal = (
        <div
            style={{
                position: 'fixed', inset: 0, zIndex: 100000,
                display: 'flex', alignItems: 'center', justifyContent: 'center',
                background: 'rgba(0,0,0,0.55)',
            }}
            onClick={( e ) => { if ( e.target === e.currentTarget ) onClose(); }}
        >
            <div style={{
                background: '#fff', borderRadius: '12px',
                width: '520px', maxWidth: '95vw',
                boxShadow: '0 20px 60px rgba(0,0,0,0.25)',
                overflow: 'hidden', display: 'flex', flexDirection: 'column',
            }}>
                {/* Header */}
                <div style={{
                    display: 'flex', alignItems: 'center', justifyContent: 'space-between',
                    padding: '14px 18px', borderBottom: '1px solid #e5e7eb',
                }}>
                    <div style={{ display: 'flex', alignItems: 'center', gap: '8px' }}>
                        <span style={{ fontSize: '16px' }}>✨</span>
                        <h2 style={{ margin: 0, fontSize: '15px', fontWeight: '600', color: '#111827' }}>
                            {__( 'AI Design Generator', 'ufaqsw' )}
                        </h2>
                    </div>
                    <button
                        type="button"
                        onClick={onClose}
                        style={{ background: 'none', border: 'none', fontSize: '22px', lineHeight: 1, cursor: 'pointer', color: '#9ca3af', padding: '0 2px' }}
                    >
                        ×
                    </button>
                </div>

                {/* Body */}
                <div style={{ padding: '18px' }}>
                    {!aiEnabled ? (
                        // AI not enabled
                        <div style={{ textAlign: 'center', padding: '24px 16px' }}>
                            <div style={{ fontSize: '28px', marginBottom: '10px' }}>🔌</div>
                            <p style={{ margin: '0 0 10px', color: '#374151', fontWeight: '500' }}>
                                {__( 'AI Integration is not enabled.', 'ufaqsw' )}
                            </p>
                            <a
                                href="edit.php?post_type=ufaqsw&page=ufaqsw_ai_integration_settings"
                                style={{ color: '#3b82f6', fontSize: '13px' }}
                            >
                                {__( 'Enable it in AI Integration Settings →', 'ufaqsw' )}
                            </a>
                        </div>
                    ) : status === 'loading' ? (
                        // Generating…
                        <div style={{ textAlign: 'center', padding: '40px 16px', color: '#6b7280' }}>
                            <div style={{ marginBottom: '14px' }}>
                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#6366f1" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" style={{ animation: 'spin 1s linear infinite', display: 'inline-block' }}>
                                    <path d="M21 12a9 9 0 1 1-6.219-8.56" />
                                </svg>
                            </div>
                            <p style={{ margin: 0, fontSize: '14px' }}>{__( 'Generating your design…', 'ufaqsw' )}</p>
                            <style>{`@keyframes spin { to { transform: rotate(360deg); } }`}</style>
                        </div>
                    ) : status === 'success' && result ? (
                        // Result
                        <>
                            <p style={{ margin: '0 0 10px', fontSize: '12px', fontWeight: '500', color: '#6b7280', textTransform: 'uppercase', letterSpacing: '0.05em' }}>
                                {__( 'Preview', 'ufaqsw' )}
                            </p>
                            <ResultSwatch settings={result} />
                            <p style={{ margin: '10px 0 0', fontSize: '11px', color: '#9ca3af', display: 'flex', alignItems: 'center', gap: '5px' }}>
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" style={{ flexShrink: 0 }}><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                {__( 'Color approximation only — apply the design to see it rendered in the live preview.', 'ufaqsw' )}
                            </p>
                            <div style={{ display: 'flex', gap: '8px', marginTop: '14px' }}>
                                <button
                                    type="button"
                                    onClick={handleApply}
                                    style={{
                                        flex: 1, background: 'linear-gradient(135deg,#6366f1,#3b82f6)',
                                        color: '#fff', border: 'none', borderRadius: '6px',
                                        padding: '10px', fontSize: '13px', fontWeight: '600', cursor: 'pointer',
                                    }}
                                >
                                    {__( 'Apply Design', 'ufaqsw' )}
                                </button>
                                <button
                                    type="button"
                                    onClick={handleRegenerate}
                                    style={{
                                        flex: 1, background: '#f3f4f6', color: '#374151',
                                        border: '1px solid #d1d5db', borderRadius: '6px',
                                        padding: '10px', fontSize: '13px', cursor: 'pointer',
                                    }}
                                >
                                    {__( 'Edit & Regenerate', 'ufaqsw' )}
                                </button>
                            </div>
                        </>
                    ) : (
                        // Compose
                        <>
                            <label style={{ display: 'block', fontSize: '13px', fontWeight: '500', color: '#374151', marginBottom: '6px' }}>
                                {__( 'Describe your ideal FAQ design:', 'ufaqsw' )}
                            </label>
                            <textarea
                                value={prompt}
                                onChange={( e ) => setPrompt( e.target.value )}
                                rows={4}
                                placeholder={__( 'e.g. "Dark mode with deep navy background, subtle card shadows, and purple accents for a SaaS product"', 'ufaqsw' )}
                                style={{
                                    width: '100%', padding: '10px 12px', boxSizing: 'border-box',
                                    border: '1px solid #d1d5db', borderRadius: '6px',
                                    fontSize: '13px', resize: 'vertical', fontFamily: 'inherit',
                                    marginBottom: '12px', lineHeight: '1.5',
                                }}
                            />

                            <div style={{ marginBottom: '14px' }}>
                                <p style={{ margin: '0 0 6px', fontSize: '11px', color: '#9ca3af', textTransform: 'uppercase', letterSpacing: '0.05em' }}>
                                    {__( 'Quick tags', 'ufaqsw' )}
                                </p>
                                <div style={{ display: 'flex', flexWrap: 'wrap', gap: '5px' }}>
                                    {QUICK_TAGS.map( ( tag ) => (
                                        <button
                                            key={tag.label}
                                            type="button"
                                            onClick={() => appendTag( tag.text )}
                                            style={{
                                                fontSize: '12px', padding: '3px 10px', borderRadius: '99px',
                                                border: '1px solid #d1d5db', background: '#f9fafb',
                                                cursor: 'pointer', color: '#374151',
                                            }}
                                        >
                                            {tag.label}
                                        </button>
                                    ) )}
                                </div>
                            </div>

                            <label style={{ display: 'flex', alignItems: 'center', gap: '7px', cursor: 'pointer', fontSize: '13px', color: '#4b5563', marginBottom: '14px' }}>
                                <input
                                    type="checkbox"
                                    checked={useCurrentAsBase}
                                    onChange={( e ) => setUseCurrentAsBase( e.target.checked )}
                                />
                                {__( 'Build on current settings (uncheck to start from scratch)', 'ufaqsw' )}
                            </label>

                            {status === 'error' && errorMsg && (
                                <div style={{
                                    marginBottom: '12px', padding: '10px 12px',
                                    background: '#fef2f2', border: '1px solid #fecaca',
                                    borderRadius: '6px', fontSize: '13px', color: '#dc2626',
                                }}>
                                    {errorMsg}
                                </div>
                            )}

                            <button
                                type="button"
                                onClick={handleGenerate}
                                disabled={!prompt.trim()}
                                style={{
                                    width: '100%',
                                    background: prompt.trim() ? 'linear-gradient(135deg,#6366f1,#3b82f6)' : '#e5e7eb',
                                    color: prompt.trim() ? '#fff' : '#9ca3af',
                                    border: 'none', borderRadius: '6px',
                                    padding: '11px', fontSize: '14px', fontWeight: '600',
                                    cursor: prompt.trim() ? 'pointer' : 'not-allowed',
                                    display: 'flex', alignItems: 'center', justifyContent: 'center', gap: '6px',
                                }}
                            >
                                <span>✨</span>
                                {__( 'Generate Design', 'ufaqsw' )}
                            </button>
                        </>
                    )}
                </div>
            </div>
        </div>
    );

    return createPortal( modal, document.body );
}
