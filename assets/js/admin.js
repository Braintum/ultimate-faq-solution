jQuery(document).ready(function($) {
	'use strict';
	

	
	$(document).on('click', '#ufaqsw_normal_icon',  function(e){
		e.preventDefault();
		$('#ufaqsw-fa-field-modal').show();
		$("#ufaqsw-fa-field-modal").attr("data", this.id);
	})
	
	$(document).on('click', '#ufaqsw_active_icon',  function(e){
		e.preventDefault();
		$('#ufaqsw-fa-field-modal').show();
		$("#ufaqsw-fa-field-modal").attr("data", this.id);
	});

	$( '.ufaqsw-fa-field-modal-close' ).on( 'click', function() {
		$('#ufaqsw-fa-field-modal').removeAttr("data");
		$('#ufaqsw-fa-field-modal').hide();

	});

	$( '.ufaqsw-fa-field-modal-icon-holder' ).on( 'click', function(e) {
		e.preventDefault();
		var fieldid = $("#ufaqsw-fa-field-modal").attr("data");
		var icon_class = $(this).attr("data-icon");
		$('#'+fieldid).val(icon_class);
		$('.ufaqsw-fa-field-modal-close').trigger("click");
		
	});
	
	$('.ufaqsw_clear_icon_field').on('click', function(e){
		e.preventDefault();
		var fieldid = $("#ufaqsw-fa-field-modal").attr("data");
		$('#'+fieldid).val('');
		$('.ufaqsw-fa-field-modal-close').trigger("click");
	})

	$("#ufaqsw_id_search").quicksearch("div.ufaqsw-fa-field-modal-icons div.ufaqsw_fa_section div.ufaqsw-fa-field-modal-icon-holder", {
		noResults: '#noresults',
		stripeRows: ['odd', 'even'],
		loader: 'span.ufaqsw-loading',
		minValLength: 2
	});
	
	$("#ufaqsw_id_search").quicksearch("div.ufaqsw-fa-field-modal-icons div.ufaqsw_fa_section", {
		noResults: '#noresults',
		stripeRows: ['odd', 'even'],
		loader: 'span.ufaqsw-loading',
		minValLength: 2
	});
	
	$('.ufaqsw_admin_faq_shorcode_copy').on('click', function(){
		  $(this).select();		  
		  document.execCommand("copy");
	})

});