/**
 * Preview Integration Example
 * ---------------------------
 * This file contains example code for integrating the appearance builder
 * with the preview iframe. This code should be included in the preview page
 * that renders the FAQ items.
 */

/**
 * Apply appearance settings to the page using CSS custom properties
 * @param {Object} settings - Appearance settings object
 */
function applyAppearance(settings) {
  if (!settings) return;
  
  const root = document.documentElement;
  
  // General settings
  if (settings.fontSize) {
    root.style.setProperty('--faq-font-size', settings.fontSize + 'px');
  }
  if (settings.fontFamily) {
    root.style.setProperty('--faq-font-family', settings.fontFamily);
  }
  
  // Question settings
  if (settings.questionTextColor) {
    root.style.setProperty('--faq-question-color', settings.questionTextColor);
  }
  if (settings.questionBgColor) {
    root.style.setProperty('--faq-question-bg', settings.questionBgColor);
  }
  
  // Answer settings
  if (settings.answerTextColor) {
    root.style.setProperty('--faq-answer-color', settings.answerTextColor);
  }
  if (settings.answerBgColor) {
    root.style.setProperty('--faq-answer-bg', settings.answerBgColor);
  }
  if (settings.answerPadding !== undefined) {
    root.style.setProperty('--faq-answer-padding', settings.answerPadding + 'px');
  }
}

/**
 * Initialize appearance integration in the preview iframe
 * This should be called when the preview page loads
 */
export function initializePreviewIntegration() {
  // Read appearance settings from query parameter
  const urlParams = new URLSearchParams(window.location.search);
  const b64 = urlParams.get('appearance');
  
  if (b64) {
    try {
      const parsed = JSON.parse(decodeURIComponent(escape(atob(b64))));
      applyAppearance(parsed);
    } catch (e) {
      console.warn('Failed to parse appearance settings from URL:', e);
    }
  }

  // Listen for postMessage updates from the parent window
  window.addEventListener('message', (event) => {
    if (!event.data || event.data.type !== 'appearance:update') {
      return;
    }
    
    applyAppearance(event.data.payload);
  }, false);
}

/**
 * CSS template for FAQ appearance styling
 * This should be included in the preview page's CSS
 */
export const CSS_TEMPLATE = `
:root {
  --faq-font-size: 16px;
  --faq-font-family: inherit;
  --faq-question-color: #111827;
  --faq-question-bg: #ffffff;
  --faq-answer-color: #374151;
  --faq-answer-bg: #ffffff;
  --faq-answer-padding: 12px;
}

.faq-root {
  font-size: var(--faq-font-size);
  font-family: var(--faq-font-family);
}

.faq-question {
  color: var(--faq-question-color);
  background: var(--faq-question-bg);
  padding: 12px;
  border: 1px solid #e5e7eb;
  border-radius: 4px 4px 0 0;
  cursor: pointer;
  transition: background-color 0.2s ease;
}

.faq-question:hover {
  opacity: 0.9;
}

.faq-answer {
  color: var(--faq-answer-color);
  background: var(--faq-answer-bg);
  padding: var(--faq-answer-padding);
  border: 1px solid #e5e7eb;
  border-top: none;
  border-radius: 0 0 4px 4px;
  line-height: 1.6;
}

.faq-item {
  margin-bottom: 16px;
}
`;

/**
 * HTML snippet for preview page integration
 * Include this in your preview page template
 */
export const HTML_INTEGRATION_SNIPPET = `
<!-- Include this in your preview page -->
<script>
  // Import and initialize preview integration
  import { initializePreviewIntegration } from './previewIntegration.js';
  
  document.addEventListener('DOMContentLoaded', () => {
    initializePreviewIntegration();
  });
</script>

<style>
  ${CSS_TEMPLATE}
</style>
`;