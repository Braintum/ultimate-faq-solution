/**
 * TemplateCards.jsx
 *
 * Visual template-picker component — replaces the plain radio buttons for the
 * "Template Style" field in the Appearance Builder.
 *
 * Each card renders a CSS-only miniature mockup of the template so users can
 * understand the layout before selecting.
 */

import React from 'react';
import { __ } from '@wordpress/i18n';

// ---------------------------------------------------------------------------
// Mini template mockups (pure CSS/JSX, no images)
// ---------------------------------------------------------------------------

function DefaultMockup() {
  return (
    <div style={{ border: '1px solid #d1d5db', borderRadius: 4, overflow: 'hidden', fontSize: 0 }}>
      {/* Group title bar */}
      <div style={{ background: '#f3f4f6', padding: '4px 6px', fontSize: 9, fontWeight: 700, color: '#374151', borderBottom: '1px solid #e5e7eb' }}>
        FAQ Group
      </div>
      {/* Row 1 – collapsed */}
      <div style={{ display: 'flex', alignItems: 'center', padding: '4px 6px', borderBottom: '1px solid #e5e7eb', background: '#fff', gap: 4 }}>
        <span style={{ flex: 1, height: 5, background: '#d1d5db', borderRadius: 2 }} />
        <span style={{ width: 8, height: 8, borderRadius: '50%', border: '1.5px solid #9ca3af', display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0, fontSize: 8, color: '#9ca3af' }}>+</span>
      </div>
      {/* Row 2 – open */}
      <div style={{ borderBottom: '1px solid #e5e7eb' }}>
        <div style={{ display: 'flex', alignItems: 'center', padding: '4px 6px', background: '#eff6ff', gap: 4 }}>
          <span style={{ flex: 1, height: 5, background: '#bfdbfe', borderRadius: 2 }} />
          <span style={{ width: 8, height: 8, borderRadius: '50%', border: '1.5px solid #60a5fa', display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0, fontSize: 8, color: '#3b82f6' }}>−</span>
        </div>
        <div style={{ padding: '4px 6px', background: '#f9fafb' }}>
          <span style={{ display: 'block', height: 4, background: '#e5e7eb', borderRadius: 2, marginBottom: 2 }} />
          <span style={{ display: 'block', height: 4, background: '#e5e7eb', borderRadius: 2, width: '70%' }} />
        </div>
      </div>
      {/* Row 3 – collapsed */}
      <div style={{ display: 'flex', alignItems: 'center', padding: '4px 6px', background: '#fff', gap: 4 }}>
        <span style={{ flex: 1, height: 5, background: '#d1d5db', borderRadius: 2 }} />
        <span style={{ width: 8, height: 8, borderRadius: '50%', border: '1.5px solid #9ca3af', display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0, fontSize: 8, color: '#9ca3af' }}>+</span>
      </div>
    </div>
  );
}

function Style1Mockup() {
  return (
    <div style={{ fontSize: 0 }}>
      {/* Group title – minimal, just text + underline */}
      <div style={{ fontSize: 9, fontWeight: 700, color: '#374151', paddingBottom: 3, borderBottom: '2px solid #374151', marginBottom: 4 }}>
        FAQ Group
      </div>
      {/* Row 1 – collapsed */}
      <div style={{ display: 'flex', alignItems: 'center', padding: '3px 0', borderBottom: '1px solid #e5e7eb', gap: 4 }}>
        <span style={{ flex: 1, height: 5, background: '#d1d5db', borderRadius: 2 }} />
        <span style={{ width: 0, height: 0, borderLeft: '4px solid transparent', borderRight: '4px solid transparent', borderTop: '5px solid #9ca3af', flexShrink: 0 }} />
      </div>
      {/* Row 2 – open */}
      <div style={{ borderBottom: '1px solid #e5e7eb' }}>
        <div style={{ display: 'flex', alignItems: 'center', padding: '3px 0', gap: 4 }}>
          <span style={{ flex: 1, height: 5, background: '#6b7280', borderRadius: 2 }} />
          <span style={{ width: 0, height: 0, borderLeft: '4px solid transparent', borderRight: '4px solid transparent', borderBottom: '5px solid #374151', flexShrink: 0 }} />
        </div>
        <div style={{ paddingLeft: 0, paddingBottom: 4 }}>
          <span style={{ display: 'block', height: 4, background: '#e5e7eb', borderRadius: 2, marginBottom: 2 }} />
          <span style={{ display: 'block', height: 4, background: '#e5e7eb', borderRadius: 2, width: '60%' }} />
        </div>
      </div>
      {/* Row 3 – collapsed */}
      <div style={{ display: 'flex', alignItems: 'center', padding: '3px 0', gap: 4 }}>
        <span style={{ flex: 1, height: 5, background: '#d1d5db', borderRadius: 2 }} />
        <span style={{ width: 0, height: 0, borderLeft: '4px solid transparent', borderRight: '4px solid transparent', borderTop: '5px solid #9ca3af', flexShrink: 0 }} />
      </div>
    </div>
  );
}

