<?php

namespace Mahedi\UltimateFaqSolution;

class Template {
	
	/**
	 * Locate template.
	 *
	 * Locate the called template.
	 * Search Order:
	 * 1. /themes/theme/ultimate-faq-solution/templates/$template_name
	 * 2. /plugins/ultimate-faq-solution/templates/$template_name.
	 *
	 * @since 2.0.0
	 *
	 * @param   string  $template_name          Template to load.
	 * @param   string  $string $template_path  Path to templates.
	 * @param   string  $default_path           Default path to template files.
	 * @return  string                          Path to the template file.
	 */
	public static function locate( $template_name, $template_path = '', $default_path = '' ) {

		// Set variable to search in the templates folder of theme.
		if ( ! $template_path ) :
			$template_path = 'ultimate-faq-solution/templates/';
		endif;

		// Set default plugin templates path.
		if ( ! $default_path ) :
			$default_path = UFAQSW__PLUGIN_DIR . 'templates/'; // Path to the template folder
		endif;

		// Search template file in theme folder.
		$template = locate_template( array(
			$template_path . $template_name . '/template.php'
		) );

		// Get plugins template file.
		if ( ! $template ) :
			$template = $default_path . $template_name . '/template.php';
		endif;

		return apply_filters( 'ufaqsw_locate_template', $template, $template_name, $template_path, $default_path );
	}
}
