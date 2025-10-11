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
				'post_type'   => 'ufaqsw',
				'numberposts' => -1,
				'post_status' => 'any',
			)
		);

		$out = array();
		foreach ( $posts as $p ) {
			$item = $this->normalizePost( $p );

			// Add appearance relation if exists.
			$appearance            = get_post_meta( $p->ID, 'linked_faq_appearance_id', true );
			$item['appearance_id'] = $appearance ? intval( $appearance ) : null;

			$out[] = $item;
		}
		return $out;
	}
}
