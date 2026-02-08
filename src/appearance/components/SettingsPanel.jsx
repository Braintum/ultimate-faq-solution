import React from 'react';
import { __ } from '@wordpress/i18n';
import { SettingsGroup, ActionButtons } from './index';

/**
 * Settings Panel Component
 * Displays the left panel with settings groups and action buttons
 */
export function SettingsPanel({ 
	schema, 
	values, 
	setField, 
	onReset, 
	onSave, 
	isSaving 
}) {
  return (
    <div className="w-96 bg-white border rounded shadow-sm flex flex-col h-[1000px]">
		{/* Header */}
		<div className="flex items-center justify-between p-4 border-b flex-shrink-0">
			<h3 className="text-lg font-semibold">{__('Appearance Builder', 'ufaqsw')}</h3>
			<div className="text-xs text-gray-500"></div>
		</div>

		{/* Action Buttons */}
		<div className="p-2 border-t flex-shrink-0 bg-white shadow-md">
			<ActionButtons onReset={onReset} onSave={onSave} isSaving={isSaving} />
		</div>

		{/* Settings Groups */}
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

		<div className="p-2 border-t flex-shrink-0 bg-white">
		</div>
    </div>
  );
}
