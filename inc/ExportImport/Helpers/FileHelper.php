<?php
/**
 * File Helper class for handling file operations.
 *
 * @package UltimateFaqSolution
 */

namespace Mahedi\UltimateFaqSolution\ExportImport\Helpers;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Helper class for file operations.
 */
class FileHelper {
	/**
	 * Download JSON response as a file.
	 *
	 * @param string $json     The JSON content to download.
	 * @param string $filename The filename for the download.
	 * @return void
	 */
	public static function downloadJsonResponse( string $json, string $filename = 'ufs-export.json' ): void {
		if ( headers_sent() ) {
			wp_die( esc_html__( 'Headers already sent, cannot download file.', 'ultimate-faq-solution' ) );
		}
		header( 'Content-Type: application/json; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename="' . sanitize_file_name( basename( $filename ) ) . '"' );
		echo wp_json_encode( json_decode( $json, true ), JSON_PRETTY_PRINT );
		exit;
	}

	/**
	 * Read and decode uploaded JSON file.
	 *
	 * @param array $file The uploaded file array.
	 * @return array|null Decoded JSON array or null on failure.
	 */
	public static function readUploadedJson( array $file ): ?array {
		if ( empty( $file['tmp_name'] ) ) {
			return null;
		}

		$content = file_get_contents( $file['tmp_name'] );  // phpcs:ignore
		if ( false === $content ) {
			return null;
		}

		$decoded = json_decode( $content, true );
		return is_array( $decoded ) ? $decoded : null;
	}
}
