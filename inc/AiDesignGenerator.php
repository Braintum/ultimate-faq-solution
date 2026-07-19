<?php
/**
 * AI Design Generator
 *
 * Generates appearance settings from a natural-language prompt using the
 * configured AI provider (from ufaqsw_ai_integration_settings).
 *
 * @package UltimateFaqSolution
 */

namespace Mahedi\UltimateFaqSolution;

use BTRefiner\API\AIProviderFactory;
use BTRefiner\Exceptions\AIProviderException;

/**
 * Class AiDesignGenerator
 */
class AiDesignGenerator {

	/**
	 * Generate design settings from a natural-language prompt.
	 *
	 * @param string $prompt          User's description of the desired design.
	 * @param array  $current_settings Current builder values for context.
	 * @return array Validated settings ready to apply.
	 * @throws \RuntimeException       If AI is disabled or JSON parsing fails.
	 * @throws AIProviderException     If the AI provider call fails.
	 */
	public function generate( string $prompt, array $current_settings ): array {
		// The BTRefiner autoloader is registered inside is_admin() in init.php,
		// but REST API requests don't pass that check — ensure it's loaded.
		if ( ! class_exists( 'BTRefiner\API\AIProviderFactory' ) ) {
			require_once UFAQSW__PLUGIN_DIR . 'inc/ai-writing-assistant/autoloader.php';
		}
		$ai_settings = get_option( 'ufaqsw_ai_integration_settings', array() );

		if ( empty( $ai_settings['enable_ai_integration'] ) ) {
			throw new \RuntimeException(
				__( 'AI Integration is not enabled. Please enable it in AI Integration settings.', 'ufaqsw' )
			);
		}

		$provider  = AIProviderFactory::make();
		$system    = $this->build_system_prompt( $current_settings );
		$raw       = $provider->complete( $prompt, $system );
		$settings  = Rest::sanitize_appearance_settings( $this->extract_json( $raw ) );

		// Never constrain the container width unless the user explicitly asked for it.
		if ( ! preg_match( '/\b(width|max.?width|container)\b/i', $prompt ) ) {
			$settings['container_max_width'] = '';
		}

		return $settings;
	}

	/**
	 * Build the system prompt encoding the full schema, examples, and current values.
	 *
	 * @param array $current_settings Current builder values passed for context.
	 * @return string System prompt string.
	 */
	private function build_system_prompt( array $current_settings ): string {
		$schema_text   = $this->describe_schema();
		$examples_text = $this->get_preset_examples();
		$current_json  = wp_json_encode( $current_settings, JSON_PRETTY_PRINT );

		return "You are a design assistant for a WordPress FAQ plugin called \"Ultimate FAQ Solution\".\n"
			. "Generate a complete set of design settings as a single JSON object based on the user's description.\n\n"
			. "## Available Settings\n\n"
			. $schema_text
			. "## Example Designs\n\n"
			. $examples_text
			. "## User's Current Settings (for context — you may build on these or replace them)\n"
			. "```json\n{$current_json}\n```\n\n"
			. "## Rules\n"
			. "- Return ONLY a valid JSON object. No explanation, no markdown code fences, no prose.\n"
			. "- Prefer the \"universal\" template for maximum design flexibility unless the user requests otherwise.\n"
			. "- Color values must be hex strings (e.g. \"#1e293b\") or empty string \"\" to inherit the theme.\n"
			. "- Numeric fields must be plain integers (e.g. 16) or empty string \"\" to use the default.\n"
			. "- Boolean fields must be true or false.\n"
			. "- Only use field names listed in the schema above. Do not invent new fields.\n"
			. "- Include all fields relevant to the chosen template.\n"
			. "- Leave `container_max_width` as empty string unless the user explicitly requests a specific width.\n";
	}

