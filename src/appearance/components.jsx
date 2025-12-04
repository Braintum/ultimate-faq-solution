import React from 'react';
import { FONTAWESOME_ICONS } from './helpers';

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
 * Color input component with color preview, hex display, and clear button
 */
export function ColorInput({ value, onChange }) {
  const hasColor = value && value !== "";
  const colorValue = hasColor ? value : "#000000";
  
  return (
    <div className="flex items-center gap-2">
      <div className="relative">
        <input
          type="color"
          value={colorValue}
          onChange={(e) => onChange(e.target.value)}
          className="w-12 h-8 p-0 border border-gray-300 rounded cursor-pointer"
        />
        {!hasColor && (
          <div className="absolute inset-0 flex items-center justify-center pointer-events-none bg-white bg-opacity-50">
            <div className="w-8 h-0.5 bg-red-500 rotate-45"></div>
          </div>
        )}
      </div>
      <input
        type="text"
        value={hasColor ? value : ""}
        onChange={(e) => onChange(e.target.value)}
        className="flex-1 px-2 py-1 text-xs font-mono border border-gray-300 rounded"
        placeholder="No color"
      />
      {hasColor && (
        <button
          type="button"
          onClick={() => onChange("")}
          className="px-2 py-1 text-xs text-red-600 hover:bg-red-50 border border-red-200 rounded"
          title="Clear color"
        >
          ✕
        </button>
      )}
    </div>
  );
}

/**
 * Range input component with live value display and clear button
 */
export function RangeInput({ value, onChange, min = 0, max = 100, step = 1 }) {
  const hasValue = value !== '' && value !== null && value !== undefined;
  const displayValue = hasValue ? value : min;
  
  return (
    <div>
      <div className="flex items-center gap-2">
        <input
          type="range"
          min={min}
          max={max}
          step={step}
          value={displayValue}
          onChange={(e) => onChange(parseInt(e.target.value, 10))}
          className="flex-1"
        />
        {hasValue && (
          <button
            type="button"
            onClick={() => onChange('')}
            className="px-2 py-0.5 text-xs text-red-600 hover:bg-red-50 border border-red-200 rounded"
            title="Clear value"
          >
            ✕
          </button>
        )}
      </div>
      <div className="text-xs mt-1 text-gray-600">
        {hasValue ? `${value}px` : 'Not set'}
      </div>
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
        <option key={opt.value} value={opt.value}>
          {opt.label}
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
 * Radio input component with card-style UI (horizontal layout)
 */
export function RadioInput({ value, onChange, options = [] }) {
  return (
    <div className="flex gap-2">
      {options.map((option) => {
        const optionValue = typeof option === 'object' ? option.value : option;
        const optionLabel = typeof option === 'object' ? option.label : option;
        const isSelected = value === optionValue;
        
        return (
          <label 
            key={optionValue} 
            className={`
              flex-1 flex items-center justify-center cursor-pointer p-2 rounded-lg border-2 transition-all
              ${isSelected 
                ? 'border-blue-500 bg-blue-50 shadow-sm' 
                : 'border-gray-200 bg-white hover:border-gray-300 hover:bg-gray-50'
              }
            `}
          >
            <div className="relative flex items-center justify-center w-4 h-4 mr-2">
              <input
                type="radio"
                name={`radio-${Math.random()}`}
                value={optionValue}
                checked={isSelected}
                onChange={(e) => onChange(e.target.value)}
                className="sr-only"
              />
              <div className={`
                w-4 h-4 rounded-full border-2 transition-all
                ${isSelected ? 'border-blue-500' : 'border-gray-300'}
              `}>
                {isSelected && (
                  <div className="absolute inset-0 flex items-center justify-center">
                    <div className="w-2 h-2 rounded-full bg-blue-500"></div>
                  </div>
                )}
              </div>
            </div>
            <span className={`text-sm font-medium whitespace-nowrap ${isSelected ? 'text-blue-700' : 'text-gray-700'}`}>
              {optionLabel}
            </span>
          </label>
        );
      })}
    </div>
  );
}

/**
 * Icon selector component with FontAwesome icons
 */
export function IconInput({ value, onChange }) {
  const [isOpen, setIsOpen] = React.useState(false);
  const selectedIcon = FONTAWESOME_ICONS.find(icon => icon.value === value) || FONTAWESOME_ICONS[0];
  
  return (
    <div className="relative">
      <button
        type="button"
        onClick={() => setIsOpen(!isOpen)}
        className="w-full flex items-center justify-between p-2 border border-gray-300 rounded hover:bg-gray-50"
      >
        <div className="flex items-center gap-2">
          {selectedIcon.icon && (
            <i className={`fa ${selectedIcon.icon} text-gray-700`}></i>
          )}
          <span className="text-sm">{selectedIcon.label}</span>
        </div>
        <i className="fa fa-chevron-down text-xs text-gray-500"></i>
      </button>
      
      {isOpen && (
        <>
          <div 
            className="fixed inset-0 z-10" 
            onClick={() => setIsOpen(false)}
          ></div>
          <div className="absolute z-20 mt-1 w-full bg-white border border-gray-300 rounded shadow-lg max-h-64 overflow-y-auto">
            {FONTAWESOME_ICONS.map((icon) => (
              <button
                key={icon.value}
                type="button"
                onClick={() => {
                  onChange(icon.value);
                  setIsOpen(false);
                }}
                className={`w-full flex items-center gap-3 p-2 hover:bg-blue-50 text-left ${
                  value === icon.value ? 'bg-blue-100' : ''
                }`}
              >
                {icon.icon ? (
                  <i className={`fa ${icon.icon} w-5 text-gray-700`}></i>
                ) : (
                  <span className="w-5 text-xs text-gray-400">-</span>
                )}
                <span className="text-sm">{icon.label}</span>
              </button>
            ))}
          </div>
        </>
      )}
    </div>
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
    case "radio":
      return <RadioInput value={value} onChange={onChange} options={config.options} />;
    case "icon":
      return <IconInput value={value} onChange={onChange} />;
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