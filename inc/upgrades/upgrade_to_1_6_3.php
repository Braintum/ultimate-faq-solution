<?php
/**
 * Upgrade script for Ultimate FAQ Solution plugin to version 1.6.3.
 *
 * This script migrates appearance data from FAQ Groups to new FAQ Appearance posts.
 * It creates new 'ufaqsw_appearance' posts and transfers existing appearance-related
 * meta fields from 'ufaqsw' posts.
 *
 * @package UltimateFaqSolution
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Creates the default appearance post for Ultimate FAQ Solution.
 *
 * Checks if a default appearance post exists. If not, creates one,
 * sets default meta values, and stores its ID in the options table.
 *
 * @return void
 */
ufaqsw_create_default_appearance();

/**
 * Migrates appearance data from FAQ Groups to new FAQ Appearance posts.
 *
 * This function retrieves all FAQ Groups, checks for existing appearance-related
 * meta fields ('header_bg_color', 'header_text_color', 'button_icon'), and if present,
 * creates a new 'faq_appearance' post for each group. The appearance meta is transferred
 * to the new post, and the FAQ Group is linked to the new Appearance post via
 * the 'linked_faq_appearance_id' meta key.
 *
 * @global WP_Post $post WordPress post object.
 *
 * @return void
 */
function faq_plugin_migrate_appearance_data() {
	$faq_groups = get_posts(
		array(
			'post_type'   => 'ufaqsw',
			'post_status' => 'any',
			'numberposts' => -1,
			'fields'      => 'ids',
		)
	);

	foreach ( $faq_groups as $group_id ) {
		// Get existing appearance settings.
		// Get all appearance-related meta.
		$appearance_data = array(
			'ufaqsw_title_color'               => get_post_meta( $group_id, 'ufaqsw_title_color', true ),
			'ufaqsw_title_font_size'           => get_post_meta( $group_id, 'ufaqsw_title_font_size', true ),
			'ufaqsw_question_color'            => get_post_meta( $group_id, 'ufaqsw_question_color', true ),
			'ufaqsw_answer_color'              => get_post_meta( $group_id, 'ufaqsw_answer_color', true ),
			'ufaqsw_question_background_color' => get_post_meta( $group_id, 'ufaqsw_question_background_color', true ),
			'ufaqsw_answer_background_color'   => get_post_meta( $group_id, 'ufaqsw_answer_background_color', true ),
			'ufaqsw_question_font_size'        => get_post_meta( $group_id, 'ufaqsw_question_font_size', true ),
			'ufaqsw_answer_font_size'          => get_post_meta( $group_id, 'ufaqsw_answer_font_size', true ),
			'ufaqsw_template'                  => get_post_meta( $group_id, 'ufaqsw_template', true ),
			'ufaqsw_answer_showall'            => get_post_meta( $group_id, 'ufaqsw_answer_showall', true ),
			'ufaqsw_hide_title'                => get_post_meta( $group_id, 'ufaqsw_hide_title', true ),
			'ufaqsw_normal_icon'               => get_post_meta( $group_id, 'ufaqsw_normal_icon', true ),
			'ufaqsw_active_icon'               => get_post_meta( $group_id, 'ufaqsw_active_icon', true ),
			'ufaqsw_faq_behaviour'             => get_post_meta( $group_id, 'ufaqsw_faq_behaviour', true ),
			'ufaqsw_question_bold'             => get_post_meta( $group_id, 'ufaqsw_question_bold', true ),
		);

		// Skip if there's no appearance data to migrate.
		if ( empty( array_filter( $appearance_data ) ) ) {
			continue;
		}

		// Create new Appearance post.
		$appearance_post_id = wp_insert_post(
			array(
				'post_type'   => 'ufaqsw_appearance',
				'post_status' => 'publish',
				'post_title'  => 'Migrated Appearance #' . $group_id,
			)
		);

		if ( is_wp_error( $appearance_post_id ) ) {
			continue;
		}

		// Transfer appearance meta.
		foreach ( $appearance_data as $key => $value ) {
			if ( ! empty( $value ) ) {
				update_post_meta( $appearance_post_id, $key, $value );
			}
		}

		// Link Appearance to FAQ Group.
		update_post_meta( $group_id, 'linked_faq_appearance_id', $appearance_post_id );
	}
}

if ( ! get_option( 'faq_upgrade_to_1_6_3', false ) ) {
	faq_plugin_migrate_appearance_data();
	update_option( 'faq_upgrade_to_1_6_3', true );
}

