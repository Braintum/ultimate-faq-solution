<?php
/**
 * Appearance Importer for Ultimate FAQ Solution plugin.
 *
 * @package Mahedi\UltimateFaqSolution\ExportImport\Importers
 */

namespace Mahedi\UltimateFaqSolution\ExportImport\Importers;

use Mahedi\UltimateFaqSolution\ExportImport\Base\BaseImporter;

/**
 * Class to handle importing of FAQ appearances.
 */
class AppearanceImporter extends BaseImporter {

	/**
	 * The type of the importer.
	 *
	 * @var string
	 */
	protected string $type = 'appearances';

	/**
	 * Import appearance data.
	 *
	 * @param array $data The appearance data to import.
	 * @return array Mapping of old IDs to new post IDs.
	 */
	public function import( array $data ): array {
		$mapping = array();
		foreach ( $data as $ap ) {
			$post_id = $this->insertPost(
				array(
					'post_type' => 'faq_appearance',
					'title'     => $ap['name'] ?? '',
					'content'   => $ap['content'] ?? '',
					'status'    => $ap['status'] ?? 'publish',
				)
			);
			if ( is_wp_error( $post_id ) ) {
				continue;
			}
			if ( ! empty( $ap['meta'] ) && is_array( $ap['meta'] ) ) {
				foreach ( $ap['meta'] as $k => $v ) {
					update_post_meta( $post_id, $k, maybe_unserialize( $v[0] ?? $v ) );
				}
			}
			$mapping[ intval( $ap['id'] ?? 0 ) ] = $post_id;
		}
		return $mapping;
	}
}
