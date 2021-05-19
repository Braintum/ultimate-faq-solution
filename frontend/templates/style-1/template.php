<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/*
* Default template by Aussie Team
* Template file name must be 'template.php' to get the template work.
* CSS file(if any) name must be 'style.css'
* Js file(if any) name must be 'script.js'
* The following classes are being used by faq directory's search script - ufaqsw_element_group_src, ufaqsw_element_src, ufaqsw_faq_question_src,
* ufaqsw_faq_answer_src. 
* So do not forgot to reuse that in next template.
*/
extract($designs);

?>
<div class="ufaqsw_content_style1 ufaqsw_element_group_src">
	
	<?php 
	//Main Title
	//Check if title is enabled
	if($hidetitle!='1' && $title_hide!='yes'): ?>
		<h2 class="ufaqsw_faq_title ufaqsw_faq_title_<?php echo esc_html(get_the_ID()); ?>"><?php echo esc_html(get_the_title()); ?></h2>
	<?php endif; ?>
  
	<?php 
		$c = 1;
		foreach($faqs as $faq): 
		/*
		* This is where the looping begans
		* Now it is for simplifing for data variables
		*/
		$faq = apply_filters( 'ufaqsw_simplify_variables', $faq );
		extract($faq);
		
		/*
		* All data variables are extracted. Available variables are
		* $question - Which contains the Question.
		* $answer - Which contains the Answer. 
		*/
		
	?>
	
   <div class="ufaqsw_toggle_default ufaqsw_toggle_default_<?php echo esc_html(get_the_ID()); ?> ufaqsw_element_src">
      <input type="checkbox" id="ufaqsw_question<?php echo esc_attr(get_the_ID().$c); ?>_style1" name="q"  class="ufaqsw_questions_style1" <?php echo esc_attr($showall=='1'?'checked="checked"':''); ?> />
	  
	  <div class="ufaqsw_title_area_style1 ufaqsw-toggle-title-area-default_<?php echo esc_attr(get_the_ID()); ?>">
	  
		  
		  <label for="ufaqsw_question<?php echo esc_attr(get_the_ID().$c); ?>_style1" class="ufaqsw_question_style1 ufaqsw-title-name-default_<?php echo esc_attr(get_the_ID()); ?>">
			<span class="ufaqsw-style1-icon">
				<i class="fa <?php echo esc_attr(isset($designs['active_icon']) && $designs['active_icon']!=''?$designs['active_icon']:'fa fa-minus') ?> ufaqsw-style1-active-icon" aria-hidden="true"></i>
				<i class="fa <?php echo esc_attr(isset($designs['normal_icon']) && $designs['normal_icon']!=''?$designs['normal_icon']:'fa fa-plus') ?> ufaqsw-style1-normal-icon"  aria-hidden="true"></i>
			</span>
			<span class="ufaqsw_faq_question_src">
			  <?php echo esc_html($question); ?>
			</span>
		  </label>
	  </div>
      <div class="ufaqsw_answers_style1 ufaqsw-toggle-inner-default_<?php echo esc_attr(get_the_ID()); ?> ufaqsw_faq_answer_src">
         <?php echo apply_filters('the_content', $answer); ?>
      </div>
   </div>
   
   <?php 
		$c++;
		endforeach;
		/*
		* This is the end of the loop.
		*/
	?>

</div>