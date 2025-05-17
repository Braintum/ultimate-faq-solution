<?php
/**
 * Template for displaying a single FAQ preview.
 *
 * @package Ultimate_FAQ_Solution
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
get_header();
if ( ! isset( $_GET['ufaqsw-preview'] ) || empty( $_GET['ufaqsw-preview'] ) ) { //phpcs:ignore
	wp_safe_redirect( home_url() );
	exit;
}
$faq_id = intval( $_GET['ufaqsw-preview'] ); //phpcs:ignore
if ( ! get_post( $faq_id ) ) {
	wp_safe_redirect( home_url() );
	exit;
}
if ( ! is_user_logged_in() ) {
	wp_safe_redirect( home_url() );
	exit;
}

the_post();

echo '<div class="container faq_preview_container">'; //phpcs:ignore
echo do_shortcode( '[ufaqsw id=' . $faq_id . ']' ); //phpcs:ignore
echo '</div>'; //phpcs:ignore
get_footer();
