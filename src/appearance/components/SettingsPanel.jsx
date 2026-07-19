import React from 'react';
import { __, _n, sprintf } from '@wordpress/i18n';
import { SettingsGroup, ActionButtons } from './index';
import { isFieldVisible } from '../helpers';

/**
 * Settings Panel Component
 *
 * Left panel of the Appearance Builder. Shows:
 *  - "Applied to X FAQ Groups" indicator (linked groups from PHP)
 *  - Save / Reset action buttons
 *  - All settings groups (layout, group title, question row, answer panel)
 *  - A status bar at the bottom after saving
 */
export function SettingsPanel({
    schema,
    values,
    setField,
    onReset,
    onSave,
    isSaving,
    linkedGroups = [],
    lastSaveGroupCount = null,
    onOpenDesignLibrary,
}) {
    const groupCount = linkedGroups.length;

    return (
        <div className="w-96 bg-white border rounded shadow-sm flex flex-col h-[1000px]">

            {/* Header */}
            <div className="flex items-center justify-between p-4 border-b flex-shrink-0">
                <h3 className="text-lg font-semibold">{__('Appearance Builder', 'ufaqsw')}</h3>
                <button
                    type="button"
                    onClick={onOpenDesignLibrary}
                    className="text-xs text-blue-600 hover:text-blue-700 font-medium border border-blue-200 hover:border-blue-400 rounded px-2.5 py-1 transition-colors cursor-pointer"
                >
                    {__('Design Library', 'ufaqsw')}
                </button>
            </div>

            {/* "Applied to X groups" indicator */}
            <div className="px-4 pt-3 pb-2 flex-shrink-0">
                {groupCount > 0 ? (
                    <div className="flex items-center gap-2 text-xs text-green-700 bg-green-50 border border-green-200 rounded px-3 py-2">
                        <svg className="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fillRule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clipRule="evenodd" />
                        </svg>
                        <span>
                            {sprintf(
                                /* translators: %d: number of FAQ groups using this appearance */
                                _n('Applied to %d FAQ Group', 'Applied to %d FAQ Groups', groupCount, 'ufaqsw'),
                                groupCount
                            )}
                            {' — '}
                            <span className="font-medium">{__('changes update all of them', 'ufaqsw')}</span>
                        </span>
                    </div>
                ) : (
                    <div className="flex items-center gap-2 text-xs text-amber-700 bg-amber-50 border border-amber-200 rounded px-3 py-2">
                        <svg className="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{__('Not applied to any FAQ Group yet.', 'ufaqsw')}</span>
                    </div>
                )}
            </div>

            {/* Action Buttons */}
            <div className="px-4 py-2 border-t flex-shrink-0 bg-white shadow-sm">
                <ActionButtons onReset={onReset} onSave={onSave} isSaving={isSaving} />
            </div>

            {/* Settings Groups */}
            <div className="flex-1 overflow-y-auto p-4 space-y-4">
                {Object.keys(schema).map((groupKey) => {
                    const group = schema[groupKey];
                    if (group.condition && !isFieldVisible({ condition: group.condition }, values)) {
                        return null;
                    }
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

            {/* Bottom status bar (visible after first save) */}
            <div className="p-3 border-t flex-shrink-0 bg-gray-50 text-xs text-gray-500 text-center">
                {lastSaveGroupCount !== null ? (
                    lastSaveGroupCount > 0 ? (
                        <span className="text-green-700">
                            {sprintf(
                                _n('Saved — live on %d page', 'Saved — live on %d pages', lastSaveGroupCount, 'ufaqsw'),
                                lastSaveGroupCount
                            )}
                        </span>
                    ) : (
                        <span className="text-amber-700">
                            {__('Saved — no FAQ Groups are using this appearance yet.', 'ufaqsw')}
                        </span>
                    )
                ) : (
                    <span>{__('Changes are reflected in the preview on the right.', 'ufaqsw')}</span>
                )}
            </div>

        </div>
    );
}

