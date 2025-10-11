<?php
/**
 * Admin Settings UI for Ultimate FAQ Solution Plugin
 *
 * This file contains the settings UI for the plugin, allowing users to configure
 * various options and features.
 *
 * @package UltimateFAQSolution
 */

use Mahedi\UltimateFaqSolution\Product_Tab;

if ( ! defined( 'ABSPATH' ) ) {
	exit;}
$allowed_html = ufaqsw_wses_allowed_menu_html();
?>

<div class="wrap">
	<h1><?php echo esc_html( 'Settings & Help - Ultimate FAQs' ); ?></h1>

	<h2 class="nav-tab-wrapper sld_nav_container">
		<a class="nav-tab ufaqsw_click_handle nav-tab-active" href="#general_settings"><?php echo esc_html( 'General Settings' ); ?></a>
		<a class="nav-tab ufaqsw_click_handle" href="#getting_started"><?php echo esc_html( 'Getting Stared' ); ?></a>

	</h2>

	<form method="post" action="options.php">
		<?php settings_fields( 'ufaqsw-plugin-settings-group' ); ?>
		<?php do_settings_sections( 'ufaqsw-plugin-settings-group' ); ?>

		<div id="general_settings">

			<h3><?php echo esc_html( 'FAQs Directory Settings' ); ?></h3>

			<table class="form-table ufaqsw_settings_table">
				<tr valign="top">
					<th scope="row"><?php echo esc_html( 'FAQs Directory Shortcode' ); ?></th>
					<td>
						<input type="text" class="ufaqsw_admin_faq_shorcode_copy" value="[ufaqsw-all]" />
						<i>
							<?php echo esc_html( 'Copy and use this shortcode to display all FAQ groups in a directory-style layout on any page. You can also use the FAQ block in the block editor for easy insertion.' ); ?>
							<br>
							<a href="https://www.braintum.com/docs/ultimate-faq-solution/displaying-faqs/shortcode-parameters/" target="_blank">
								<?php echo esc_html( 'Learn more about shortcode parameters' ); ?>
							</a>
						</i>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php echo esc_html( 'Live Search' ); ?></th>
					<td>
						<label>
							<input type="checkbox" name="ufaqsw_enable_search" value="on" <?php echo ( esc_attr( get_option( 'ufaqsw_enable_search' ) ) === 'on' ? 'checked="checked"' : '' ); ?> />
							<?php echo esc_html( 'Enable Live Search' ); ?>
						</label>
						<i><?php echo wp_kses( 'Enable this option to add a fast, modern live search to your <b>FAQs Directory</b> page. Instantly filter FAQs as users type, powered by JavaScript.', $allowed_html ); ?></i>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row"><?php echo esc_html( 'Sticky Filter' ); ?></th>
					<td>
						<label>
							<input type="checkbox" name="ufaqsw_enable_filter" value="on" <?php echo ( esc_attr( get_option( 'ufaqsw_enable_filter' ) ) === 'on' ? 'checked="checked"' : '' ); ?> />
							<?php echo esc_html( 'Enable Sticky Filter' ); ?>
						</label>
						<br>
						<i><?php echo wp_kses( 'Display sticky filter buttons at the top of the FAQ list, allowing users to easily filter FAQs by group as they scroll.', $allowed_html ); ?></i>
					</td>
				</tr>

			</table>

			<h3><?php echo esc_html( 'FAQ Group Detail Page' ); ?></h3>
			<p>
				<?php echo esc_html( 'You can still use shortcodes to display FAQ groups in pages or posts. However, enabling the detail page option will add a dedicated detail page for each FAQ group.' ); ?>
			</p>

			<table class="form-table ufaqsw_settings_table">

				<tr valign="top">
					<th scope="row"><?php echo esc_html( 'FAQ Group Detail Page' ); ?></th>
					<td>
						<label>
							<input type="checkbox" name="ufaqsw_enable_group_detail_page" value="on" <?php echo ( esc_attr( get_option( 'ufaqsw_enable_group_detail_page' ) ) === 'on' ? 'checked="checked"' : '' ); ?> />
							<?php echo esc_html( 'Enable FAQ Group Detail Page' ); ?>
						</label>
						<i><?php echo wp_kses( 'Enable this option to add a dedicated detail page for each FAQ group, allowing for a more focused presentation of FAQs within that group.', $allowed_html ); ?></i>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php echo esc_html( 'Detail Page Slug' ); ?></th>
					<td>
						
						<input type="text" name="ufaqsw_detail_page_slug" class="ufaqsw_detail_page_slug" value="<?php echo get_option( 'ufaqsw_detail_page_slug' ) ? esc_attr( get_option( 'ufaqsw_detail_page_slug' ) ) : 'faq-group'; ?>" />
						<i>
							<?php echo esc_html( 'This slug will be used in the URL for the FAQ group detail page.' ); ?>
							<br>
							<strong style="color: #d63638;">
								<?php echo esc_html( 'Warning: Changing this permalink will update the URLs for all FAQ group detail pages. Any existing links to FAQ groups using the old slug will no longer work.' ); ?>
							</strong>
						</i>
					</td>
				</tr>


			</table>

			<h3><?php echo esc_html( 'Woocommerce Settings' ); ?></h3>
			<p>
				<?php echo esc_html( 'Configure WooCommerce integration settings for displaying FAQs on product pages.' ); ?>
				<a href="https://www.braintum.com/docs/ultimate-faq-solution/advanced-configurations/woocommerce/" target="_blank">
					<?php echo esc_html( 'Learn more' ); ?>
				</a>
			</p>
			<?php if ( class_exists( 'WooCommerce' ) ) : ?>
				<table class="form-table ufaqsw_settings_table">
					<tr valign="top">
						<th scope="row"><?php echo esc_html( 'Product FAQ' ); ?></th>
						<td>
							<label>
								<input type="checkbox" name="ufaqsw_enable_woocommerce" value="on" <?php echo ( esc_attr( get_option( 'ufaqsw_enable_woocommerce' ) ) === 'on' ? 'checked="checked"' : '' ); ?> />
								<?php echo esc_html( 'Enable Product FAQ' ); ?>
							</label>
							<i>
								<?php echo wp_kses( 'Enable this option to display FAQs on the product landing page.', $allowed_html ); ?>
							</i>
						</td>
					</tr>

					<tr valign="top" id="ufaqsw_faq_tab_label_tr">
						<th scope="row"><?php echo esc_html( 'FAQ Tab Label' ); ?></th>
						<td>
							<div id="ufaqsw_global_faq_fields">
								<input type="text" name="ufaqsw_global_faq_label" size="100" value="<?php echo esc_attr( '' !== get_option( 'ufaqsw_global_faq_label' ) ? get_option( 'ufaqsw_global_faq_label' ) : 'Faqs' ); ?>"  />
								<i><?php echo esc_html( 'Add faq tab label. e.g: Faqs' ); ?></i>
							</div>
						</td>
					</tr>

					<tr valign="top">
						<th scope="row"><?php echo esc_html( 'Hide FAQ Group Title' ); ?></th>
						<td>
							<label>
								<input type="checkbox" name="ufaqsw_product_hide_group_title" value="on" <?php echo ( esc_attr( get_option( 'ufaqsw_product_hide_group_title' ) ) === 'on' ? 'checked="checked"' : '' ); ?> />
								<?php echo esc_html( 'Hide the FAQ group title on the product page.' ); ?>
							</label>
							<i>
								<?php echo wp_kses( 'Enable this option to hide the FAQ group title on the WooCommerce product page for a cleaner look.', $allowed_html ); ?>
							</i>
						</td>
					</tr>

					<tr valign="top">
						<th scope="row"><?php echo esc_html( 'Global FAQ for All Products' ); ?></th>
						<td>
							<label>
								<input type="checkbox" name="ufaqsw_enable_global_faq" value="on" <?php echo ( esc_attr( get_option( 'ufaqsw_enable_global_faq' ) ) === 'on' ? 'checked="checked"' : '' ); ?> />
								<?php echo esc_html( 'Enable Global FAQ for All Products' ); ?>
							</label>
							<i><?php echo wp_kses( 'Enable this option to show a global <b>FAQ</b> to all products', $allowed_html ); ?></i>
						</td>
					</tr>

					<?php
					$faqs = Product_Tab::get_instance()->get_all_faqs();
					?>

					<tr valign="top" id="ufaqsw_global_faq_group_field_tr" style="<?php echo ( esc_attr( get_option( 'ufaqsw_enable_global_faq' ) ) === 'on' ? '' : 'display:none;' ); ?>">
						<th scope="row"><?php echo esc_html( 'Select A FAQ Group' ); ?></th>
						<td>
							<div id="ufaqsw_global_faq_group_field">
								<select name="ufaqsw_global_faq" >
									<?php
									foreach ( $faqs as $key => $val ) {
										echo '<option value="' . esc_attr( $key ) . '" ' . ( (int) get_option( 'ufaqsw_global_faq' ) === $key ? 'selected="selected"' : '' ) . ' > ' . esc_html( $val ) . ' </option>';
									}
									?>
								</select>
							</div>
						</td>
					</tr>

					<script>
					document.addEventListener('DOMContentLoaded', function() {
						const globalFaqCheckbox = document.querySelector('input[name="ufaqsw_enable_global_faq"]');
						const groupTr = document.getElementById('ufaqsw_global_faq_group_field_tr');
						if (globalFaqCheckbox) {
							globalFaqCheckbox.addEventListener('change', function() {
								if (this.checked) {
									groupTr.style.display = '';
								} else {
									groupTr.style.display = 'none';
								}
							});
						}
					});
					</script>

				</table>
			<?php else : ?>
				<table class="form-table ufaqsw_settings_table">
					<tr valign="top">
						<td colspan="2">
							<p>
								<?php echo esc_html__( 'You need the WooCommerce plugin installed and activated to use these integration settings.', 'ufaqsw' ); ?>
							</p>
						</td>
					</tr>
				</table>
			<?php endif; ?>

			<h3><?php echo esc_html( 'Labels' ); ?></h3>
			<p><?php echo esc_html( 'Customize all static texts displayed on the frontend by this plugin.' ); ?></p>
			<table class="form-table ufaqsw_settings_table">

				<tr valign="top">
					<th scope="row"><?php echo esc_html( 'Live Search' ); ?></th>
					<td>
						<input type="text" name="ufaqsw_live_search_text" size="100" value="<?php echo esc_attr( ( '' !== get_option( 'ufaqsw_live_search_text' ) ? get_option( 'ufaqsw_live_search_text' ) : esc_html( 'Live Search..' ) ) ); ?>"  />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php echo esc_html( 'Loading' ); ?></th>
					<td>
						<input type="text" name="ufaqsw_live_search_loading_text" size="100" value="<?php echo esc_attr( ( '' !== get_option( 'ufaqsw_live_search_loading_text' ) ? get_option( 'ufaqsw_live_search_loading_text' ) : esc_html( 'Loading...' ) ) ); ?>"  />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php echo esc_html( 'No result Found' ); ?></th>
					<td>
						<input type="text" name="ufaqsw_search_result_not_found" size="100" value="<?php echo esc_attr( '' !== get_option( 'ufaqsw_search_result_not_found' ) ? get_option( 'ufaqsw_search_result_not_found' ) : esc_html( 'No result Found!' ) ); ?>"  />
					</td>
				</tr>

			</table>

			<h3><?php echo esc_html( 'Custom Css' ); ?></h3>
			<p><?php echo esc_html( 'Add your own CSS to customize the appearance of the FAQs on the frontend.' ); ?></p>

			<table class="form-table ufaqsw_settings_table">

				<tr valign="top">
					<th scope="row"><?php echo esc_html( 'Custom Css' ); ?></th>
					<td>
						<textarea name="ufaqsw_setting_custom_style" id="ufaqsw_setting_custom_style" rows="10" cols="100"><?php echo esc_attr( get_option( 'ufaqsw_setting_custom_style' ) ); ?></textarea>
						<?php echo wp_kses( '<br><i>Write your custom CSS here. Please do not use <b>style</b> tag in this textarea. Use *!important* flag if the styling does not take place as expected.</i>', $allowed_html ); ?>
					</td>
				</tr>

			</table>
		</div>

		<div id="getting_started" style="display:none">
			<div class="wrap">

				<div id="poststuff">
					<div class="ufaqsw-get-started">

						<h1>🎉 Welcome to the Ultimate FAQ Solution!</h1>
						<p>You are awesome, by the way.</p>

						<h2>🚀 Getting Started</h2>
						<p>Getting started with Ultimate FAQ Solution is super easy. Just follow these simple steps:</p>

						<ol>
							<li>
							<strong>Create a FAQ Group:</strong><br>
							Go to <em>New FAQ Group</em> and create one by giving it a name. Then start adding your questions and answers by filling out the fields. Use the "Add New" button to add more entries.<br>
							The answer field uses WordPress's built-in WYSIWYG editor — so you can add text, images, links, or anything else you need.
							</li>

							<li>
							<strong>Insert Your FAQ Group on a Page:</strong><br>
							After creating your FAQ Group and adding all your content, it’s time to add it to a page. Each FAQ Group has its own shortcode, which you can find on the <em>Manage FAQ Groups</em> page.
							</li>

							<li>
							<strong>Display the FAQ:</strong><br>
							Copy the shortcode and paste it anywhere on your site — in a page, post, or widget area. That’s it!
							</li>
						</ol>

						<hr>

						<h2>💬 Enhance UX with the FAQ Assistant</h2>
						<p>
							Want to make FAQs even easier to access? Turn on the <strong>FAQ Assistant</strong> feature from the plugin settings.  
							It adds a floating help icon to every page on your site, opening an interactive assistant window where users can browse FAQ Groups and find answers quickly.
						</p>

						<p>
							To enable it, go to <a href="<?php echo esc_url( admin_url( 'edit.php?post_type=ufaqsw&page=ufaqsw_chatbot_settings' ) ); ?>"><strong>FAQ Assistant Settings</strong></a> from the plugin menu and toggle the <em>“Enable FAQ Assistant”</em> option.
						</p>

						<hr>

						<p><strong>Any troubles?</strong><br>
						We’re here to help! You can:</p>

						<ul>
							<li>✉️ Email us at <a href="mailto:braintum@gmail.com">braintum@gmail.com</a></li>
							<li>🧾 Open a support ticket at <a href="https://www.braintum.com/support/" target="_blank">https://www.braintum.com/support/</a></li>
						</ul>

						<blockquote style="background: #f9f9f9; border-left: 4px solid #0073aa; padding: 10px 15px;">
							We recommend opening a support ticket through our website for the <strong>best support experience</strong> — fast, friendly, and personalized.
						</blockquote>

						<p><strong>⏱️ We typically respond within 1 business day</strong> to all support requests.</p>

						<hr>

						<h2>📚 Plugin Documentation</h2>

						<p>Before reaching out, you may want to browse our documentation — most common questions and setup steps are covered there:</p>

						<p>
							🔗 <a href="https://www.braintum.com/docs/ultimate-faq-solution/" target="_blank"><strong>View Ultimate FAQ Solution Documentation</strong></a>
						</p>

					</div>
				</div>
					<!-- /poststuff -->
			</div>
		</div>

		<?php submit_button(); ?>
	</form>
</div>
