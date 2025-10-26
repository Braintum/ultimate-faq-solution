/**
 * Helper functions for the Appearance Builder
 */

/**
 * Convert object to base64 string
 * @param {Object} obj - Object to encode
 * @returns {string} Base64 encoded string
 */
export function toBase64(obj) {
  try {
    return btoa(unescape(encodeURIComponent(JSON.stringify(obj))));
  } catch (e) {
    return "";
  }
}

/**
 * Convert base64 string to object
 * @param {string} b64 - Base64 encoded string
 * @returns {Object|null} Decoded object or null if invalid
 */
export function fromBase64(b64) {
  try {
    return JSON.parse(decodeURIComponent(escape(atob(b64))));
  } catch (e) {
    return null;
  }
}

/**
 * Default settings schema: groups -> fields
 */
export const DEFAULT_SCHEMA = {
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

/**
 * Build initial state from schema and initial values
 * @param {Object} schema - Settings schema
 * @param {Object} initialValues - Initial values to use
 * @returns {Object} Initial state object
 */
export function buildInitialState(schema, initialValues = {}) {
  const state = {};
  Object.keys(schema).forEach((groupKey) => {
    const group = schema[groupKey];
    Object.keys(group.fields).forEach((fieldKey) => {
      const cfg = group.fields[fieldKey];
      state[fieldKey] = initialValues[fieldKey] ?? cfg.default ?? null;
    });
  });
  return state;
}

/**
 * Reset values to schema defaults
 * @param {Object} schema - Settings schema
 * @returns {Object} Reset state object
 */
export function resetToDefaults(schema) {
  const state = {};
  Object.keys(schema).forEach((groupKey) => {
    const group = schema[groupKey];
    Object.keys(group.fields).forEach((fieldKey) => {
      const cfg = group.fields[fieldKey];
      state[fieldKey] = cfg.default ?? null;
    });
  });
  return state;
}