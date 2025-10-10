<?php
namespace Mahedi\UltimateFaqSolution\ExportImport\Contracts;

interface ImporterInterface {

    public function getType(): string;
	
    /**
     * Import data for this type.
     * @param array $data
     * @return array optionally return results / mapping of old->new ids
     */
    public function import( array $data ): array;
}
