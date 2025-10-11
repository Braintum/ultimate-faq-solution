<?php
/**
 * Appearance Exporter for Ultimate FAQ Solution plugin.
 *
 * @package Mahedi\UltimateFaqSolution\ExportImport\Exporters
 */

namespace Mahedi\UltimateFaqSolution\ExportImport\Exporters;

use Mahedi\UltimateFaqSolution\ExportImport\Base\BaseExporter;

/**
 * Class to handle exporting of FAQ appearances.
 */
class AppearanceExporter extends BaseExporter {

	/**
	 * The type of the exporter.
	 *
	 * @var string
	 */
	protected string $type = 'appearances';

	/**
	 * Export appearance data.
	 *
	 * @return array<string, mixed>
	 */
	public function export(): array {
		$posts = get_posts(
			array(
				'post_type'   => 'ufaqsw_appearance',
				'numberposts' => -1,
				'post_status' => 'any',
				'orderby'     => 'date',
				'order'       => 'ASC',
			)
		);

		$out = array();
		foreach ( $posts as $p ) {
			$out[] = array(
				'id'      => $p->ID,
				'name'    => $p->post_title,
				'content' => $p->post_content,
				'meta'    => get_post_meta( $p->ID ),
				'status'  => $p->post_status,
			);
		}
		return $out;
	}
}
