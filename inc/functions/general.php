<?php
/**
 * General functions for Ultimate FAQ Solution plugin.
 *
 * This file contains helper functions for managing FAQ appearance and related options.
 *
 * @package UltimateFAQSolution
 */

/**
 * Create the default appearance post for Ultimate FAQ Solution.
 *
 * Checks if a default appearance post exists. If not, creates one,
 * sets default meta values, and stores its ID in the options table.
 *
 * @return void
 */
function ufaqsw_create_default_appearance() {
	$existing = get_option( 'faq_default_appearance_id' );

	// Only create if not already exists.
	if ( ! empty( $existing ) && get_post( $existing ) ) {
		return;
	}

	// Create new post.
	$appearance_id = wp_insert_post(
		array(
			'post_type'   => 'ufaqsw_appearance',
			'post_title'  => 'Default Appearance',
			'post_status' => 'publish',
		)
	);

	if ( is_wp_error( $appearance_id ) ) {
		return;
	}

	// Add default meta values.
	update_post_meta( $appearance_id, 'ufaqsw_template', 'default' );
	update_post_meta( $appearance_id, 'ufaqsw_faq_behaviour', 'toggle' );

	// Save the ID in options so it can be referenced later.
	update_option( 'faq_default_appearance_id', $appearance_id );
}

/**
 * Retrieves the appearance ID associated with a given FAQ group.
 *
 * This function first attempts to fetch a linked appearance ID from the group's post meta.
 * If a valid appearance ID is found and the corresponding post exists, it returns that ID.
 * Otherwise, it falls back to returning the default appearance ID set in the options.
 *
 * @param int $group_id The ID of the FAQ group.
 * @return int The appearance ID linked to the group, or the default appearance ID if none is linked.
 */
function ufaqsw_get_appearance_id( $group_id ) {
	// Get the linked appearance ID for the FAQ group.
	$appearance_id = get_post_meta( $group_id, 'linked_faq_appearance_id', true );

	if ( ! empty( $appearance_id ) && get_post( $appearance_id ) ) {
		return $appearance_id;
	}

	// If no linked appearance, return the default appearance ID.
	return get_option( 'faq_default_appearance_id', 0 );
}
