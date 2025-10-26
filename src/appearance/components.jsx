import React from 'react';

/**
 * Reusable form components for the Appearance Builder
 */

/**
 * Label component
 */
export const Label = ({ children }) => (
  <label className="block text-sm font-medium text-gray-700 mb-1">{children}</label>
);

/**
 * Color input component
 */
export function ColorInput({ value, onChange }) {
  return (
    <input
      type="color"
      value={value}
      onChange={(e) => onChange(e.target.value)}
      className="w-12 h-8 p-0 border-0"
    />
  );
}

/**
 * Range input component with live value display
 */
export function RangeInput({ value, onChange, min = 0, max = 100, step = 1 }) {
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

/**
 * Select dropdown component
 */
export function SelectInput({ value, onChange, options = [] }) {
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

/**
 * Text input component
 */
export function TextInput({ value, onChange }) {
  return (
    <input
      type="text"
      value={value}
      onChange={(e) => onChange(e.target.value)}
      className="w-full p-1"
    />
  );
}

/**
 * Toggle/Switch component
 */
export function ToggleInput({ value, onChange }) {
  return (
    <label className="inline-flex items-center cursor-pointer">
      <input
        type="checkbox"
        checked={value}
        onChange={(e) => onChange(e.target.checked)}
        className="sr-only"
      />
      <div className={`relative w-11 h-6 rounded-full transition-colors ${
        value ? 'bg-blue-600' : 'bg-gray-200'
      }`}>
        <div className={`absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full transition-transform ${
          value ? 'translate-x-5' : 'translate-x-0'
        }`} />
      </div>
    </label>
  );
}

/**
 * Custom field renderer based on field type
 */
export function FieldRenderer({ fieldKey, config, value, onChange }) {
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
    case "toggle":
      return <ToggleInput value={value} onChange={onChange} />;
    case "text":
    default:
      return <TextInput value={value} onChange={onChange} />;
  }
}

/**
 * Settings group component
 */
export function SettingsGroup({ groupKey, group, values, setField }) {
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
}

/**
 * Action buttons component
 */
export function ActionButtons({ onReset, onSave }) {
  return (
    <div className="flex gap-2">
      <button
        type="button"
        onClick={onReset}
        className="px-3 py-1 rounded border text-sm"
      >
        Reset
      </button>
      <button
        type="button"
        onClick={onSave}
        className="px-3 py-1 rounded bg-blue-600 text-white text-sm"
      >
        Save
      </button>
    </div>
  );
}