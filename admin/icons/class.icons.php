<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/*
* @package - Icons by Braintum
* @Author - Mahedi Hasan
* @Description - Responsible for icon Choosing modal for RS FAQ
* @Since 1.0
*/
class UFAQSW_icon_settings
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
		add_action( 'admin_footer', array(&$this, 'ufaqsw_modal_fa'));
	}
	
	public function ufaqsw_modal_fa() {
	
		global $post, $typenow, $current_screen;

		if( $typenow && 'ufaqsw' == $typenow){
			$data = file(UFAQSW__PLUGIN_DIR.'assets/data/fa-data.txt');//file in to an array
			$icons = array();
			foreach($data as $key=>$val){
				$val = explode('=>',$val);
				$title = $val[0];
				$class = explode(',',$val[1]);
				foreach($class as $v=>$k){
					if(strlen($k)>2){
						$icons[$title][] = trim($k);
					}
				}
			}
			include_once UFAQSW__PLUGIN_DIR.'admin/icons/ui.php';
		}

	}
	
}

function ufaqsw_icons_setting(){
	return UFAQSW_icon_settings::get_instance();
}
ufaqsw_icons_setting();