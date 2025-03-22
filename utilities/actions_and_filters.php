<?php
if ( ! defined( 'ABSPATH' ) ) exit;


// Title filter callback function
function ufaqsw_the_title($title){
	return $title;
}
add_filter( 'ufaqsw_the_title', 'ufaqsw_the_title');



// Simplify variables filter callback function
function ufaqsw_simplify_variables( array $data ) {
	$formated_data = array();
	$formated_data['question'] = (isset($data['ufaqsw_faq_question'])?apply_filters('ufaqsw_the_title', $data['ufaqsw_faq_question']):'');
	$formated_data['answer'] = (isset($data['ufaqsw_faq_answer'])?apply_filters('the_content', $data['ufaqsw_faq_answer']):'');
	return $formated_data;
}
add_filter( 'ufaqsw_simplify_variables', 'ufaqsw_simplify_variables');

// Simplify design configuration variables filter callback function
function ufaqsw_simplify_configuration_variables( $id ) {
	
	$title_color = get_post_meta( $id, 'ufaqsw_title_color');
	$title_font_size = get_post_meta( $id, 'ufaqsw_title_font_size');
	$question_color = get_post_meta( $id, 'ufaqsw_question_color');
	$answer_color = get_post_meta( $id, 'ufaqsw_answer_color');
	$question_background_color = get_post_meta( $id, 'ufaqsw_question_background_color');
	$answer_background_color = get_post_meta( $id, 'ufaqsw_answer_background_color');
	$question_font_size = get_post_meta( $id, 'ufaqsw_question_font_size');
	$answer_font_size = get_post_meta( $id, 'ufaqsw_answer_font_size');
	$template = get_post_meta( $id, 'ufaqsw_template');
	$showall = get_post_meta( $id, 'ufaqsw_answer_showall');
	$hidetitle = get_post_meta( $id, 'ufaqsw_hide_title');
	$normal_icon = get_post_meta( $id, 'ufaqsw_normal_icon');
	$active_icon = get_post_meta( $id, 'ufaqsw_active_icon');
	$behaviour = get_post_meta( $id, 'ufaqsw_faq_behaviour');
	
	$formated_data = array();
	$formated_data['title_color'] = (isset($title_color[0])?$title_color[0]:'');
	$formated_data['title_font_size'] = (isset($title_font_size[0])?$title_font_size[0]:'');
	$formated_data['question_color'] = (isset($question_color[0])?$question_color[0]:'');
	$formated_data['answer_color'] = (isset($answer_color[0])?$answer_color[0]:'');
	$formated_data['question_background_color'] = (isset($question_background_color[0])?$question_background_color[0]:'');
	$formated_data['answer_background_color'] = (isset($answer_background_color[0])?$answer_background_color[0]:'');
	$formated_data['question_font_size'] = (isset($question_font_size[0])?$question_font_size[0]:'');
	$formated_data['answer_font_size'] = (isset($answer_font_size[0])?$answer_font_size[0]:'');
	$formated_data['template'] = (isset($template[0])?$template[0]:'default');
	$formated_data['showall'] = (isset($showall[0])&&$showall[0]=='on'?1:0);
	$formated_data['hidetitle'] = (isset($hidetitle[0])&&$hidetitle[0]=='on'?1:0);
	$formated_data['normal_icon'] = (isset($normal_icon[0])?$normal_icon[0]:'');
	$formated_data['active_icon'] = (isset($active_icon[0])?$active_icon[0]:'');
	$formated_data['behaviour'] = (isset($behaviour[0])?$behaviour[0]:'toggle');
	return $formated_data;
}
add_filter( 'ufaqsw_simplify_configuration_variables', 'ufaqsw_simplify_configuration_variables');

// Simplify design configuration variables filter callback function
function ufaqsw_simplify_faqs( $faqs ) {
	if(isset($faqs[0]) && !empty($faqs[0])){
		return $faqs[0];
	}else{
		return array();
	}	
}
add_filter( 'ufaqsw_simplify_faqs', 'ufaqsw_simplify_faqs');

// Template filter callback function
function ufaqsw_template_filter( $template ) {
	return $template;
}
add_filter( 'ufaqsw_template_filter', 'ufaqsw_template_filter');

function ufaqsw_wses_allowed_menu_html() {
	return array (
		'a' => array (
			'href' => array(),
			'class' => array(),
			'target'=> array(),
		),
		'b' => array(
			'title' => array()
		),
		'p' => array('title' => array()),
		'u' => array('title' => array()),
		'i' => array('title' => array()),
		'span'=> array(),
		'br'=> array()
	);
}

function ufaqsw_wpkses_post_tags( $tags, $context ) {
	if ( 'post' === $context ) {
		$tags['iframe'] = array(
			'src'             => true,
			'height'          => true,
			'width'           => true,
			'frameborder'     => true,
			'allowfullscreen' => true,
		);
	}

	return $tags;
}

add_filter( 'wp_kses_allowed_html', 'ufaqsw_wpkses_post_tags', 10, 2 );