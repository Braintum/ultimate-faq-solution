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
    label: "Layout & Behaviour",
    subtitle: "Controls the overall structure and interaction mode.",
    fields: {
      template: {
        type: "radio",
        label: "Template Style",
        options: [
          { value: "default",   label: "Default" },
          { value: "style-1",   label: "Style 1" },
          { value: "style-2",   label: "Style 2" },
          { value: "universal", label: "Universal" },
        ],
        default: "default",
      },
      layout: {
        type: "radio",
        label: "Layout Variant",
        options: [
          { value: "classic", label: "Classic" },
          { value: "card",    label: "Card" },
          { value: "minimal", label: "Minimal" },
          { value: "boxed",   label: "Boxed" },
        ],
        default: "classic",
        condition: { field: "template", value: "universal" },
      },
      behaviour: {
        type: "select",
        label: "Behaviour",
        options: [
          { value: "accordion", label: "Accordion" },
          { value: "toggle",    label: "Toggle" },
        ],
        default: "accordion",
      },
      showall: {
        type: "toggle",
        label: "Show All Answers Opened",
        default: false,
        condition: { field: "behaviour", value: "toggle" },
      },
      animation: {
        type: "select",
        label: "Open / Close Animation",
        options: [
          { value: "none",  label: "None" },
          { value: "slide", label: "Slide" },
          { value: "fade",  label: "Fade" },
        ],
        default: "none",
        condition: { field: "template", value: "universal" },
      },
      icon_position: {
        type: "select",
        label: "Icon Position",
        options: [
          { value: "right", label: "Right" },
          { value: "left",  label: "Left" },
        ],
        default: "right",
        condition: { field: "template", value: "universal" },
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
    label: "Group Title",
    subtitle: "Styles the section heading shown above your FAQs.",
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
        condition: { field: "hidetitle", value: false },
      },
      title_font_size: {
        type: "range",
        label: "Title Font Size",
        min: 12,
        max: 100,
        step: 1,
        default: '',
        condition: { field: "hidetitle", value: false },
      },
    },
  },
  question: {
    label: "Question Row",
    subtitle: "Styles each clickable question item.",
    fields: {
      question_color:            { type: "color",  label: "Text Color",       default: "" },
      question_background_color: { type: "color",  label: "Background Color", default: "" },
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
    label: "Answer Panel",
    subtitle: "Styles the content revealed when a question is clicked.",
    fields: {
      answer_color:            { type: "color", label: "Text Color",       default: "" },
      answer_background_color: { type: "color", label: "Background Color", default: "" },
      answer_font_size: {
        type: "range",
        label: "Font Size",
        min: 12,
        max: 36,
        step: 1,
        default: '',
      },
    },
  },
  active_states: {
    label: "Active & Hover States",
    subtitle: "Colors applied when items are open or hovered.",
    condition: { field: "template", value: "universal" },
    fields: {
      active_question_color:    { type: "color", label: "Active Question Color",      default: "" },
      active_question_bg:       { type: "color", label: "Active Question Background", default: "" },
      icon_color:               { type: "color", label: "Icon Color",                 default: "" },
      active_icon_color:        { type: "color", label: "Active Icon Color",          default: "" },
      title_bg_color:           { type: "color", label: "Group Title Background",     default: "" },
    },
  },
  spacing: {
    label: "Spacing",
    subtitle: "Padding, gap, and max-width controls.",
    condition: { field: "template", value: "universal" },
    fields: {
      item_padding: {
        type: "range",
        label: "Item Padding",
        min: 4,
        max: 48,
        step: 1,
        default: '',
      },
      item_gap: {
        type: "range",
        label: "Gap Between Items",
        min: 0,
        max: 32,
        step: 1,
        default: '',
      },
      container_max_width: {
        type: "range",
        label: "Max Width (px)",
        min: 400,
        max: 1400,
        step: 10,
        default: '',
      },
    },
  },
  borders: {
    label: "Borders",
    subtitle: "Border radius, width, style, position, and shadow.",
    condition: { field: "template", value: "universal" },
    fields: {
      border_radius: {
        type: "range",
        label: "Border Radius",
        min: 0,
        max: 32,
        step: 1,
        default: '',
      },
      border_width: {
        type: "range",
        label: "Border Width",
        min: 0,
        max: 8,
        step: 1,
        default: '',
      },
      border_style: {
        type: "select",
        label: "Border Style",
        options: [
          { value: "solid",  label: "Solid" },
          { value: "dashed", label: "Dashed" },
          { value: "dotted", label: "Dotted" },
        ],
        default: "solid",
      },
      item_border_position: {
        type: "select",
        label: "Border Position",
        options: [
          { value: "all",          label: "All Sides" },
          { value: "bottom-only",  label: "Bottom Only" },
          { value: "left-accent",  label: "Left Accent" },
        ],
        default: "all",
      },
      active_border_color: {
        type: "color",
        label: "Active Item Border Color",
        default: "",
      },
      shadow_style: {
        type: "select",
        label: "Item Shadow",
        options: [
          { value: "none",   label: "None" },
          { value: "subtle", label: "Subtle" },
          { value: "medium", label: "Medium" },
          { value: "strong", label: "Strong" },
        ],
        default: "none",
      },
    },
  },
  typography: {
    label: "Typography",
    subtitle: "Font weight, line height, spacing, and icon size.",
    condition: { field: "template", value: "universal" },
    fields: {
      question_font_weight: {
        type: "select",
        label: "Question Font Weight",
        options: [
          { value: "",    label: "Default" },
          { value: "400", label: "Normal (400)" },
          { value: "500", label: "Medium (500)" },
          { value: "600", label: "Semi-Bold (600)" },
          { value: "700", label: "Bold (700)" },
        ],
        default: "",
      },
      title_font_weight: {
        type: "select",
        label: "Title Font Weight",
        options: [
          { value: "",    label: "Default" },
          { value: "400", label: "Normal (400)" },
          { value: "500", label: "Medium (500)" },
          { value: "600", label: "Semi-Bold (600)" },
          { value: "700", label: "Bold (700)" },
        ],
        default: "",
      },
      question_letter_spacing: {
        type: "range",
        label: "Question Letter Spacing",
        min: 0,
        max: 4,
        step: 0.5,
        default: '',
      },
      question_text_transform: {
        type: "select",
        label: "Question Text Transform",
        options: [
          { value: "",            label: "None" },
          { value: "uppercase",   label: "Uppercase" },
          { value: "capitalize",  label: "Capitalize" },
        ],
        default: "",
      },
      answer_line_height: {
        type: "select",
        label: "Answer Line Height",
        options: [
          { value: "",    label: "Default" },
          { value: "1.4", label: "Tight (1.4)" },
          { value: "1.6", label: "Normal (1.6)" },
          { value: "1.8", label: "Relaxed (1.8)" },
          { value: "2.0", label: "Loose (2.0)" },
        ],
        default: "",
      },
      icon_size: {
        type: "range",
        label: "Icon Size",
        min: 8,
        max: 30,
        step: 1,
        default: '',
      },
    },
  },
  custom_css: {
    label: "Custom CSS",
    subtitle: "Additional CSS applied directly to this FAQ group.",
    condition: { field: "template", value: "universal" },
    fields: {
      custom_css: {
        type: "textarea",
        label: "Custom CSS",
        placeholder: "/* e.g. .ufaqsw-faq { font-family: Georgia, serif; } */",
        default: "",
      },
    },
  },
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