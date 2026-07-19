import React, { useState, useMemo, useEffect } from 'react';
import { createPortal } from 'react-dom';
import { __ } from '@wordpress/i18n';

const CATEGORY_LABELS = {
    minimal:      'Minimal',
    colorful:     'Colorful',
    dark:         'Dark',
    professional: 'Professional',
    legacy:       'Legacy Templates',
};

function categoryLabel( cat ) {
    return CATEGORY_LABELS[ cat ] || ( cat.charAt( 0 ).toUpperCase() + cat.slice( 1 ) );
}

function PresetCard( { preset, onApply } ) {
    const s = preset.settings || {};
    const radius = ( s.border_radius || '4' ) + 'px';

    return (
        <div style={{
            background: '#fff',
            border: '1px solid #e5e7eb',
            borderRadius: '10px',
            overflow: 'hidden',
            display: 'flex',
            flexDirection: 'column',
            boxShadow: '0 1px 3px rgba(0,0,0,0.06)',
            transition: 'box-shadow 0.2s, border-color 0.2s',
        }}>
            {/* Colour swatch */}
            <div style={{ margin: '12px 12px 0', borderRadius: radius, overflow: 'hidden', border: '1px solid #e5e7eb' }}>
                <div style={{
                    display: 'flex',
                    alignItems: 'center',
                    justifyContent: 'space-between',
                    padding: '9px 13px',
                    fontSize: '11px',
                    fontWeight: '600',
                    borderBottom: '1px solid ' + ( s.border_color || '#e2e8f0' ),
                    background: s.question_background_color || '#f8fafc',
                    color: s.question_color || '#111827',
                }}>
                    <span>{__( 'Question row', 'ufaqsw' )}</span>
                    <span>+</span>
                </div>
                <div style={{
                    padding: '9px 13px',
                    fontSize: '11px',
                    lineHeight: '1.5',
                    background: s.answer_background_color || '#fff',
                    color: s.answer_color || '#555',
                }}>
                    {__( 'Answer panel preview text.', 'ufaqsw' )}
                </div>
            </div>

            {/* Meta */}
            <div style={{ padding: '10px 12px 8px', flex: 1 }}>
                <div style={{ fontSize: '13px', fontWeight: '600', color: '#111827', marginBottom: '3px' }}>
                    {preset.name || ''}
                </div>
                <div style={{ fontSize: '11px', color: '#6b7280', marginBottom: '8px', lineHeight: '1.5' }}>
                    {preset.description || ''}
                </div>
                <div style={{ display: 'flex', gap: '6px', flexWrap: 'wrap' }}>
                    <span style={{ display: 'inline-block', padding: '2px 8px', borderRadius: '999px', fontSize: '11px', fontWeight: '500', background: '#e0e7ff', color: '#3730a3' }}>
                        {( ( s.layout || 'classic' ).charAt( 0 ).toUpperCase() + ( s.layout || 'classic' ).slice( 1 ) )}
                    </span>
                    {preset.category && (
                        <span style={{ display: 'inline-block', padding: '2px 8px', borderRadius: '999px', fontSize: '11px', fontWeight: '500', background: '#dcfce7', color: '#166534' }}>
                            {categoryLabel( preset.category )}
                        </span>
                    )}
                </div>
            </div>

            {/* Apply button */}
            <div style={{ padding: '0 12px 12px' }}>
                <button
                    type="button"
                    onClick={onApply}
                    style={{
                        width: '100%',
                        background: '#2563eb',
                        color: '#fff',
                        fontSize: '13px',
                        fontWeight: '500',
                        padding: '7px 16px',
                        borderRadius: '6px',
                        border: 'none',
                        cursor: 'pointer',
                        transition: 'background 0.15s',
                    }}
                    onMouseEnter={e => { e.currentTarget.style.background = '#1d4ed8'; }}
                    onMouseLeave={e => { e.currentTarget.style.background = '#2563eb'; }}
                >
                    {__( 'Apply Design', 'ufaqsw' )}
                </button>
            </div>
        </div>
    );
}

