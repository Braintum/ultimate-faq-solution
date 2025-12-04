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

  // Save settings (example: for saving via WP rest / AJAX)
  function handleSave() {
    console.log("Export (save) settings:", values);
    // TODO: Implement actual save functionality
    // Example: saveSettings(values);
  }

  // ----------------------------- UI -----------------------------
  return (
    <div className="flex gap-4 p-4">
      {/* Left: Settings panel */}
      <div className="w-96 bg-white border rounded p-4 shadow-sm sticky top-4">
        <div className="flex items-center justify-between mb-4">
          <h3 className="text-lg font-semibold">Appearance Builder</h3>
          <div className="text-xs text-gray-500">Live preview</div>
        </div>

        <div className="space-y-4">
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

          <ActionButtons onReset={handleReset} onSave={handleSave} />

          <div className="text-xs text-gray-500 mt-2">
            Tip: you can extend the <code>schema</code> prop to add more settings fields or custom
            field types. The builder will render them automatically.
          </div>
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
            className="w-full h-[600px]"
          />
        </div>

        <div className="text-xs text-gray-500 mt-1">Iframe src: <code className="break-all">{iframeSrc}</code></div>
      </div>
    </div>
  );
}
