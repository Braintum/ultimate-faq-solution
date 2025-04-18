import { registerBlockType } from '@wordpress/blocks';
import { useSelect } from '@wordpress/data';
import { useEffect, useState } from '@wordpress/element';
import { PanelBody, SelectControl, CheckboxControl } from '@wordpress/components';
import { InspectorControls } from '@wordpress/block-editor';
import { __, _x } from '@wordpress/i18n';
import apiFetch from '@wordpress/api-fetch';

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
		
		const [faqGroups, setFaqGroups] = useState([]);

		useEffect(() => {
			apiFetch({ path: '/wp/v2/ufaqsw?per_page=100' }).then((posts) => {
				setFaqGroups(posts);
			});
		}, []);

		const behaviours = useSelect((select) => {
			return [
				{
					label: __( 'Choose a behaviour', 'ufaqsw' ),
					value: '',
				},
				{
					label: __( 'Accordion', 'ufaqsw' ),
					value: 'accordion',
				},
				{
					label: __( 'Toggle', 'ufaqsw' ),
					value: 'toggle',
				},

			];
		}, []);

		const orders = useSelect((select) => {
			return [
				{
					label: __( 'Choose an order', 'ufaqsw' ),
					value: '',
				},
				{
					label: __( 'ASC', 'ufaqsw' ),
					value: 'asc',
				},
				{
					label: __( 'DESC', 'ufaqsw' ),
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
					<PanelBody title={__( 'FAQ Settings', 'ufaqsw' )}>
						<SelectControl
							label={__( 'Select FAQ Group', 'ufaqsw' )}
							help={__( 'Choose a group to display FAQs from.', 'ufaqsw' )}
							value={attributes.group}
							options={
								faqGroups
									? [{ label: __( 'Select a FAQ group', 'ufaqsw'), value: '' }, { label: __('All', 'ufaqsw'), value: 'all' }].concat(
										faqGroups.map((group) => ({
											label: group.title,
											value: group.id
										}))
									  )
									: [{ label: __('Loading...', 'ufaqsw'), value: '' }]
							}
							onChange={(value) => setAttributes({ group: value })}
						/>

						<CheckboxControl
                            label={__( 'Hide group title', 'ufaqsw' )}
							help={__( 'Check to hide the group title. This will override the faq group setting.', 'ufaqsw' )}
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
