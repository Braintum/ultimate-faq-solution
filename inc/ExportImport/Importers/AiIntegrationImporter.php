<?php
/**
 * Settings Importer for Ultimate FAQ Solution plugin.
 *
 * @package Mahedi\UltimateFaqSolution\ExportImport\Importers
 */

namespace Mahedi\UltimateFaqSolution\ExportImport\Importers;

use Mahedi\UltimateFaqSolution\ExportImport\Base\BaseImporter;

/**
 * Class to handle importing of AI integration settings.
 */
class AiIntegrationImporter extends BaseImporter {

	/**
	 * The type of the importer.
	 *
	 * @var string
	 */
	protected string $type = 'ai_integration';

	/**
	 * Import settings data.
	 *
	 * @param array $data The settings data to import.
	 * @return array Import report.
	 */
	public function import( array $data ): array {

		foreach ( $data as $key => $value ) {
			update_option( $key, maybe_unserialize( $value ) );
		}

		return $data['ufaqsw_ai_integration_settings'];
	}
}
