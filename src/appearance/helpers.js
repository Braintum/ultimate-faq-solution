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
 * FontAwesome icons list for icon selector
 * Loaded from backend via wp_localize_script
 */
export const FONTAWESOME_ICONS = window.ufaqAppearanceData?.icons || [];

/**
 * Default settings schema: groups -> fields
 */
export const DEFAULT_SCHEMA = {
  general: {
    label: "General",
    fields: {
      template: {
        type: "radio",
        label: "Template Style",
        options: [
          { value: "default", label: "Default" },
          { value: "style-1", label: "Style 1" },
          { value: "style-2", label: "Style 2" },
        ],
        default: "default",
      },
      behaviour: {
        type: "select",
        label: "Behaviour",
        options: [
          { value: "accordion", label: "Accordion" },
          { value: "toggle", label: "Toggle" },
        ],
        default: "accordion",
      },
      showall: {
        type: "toggle",
        label: "Show All Answers Opened",
        default: false,
      },
      border_color: {
        type: "color",
        label: "Border Color",
        default: "",
      },
      normal_icon: {
        type: "icon",
        label: "Normal Icon",
        default: "fa-plus",
      },
      active_icon: {
        type: "icon",
        label: "Active Icon",
        default: "fa-minus",
      },
    },
  },
  group: {
    label: "Group",
    fields: {
      hidetitle: {
        type: "toggle",
        label: "Hide Title",
        default: false,
      },
      title_color: { type: "color", label: "Title Color", default: "" },
      title_font_size: {
        type: "range",
        label: "Title Font Size",
        min: 12,
        max: 100,
        step: 1,
        default: '',
      },
    },
  },
  question: {
    label: "Question",
    fields: {
      question_color: { type: "color", label: "Text color", default: "" },
      question_background_color: { type: "color", label: "Background color", default: "" },
      question_font_size: {
        type: "range",
        label: "Font Size",
        min: 12,
        max: 36,
        step: 1,
        default: '',
      },
      question_bold: {
        type: "toggle",
        label: "Display Question in Bold",
        default: false,
      },
    },
  },
  answer: {
    label: "Answer",
    fields: {
      answer_color: { type: "color", label: "Text color", default: "" },
      answer_background_color: { type: "color", label: "Background color", default: "" },
      answer_font_size: {
        type: "range",
        label: "Font Size",
        min: 12,
        max: 36,
        step: 1,
        default: '',
      },
    },
  }
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