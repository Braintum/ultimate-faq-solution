<?php
/**
 * Chatbot class
 *
 * @package UltimateFaqSolution
 * @author  Mahedi
 * @license GPL-2.0-or-later
 * @link    https://example.com
 */

namespace Mahedi\UltimateFaqSolution;

/**
 * Class Chatbot
 *
 * Handles the generation of FAQ schema for Rest purposes.
 */
class Chatbot {

	/**
	 * Constructor to initialize the Rest class.
	 *
	 * Adds the action to generate FAQ schema in the head section.
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_react_chatbot_script' ) );
		add_shortcode( 'ufaqsw_chatbot', array( $this, 'chatbot_shortcode' ) );
	}

	// Enqueue the bundled React chatbot script.
	public function enqueue_react_chatbot_script() {
		wp_enqueue_script(
			'react-chatbot',
			UFAQSW_ASSETS_URL . 'dist/bundle.js',
			array(),
			fileatime( UFAQSW__PLUGIN_DIR . 'assets/dist/bundle.js' ),
			true
		);
	}

	// Shortcode to display the chatbot.
	public function chatbot_shortcode() {
		return '<div id="chatbot-root"></div>';
	}
}


