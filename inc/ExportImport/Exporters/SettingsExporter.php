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
class SettingsExporter extends BaseExporter {

	/**
	 * The type of the exporter.
	 *
	 * @var string
	 */
	protected string $type = 'settings';

	/**
	 * Export settings data.
	 *
	 * @return array<string, mixed>
	 */
	public function export(): array {
		$settings = get_option( 'ultimate_faq_solution_settings', array() );
		return (array) $settings;
	}
}
