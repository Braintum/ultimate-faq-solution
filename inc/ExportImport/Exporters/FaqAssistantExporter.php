<?php
/**
 * Settings Exporter for Ultimate FAQ Solution plugin.
 *
 * @package Mahedi\UltimateFaqSolution\ExportImport\Exporters
 */

namespace Mahedi\UltimateFaqSolution\ExportImport\Exporters;

use Mahedi\UltimateFaqSolution\ExportImport\Base\BaseExporter;

/**
 * Class to handle exporting of FAQ settings.
 */
class FaqAssistantExporter extends BaseExporter {

	/**
	 * The type of the exporter.
	 *
	 * @var string
	 */
	protected string $type = 'faq_assistant';

	/**
	 * Export settings data.
	 *
	 * @return array<string, mixed>
	 */
	public function export(): array {

		$settings = array();

		$settings['ufaqsw_chatbot_settings'] = get_option( 'ufaqsw_chatbot_settings' );
		return $settings;
	}
}
