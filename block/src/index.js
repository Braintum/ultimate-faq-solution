import { registerBlockType } from '@wordpress/blocks';
import { useSelect } from '@wordpress/data';
import { useState } from 'react';  // Import useState from React
import { PanelBody, SelectControl, CheckboxControl } from '@wordpress/components';
import { InspectorControls } from '@wordpress/block-editor';

registerBlockType('ultimate-faq-solution/block', {
    title: 'FAQ',
    icon: 'editor-help',
    category: 'widgets',
    attributes: {
        group: {
            type: 'string',
            default: ''
        },
		exclude: {
            type: 'array',
            default: [],
        },
		column: {
            type: 'string',
            default: [],
        },
		behaviour: {
            type: 'string',
            default: [],
        },
		elements_order: {
            type: 'string',
            default: [],
        },
		hideTitle: {
            type: 'boolean',
            default: false,  // default to false
        }
    },
	/**
	 * Edit function for the Ultimate FAQ block.
	 *
	 * This function renders the block's edit interface, including the settings panel
	 * for selecting FAQ groups and excluding specific groups when "All" is selected.
	 *
	 * @param {Object} props - The properties passed to the edit function.
	 * @param {Object} props.attributes - The block's attributes.
	 * @param {Function} props.setAttributes - Function to update block attributes.
	 * @param {boolean} props.isSelected - Whether the block is selected in the editor.
	 * @param {Object} props.context - The block's context.
	 *
	 * @returns {JSX.Element} The edit interface for the block.
	 */
    edit({ attributes, setAttributes, isSelected, context }) {
		
		const faqGroups = useSelect((select) => {
			return select('core').getEntityRecords('postType', 'ufaqsw', { per_page: -1 });
		}, []);

		const columns = useSelect((select) => {
			return [
				{
					label: 'Choose a column',
					value: '',
				},
				{
					label: '1',
					value: '1',
				},
				{
					label: '2',
					value: '2',
				},
				{
					label: '3',
					value: '3',
				},
			];
		}, []);

		const behaviours = useSelect((select) => {
			return [
				{
					label: 'Choose a behaviour',
					value: '',
				},
				{
					label: 'Accordion',
					value: 'accordion',
				},
				{
					label: 'Toggle',
					value: 'toggle',
				},

			];
		}, []);

		const orders = useSelect((select) => {
			return [
				{
					label: 'Choose an order',
					value: '',
				},
				{
					label: 'ASC',
					value: 'asc',
				},
				{
					label: 'DESC',
					value: 'desc',
				},

			];
		}, []);

		const shortcode = function() {

			if (  !attributes.group ) {
				return '';
			}

			let text = '[ ';

			if ( 'all' === attributes.group) {
				text += 'ufaqsw-all ';

				if ( attributes.exclude.length > 0 ) {
					text += 'exclude="' + attributes.exclude.join(',') + '" ';
				}
				if ( attributes.column.length > 0 ) {
					text += 'column="' + attributes.column + '" ';
				}
				if ( attributes.behaviour.length > 0 ) {
					text += 'behaviour="' + attributes.behaviour + '" ';
				}

			} else {
				text += 'ufaqsw id="' + attributes.group + '" ';
			}

			
			if ( attributes.elements_order.length > 0 ) {
				text += 'elements_order="' + attributes.elements_order + '" ';
			}
			if ( attributes.hideTitle ) {
				text += 'title_hide="1" ';
			}
			text += ']';
			return text;
		}
	
		return (
			<>
				<InspectorControls>
					<PanelBody title="FAQ Settings">
						<SelectControl
							label="Select FAQ Group"
							help="Choose a group to display FAQs from."
							value={attributes.group}
							options={
								faqGroups
									? [{ label: 'Select a FAQ group', value: '' }, { label: 'All', value: 'all' }].concat(
										faqGroups.map((group) => ({
											label: group.title,
											value: group.id
										}))
									  )
									: [{ label: 'Loading...', value: '' }]
							}
							onChange={(value) => setAttributes({ group: value })}
						/>

						<CheckboxControl
                            label="Hide group title"
							help="Check to hide the group title. This will override the faq group setting."
                            checked={attributes.hideTitle}
                            onChange={(value) => setAttributes({ hideTitle: value })}
                        />

						{attributes.group === 'all' && (
							<SelectControl
								label="Behaviour"
								help="Choose a behaviour for the FAQ display. This will override the faq group settings."
								value={attributes.behaviour}
								options={behaviours}
								onChange={(value) => setAttributes({ behaviour: value })}
							/>
						)}

						<SelectControl
							label="Order"
							value={attributes.elements_order}
							options={orders}
							onChange={(value) => setAttributes({ elements_order: value })}
						/>

						{attributes.group === 'all' && (
							<SelectControl
								label="Column"
								help="Select Column to display FAQs from."
								value={attributes.column}
								options={columns}
								onChange={(value) => setAttributes({ column: value })}
							/>
						)}
						{attributes.group === 'all' && (
							<PanelBody title="Exclude Individual FAQ Groups">
								{faqGroups &&
									faqGroups.map((group) => (
										<CheckboxControl
											key={group.id}
											label={group.title}
											checked={attributes.exclude.includes(group.id)}
											onChange={(isChecked) => {
												const updatedExclude = isChecked
													? [...attributes.exclude, group.id]
													: attributes.exclude.filter((id) => id !== group.id);
												setAttributes({ exclude: updatedExclude });
											}}
										/>
									))}
							</PanelBody>
						)}
					</PanelBody>
					
				</InspectorControls>
				<div className="components-placeholder is-large">
					<div class="components-placeholder__label">
						<span className="faq-block-icon dashicons dashicons-editor-help" role="img" aria-label="FAQ Icon"></span> FAQ Block
					</div>
					<div className="components-placeholder__instructions">
						Configure the block from right panel - Faq Settings
					</div>
					<div className="components-placeholder__fieldset">
						<label>Generated Shortcode</label>
						<input
							className="components-text-control__input"
							type="text"
							disabled
							value={shortcode()} // Dynamically set the value using the shortcode function
						></input>
					</div>
					
				</div>
			</>
		);
	},
    save() {
        // Rendered server-side via shortcode, so return null
        return null;
    }
});
