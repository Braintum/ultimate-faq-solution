<?php
/**
 * FAQ Export/Import Admin Page
 *
 * This file provides the HTML structure for the FAQ export/import functionality in the admin panel.
 *
 * @package UltimateFAQSolution
 */

?>
<div class="wrap">
	<h1><?php echo esc_html__( 'Export/Import FAQ Groups', 'ufaqsw' ); ?></h1>
	<hr>
	<h2><?php echo esc_html__( 'Export', 'ufaqsw' ); ?></h2>
	<p>
		<?php echo esc_html__( 'To export all FAQ groups, click here', 'ufaqsw' ); ?>
		<a class="button button-primary" href="<?php echo esc_attr( admin_url( 'export.php?download=true&cat=0&post_author=0&post_start_date=0&post_end_date=0&post_status=0&page_author=0&page_start_date=0&page_end_date=0&page_status=0&content=' . UFAQSW_PRFX . '&attachment_start_date=0&attachment_end_date=0&submit=Download+Export+File' ) ); ?>"><?php echo esc_html__( 'Export', 'ufaqsw' ); ?></a>
	</p>
	<hr>

	<h2><?php echo esc_html__( 'Import', 'ufaqsw' ); ?></h2>
	<p>
	<?php echo esc_html__( 'To import FAQ groups, go to', 'ufaqsw' ); ?> 
		<a href="<?php echo esc_attr( admin_url( 'import.php' ) ); ?>"><?php echo esc_html__( 'Tools → Import', 'ufaqsw' ); ?></a>, 
		<?php echo esc_html__( 'then choose "WordPress" and upload the exported file.', 'ufaqsw' ); ?>
	</p>

	<p><?php echo esc_html__( 'If the WordPress Importer is not yet installed, it will prompt you to install it. Just click the “Install Now” button, then once installed, click “Run Importer.”', 'ufaqsw' ); ?></p>

	<p>
		<?php echo esc_html__( 'On the importer screen, upload the', 'ufaqsw' ); ?> <code>.xml</code> <?php echo esc_html__( 'file you previously exported. Be sure to check the option to', 'ufaqsw' ); ?> <strong><?php echo esc_html__( '“Download and import file attachments”', 'ufaqsw' ); ?></strong> <?php echo esc_html__( 'if you want to bring in any images or media associated with your FAQs.', 'ufaqsw' ); ?>
	</p>

	<p>
		<?php echo esc_html__( 'After the import is complete, all your FAQ groups will be added to the current site, including their content, and metadata.', 'ufaqsw' ); ?>
	</p>
</div>
