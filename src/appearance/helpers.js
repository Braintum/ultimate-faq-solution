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
        condition: { field: "behaviour", value: "toggle" }
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
      title_color: { 
        type: "color", 
        label: "Title Color", 
        default: "",
        condition: { field: "hidetitle", value: false }
      },
      title_font_size: {
        type: "range",
        label: "Title Font Size",
        min: 12,
        max: 100,
        step: 1,
        default: '',
        condition: { field: "hidetitle", value: false }
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
 * Check if a field should be visible based on its condition
 * @param {Object} fieldConfig - Field configuration object
 * @param {Object} values - Current form values
 * @returns {boolean} True if field should be visible
 */
export function isFieldVisible(fieldConfig, values) {
  // If no condition defined, field is always visible
  if (!fieldConfig.condition) {
    return true;
  }

  const { field, value } = fieldConfig.condition;
  
  // Get the current value of the field being checked
  const currentValue = values[field];
  
  // Normalize boolean values (handle 0/1 from database)
  const normalizedCurrent = normalizeBooleanValue(currentValue);
  const normalizedExpected = normalizeBooleanValue(value);
  
  // Compare normalized values
  return normalizedCurrent === normalizedExpected;
}

/**
 * Normalize boolean-like values
 * Converts 0/1/"0"/"1"/true/false to proper booleans
 * @param {*} value - Value to normalize
 * @returns {boolean|*} Normalized boolean or original value if not boolean-like
 */
function normalizeBooleanValue(value) {
  // Handle numeric 0 and 1
  if (value === 0 || value === "0") return false;
  if (value === 1 || value === "1") return true;
  
  // Handle boolean true/false
  if (value === true || value === false) return value;
  
  // Return original value for non-boolean types
  return value;
}

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
      // Ensure we always use the default if initialValues doesn't have the field
      if (fieldKey in initialValues) {
        state[fieldKey] = initialValues[fieldKey];
      } else {
        state[fieldKey] = cfg.default !== undefined ? cfg.default : null;
      }
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