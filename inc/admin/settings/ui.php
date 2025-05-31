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
		<a class="nav-tab ufaqsw_click_handle nav-tab-active" href="#getting_started"><?php echo esc_html( 'Getting Stared' ); ?></a>
		<a class="nav-tab ufaqsw_click_handle" href="#faq_directory"><?php echo esc_html( 'Shortcodes' ); ?></a>
		<a class="nav-tab ufaqsw_click_handle" href="#language_settings"><?php echo esc_html( 'Language Settings' ); ?></a>
		<a class="nav-tab ufaqsw_click_handle" href="#general_settings"><?php echo esc_html( 'Woocommerce' ); ?></a>
		<a class="nav-tab ufaqsw_click_handle" href="#custom_css"><?php echo esc_html( 'Custom Css' ); ?></a>
		<a class="nav-tab ufaqsw_click_handle" href="#support"><?php echo esc_html( 'Support' ); ?></a>
	</h2>

	<form method="post" action="options.php">
		<?php settings_fields( 'ufaqsw-plugin-settings-group' ); ?>
		<?php do_settings_sections( 'ufaqsw-plugin-settings-group' ); ?>

		<div id="getting_started">
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

					</div>
				</div>
					<!-- /poststuff -->
			</div>
		</div>

		<div id="faq_directory" style="display:none">
			<?php echo wp_kses( '<p>You can display all your <b>FAQ Groups</b> in one page as a <b>FAQs Directory</b>. A very simple <b>Shortcode</b> will does the job. Also there is a quick search feature available which will able to search & find FAQs before user\'s typing completed.</p>', $allowed_html ); ?>
			<p><b><?php echo esc_html( 'FAQs Directory Shortcode:' ); ?></b> <input type="text" class="ufaqsw_admin_faq_shorcode_copy" value="[ufaqsw-all]" /></p>

			<table class="form-table">
				<tr valign="top">
					<th scope="row"><?php echo esc_html( 'Enable Search for FAQs Directory' ); ?></th>
					<td>
						<input type="checkbox" name="ufaqsw_enable_search" value="on" <?php echo ( esc_attr( get_option( 'ufaqsw_enable_search' ) ) === 'on' ? 'checked="checked"' : '' ); ?> />
						<i><?php echo wp_kses( 'Turn it on to add a <b>Search Bar</b> at the top of <b>FAQs Directory</b>. It is the most quick any easy <b>Live Search</b> system powered by modern javascript.', $allowed_html ); ?></i>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row"><?php echo esc_html( 'Enable Filter by Group' ); ?></th>
					<td>
						<input type="checkbox" name="ufaqsw_enable_filter" value="on" <?php echo ( esc_attr( get_option( 'ufaqsw_enable_filter' ) ) === 'on' ? 'checked="checked"' : '' ); ?> />
						<i><?php echo wp_kses( 'Display a set of filter buttons above the FAQ list, allowing users to view FAQs by specific groups.', $allowed_html ); ?></i>
					</td>
				</tr>

			</table>
			<hr>
			<p><b><u><?php echo esc_html( 'Directory Shortcode Parameters' ); ?></u></b></p>
			<p><?php echo wp_kses( 'There are some parameters for <b>[ufaqsw-all]</b> shortcode to inhance its usability.', $allowed_html ); ?></p>
			<p>
				<b><?php echo esc_html( '1. exclude' ); ?></b><br>
				<?php
				echo wp_kses(
					'<span>This parameter accepts <b>FAQ Group ID</b> which you can find in <b>Manage FAQ Groups</b> page. This parameter can be used for excluding a <b>FAQ Group</b> from <b>FAQ Directory</b>. The basic use case would be - you can remove a <b>FAQ Group</b> which you have added for a Woocommerce Product specifically from <b>FAQs Directory</b>. Also you can remove multiple FAQ Groups by adding multiple FAQ Group ID seperated by (,)Comma.</span><br>
				<span><b>Ex:</b> [ufaqsw-all exclude="1, 2"] </span>',
					$allowed_html
				);
				?>
			</p>
			<p>
				<b><?php echo esc_html( '2. behaviour' ); ?></b><br>
				<?php
				echo wp_kses(
					'<span>Supported values for this parameter are toggle, accordion.
				<span><b>Ex:</b> [ufaqsw-all exclude="1, 2" behaviour="accordion"] </span>',
					$allowed_html
				);
				?>
			</p>

			<p><b><u><?php echo esc_html( 'Group Shortcode Parameters' ); ?></u></b></p>
			<p><?php echo wp_kses( 'There are some parameters for <b>[ufaqsw]</b> shortcode to inhance its usability.', $allowed_html ); ?></p>

			<p>
				<b><?php echo esc_html( '1. id' ); ?></b><br>
				<?php
				echo wp_kses(
					'<span>This parameter accepts <b>FAQ Group ID</b> which you can find in <b>Manage FAQ Groups</b> page.</span><br>
				<span><b>Ex:</b> [ufaqsw id="1"] </span>',
					$allowed_html
				);
				?>
			</p>

			<p>
				<b><?php echo esc_html( '1. elements_order' ); ?></b><br>
				<?php
				echo wp_kses(
					'<span>This parameter allows faq elements to sort from last to first. Available values: ASC, DESC. By default it is ASC</span><br>
				<span><b>Ex:</b> [ufaqsw id="1" elements_order="DESC"] </span>',
					$allowed_html
				);
				?>
			</p>
		</div>
		<div id="language_settings" style="display:none">
			<?php echo wp_kses( '<p>In this section you can change all of the texts that <b title="Ultimate FAQ Solution"><u>Ultimate FAQ Solution</u></b> using in the Frontend.</p>', $allowed_html ); ?>
			<table class="form-table">

				<tr valign="top">
					<th scope="row"><?php echo esc_html( 'Live Search Placeholder Text' ); ?></th>
					<td>
						<input type="text" name="ufaqsw_live_search_text" size="100" value="<?php echo esc_attr( ( '' !== get_option( 'ufaqsw_live_search_text' ) ? get_option( 'ufaqsw_live_search_text' ) : esc_html( 'Live Search..' ) ) ); ?>"  />
						<i><?php echo esc_html( 'Please change the text if it is not suitable for you.' ); ?></i>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php echo esc_html( 'Live Search Loading.. Text' ); ?></th>
					<td>
						<input type="text" name="ufaqsw_live_search_loading_text" size="100" value="<?php echo esc_attr( ( '' !== get_option( 'ufaqsw_live_search_loading_text' ) ? get_option( 'ufaqsw_live_search_loading_text' ) : esc_html( 'Loading...' ) ) ); ?>"  />
						<i><?php echo esc_html( 'Please change the text if it is not suitable for you.' ); ?></i>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php echo esc_html( 'Search Result Not Found Text' ); ?></th>
					<td>
						<input type="text" name="ufaqsw_search_result_not_found" size="100" value="<?php echo esc_attr( '' !== get_option( 'ufaqsw_search_result_not_found' ) ? get_option( 'ufaqsw_search_result_not_found' ) : esc_html( 'No result Found!' ) ); ?>"  />
						<i><?php echo esc_html( 'Please change the text if it is not suitable for you.' ); ?></i>
					</td>
				</tr>

			</table>
		</div>

		<div id="general_settings" style="display:none">
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><?php echo esc_html( 'Enable FAQ for Woocommerce' ); ?></th>
					<td>
						<input type="checkbox" name="ufaqsw_enable_woocommerce" value="on" <?php echo ( esc_attr( get_option( 'ufaqsw_enable_woocommerce' ) ) === 'on' ? 'checked="checked"' : '' ); ?> />
						<i><?php echo wp_kses( 'Turn it on to enable faq for <b>Woocommerce</b>. It will add an extra tab called <b>FAQ</b> in every <b>Product Edit</b> page in <b>Product Data</b> Section', $allowed_html ); ?></i>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row"><?php echo esc_html( 'Enable Global FAQ for All Products' ); ?></th>
					<td>
						<input type="checkbox" name="ufaqsw_enable_global_faq" value="on" <?php echo ( esc_attr( get_option( 'ufaqsw_enable_global_faq' ) ) === 'on' ? 'checked="checked"' : '' ); ?> />
						<i><?php echo wp_kses( 'Enable this option to show a global <b>FAQ</b> to all products', $allowed_html ); ?></i>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row"><?php echo esc_html( 'Global FAQ Tab Label' ); ?></th>
					<td>
						<input type="text" name="ufaqsw_global_faq_label" size="100" value="<?php echo esc_attr( '' !== get_option( 'ufaqsw_global_faq_label' ) ? get_option( 'ufaqsw_global_faq_label' ) : 'Faqs' ); ?>"  />
						<i><?php echo esc_html( 'Please add faq tab label. e.g: Faqs' ); ?></i>
					</td>
				</tr>

				<?php
				$faqs = Product_Tab::get_instance()->get_all_faqs();
				?>

				<tr valign="top">
					<th scope="row"><?php echo esc_html( 'Select A Global FAQ Group' ); ?></th>
					<td>
						<select name="ufaqsw_global_faq" >
							<?php

							foreach ( $faqs as $key => $val ) {
								echo '<option value="' . esc_attr( $key ) . '" ' . ( get_option( 'ufaqsw_global_faq' ) === $key ? 'selected="selected"' : '' ) . ' > ' . esc_html( $val ) . ' </option>';
							}
							?>
						</select>
					</td>
				</tr>

			</table>
		</div>

		<div id="custom_css" style="display:none">
			<table class="form-table">

				<tr valign="top">
					<th scope="row"><?php echo esc_html( 'Custom Css' ); ?></th>
					<td>
						<textarea name="ufaqsw_setting_custom_style" id="ufaqsw_setting_custom_style" rows="10" cols="100"><?php echo esc_attr( get_option( 'ufaqsw_setting_custom_style' ) ); ?></textarea>
						<?php echo wp_kses( '<br><i>Write your custom CSS here. Please do not use <b>style</b> tag in this textarea. Use *!important* flag if the styling does not take place as expected.</i>', $allowed_html ); ?>
					</td>
				</tr>

			</table>
		</div>

		<div id="support" style="display:none">
			<div class="wrap">
				<div id="poststuff">

						<h1>🛠️ Welcome to the Ultimate FAQ Solution Support Center</h1>

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

						<ul>
							<li>Getting Started</li>
							<li>Creating FAQ Groups & Items</li>
							<li>Using Shortcodes & Blocks</li>
							<li>FAQ Assistant Setup</li>
							<li>Advanced Configuration</li>
							<li>Troubleshooting</li>
						</ul>

					</div>
					<!-- /poststuff -->
			</div>
		</div>

		<?php submit_button(); ?>
	</form>
</div>
