<?php
/**
 * ExporterInterface contract for Ultimate FAQ Solution plugin.
 *
 * @package Mahedi\UltimateFaqSolution\ExportImport\Contracts
 */

namespace Mahedi\UltimateFaqSolution\ExportImport\Contracts;

interface ExporterInterface {

	/**
	 * Gets the type of the exporter.
	 *
	 * @return string
	 */
	public function getType(): string;

	/**
	 * Exports data for this type as an array structure.
	 *
	 * @return array<string, mixed>
	 */
	public function export(): array;
}
