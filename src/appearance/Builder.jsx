import React, { useEffect, useMemo, useState } from "react";

/**
 * Appearance Visual Builder (single-file React component)
 * ------------------------------------------------------
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

// ----------------------------- Helpers -----------------------------
function toBase64(obj) {
  try {
    return btoa(unescape(encodeURIComponent(JSON.stringify(obj))));
  } catch (e) {
    return "";
  }
}

function fromBase64(b64) {
  try {
    return JSON.parse(decodeURIComponent(escape(atob(b64))));
  } catch (e) {
    return null;
  }
}

// default settings schema: groups -> fields
const DEFAULT_SCHEMA = {
  general: {
    label: "General",
    fields: {
      fontSize: {
        type: "range",
        label: "Font size (px)",
        min: 12,
        max: 36,
        step: 1,
        default: 16,
      },
      fontFamily: {
        type: "select",
        label: "Font family",
        options: ["inherit", "Inter, system-ui, sans-serif", "Georgia, serif"],
        default: "inherit",
      },
    },
  },
  question: {
    label: "Question",
    fields: {
      questionTextColor: { type: "color", label: "Text color", default: "#111827" },
      questionBgColor: { type: "color", label: "Background color", default: "#ffffff" },
    },
  },
  answer: {
    label: "Answer",
    fields: {
      answerTextColor: { type: "color", label: "Text color", default: "#374151" },
      answerBgColor: { type: "color", label: "Background color", default: "#ffffff" },
      answerPadding: { type: "range", label: "Padding (px)", min: 0, max: 40, step: 1, default: 12 },
    },
  },
};

// ----------------------------- Field components -----------------------------

const Label = ({ children }) => (
  <label className="block text-sm font-medium text-gray-700 mb-1">{children}</label>
);

function ColorInput({ value, onChange }) {
  return (
    <input
      type="color"
      value={value}
      onChange={(e) => onChange(e.target.value)}
      className="w-12 h-8 p-0 border-0"
    />
  );
}

function RangeInput({ value, onChange, min = 0, max = 100, step = 1 }) {
  return (
    <div>
      <input
        type="range"
        min={min}
        max={max}
        step={step}
        value={value}
        onChange={(e) => onChange(parseInt(e.target.value, 10))}
        className="w-full"
      />
      <div className="text-xs mt-1">{value}px</div>
    </div>
  );
}

function SelectInput({ value, onChange, options = [] }) {
  return (
    <select value={value} onChange={(e) => onChange(e.target.value)} className="w-full p-1">
      {options.map((opt) => (
        <option key={opt} value={opt}>
          {opt}
        </option>
      ))}
    </select>
  );
}

function TextInput({ value, onChange }) {
  return (
    <input
      type="text"
      value={value}
      onChange={(e) => onChange(e.target.value)}
      className="w-full p-1"
    />
  );
}

// Custom renderer for a field based on type
function FieldRenderer({ fieldKey, config, value, onChange }) {
  switch (config.type) {
    case "color":
      return <ColorInput value={value} onChange={onChange} />;
    case "range":
      return (
        <RangeInput
          value={value}
          onChange={onChange}
          min={config.min}
          max={config.max}
          step={config.step}
        />
      );
    case "select":
      return <SelectInput value={value} onChange={onChange} options={config.options} />;
    case "text":
    default:
      return <TextInput value={value} onChange={onChange} />;
  }
}

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
    const s = {};
    Object.keys(schema).forEach((groupKey) => {
      const group = schema[groupKey];
      Object.keys(group.fields).forEach((fieldKey) => {
        const cfg = group.fields[fieldKey];
        s[fieldKey] = initialValues[fieldKey] ?? cfg.default ?? null;
      });
    });
    return s;
  }, [schema, initialValues]);

  const [values, setValues] = useState(initialState);
  const [iframeLoaded, setIframeLoaded] = useState(false);
  const iframeRef = React.useRef(null);

  // Assemble the iframe src with a base64 encoded appearance param
  const iframeSrc = useMemo(() => {
    const encoded = toBase64(values);
    // append appearance param without breaking existing querystring
    const sep = previewBaseUrl.includes("?") ? "&" : "?";
    return `${previewBaseUrl}${sep}appearance=${encoded}`;
  }, [previewBaseUrl, values]);

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
  function resetDefaults() {
    const s = {};
    Object.keys(schema).forEach((groupKey) => {
      const group = schema[groupKey];
      Object.keys(group.fields).forEach((fieldKey) => {
        const cfg = group.fields[fieldKey];
        s[fieldKey] = cfg.default ?? null;
      });
    });
    setValues(s);
  }

  // Export settings (example: for saving via WP rest / AJAX)
  function exportSettings() {
    return values;
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
              <div key={groupKey} className="border rounded p-3">
                <div className="font-medium mb-2">{group.label}</div>
                <div className="space-y-3">
                  {Object.keys(group.fields).map((fieldKey) => {
                    const cfg = group.fields[fieldKey];
                    return (
                      <div key={fieldKey} className="">
                        <Label>{cfg.label}</Label>
                        <FieldRenderer
                          fieldKey={fieldKey}
                          config={cfg}
                          value={values[fieldKey]}
                          onChange={(val) => setField(fieldKey, val)}
                        />
                      </div>
                    );
                  })}
                </div>
              </div>
            );
          })}

          <div className="flex gap-2">
            <button
              type="button"
              onClick={resetDefaults}
              className="px-3 py-1 rounded border text-sm"
            >
              Reset
            </button>
            <button
              type="button"
              onClick={() => console.log("Export (save) settings:", exportSettings())}
              className="px-3 py-1 rounded bg-blue-600 text-white text-sm"
            >
              Save (example)
            </button>
          </div>

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

// ----------------------------- Preview-side snippet (example) -----------------------------
// The preview frame (the URL used as previewBaseUrl) should include a small script to
// read the `appearance` query param and/or to listen for postMessage updates.
// Example (to be included inside the HTML that renders FAQ items inside iframe):
/*
<script>
  function applyAppearance(settings) {
    // map settings to CSS variables for easy styling
    if (!settings) return;
    const root = document.documentElement;
    if (settings.fontSize) root.style.setProperty('--faq-font-size', settings.fontSize + 'px');
    if (settings.fontFamily) root.style.setProperty('--faq-font-family', settings.fontFamily);
    if (settings.questionTextColor) root.style.setProperty('--faq-question-color', settings.questionTextColor);
    if (settings.questionBgColor) root.style.setProperty('--faq-question-bg', settings.questionBgColor);
    if (settings.answerTextColor) root.style.setProperty('--faq-answer-color', settings.answerTextColor);
    if (settings.answerBgColor) root.style.setProperty('--faq-answer-bg', settings.answerBgColor);
    if (settings.answerPadding !== undefined) root.style.setProperty('--faq-answer-padding', settings.answerPadding + 'px');
  }

  // read query param
  const urlParams = new URLSearchParams(window.location.search);
  const b64 = urlParams.get('appearance');
  if (b64) {
    const parsed = (function(){ try { return JSON.parse(decodeURIComponent(escape(atob(b64)))); } catch(e){ return null; } })();
    applyAppearance(parsed);
  }

  window.addEventListener('message', (ev) => {
    if (!ev.data || ev.data.type !== 'appearance:update') return;
    applyAppearance(ev.data.payload);
  }, false);
</script>

<style>
  :root{
    --faq-font-size: 16px;
    --faq-font-family: inherit;
    --faq-question-color: #111827;
    --faq-question-bg: #fff;
    --faq-answer-color: #374151;
    --faq-answer-bg: #fff;
    --faq-answer-padding: 12px;
  }
  .faq-root { font-size: var(--faq-font-size); font-family: var(--faq-font-family); }
  .faq-question { color: var(--faq-question-color); background: var(--faq-question-bg); }
  .faq-answer { color: var(--faq-answer-color); background: var(--faq-answer-bg); padding: var(--faq-answer-padding); }
</style>
*/