	/**
	 * Produce a compact human-readable description of every setting for the AI.
	 *
	 * @return string Multi-line schema description.
	 */
	private function describe_schema(): string {
		$schema = self::get_schema_for_ai();
		$lines  = array();

		foreach ( $schema as $group ) {
			$header = "### {$group['label']}";
			if ( ! empty( $group['condition'] ) ) {
				$header .= ' (universal template only)';
			}
			$lines[] = $header;

			foreach ( $group['fields'] as $key => $field ) {
				$line = "- `{$key}` ({$field['type']}): {$field['label']}";

				if ( ! empty( $field['options'] ) ) {
					$vals  = array_column( $field['options'], 'value' );
					$line .= ' — ' . implode( ' | ', array_map( fn( $v ) => "\"$v\"", $vals ) );
				}
				if ( isset( $field['min'], $field['max'] ) ) {
					$line .= " — {$field['min']}–{$field['max']}";
				}
				if ( ! empty( $field['condition'] ) ) {
					$c     = $field['condition'];
					$cv    = is_bool( $c['value'] ) ? ( $c['value'] ? 'true' : 'false' ) : "\"{$c['value']}\"";
					$line .= " (only when {$c['field']} = {$cv})";
				}

				$lines[] = $line;
			}
			$lines[] = '';
		}

		return implode( "\n", $lines );
	}

