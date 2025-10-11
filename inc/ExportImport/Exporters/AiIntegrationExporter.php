<?php
/**
 * Settings Exporter for Ultimate FAQ Solution plugin.
 *
 * @package Mahedi\UltimateFaqSolution\ExportImport\Exporters
 */

namespace Mahedi\UltimateFaqSolution\ExportImport\Exporters;

use Mahedi\UltimateFaqSolution\ExportImport\Base\BaseExporter;

/**
 * Class to handle exporting of AI integration settings.
 */
class AiIntegrationExporter extends BaseExporter {

	/**
	 * The type of the exporter.
	 *
	 * @var string
	 */
	protected string $type = 'ai_integration';

	/**
	 * Export settings data.
	 *
	 * @return array<string, mixed>
	 */
	public function export(): array {

		$settings = array();

		$settings['ufaqsw_ai_integration_settings'] = get_option( 'ufaqsw_ai_integration_settings' );
		return $settings;
	}
}
