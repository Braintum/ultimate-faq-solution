<?php if ( ! defined( 'ABSPATH' ) ) exit; 
$allowed_html = ufaqsw_wses_allowed_menu_html();
?>

<div class="wrap swpm-admin-menu-wrap ufaqsw_setting_panel">
	<h1 class="ufaqsw_heading_settings"><?php echo esc_html('Settings & Help - Ultimate FAQs'); ?></h1>

	<h2 class="nav-tab-wrapper sld_nav_container">
		<a class="nav-tab sld_click_handle nav-tab-active" href="#general_settings"><?php echo esc_html('General'); ?></a>
		<a class="nav-tab sld_click_handle" href="#faq_directory"><?php echo esc_html('Shortcodes'); ?></a>
		<a class="nav-tab sld_click_handle" href="#language_settings"><?php echo esc_html('Language Settings'); ?></a>
		<a class="nav-tab sld_click_handle" href="#custom_css"><?php echo esc_html('Custom Css'); ?></a>
		<a class="nav-tab sld_click_handle" href="#getting_started"><?php echo esc_html('Getting Stared'); ?></a>
		<a class="nav-tab sld_click_handle" href="#support"><?php echo esc_html('Support'); ?></a>
	</h2>
	
	<form method="post" action="options.php">
		<?php settings_fields( 'ufaqsw-plugin-settings-group' ); ?>
		<?php do_settings_sections( 'ufaqsw-plugin-settings-group' ); ?>
		<div id="general_settings">
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><?php echo esc_html('Enable FAQ for Woocommerce'); ?></th>
					<td>
						<input type="checkbox" name="ufaqsw_enable_woocommerce" value="on" <?php echo (esc_attr( get_option('ufaqsw_enable_woocommerce') )=='on'?'checked="checked"':''); ?> />
						<i><?php echo wp_kses('Turn it on to enable faq for <b>Woocommerce</b>. It will add an extra tab called <b>FAQ</b> in every <b>Product Edit</b> page in <b>Product Data</b> Section', $allowed_html); ?></i>
					</td>
				</tr>

			</table>
		</div>
		<div id="faq_directory" style="display:none">
		
			<?php echo wp_kses('<p>You can display all your <b>FAQ Groups</b> in one page as a <b>FAQs Directory</b>. A very simple <b>Shortcode</b> will does the job. Also there is a quick search feature available which will able to search & find FAQs before user\'s typing completed.</p>', $allowed_html); ?>
			
			
			<p><b><?php echo esc_html('FAQs Directory Shortcode:'); ?></b> <input type="text" class="ufaqsw_admin_faq_shorcode_copy" value="[ufaqsw-all]" /></p>
		
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><?php echo esc_html('Enable Search for FAQs Directory'); ?></th>
					<td>
						<input type="checkbox" name="ufaqsw_enable_search" value="on" <?php echo (esc_attr( get_option('ufaqsw_enable_search') )=='on'?'checked="checked"':''); ?> />
						<i><?php echo wp_kses('Turn it on to add a <b>Search Bar</b> at the top of <b>FAQs Directory</b>. It is the most quick any easy <b>Live Search</b> system powered by modern javascript.', $allowed_html); ?></i>
					</td>
				</tr>

			</table>
			<p><b><u><?php echo esc_html('Directory Shortcode Parameters'); ?></u></b></p>
			<p><?php echo wp_kses('There are some parameters for <b>[ufaqsw-all]</b> shortcode to inhance its usability.', $allowed_html); ?></p>
			<p>
				<b><?php echo esc_html('1. exclude'); ?></b><br>
				
				<?php echo wp_kses('<span>This parameter accepts <b>FAQ Group ID</b> which you can find in <b>Manage FAQ Groups</b> page. This parameter can be used for excluding a <b>FAQ Group</b> from <b>FAQ Directory</b>. The basic use case would be - you can remove a <b>FAQ Group</b> which you have added for a Woocommerce Product specifically from <b>FAQs Directory</b>. Also you can remove multiple FAQ Groups by adding multiple FAQ Group ID seperated by (,)Comma.</span><br>
				<span><b>Ex:</b> [ufaqsw-all exclude="1, 2"] </span>', $allowed_html); ?>
				
			</p>
			
			<p>
				<b><?php echo esc_html('2. column'); ?></b><br>
				<?php echo wp_kses('<span>This parameter only accepts a number. By this parameter you can specify the column of your <b>FAQs Directory</b>. By Default, it is a single column <b>FAQs Directory</b>. You can set up to <b>3</b> column.
				<span><b>Ex:</b> [ufaqsw-all exclude="1, 2" column="3"] </span>', $allowed_html); ?>
				
			</p>
			<p>
				<b><?php echo esc_html('3. behaviour'); ?></b><br>
				<?php echo wp_kses('<span>Supported values for this parameter are toggle, accordion.
				<span><b>Ex:</b> [ufaqsw-all exclude="1, 2" column="3" behaviour="accordion"] </span>', $allowed_html); ?>
				
			</p>

			<p><b><u><?php echo esc_html('Group Shortcode Parameters'); ?></u></b></p>
			<p><?php echo wp_kses('There are some parameters for <b>[ufaqsw]</b> shortcode to inhance its usability.', $allowed_html); ?></p>

			<p>
				<b><?php echo esc_html('1. id'); ?></b><br>
				
				<?php echo wp_kses('<span>This parameter accepts <b>FAQ Group ID</b> which you can find in <b>Manage FAQ Groups</b> page.</span><br>
				<span><b>Ex:</b> [ufaqsw id="1"] </span>', $allowed_html); ?>
				
			</p>

			<p>
				<b><?php echo esc_html('1. elements_order'); ?></b><br>
				
				<?php echo wp_kses('<span>This parameter allows faq elements to sort from last to first. Available values: ASC, DESC. By default it is ASC</span><br>
				<span><b>Ex:</b> [ufaqsw id="1" elements_order="DESC"] </span>', $allowed_html); ?>
				
			</p>
		
		</div>
		<div id="language_settings" style="display:none">
		
			<?php echo wp_kses('<p>In this section you can change all of the texts that <b title="Ultimate FAQ Solution"><u>Ultimate FAQ Solution</u></b> using in the Frontend.</p>', $allowed_html); ?>
			
			
			<table class="form-table">

				<tr valign="top">
					<th scope="row"><?php echo esc_html('Live Search Placeholder Text'); ?></th>
					<td>
						<input type="text" name="ufaqsw_live_search_text" size="100" value="<?php echo esc_attr( (get_option('ufaqsw_live_search_text')!=''? get_option('ufaqsw_live_search_text') : esc_html('Live Search..')) ); ?>"  />
						<i><?php echo esc_html('Please change the text if it is not suitable for you.'); ?></i>
					</td>
				</tr>
				
				<tr valign="top">
					<th scope="row"><?php echo esc_html('Live Search Loading.. Text'); ?></th>
					<td>
						<input type="text" name="ufaqsw_live_search_loading_text" size="100" value="<?php echo esc_attr( (get_option('ufaqsw_live_search_loading_text')!=''? get_option('ufaqsw_live_search_loading_text') : esc_html('Loading...')) ); ?>"  />
						<i><?php echo esc_html('Please change the text if it is not suitable for you.'); ?></i>
					</td>
				</tr>
				
				<tr valign="top">
					<th scope="row"><?php echo esc_html('Search Result Not Found Text'); ?></th>
					<td>
						<input type="text" name="ufaqsw_search_result_not_found" size="100" value="<?php echo esc_attr( get_option('ufaqsw_search_result_not_found')!=''?get_option('ufaqsw_search_result_not_found'):esc_html('No result Found!') ); ?>"  />
						<i><?php echo esc_html('Please change the text if it is not suitable for you.'); ?></i>
					</td>
				</tr>

			</table>
		</div>
		<div id="custom_css" style="display:none">
			<table class="form-table">

				<tr valign="top">
					<th scope="row"><?php echo esc_html('Custom Css'); ?></th>
					<td>
						
						<textarea name="ufaqsw_setting_custom_style" id="ufaqsw_setting_custom_style" rows="10" cols="100"><?php echo esc_attr( get_option('ufaqsw_setting_custom_style') ); ?></textarea>
						
						<?php echo wp_kses('<br><i>Write your custom CSS here. Please do not use <b>style</b> tag in this textarea. Use *!important* flag if the styling does not take place as expected.</i>', $allowed_html); ?>
						
					</td>
				</tr>

			</table>
		</div>
		
		<div id="getting_started" style="display:none">
			<div class="wrap">
	
				<div id="poststuff">

							<h1><?php echo wp_kses('Welcome to the Ultimate FAQ Solution! You are <strong>awesome</strong>, by the way.', $allowed_html); ?></h1>
							<h3><?php echo esc_html('Getting Started'); ?></h3>
															
							<p><?php echo esc_html('Getting started with Ultimate FAQ Solution is super easy'); ?></p>

							<p><?php echo esc_html('With that in mind you should start with the following simple steps.') ?></p>

							<p><?php echo wp_kses('<br><b>1.</b> Go to New FAQ Group and create one by giving it a name. Then simply start adding question and answer by filling up the fields. Use the <strong>Add New</strong> button to add more questions & answers you want in your FAQ Grouop. The answer field is WP\'s default wysiwyg editor. So you can add what ever you want.', $allowed_html); ?></p>

							<p>
							<?php echo wp_kses('<br><b>2.</b> After creating a <b>FAQ Group</b> and put all questions and answers, Now its time to add the <b>FAQ Group</b> into a page. As you know <b>Ultimate FAQ Solution</b> is fully shortcode driven. So each of the <b>FAQ Group</b> has there own shortcode. You can find the shortcode for individual <b>FAQ Group</b> in <b>Manage FAQ Groups</b> page.', $allowed_html); ?>
							</p>
							
							<p>
							<?php echo wp_kses('<br><b>3.</b> Copy the shorcode and add it where ever you want. That\'s it.', $allowed_html); ?>
							</p>

				</div>
					<!-- /poststuff -->
			</div>
		</div>
		
		<div id="support" style="display:none">
			<div class="wrap">
	
				<div id="poststuff">

							<h1><?php echo wp_kses('Welcome to the Ultimate FAQ Solution Support Section.', $allowed_html); ?></h1>
							
							<h3><?php echo esc_html('Any Troubles?'); ?></h3>
															
							<p><?php echo wp_kses('you can simply shoot us an email at <b>braintum@gmail.com</b> or you can open a support ticket at <a href="https://braintum.com/support/" target="_blank">https://braintum.com/support/</a>' , $allowed_html); ?></p>

							<p><?php echo esc_html('We recommend you to open a support ticket from our support page for awesome & nice support experience.') ?></p>

							<p><?php echo esc_html('We generally reply within 1 day for all kind of support request.') ?></p>

							<h3><?php echo esc_html('Online Documentation of Ultimate FAQ Solution?'); ?></h3>
							<p><?php echo wp_kses('Please click on the link for <a href="https://www.braintum.com/documentation/ultimate-faq-solution.html" target="_blank">Online Documentation</a>', $allowed_html) ?></p>
							

				</div>
					<!-- /poststuff -->
			</div>
		</div>
		
		<?php submit_button(); ?>
	</form>
	
</div>
