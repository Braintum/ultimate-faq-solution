<?php

if( !class_exists('BT_ufaqsw_rating') ){
	
	class BT_ufaqsw_rating{
		
		public $plugin_name = "ultimate-faq-solution"; //Without spaces
		public $plugin_full_name = "Ultimate FAQ Solution";
		public $logo_url;
		
		public $plugin_rating_url = "https://wordpress.org/support/plugin/ultimate-faq-solution/reviews/#new-post";
		
		public function __construct(){
			$this->logo_url = UFAQSW__ASSETS_URL . "/logo.png";
		}
		
		function run(){
			add_action('admin_init', array($this, 'ufaqsw_admin_notice_rating'));
		}
		
		/**
		 *	Check and Dismiss review message.
		 *
		 */
		private function review_dismissal() {

			
		
			//delete_site_option( 'wp_analytify_review_dismiss' );
			if ( ! is_admin() ||
				! isset( $_GET['_wpnonce'] ) ||
				! wp_verify_nonce( sanitize_key( wp_unslash( $_GET['_wpnonce'] ) ), 'ufaqsw-'.$this->plugin_name.'-rating-nonce' ) ||
				! isset( $_GET['ufaqsw_'.$this->plugin_name.'_rating_dismiss'] ) ) {

				return;
			}

			
			update_option( 'ufaqsw_'.$this->plugin_name.'_rating_dismiss', 'yes' );
			
		}
		
		/**
		 * Set time to current so review notice will popup after X days
		 */
		function review_prending() {

			// delete_site_option( 'wp_analytify_review_dismiss' );
			if ( ! is_admin() ||
				! isset( $_GET['_wpnonce'] ) ||
				! wp_verify_nonce( sanitize_key( wp_unslash( $_GET['_wpnonce'] ) ), 'ufaqsw-'.$this->plugin_name.'-rating-nonce' ) ||
				! isset( $_GET['ufaqsw_'.$this->plugin_name.'_rating_later'] ) ) {

				return;
			}

			// Reset Time to current time.
			update_option( 'ufaqsw_'.$this->plugin_name.'_rating_active_time', time() );

		}
		
		public function ufaqsw_admin_notice_rating(){
			
			$this->review_dismissal();
			$this->review_prending();
			
			$activation_time 	= get_option( 'ufaqsw_'.$this->plugin_name.'_rating_active_time' );
			$review_dismissal	= get_option( 'ufaqsw_'.$this->plugin_name.'_rating_dismiss' );
			//echo $review_dismissal;exit;
			if ( 'yes' == $review_dismissal ) {
				return;
			}

			if ( ! $activation_time ) {

				$activation_time = time();
				add_option( 'ufaqsw_'.$this->plugin_name.'_rating_active_time', $activation_time );
			}
			
			// 604800 = 7 Days in seconds.
			if ( time() - $activation_time > 604800 ) {
				add_action( 'admin_enqueue_scripts', array($this, 'ufaqsw_load_rating_style') );
				add_action( 'admin_notices' , array( $this, 'ufaqsw_rating_notice_message' ) );
			}
		}
		
		public function ufaqsw_load_rating_style(){
			wp_enqueue_style( 'ufaqsw_rating_stylesheet', plugin_dir_url(__FILE__)."css/style.css");
		}
		
		public function ufaqsw_rating_notice_message(){
			
			/*if ( ! is_admin() ||
				! current_user_can( 'manage_options' ) ) {
				return;
			}*/
			
			$scheme      = (parse_url( $_SERVER['REQUEST_URI'], PHP_URL_QUERY )) ? '&' : '?';
			
			$url         = $_SERVER['REQUEST_URI'] . $scheme . 'ufaqsw_'.$this->plugin_name.'_rating_dismiss=yes';
			
			$dismiss_url = wp_nonce_url( $url, 'ufaqsw-'.$this->plugin_name.'-rating-nonce' );

			$_later_link = $_SERVER['REQUEST_URI'] . $scheme . 'ufaqsw_'.$this->plugin_name.'_rating_later=yes';
			
			$later_url   = wp_nonce_url( $_later_link, 'ufaqsw-'.$this->plugin_name.'-rating-nonce' );
			
		?>
			<div class="ufaqsw-review-notice">
				<div class="ufaqsw-review-thumbnail">
					<img src="<?php echo esc_url($this->logo_url); ?>" alt="">
				</div>
				
				<div class="ufaqsw-review-text">
				
					<h3><?php _e( 'Leave A Review for Ultimate FAQ Solution?', 'ufaqsw' ) ?></h3>
					
					<p><?php _e( 'We hope you\'ve enjoyed using <b>Ultimate FAQ Solution</b>! Would you consider leaving us a review on WordPress.org?', 'ufaqsw' ) ?></p>
					
					<ul class="ufaqsw-review-ul">
					
						<li><a href="<?php echo esc_url($this->plugin_rating_url); ?>" target="_blank"><span class="dashicons dashicons-star-filled"></span><?php _e( 'Leave A Review', 'ufaqsw' ) ?></a></li>
						 <li><a href="<?php echo esc_url($dismiss_url) ?>"><span class="dashicons dashicons-yes"></span><?php _e( 'I\'ve already left a review', 'ufaqsw' ) ?></a></li>
						 <li><a href="<?php echo esc_url($later_url) ?>"><span class="dashicons dashicons-calendar"></span><?php _e( 'Maybe Later', 'ufaqsw' ) ?></a></li>
						 <li><a href="<?php echo esc_url($dismiss_url) ?>"><span class="dashicons dashicons-no"></span><?php _e( 'Never show this again', 'ufaqsw' ) ?></a></li>
			 
					</ul>
				</div>
			</div>
		<?php
		}
		
	}
}
$ufaqsw_rating = new BT_ufaqsw_rating();

$ufaqsw_rating->plugin_name = 'ultimate-faq-solution';

$ufaqsw_rating->run();
