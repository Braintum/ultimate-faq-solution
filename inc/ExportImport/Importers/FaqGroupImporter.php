<?php
/**
 * FAQ Group Importer for Ultimate FAQ Solution plugin.
 *
 * @package Mahedi\UltimateFaqSolution\ExportImport\Importers
 */

namespace Mahedi\UltimateFaqSolution\ExportImport\Importers;

use Mahedi\UltimateFaqSolution\ExportImport\Base\BaseImporter;

/**
 * Class to handle importing of FAQ groups along with their FAQ items.
 */
class FaqGroupImporter extends BaseImporter {

	/**
	 * The type of the importer.
	 *
	 * @var string
	 */
	protected string $type = 'faq_groups';

	/**
	 * Import FAQ group data along with embedded FAQ items.
	 *
	 * @param array $data The FAQ group data to import.
	 * @return array Mapping of old IDs to new post IDs.
	 */
	public function import( array $data ): array {
		$mapping = array(); // old_id => new_id.
		foreach ( $data as $group ) {
			// Create group.
			$post_id = $this->insertPost(
				array(
					'post_type' => 'ufaqsw',
					'title'     => $group['title'] ?? '',
					'content'   => $group['content'] ?? '',
					'status'    => $group['status'] ?? 'publish',
				)
			);

			if ( is_wp_error( $post_id ) ) {
				continue;
			}

			// Restore meta.
			if ( ! empty( $group['meta'] ) && is_array( $group['meta'] ) ) {
				foreach ( $group['meta'] as $k => $v ) {
					// Meta values may be arrays with numeric keys; use update_post_meta.
					update_post_meta( $post_id, $k, maybe_unserialize( $v[0] ?? $v ) );
				}
			}

			// Appearance relationship (store old appearance id for later mapping).
			if ( isset( $group['appearance_id'] ) ) {
				update_post_meta( $post_id, '_old_appearance_id', $group['appearance_id'] );
			}

			$mapping[ intval( $group['id'] ?? 0 ) ] = $post_id;
		}

		return $mapping;
	}
}
