<?php
/**
 * Import Manager for handling FAQ import operations.
 *
 * @package Mahedi\UltimateFaqSolution\ExportImport\Managers
 */

namespace Mahedi\UltimateFaqSolution\ExportImport\Managers;

use Mahedi\UltimateFaqSolution\ExportImport\Contracts\ImporterInterface;

/**
 * Manager class for handling FAQ import operations.
 */
class ImportManager {

	/**
	 * Registered importers.
	 *
	 * @var array<string, ImporterInterface>
	 */
	protected array $importers = array();

	/**
	 * Register an importer for a specific type.
	 *
	 * @param ImporterInterface $importer The importer instance to register.
	 * @return void
	 */
	public function registerImporter( ImporterInterface $importer ): void {
		$this->importers[ $importer->getType() ] = $importer;
	}

	/**
	 * Import the given payload using registered importers.
	 *
	 * @param array $payload The payload to import.
	 * @return array The import report.
	 */
	public function importPayload( array $payload ): array {
		$report = array(
			'meta'    => $payload['meta'] ?? array(),
			'results' => array(),
		);

		$data = $payload['data'] ?? array();
		foreach ( $data as $type => $items ) {
			if ( isset( $this->importers[ $type ] ) ) {
				$result                     = $this->importers[ $type ]->import( $items );
				$report['results'][ $type ] = $result;
			} else {
				$report['results'][ $type ] = array(
					'skipped' => true,
					'reason'  => 'no importer registered for this type',
				);
			}
		}
		return $report;
	}
}
