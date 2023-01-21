<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/*
* @Author - Mahedi Hasan
* @Description - Necessary setup during installation
* @Since 1.0
*/
class UFAQSW_installation
{
	
	public function __construct(){
		add_action( 'activated_plugin', array(&$this, 'activation_redirect') );
	}
	
	public function activation_redirect( $plugin ) {
		if( $plugin == UFAQSW_BASE ) {
			if( 'cli' !== php_sapi_name() ){
				exit( wp_redirect( admin_url( 'edit.php?post_type=ufaqsw&page=ufaqsw-settings#getting_started') ) );
			}
		}
	}

	/* This Function will run during plugin Activation */
	public static function plugin_activation() {
		if(!get_option('ufaqsw_enable_search')){
			update_option('ufaqsw_enable_search','on');
		}
		if(!get_option('ufaqsw_live_search_loading_text')){
			update_option('ufaqsw_live_search_loading_text','Loading...');			
		}
		if(!get_option('ufaqsw_search_result_not_found')){
			update_option('ufaqsw_search_result_not_found','No result Found!');
		}
		if(!get_option('ufaqsw_live_search_text')){
			update_option('ufaqsw_live_search_text','Live Search..');
		}
	}
	
	/* This Function will run during plugin Deactivation */
	public static function plugin_deactivation(){
		
	}
}

new UFAQSW_installation();
