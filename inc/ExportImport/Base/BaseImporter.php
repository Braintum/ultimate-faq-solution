<?php
/**
 * Base Importer class for Ultimate FAQ Solution plugin.
 *
 * @package Mahedi\UltimateFaqSolution\ExportImport\Base
 */

namespace Mahedi\UltimateFaqSolution\ExportImport\Base;

use Mahedi\UltimateFaqSolution\ExportImport\Contracts\ImporterInterface;

/**
 * Base class for importers providing common functionality.
 */
abstract class BaseImporter implements ImporterInterface {
	/**
	 * The type identifier for this importer.
	 *
	 * @var string
	 */
	protected string $type = '';

	/**
	 * Get the type identifier for this importer.
	 *
	 * @return string
	 */
	public function getType(): string {
		return $this->type;
	}

	/**
	 * Insert/Update post
	 *
	 * @param array $args Post data.
	 * @return int new post ID
	 */
	protected function insertPost( array $args ): int {
		$default = array(
			'post_type' => $args['post_type'] ?? 'post',
			'post_title' => $args['title'] ?? '',
			'post_content' => $args['content'] ?? '',
			'post_status' => $args['status'] ?? 'publish',
		);
		$postarr = array(
			'post_type' => $default['post_type'],
			'post_title' => $default['post_title'],
			'post_content' => $default['post_content'],
			'post_status' => $default['post_status'],
		);
		return (int) wp_insert_post( $postarr, true );
	}
}
