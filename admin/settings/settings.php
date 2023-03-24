<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/*
* @package - Settings by Braintum
* @Author - Mahedi Hasan
* @Description - Responsible for handling all global resoures for RS FAQ
* @Since 1.0
*/
class UFAQSW_global_settings
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
		add_action( 'admin_menu', array(&$this, 'show_settings_page_callback_func') );
		add_action( 'admin_init', array(&$this, 'register_plugin_settings') );
	}
	
	public function show_settings_page_callback_func(){
		
		add_submenu_page(
			'edit.php?post_type=ufaqsw',
			'Settings & Help - Ultimate FAQs',
			'Settings & Help',
			'manage_options',
			'ufaqsw-settings',
			array(&$this, 'settings_page_callback_func' )
		);
		
		
	}
	
	public function register_plugin_settings(){
		register_setting( 'ufaqsw-plugin-settings-group', 'ufaqsw_enable_woocommerce' );
		register_setting( 'ufaqsw-plugin-settings-group', 'ufaqsw_enable_search' );
		register_setting( 'ufaqsw-plugin-settings-group', 'ufaqsw_live_search_text' );
		register_setting( 'ufaqsw-plugin-settings-group', 'ufaqsw_live_search_loading_text' );
		register_setting( 'ufaqsw-plugin-settings-group', 'ufaqsw_search_result_not_found' );
		register_setting( 'ufaqsw-plugin-settings-group', 'ufaqsw_setting_custom_style' );

		register_setting( 'ufaqsw-plugin-settings-group', 'ufaqsw_enable_global_faq' );
		register_setting( 'ufaqsw-plugin-settings-group', 'ufaqsw_global_faq_label' );
		register_setting( 'ufaqsw-plugin-settings-group', 'ufaqsw_global_faq' );
	}
	
	public function settings_page_callback_func(){
		if(file_exists(UFAQSW__PLUGIN_DIR.'admin/settings/ui.php')){
			include_once UFAQSW__PLUGIN_DIR.'admin/settings/ui.php';
		}
	}
}

function ufaqsw_global_settings(){
	return UFAQSW_global_settings::get_instance();
}
ufaqsw_global_settings();