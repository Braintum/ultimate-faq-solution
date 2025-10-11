<?php
/**
 * Settings Importer for Ultimate FAQ Solution plugin.
 *
 * @package Mahedi\UltimateFaqSolution\ExportImport\Importers
 */

namespace Mahedi\UltimateFaqSolution\ExportImport\Importers;

use Mahedi\UltimateFaqSolution\ExportImport\Base\BaseImporter;

/**
 * Class to handle importing of FAQ settings.
 */
class FaqAssistantImporter extends BaseImporter {

	/**
	 * The type of the importer.
	 *
	 * @var string
	 */
	protected string $type = 'faq_assistant';

	/**
	 * Import settings data.
	 *
	 * @param array $data The settings data to import.
	 * @return array Import report.
	 */
	public function import( array $data ): array {

		foreach ( $data as $key => $value ) {

			if ( isset( $value['faq_groups'] ) && is_array( $value['faq_groups'] ) ) {
				$updated_groups = array();
				foreach ( $value['faq_groups'] as $old_id ) {
					$new_id = $this->getMappedId( intval( $old_id ) );
					if ( $new_id ) {
						$updated_groups[] = $new_id;
					} else {
						$updated_groups[] = $old_id; // Fallback to old ID if no mapping found.
					}
				}
				$value['faq_groups'] = $updated_groups;

			}

			update_option( $key, maybe_unserialize( $value ) );
		}
		return $data['ufaqsw_chatbot_settings'];
	}

	/**
	 * Get the new ID mapped from the old ID for FAQ groups.
	 *
	 * @param int $old_id The old FAQ group ID.
	 * @return int|null The new FAQ group ID or null if not found.
	 */
	private function getMappedId( int $old_id ): ?int {
		global $wpdb;
		$meta_key = '_old_group_id';
		$new_id   = $wpdb->get_var( // phpcs:ignore
			$wpdb->prepare(
				"SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = %s AND meta_value = %d LIMIT 1",
				$meta_key,
				$old_id
			)
		);
		return $new_id ? intval( $new_id ) : null;
	}
}