	/**
	 * Load a handful of bundled presets as few-shot examples for the AI.
	 *
	 * @return string Formatted example block.
	 */
	private function get_preset_examples(): string {
		$preset_dir = UFAQSW__PLUGIN_DIR . 'inc/design-presets/';
		$show       = array( 'clean-white', 'dark-pro', 'bold-orange', 'enterprise-gray' );
		$output     = array();

		foreach ( $show as $id ) {
			$file = $preset_dir . $id . '.json';
			if ( ! file_exists( $file ) ) {
				continue;
			}
			$data = json_decode( file_get_contents( $file ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
			if ( ! $data || empty( $data['settings'] ) ) {
				continue;
			}
			$output[] = "### {$data['name']} ({$data['category']}): {$data['description']}\n"
				. wp_json_encode( $data['settings'] );
		}

		return implode( "\n\n", $output ) . "\n\n";
	}

	/**
	 * Extract the first JSON object from the AI response, stripping any surrounding prose.
	 *
	 * @param string $raw Raw AI response text.
	 * @return array Decoded associative array.
	 * @throws \RuntimeException If no valid JSON object is found.
	 */
	private function extract_json( string $raw ): array {
		// Strip markdown code fences the model may have added despite instructions.
		$raw   = trim( preg_replace( '/^```(?:json)?\s*|\s*```$/m', '', $raw ) );
		$start = strpos( $raw, '{' );
		$end   = strrpos( $raw, '}' );

		if ( false === $start || false === $end ) {
			throw new \RuntimeException(
				__( 'AI did not return a valid JSON object. Please try again.', 'ufaqsw' )
			);
		}

		$decoded = json_decode( substr( $raw, $start, $end - $start + 1 ), true );

		if ( JSON_ERROR_NONE !== json_last_error() ) {
			throw new \RuntimeException(
				__( 'AI returned malformed JSON. Please try again.', 'ufaqsw' )
			);
		}

		return $decoded;
	}

	/**
	 * PHP mirror of DEFAULT_SCHEMA from helpers.js.
	 *
	 * Must be kept in sync when the JS schema changes.
	 *
	 * @return array Schema definition.
	 */
	public static function get_schema_for_ai(): array {
		return array(
			'general'       => array(
				'label'  => 'Layout & Behaviour',
				'fields' => array(
					'template'      => array(
						'type'    => 'radio',
						'label'   => 'Template Style',
						'options' => array(
							array( 'value' => 'default',   'label' => 'Default' ),
							array( 'value' => 'style-1',   'label' => 'Style 1' ),
							array( 'value' => 'style-2',   'label' => 'Style 2' ),
							array( 'value' => 'universal', 'label' => 'Universal (recommended, most features)' ),
						),
					),
					'layout'        => array(
						'type'      => 'radio',
						'label'     => 'Layout Variant',
						'condition' => array( 'field' => 'template', 'value' => 'universal' ),
						'options'   => array(
							array( 'value' => 'classic', 'label' => 'Classic' ),
							array( 'value' => 'card',    'label' => 'Card' ),
							array( 'value' => 'minimal', 'label' => 'Minimal' ),
							array( 'value' => 'boxed',   'label' => 'Boxed' ),
						),
					),
					'behaviour'     => array(
						'type'    => 'select',
						'label'   => 'Behaviour',
						'options' => array(
							array( 'value' => 'accordion', 'label' => 'Accordion' ),
							array( 'value' => 'toggle',    'label' => 'Toggle' ),
						),
					),
					'showall'       => array(
						'type'      => 'toggle',
						'label'     => 'Show All Answers Opened',
						'condition' => array( 'field' => 'behaviour', 'value' => 'toggle' ),
					),
					'animation'     => array(
						'type'      => 'select',
						'label'     => 'Animation',
						'condition' => array( 'field' => 'template', 'value' => 'universal' ),
						'options'   => array(
							array( 'value' => 'none',  'label' => 'None' ),
							array( 'value' => 'slide', 'label' => 'Slide' ),
							array( 'value' => 'fade',  'label' => 'Fade' ),
						),
					),
					'icon_position' => array(
						'type'      => 'select',
						'label'     => 'Icon Position',
						'condition' => array( 'field' => 'template', 'value' => 'universal' ),
						'options'   => array(
							array( 'value' => 'right', 'label' => 'Right' ),
							array( 'value' => 'left',  'label' => 'Left' ),
						),
					),
					'border_color'  => array( 'type' => 'color', 'label' => 'Border Color' ),
					'normal_icon'   => array( 'type' => 'text', 'label' => 'Normal Icon (FontAwesome class, e.g. "fa-plus", "fa-chevron-down")' ),
					'active_icon'   => array( 'type' => 'text', 'label' => 'Active Icon (FontAwesome class, e.g. "fa-minus", "fa-chevron-up")' ),
				),
			),
			'group'         => array(
				'label'  => 'Group Title',
				'fields' => array(
					'hidetitle'       => array( 'type' => 'toggle', 'label' => 'Hide Title' ),
					'title_color'     => array(
						'type'      => 'color',
						'label'     => 'Title Color',
						'condition' => array( 'field' => 'hidetitle', 'value' => false ),
					),
					'title_font_size' => array(
						'type'      => 'range',
						'label'     => 'Title Font Size',
						'min'       => 12,
						'max'       => 100,
						'condition' => array( 'field' => 'hidetitle', 'value' => false ),
					),
				),
			),
			'question'      => array(
				'label'  => 'Question Row',
				'fields' => array(
					'question_color'            => array( 'type' => 'color',  'label' => 'Question Text Color' ),
					'question_background_color' => array( 'type' => 'color',  'label' => 'Question Background Color' ),
					'question_font_size'        => array( 'type' => 'range',  'label' => 'Question Font Size', 'min' => 12, 'max' => 36 ),
					'question_bold'             => array( 'type' => 'toggle', 'label' => 'Bold Question Text' ),
				),
			),
			'answer'        => array(
				'label'  => 'Answer Panel',
				'fields' => array(
					'answer_color'            => array( 'type' => 'color', 'label' => 'Answer Text Color' ),
					'answer_background_color' => array( 'type' => 'color', 'label' => 'Answer Background Color' ),
					'answer_font_size'        => array( 'type' => 'range', 'label' => 'Answer Font Size', 'min' => 12, 'max' => 36 ),
				),
			),
			'active_states' => array(
				'label'     => 'Active & Hover States',
				'condition' => array( 'field' => 'template', 'value' => 'universal' ),
				'fields'    => array(
					'active_question_color' => array( 'type' => 'color', 'label' => 'Active Question Color' ),
					'active_question_bg'    => array( 'type' => 'color', 'label' => 'Active Question Background' ),
					'icon_color'            => array( 'type' => 'color', 'label' => 'Icon Color' ),
					'active_icon_color'     => array( 'type' => 'color', 'label' => 'Active Icon Color' ),
					'title_bg_color'        => array( 'type' => 'color', 'label' => 'Group Title Background' ),
					'active_border_color'   => array( 'type' => 'color', 'label' => 'Active Item Border Color' ),
				),
			),
			'spacing'       => array(
				'label'     => 'Spacing',
				'condition' => array( 'field' => 'template', 'value' => 'universal' ),
				'fields'    => array(
					'item_padding'        => array( 'type' => 'range', 'label' => 'Item Padding (px)',       'min' => 4,   'max' => 48 ),
					'item_gap'            => array( 'type' => 'range', 'label' => 'Gap Between Items (px)',  'min' => 0,   'max' => 32 ),
					'container_max_width' => array( 'type' => 'range', 'label' => 'Max Width (px)',          'min' => 400, 'max' => 1400 ),
				),
			),
			'borders'       => array(
				'label'     => 'Borders',
				'condition' => array( 'field' => 'template', 'value' => 'universal' ),
				'fields'    => array(
					'border_radius'        => array( 'type' => 'range',  'label' => 'Border Radius (px)', 'min' => 0, 'max' => 32 ),
					'border_width'         => array( 'type' => 'range',  'label' => 'Border Width (px)',  'min' => 0, 'max' => 8 ),
					'border_style'         => array(
						'type'    => 'select',
						'label'   => 'Border Style',
						'options' => array(
							array( 'value' => 'solid',  'label' => 'Solid' ),
							array( 'value' => 'dashed', 'label' => 'Dashed' ),
							array( 'value' => 'dotted', 'label' => 'Dotted' ),
						),
					),
					'item_border_position' => array(
						'type'    => 'select',
						'label'   => 'Border Position',
						'options' => array(
							array( 'value' => 'all',         'label' => 'All Sides' ),
							array( 'value' => 'bottom-only', 'label' => 'Bottom Only' ),
							array( 'value' => 'left-accent', 'label' => 'Left Accent' ),
						),
					),
					'shadow_style'         => array(
						'type'    => 'select',
						'label'   => 'Shadow',
						'options' => array(
							array( 'value' => 'none',   'label' => 'None' ),
							array( 'value' => 'subtle', 'label' => 'Subtle' ),
							array( 'value' => 'medium', 'label' => 'Medium' ),
							array( 'value' => 'strong', 'label' => 'Strong' ),
						),
					),
				),
			),
			'typography'    => array(
				'label'     => 'Typography',
				'condition' => array( 'field' => 'template', 'value' => 'universal' ),
				'fields'    => array(
					'question_font_weight'    => array(
						'type'    => 'select',
						'label'   => 'Question Font Weight',
						'options' => array(
							array( 'value' => '',    'label' => 'Default' ),
							array( 'value' => '400', 'label' => '400' ),
							array( 'value' => '500', 'label' => '500' ),
							array( 'value' => '600', 'label' => '600' ),
							array( 'value' => '700', 'label' => '700' ),
						),
					),
					'title_font_weight'       => array(
						'type'    => 'select',
						'label'   => 'Title Font Weight',
						'options' => array(
							array( 'value' => '',    'label' => 'Default' ),
							array( 'value' => '400', 'label' => '400' ),
							array( 'value' => '500', 'label' => '500' ),
							array( 'value' => '600', 'label' => '600' ),
							array( 'value' => '700', 'label' => '700' ),
						),
					),
					'question_letter_spacing' => array( 'type' => 'range',  'label' => 'Question Letter Spacing', 'min' => 0, 'max' => 4 ),
					'question_text_transform' => array(
						'type'    => 'select',
						'label'   => 'Question Text Transform',
						'options' => array(
							array( 'value' => '',           'label' => 'None' ),
							array( 'value' => 'uppercase',  'label' => 'Uppercase' ),
							array( 'value' => 'capitalize', 'label' => 'Capitalize' ),
						),
					),
					'answer_line_height'      => array(
						'type'    => 'select',
						'label'   => 'Answer Line Height',
						'options' => array(
							array( 'value' => '',    'label' => 'Default' ),
							array( 'value' => '1.4', 'label' => '1.4' ),
							array( 'value' => '1.6', 'label' => '1.6' ),
							array( 'value' => '1.8', 'label' => '1.8' ),
							array( 'value' => '2.0', 'label' => '2.0' ),
						),
					),
					'icon_size'               => array( 'type' => 'range', 'label' => 'Icon Size (px)', 'min' => 8, 'max' => 30 ),
				),
			),
		);
	}
}
