<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/*
* @package - Global Resource Handler by Braintum
* @Author - Mahedi Hasan
* @Description - Responsible for handling all global resoures for RS FAQ
* @Since 1.0
*/
class UFAQSW_global_resources
{
	// class instance
	static $instance;

	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array($this, 'render_resources_frontend')); 
		add_action( 'admin_enqueue_scripts', array($this, 'render_resources_backend')); 
	}
	public function render_resources_frontend(){
		wp_register_style( 'ufaqsw_fa_css', UFAQSW__PLUGIN_URL . 'assets/css/font-awesome.min.css' );
		wp_enqueue_style( 'ufaqsw_fa_css' );
		wp_register_style( 'ufaqsw_fa_default_all_css', UFAQSW__PLUGIN_URL . 'assets/css/default-all.css' );
		
		//jQuery for fronend
		wp_enqueue_script( 'jquery', 'jquery');
		wp_register_script( 'ufaqsw-grid-js', UFAQSW__PLUGIN_URL . 'assets/js/packery.min.js', array('jquery'), UFAQSW_VERSION, true );
		wp_register_script( 'ufaqsw-default-all-js', UFAQSW__PLUGIN_URL . 'assets/js/default_all.js', array('jquery', 'ufaqsw-grid-js'), UFAQSW_VERSION, true );
		wp_register_script( 'ufaqsw-quicksearch-front-js', UFAQSW__PLUGIN_URL . 'assets/js/jquery.quicksearch.js', array( 'jquery' ) );
		
		
	}
	public function render_resources_backend(){
		//jQuery for backend
		wp_enqueue_media();
		wp_enqueue_script( 'jquery', 'jquery');
		wp_register_style( 'ufaqsw_backend_fa_css', UFAQSW__PLUGIN_URL . 'assets/css/font-awesome.min.css' );
		wp_enqueue_style( 'ufaqsw_backend_fa_css' );
		wp_register_style( 'ufaqsw_backend_admin_style', UFAQSW__PLUGIN_URL . 'assets/css/admin-style.css' );
		wp_enqueue_style( 'ufaqsw_backend_admin_style' );
		wp_register_script( 'ufaqsw-admin-js', UFAQSW__PLUGIN_URL . 'assets/js/admin.js', array('jquery'), UFAQSW_VERSION, true ); 
		wp_enqueue_script( 'ufaqsw-admin-js');
		wp_enqueue_script( 'ufaqsw-quicksearch-js', UFAQSW__PLUGIN_URL . 'assets/js/jquery.quicksearch.js', array( 'jquery' ) );
		

	}
}

function ufaqsw_global_resources(){
	return UFAQSW_global_resources::get_instance();
}
ufaqsw_global_resources();