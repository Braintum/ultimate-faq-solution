<?php
/**
 * BaseExporter.php
 *
 * This file contains the abstract BaseExporter class for the Ultimate FAQ Solution plugin.
 *
 * @package UltimateFaqSolution
 */

namespace Mahedi\UltimateFaqSolution\ExportImport\Base;

use Mahedi\UltimateFaqSolution\ExportImport\Contracts\ExporterInterface;

/**
 * Base class for all exporter implementations in the Ultimate FAQ Solution plugin.
 *
 * Provides common functionality for exporting data, such as normalizing WP_Post objects.
 */
abstract class BaseExporter implements ExporterInterface {

	/**
	 * The type of the exporter.
	 *
	 * @var string
	 */
	protected string $type = '';

	/**
	 * Get the type of the exporter.
	 *
	 * @return string
	 */
	public function getType(): string {
		return $this->type;
	}

	/**
	 * Normalize a WP_Post object into an array.
	 *
	 * @param \WP_Post $post The post object to normalize.
	 * @return array The normalized post data.
	 */
	protected function normalizePost( \WP_Post $post ): array {
		return array(
			'id'      => $post->ID,
			'title'   => $post->post_title,
			'content' => $post->post_content,
			'status'  => $post->post_status,
			'meta'    => get_post_meta( $post->ID ),
		);
	}
}
