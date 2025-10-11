<?php
/**
 * Importer Interface for Ultimate FAQ Solution plugin.
 *
 * @package Mahedi\UltimateFaqSolution\ExportImport\Contracts
 */

namespace Mahedi\UltimateFaqSolution\ExportImport\Contracts;

interface ImporterInterface {

	/**
	 * Get the type identifier for this importer.
	 *
	 * @return string
	 */
	public function getType(): string;

	/**
	 * Import data for this type.
	 *
	 * @param array $data The data to import.
	 * @return array optionally return results / mapping of old->new ids
	 */
	public function import( array $data ): array;
}