function Style2Mockup() {
  return (
    <div style={{ border: '1px solid #d1d5db', borderRadius: 4, overflow: 'hidden', fontSize: 0 }}>
      {/* Group title – colored card header */}
      <div style={{ background: '#1e40af', padding: '4px 6px', fontSize: 9, fontWeight: 700, color: '#fff' }}>
        FAQ Group
      </div>
      {/* Row 1 – collapsed */}
      <div style={{ display: 'flex', alignItems: 'center', padding: '4px 6px', borderBottom: '1px solid #dbeafe', background: '#eff6ff', gap: 4 }}>
        <span style={{ flex: 1, height: 5, background: '#bfdbfe', borderRadius: 2 }} />
        <span style={{ fontSize: 10, color: '#2563eb', fontWeight: 700, flexShrink: 0 }}>+</span>
      </div>
      {/* Row 2 – open */}
      <div style={{ borderBottom: '1px solid #dbeafe' }}>
        <div style={{ display: 'flex', alignItems: 'center', padding: '4px 6px', background: '#dbeafe', gap: 4 }}>
          <span style={{ flex: 1, height: 5, background: '#93c5fd', borderRadius: 2 }} />
          <span style={{ fontSize: 10, color: '#1d4ed8', fontWeight: 700, flexShrink: 0 }}>−</span>
        </div>
        <div style={{ padding: '4px 6px', background: '#fff' }}>
          <span style={{ display: 'block', height: 4, background: '#e5e7eb', borderRadius: 2, marginBottom: 2 }} />
          <span style={{ display: 'block', height: 4, background: '#e5e7eb', borderRadius: 2, width: '65%' }} />
        </div>
      </div>
      {/* Row 3 – collapsed */}
      <div style={{ display: 'flex', alignItems: 'center', padding: '4px 6px', background: '#eff6ff', gap: 4 }}>
        <span style={{ flex: 1, height: 5, background: '#bfdbfe', borderRadius: 2 }} />
        <span style={{ fontSize: 10, color: '#2563eb', fontWeight: 700, flexShrink: 0 }}>+</span>
      </div>
    </div>
  );
}

const TEMPLATE_MOCKUPS = {
  default: DefaultMockup,
  'style-1': Style1Mockup,
  'style-2': Style2Mockup,
};

const TEMPLATE_DESCRIPTIONS = {
  default: __('Classic bordered accordion with icon buttons', 'ufaqsw'),
  'style-1': __('Minimal flat accordion with underline dividers', 'ufaqsw'),
  'style-2': __('Card layout with a bold colored group header', 'ufaqsw'),
};

// ---------------------------------------------------------------------------
// TemplateCards component
// ---------------------------------------------------------------------------

/**
 * Replaces the plain RadioInput for the "template" field with visual preview cards.
 *
 * @param {{ value: string, onChange: function, options: Array }} props
 */
export function TemplateCards({ value, onChange, options = [] }) {
  return (
    <div style={{ display: 'flex', flexDirection: 'column', gap: 10 }}>
      {options.map((option) => {
        const optionValue = typeof option === 'object' ? option.value : option;
        const optionLabel = typeof option === 'object' ? option.label : option;
        const isSelected  = value === optionValue;
        const Mockup      = TEMPLATE_MOCKUPS[optionValue];
        const description = TEMPLATE_DESCRIPTIONS[optionValue] || '';

        return (
          <label
            key={optionValue}
            style={{
              display: 'block',
              cursor: 'pointer',
              borderRadius: 8,
              border: isSelected ? '2px solid #2271b1' : '2px solid #e5e7eb',
              background: isSelected ? '#f0f6fc' : '#fff',
              padding: 10,
              transition: 'border-color 0.15s, background 0.15s',
              boxShadow: isSelected ? '0 0 0 3px rgba(34,113,177,0.12)' : 'none',
            }}
          >
            <input
              type="radio"
              name="ufaqsw-template-selector"
              value={optionValue}
              checked={isSelected}
              onChange={() => onChange(optionValue)}
              style={{ position: 'absolute', opacity: 0, width: 0, height: 0 }}
            />

            {/* Top row: label + check */}
            <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', marginBottom: 8 }}>
              <span style={{
                fontSize: 13,
                fontWeight: 600,
                color: isSelected ? '#1d4ed8' : '#1d2327',
              }}>
                {optionLabel}
              </span>
              {isSelected && (
                <span style={{
                  width: 18,
                  height: 18,
                  borderRadius: '50%',
                  background: '#2271b1',
                  display: 'flex',
                  alignItems: 'center',
                  justifyContent: 'center',
                  flexShrink: 0,
                }}>
                  <svg width="10" height="10" viewBox="0 0 12 12" fill="none">
                    <path d="M2 6l3 3 5-5" stroke="#fff" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"/>
                  </svg>
                </span>
              )}
            </div>

            {/* CSS mockup */}
            {Mockup && <Mockup />}

            {/* Description */}
            {description && (
              <p style={{ margin: '8px 0 0', fontSize: 11, color: '#6b7280', lineHeight: 1.4 }}>
                {description}
              </p>
            )}
          </label>
        );
      })}
    </div>
  );
}
