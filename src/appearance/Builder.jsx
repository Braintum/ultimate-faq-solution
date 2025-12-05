import React, { useEffect, useMemo, useState } from "react";
import { toBase64, DEFAULT_SCHEMA, buildInitialState, resetToDefaults } from './helpers';
import { SettingsGroup, ActionButtons } from './components';

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
          throw new Error('Failed to save settings');
        }

        // After successful save, submit the form
        const form = e.target;
        form.removeEventListener('submit', handleFormSubmit);
        form.submit();
      } catch (error) {
        console.error('Error saving settings:', error);
        setNotification({ type: 'error', message: 'Failed to save settings. Please try again.' });
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
        throw new Error('Failed to save settings');
      }

      const result = await response.json();
      console.log('Settings saved successfully:', result);
      
      // Show success notification
      setNotification({ type: 'success', message: 'Settings saved successfully!' });
      
      // Auto-hide notification after 3 seconds
      setTimeout(() => setNotification(null), 3000);
    } catch (error) {
      console.error('Error saving settings:', error);
      
      // Show error notification
      setNotification({ type: 'error', message: 'Failed to save settings. Please try again.' });
      
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
      <div className="w-96 bg-white border rounded shadow-sm flex flex-col h-[1000px]">
        <div className="flex items-center justify-between p-4 border-b flex-shrink-0">
          <h3 className="text-lg font-semibold">Appearance Builder</h3>
          <div className="text-xs text-gray-500"></div>
        </div>

        <div className="flex-1 overflow-y-auto p-4 space-y-4">
          {Object.keys(schema).map((groupKey) => {
            const group = schema[groupKey];
            return (
              <SettingsGroup
                key={groupKey}
                groupKey={groupKey}
                group={group}
                values={values}
                setField={setField}
              />
            );
          })}
        </div>

        {/* Fixed action buttons at bottom */}
        <div className="p-4 border-t flex-shrink-0 bg-white">
          <ActionButtons onReset={handleReset} onSave={handleSave} isSaving={isSaving} />
        </div>
      </div>

      {/* Right: Preview */}
      <div className="flex-1 flex flex-col gap-2">
        <div className="flex items-center justify-between">
          <div className="text-sm text-gray-600">Live Preview</div>
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
                <p className="text-gray-600 text-sm">Loading preview...</p>
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

      {/* Notification Toast */}
      {notification && (
        <div className="fixed bottom-4 left-4 z-50 animate-slide-in">
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
