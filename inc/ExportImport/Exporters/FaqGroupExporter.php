<?php
/**
 * FAQ Group Exporter class file.
 *
 * @package UltimateFaqSolution
 */

namespace Mahedi\UltimateFaqSolution\ExportImport\Exporters;

use Mahedi\UltimateFaqSolution\ExportImport\Base\BaseExporter;

/**
 * Exports FAQ groups along with their related appearance and FAQ items.
 */
class FaqGroupExporter extends BaseExporter {

	/**
	 * The type of exporter.
	 *
	 * @var string
	 */
	protected string $type = 'faq_groups';

	/**
	 * Export FAQ groups with their related appearance and FAQ items.
	 *
	 * @return array The exported FAQ groups data.
	 */
	public function export(): array {
		$posts = get_posts(
			array(
				'post_type'   => 'faq_group',
				'numberposts' => -1,
				'post_status' => 'any',
			)
		);

		$out = array();
		foreach ( $posts as $p ) {
			$item = $this->normalizePost( $p );
			// Add appearance relation if exists.
			$appearance            = get_post_meta( $p->ID, '_appearance_id', true );
			$item['appearance_id'] = $appearance ? intval( $appearance ) : null;

			// Gather child FAQ items.
			$faq_items = get_posts(
				array(
					'post_type'   => 'faq_item',
					'numberposts' => -1,
					'post_status' => 'any',
					'meta_query'  => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
						array(
							'key'     => '_faq_group_id',
							'value'   => $p->ID,
							'compare' => '=',
						),
					),
				)
			);

			$item['faq_items'] = array();
			foreach ( $faq_items as $fi ) {
				$item['faq_items'][] = array(
					'id'      => $fi->ID,
					'title'   => $fi->post_title,
					'content' => $fi->post_content,
					'meta'    => get_post_meta( $fi->ID ),
					'status'  => $fi->post_status,
				);
			}

			$out[] = $item;
		}
		return $out;
	}
}
