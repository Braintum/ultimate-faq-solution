<?php
/**
 * Settings Importer for Ultimate FAQ Solution plugin.
 *
 * @package Mahedi\UltimateFaqSolution\ExportImport\Importers
 */

namespace Mahedi\UltimateFaqSolution\ExportImport\Importers;

use Mahedi\UltimateFaqSolution\ExportImport\Base\BaseImporter;

/**
 * Class to handle importing of FAQ settings.
 */
class SettingsImporter extends BaseImporter {

	/**
	 * The type of the importer.
	 *
	 * @var string
	 */
	protected string $type = 'settings';

	/**
	 * Import settings data.
	 *
	 * @param array $data The settings data to import.
	 * @return array Import report.
	 */
	public function import( array $data ): array {
		// Overwrite by default.
		update_option( 'ultimate_faq_solution_settings', $data );
		return array( 'status' => 'ok' );
	}
}
