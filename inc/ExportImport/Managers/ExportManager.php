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
				'version'     => UFAQSW_VERSION,
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
		$out = array(
			'meta' => array(
				'exported_at' => gmdate( 'c' ),
				'plugin'      => 'ultimate-faq-solution',
				'version'     => UFAQSW_VERSION,
			),
			'data' => array(),
		);

		foreach ( $types as $type ) {
			if ( isset( $this->exporters[ $type ] ) ) {
				$out['data'][ $type ] = $this->exporters[ $type ]->export();
			}
		}

		return $out;
	}

	/**
	 * Export all data as a JSON string
	 *
	 * @param array $types Array of selected types to export.
	 * @return string
	 */
	public function exportToJson( array $types ): string {
		return wp_json_encode( $this->export( $types ), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE );
	}

	/**
	 * Get all available exporter keys for UI rendering
	 */
	public function getAvailableExportTypes(): array {
		return array_keys( $this->exporters );
	}
}
