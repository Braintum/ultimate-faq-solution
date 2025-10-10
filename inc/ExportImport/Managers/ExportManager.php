<?php
/**
 * Export Manager for handling FAQ export operations.
 *
 * @package Mahedi\UltimateFaqSolution\ExportImport\Managers
 */

namespace Mahedi\UltimateFaqSolution\ExportImport\Managers;

use Mahedi\UltimateFaqSolution\ExportImport\Contracts\ExporterInterface;

/**
 * Manager class for handling FAQ export operations.
 */
class ExportManager {

	/**
	 * Undocumented variable
	 *
	 * @var array
	 */
	protected array $exporters = array();

	/**
	 * Register an exporter for a specific type.
	 *
	 * @param ExporterInterface $exporter The exporter instance to register.
	 * @return void
	 */
	public function registerExporter( ExporterInterface $exporter ): void {
		$this->exporters[ $exporter->getType() ] = $exporter;
	}

	/**
	 * Export all registered types of data
	 *
	 * @return array
	 */
	public function exportAll(): array {
		$out = array(
			'meta' => array(
				'exported_at' => gmdate( 'c' ),
				'plugin'      => 'ultimate-faq-solution',
				'version'     => '1.0.0',
			),
			'data' => array(),
		);

		foreach ( $this->exporters as $type => $exporter ) {
			$out['data'][ $type ] = $exporter->export();
		}
		return $out;
	}

	/**
	 * Export selected types of data
	 *
	 * @param array $types Array of selected types (e.g. ['faq_groups', 'settings']).
	 * @return array
	 */
	public function export( array $types ): array {
		$export_data = array();

		foreach ( $types as $type ) {
			if ( isset( $this->exporters[ $type ] ) ) {
				$export_data[ $type ] = $this->exporters[ $type ]->export();
			}
		}

		return $export_data;
	}

	/**
	 * Export all data as a JSON string
	 *
	 * @return string
	 */
	public function exportToJson(): string {
		return wp_json_encode( $this->exportAll(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE );
	}
}