export function DesignLibraryModal( { isOpen, onClose, onApply } ) {
    const data    = ( typeof window !== 'undefined' && window.ufaqDesignLibraryData ) || {};
    const presets = data.presets || [];

    const [ activeCategory, setActiveCategory ] = useState( 'all' );

    const categories = useMemo( () => {
        const seen = new Set();
        presets.forEach( p => { if ( p.category ) seen.add( p.category ); } );
        return Array.from( seen ).sort();
    }, [ presets ] );

    // Close on Escape key
    useEffect( () => {
        if ( ! isOpen ) return;
        const onKey = ( e ) => { if ( e.key === 'Escape' ) onClose(); };
        document.addEventListener( 'keydown', onKey );
        return () => document.removeEventListener( 'keydown', onKey );
    }, [ isOpen, onClose ] );

    if ( ! isOpen ) return null;

    const visible = activeCategory === 'all'
        ? presets
        : presets.filter( p => p.category === activeCategory );

    const tabs = [ 'all', ...categories ];

    const modal = (
        <div style={{
            position: 'fixed',
            inset: 0,
            zIndex: 999999,
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center',
        }}>
            {/* Backdrop */}
            <div
                onClick={onClose}
                style={{
                    position: 'absolute',
                    inset: 0,
                    background: 'rgba(0,0,0,0.65)',
                }}
            />

            {/* Modal panel */}
            <div style={{
                position: 'relative',
                background: '#fff',
                borderRadius: '12px',
                boxShadow: '0 25px 50px rgba(0,0,0,0.25)',
                width: '100%',
                maxWidth: '860px',
                margin: '0 16px',
                maxHeight: '88vh',
                display: 'flex',
                flexDirection: 'column',
                fontFamily: '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif',
            }}>

                {/* Header */}
                <div style={{
                    display: 'flex',
                    alignItems: 'flex-start',
                    justifyContent: 'space-between',
                    padding: '20px 24px 16px',
                    borderBottom: '1px solid #e5e7eb',
                    flexShrink: 0,
                }}>
                    <div>
                        <h2 style={{ margin: 0, fontSize: '16px', fontWeight: '600', color: '#111827', lineHeight: 1.3 }}>
                            {__( 'Design Library', 'ufaqsw' )}
                        </h2>
                        <p style={{ margin: '4px 0 0', fontSize: '13px', color: '#6b7280' }}>
                            {__( 'Apply a pre-built design as your starting point — every detail stays customisable.', 'ufaqsw' )}
                        </p>
                    </div>
                    <button
                        type="button"
                        onClick={onClose}
                        aria-label={__( 'Close', 'ufaqsw' )}
                        style={{
                            background: 'none',
                            border: 'none',
                            cursor: 'pointer',
                            padding: '4px',
                            marginLeft: '16px',
                            flexShrink: 0,
                            color: '#9ca3af',
                            display: 'flex',
                            alignItems: 'center',
                        }}
                    >
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {/* Category tabs */}
                <div style={{
                    display: 'flex',
                    padding: '0 24px',
                    borderBottom: '1px solid #e5e7eb',
                    flexShrink: 0,
                }}>
                    {tabs.map( cat => (
                        <button
                            key={cat}
                            type="button"
                            onClick={() => setActiveCategory( cat )}
                            style={{
                                background: 'none',
                                border: 'none',
                                borderBottom: activeCategory === cat ? '2px solid #2563eb' : '2px solid transparent',
                                marginBottom: '-1px',
                                padding: '10px 14px',
                                fontSize: '13px',
                                fontWeight: activeCategory === cat ? '600' : '400',
                                color: activeCategory === cat ? '#2563eb' : '#6b7280',
                                cursor: 'pointer',
                                transition: 'color 0.15s',
                                whiteSpace: 'nowrap',
                            }}
                        >
                            {cat === 'all' ? __( 'All', 'ufaqsw' ) : categoryLabel( cat )}
                        </button>
                    ) )}
                </div>

                {/* Grid */}
                <div style={{ flex: 1, overflowY: 'auto', padding: '20px 24px' }}>
                    {visible.length === 0 ? (
                        <p style={{ textAlign: 'center', color: '#9ca3af', padding: '48px 0' }}>
                            {__( 'No designs found in this category.', 'ufaqsw' )}
                        </p>
                    ) : (
                        <div style={{
                            display: 'grid',
                            gridTemplateColumns: 'repeat(auto-fill, minmax(240px, 1fr))',
                            gap: '16px',
                        }}>
                            {visible.map( preset => (
                                <PresetCard
                                    key={preset.id}
                                    preset={preset}
                                    onApply={() => { onApply( preset.name, preset.settings ); onClose(); }}
                                />
                            ) )}
                        </div>
                    )}
                </div>
            </div>
        </div>
    );

    return createPortal( modal, document.body );
}
