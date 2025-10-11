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
					'post_type' => 'ufaqsw_appearance',
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

		if ( ! empty( $mapping ) ) {
			$this->updateMapping( $mapping );
		}

		return $mapping;
	}

	/**
	 * Update FAQ groups to point to new appearance IDs based on the provided mapping.
	 *
	 * @param array $mapping Mapping of old appearance IDs to new post IDs.
	 * @return void
	 */
	private function updateMapping( array $mapping ): void {
		// Update FAQ groups to point to new appearance IDs.
		$posts = get_posts(
			array(
				'post_type'   => 'ufaqsw',
				'numberposts' => -1,
				'post_status' => 'any',
				'meta_query'  => array(
					array(
						'key'     => '_old_appearance_id',
						'compare' => 'EXISTS',
					),
				),
			)
		);

		foreach ( $posts as $p ) {
			$old_id = get_post_meta( $p->ID, '_old_appearance_id', true );
			if ( $old_id && isset( $mapping[ intval( $old_id ) ] ) ) {
				update_post_meta( $p->ID, 'linked_faq_appearance_id', $mapping[ intval( $old_id ) ] );
				delete_post_meta( $p->ID, '_old_appearance_id' );
			}
		}
	}
}
