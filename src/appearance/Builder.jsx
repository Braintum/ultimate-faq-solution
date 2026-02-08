import React, { useEffect, useMemo, useState } from "react";
import { toBase64, DEFAULT_SCHEMA, buildInitialState, resetToDefaults } from './helpers';
import { SettingsPanel, PreviewPanel } from './components';
import { __ } from '@wordpress/i18n';

/**
 * Appearance Visual Builder
 * -------------------------
 * Export: default AppearanceBuilder
 *
 * Features included:
 * - Settings schema + "advanced" components (Color, Range, Select, Toggle, Text)
 * - Live preview inside an <iframe>
 * - Two ways to send changes to iframe:
 *   1) append `?appearance=<base64json>` to iframe src (good for permalinkable previews)
 *   2) postMessage to the iframe for immediate updates
 * - Extensible registry: add new field types or new settings groups easily
 * - Minimal, Tailwind-friendly UI (no external CSS imports)
 *
 * How to use in WordPress admin (brief):
 * - Bundle this component with your build tool (webpack/Vite) and enqueue in WP admin
 * - Render a root div on the Appearance CPT edit page and bootstrap React into it
 * - Provide `previewBaseUrl` prop — an URL which will render the FAQ items and can accept
 *   appearance data by either query param or via postMessage.
 *
 * Notes / integration ideas:
 * - The preview endpoint can be a custom admin-ajax.php action or a front-end endpoint that
 *   loads the FAQ items by appearance id / preview data. It should listen for postMessage
 *   events and apply the received styles.
 * - For security, the preview iframe should be same-origin or allow a controlled postMessage origin.
 */

// ----------------------------- Main Builder -----------------------------
export default function AppearanceBuilder({
  previewBaseUrl = "/?preview=faq",
  initialValues = {},
  schema = DEFAULT_SCHEMA,
  iframeTitle = "FAQ appearance preview",
  postMessageTargetOrigin = "*",
}) {
  // Build initial state by walking schema and taking defaults or initialValues
  const initialState = useMemo(() => {
    return buildInitialState(schema, initialValues);
  }, [schema, initialValues]);

  const [values, setValues] = useState(initialState);
  const [debouncedValues, setDebouncedValues] = useState(initialState);
  const [iframeLoaded, setIframeLoaded] = useState(false);
  const [isSaving, setIsSaving] = useState(false);
  const [notification, setNotification] = useState(null);
  const iframeRef = React.useRef(null);

  // Debounce values changes - only update after 1 second of no changes
  useEffect(() => {
    const timeoutId = setTimeout(() => {
      setDebouncedValues(values);
    }, 1000); // 1 second delay

    return () => clearTimeout(timeoutId);
  }, [values]);

  // Assemble the iframe src with a base64 encoded appearance param
  // Using debouncedValues instead of values to prevent too many reloads
  const iframeSrc = useMemo(() => {
    const encoded = toBase64(debouncedValues);
    // append appearance param without breaking existing querystring
    const sep = previewBaseUrl.includes("?") ? "&" : "?";
    return `${previewBaseUrl}${sep}appearance=${encoded}`;
  }, [previewBaseUrl, debouncedValues]);

  // Reset iframe loaded state when URL changes
  useEffect(() => {
    setIframeLoaded(false);
  }, [iframeSrc]);

  // Intercept WordPress publish/update button to save settings first
  useEffect(() => {
    const handleFormSubmit = async (e) => {
      const publishButton = e.target.querySelector('#publish');
      if (!publishButton || !publishButton.contains(e.submitter)) {
        return;
      }

      e.preventDefault();
      e.stopPropagation();

      // Save appearance settings first
      setIsSaving(true);
      try {
        const data = window.ufaqAppearanceData || {};
        const postId = data.postId || new URLSearchParams(window.location.search).get('post');
        
        const response = await fetch(data.saveEndpoint || '/wp-json/ufaqsw/v1/appearance/save', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-WP-Nonce': data.nonce || '',
          },
          body: JSON.stringify({
            post_id: postId,
            settings: values,
          }),
        });

        if (!response.ok) {
          throw new Error(__('Failed to save settings', 'ufaqsw'));
        }

        // After successful save, submit the form
        const form = e.target;
        form.removeEventListener('submit', handleFormSubmit);
        form.submit();
      } catch (error) {
        console.error('Error saving settings:', error);
        setNotification({ type: 'error', message: __('Failed to save settings. Please try again.', 'ufaqsw') });
        setTimeout(() => setNotification(null), 5000);
      } finally {
        setIsSaving(false);
      }
    };

    const form = document.querySelector('#post');
    if (form) {
      form.addEventListener('submit', handleFormSubmit);
      return () => form.removeEventListener('submit', handleFormSubmit);
    }
  }, [values]);

  // Post message updates for immediate responses once iframe has loaded
  useEffect(() => {
    if (!iframeLoaded) return;
    const win = iframeRef.current?.contentWindow;
    if (!win) return;
    try {
      win.postMessage({ type: "appearance:update", payload: values }, postMessageTargetOrigin);
    } catch (e) {
      // ignore
    }
  }, [values, iframeLoaded, postMessageTargetOrigin]);

  // Generic setter
  function setField(key, val) {
    setValues((prev) => ({ ...prev, [key]: val }));
  }

  // Reset to defaults
  function handleReset() {
    setValues(resetToDefaults(schema));
  }

  // Save settings to database via WordPress REST API
  async function handleSave() {
    setIsSaving(true);
    setNotification(null);

    try {
      const data = window.ufaqAppearanceData || {};
      const postId = data.postId || new URLSearchParams(window.location.search).get('post');
      
      const response = await fetch(data.saveEndpoint || '/wp-json/ufaqsw/v1/appearance/save', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-WP-Nonce': data.nonce || '',
        },
        body: JSON.stringify({
          post_id: postId,
          settings: values,
        }),
      });

      if (!response.ok) {
        throw new Error(__('Failed to save settings', 'ufaqsw'));
      }

      const result = await response.json();
      console.log('Settings saved successfully:', result);
      
      // Show success notification
      setNotification({ type: 'success', message: __('Settings saved successfully!', 'ufaqsw') });
      
      // Auto-hide notification after 3 seconds
      setTimeout(() => setNotification(null), 3000);
    } catch (error) {
      console.error('Error saving settings:', error);
      
      // Show error notification
      setNotification({ type: 'error', message: __('Failed to save settings. Please try again.', 'ufaqsw') });
      
      // Auto-hide notification after 5 seconds
      setTimeout(() => setNotification(null), 5000);
    } finally {
      setIsSaving(false);
    }
  }

  // ----------------------------- UI -----------------------------
  return (
    <div className="flex gap-4 py-4">
        {/* Left: Settings panel */}
        <SettingsPanel 
            schema={schema} 
            values={values} 
            setField={setField} 
            onReset={handleReset} 
            onSave={handleSave} 
            isSaving={isSaving} 
        />

        {/* Right: Preview */}
        <PreviewPanel
            iframeLoaded={iframeLoaded}
            iframeSrc={iframeSrc}
            iframeTitle={iframeTitle}
            iframeRef={iframeRef}
            setIframeLoaded={setIframeLoaded}
        />

        {/* Notification Toast */}
        {notification && (
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
        )}
    </div>
  );
}
