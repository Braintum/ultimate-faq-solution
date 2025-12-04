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
      
      // Show success message (you can use a toast notification library)
      alert('Settings saved successfully!');
    } catch (error) {
      console.error('Error saving settings:', error);
      alert('Failed to save settings. Please try again.');
    }
  }

  // ----------------------------- UI -----------------------------
  return (
    <div className="flex gap-4 p-4">
      {/* Left: Settings panel */}
      <div className="w-96 bg-white border rounded shadow-sm flex flex-col h-[1000px]">
        <div className="flex items-center justify-between p-4 border-b flex-shrink-0">
          <h3 className="text-lg font-semibold">Appearance Builder</h3>
          <div className="text-xs text-gray-500">Live preview</div>
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

          <div className="text-xs text-gray-500 mt-2">
            Tip: you can extend the <code>schema</code> prop to add more settings fields or custom
            field types. The builder will render them automatically.
          </div>
        </div>

        {/* Fixed action buttons at bottom */}
        <div className="p-4 border-t flex-shrink-0 bg-white">
          <ActionButtons onReset={handleReset} onSave={handleSave} />
        </div>
      </div>

      {/* Right: Preview */}
      <div className="flex-1 flex flex-col gap-2">
        <div className="flex items-center justify-between">
          <div className="text-sm text-gray-600">Preview</div>
          <div className="text-xs text-gray-500">Using both query param + postMessage</div>
        </div>

        <div className="flex-1 border rounded overflow-hidden">
          <iframe
            title={iframeTitle}
            src={iframeSrc}
            ref={iframeRef}
            onLoad={() => setIframeLoaded(true)}
            className="w-full h-[100%]"
          />
        </div>
      </div>
    </div>
  );
}
