<?php
/**
 * Font Awesome Icon Selection Modal
 *
 * This file contains the modal for selecting Font Awesome icons in the admin interface.
 *
 * @package UltimateFAQSolution
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;}
$allowed_html = ufaqsw_wses_allowed_menu_html();
?>

<div class="ufaqsw-fa-field-modal" id="ufaqsw-fa-field-modal">
	<div class="ufaqsw-fa-field-modal-close">&times;</div>
	<h1 class="ufaqsw-fa-field-modal-title"><?php echo esc_html__( 'Select Font Awesome Icon', 'ufaqsw' ); ?></h1>

	<div class="ufaqsw-fa-field-modal-icons">
		<form action="#">
			<fieldset>
				<label><?php echo esc_html( 'Search Icons' ); ?></label>
				<input type="search" name="search" value="" id="ufaqsw_id_search" /> <span class="ufaqsw-loading"><?php echo esc_html__( 'Loading...', 'ufaqsw' ); ?></span>
			</fieldset>
		</form>
		<button class="button button-primary button-large ufaqsw_clear_icon_field"><?php echo esc_html( 'Clear Icon Field & Use Default' ); ?></button>
		<div class="ufaqsw_clear_fix"></div>
	<?php if ( $icons ) : ?>
		<?php foreach ( $icons as $head => $iconlist ) : ?>
		<div class="ufaqsw_fa_section" style="display:block;overflow: hidden;"><h2><?php echo esc_html( $head ); ?></h2>
			<?php foreach ( $iconlist as $key => $cls ) : ?>
		<div class="ufaqsw-fa-field-modal-icon-holder" data-icon="<?php echo esc_attr( $cls ); ?>">
			<div class="ufaqsw-icon">
			<i class="fa <?php echo esc_attr( $cls ); ?>"></i>
			</div>
			<div class="ufaqsw-label">
				<?php echo esc_html( $cls ); ?>
			</div>
		</div>

		<?php endforeach; ?>
		</div>
		<?php endforeach; ?>

	<?php endif; ?>
	</div>
</div>
