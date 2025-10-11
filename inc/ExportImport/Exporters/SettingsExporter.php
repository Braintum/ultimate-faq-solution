<?php
/**
 * Settings Exporter for Ultimate FAQ Solution plugin.
 *
 * @package Mahedi\UltimateFaqSolution\ExportImport\Exporters
 */

namespace Mahedi\UltimateFaqSolution\ExportImport\Exporters;

use Mahedi\UltimateFaqSolution\ExportImport\Base\BaseExporter;

/**
 * Class to handle exporting of FAQ settings.
 */
class SettingsExporter extends BaseExporter {

	/**
	 * The type of the exporter.
	 *
	 * @var string
	 */
	protected string $type = 'settings';

	/**
	 * Export settings data.
	 *
	 * @return array<string, mixed>
	 */
	public function export(): array {

		$settings = array();

		$settings_field = array(
			'ufaqsw_enable_woocommerce',
			'ufaqsw_enable_search',
			'ufaqsw_enable_filter',
			'ufaqsw_live_search_text',
			'ufaqsw_live_search_loading_text',
			'ufaqsw_search_result_not_found',
			'ufaqsw_setting_custom_style',
			'ufaqsw_enable_global_faq',
			'ufaqsw_global_faq_label',
			'ufaqsw_product_hide_group_title',
			'ufaqsw_global_faq',
			'ufaqsw_detail_page_slug',
			'ufaqsw_enable_group_detail_page',
		);

		foreach ( $settings_field as $field ) {
			$settings[ $field ] = get_option( $field );
		}

		return $settings;
	}
}
