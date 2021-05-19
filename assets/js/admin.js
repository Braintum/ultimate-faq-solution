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
	
	//Settings tab scripts

	$('.sld_click_handle').on('click', function(e){
		
		var obj = $(this);
		var container_id = obj.attr('href');
		$('.sld_click_handle').each(function(){
			$(this).removeClass('nav-tab-active');
			$($(this).attr('href')).hide();
		})
		obj.addClass('nav-tab-active');
		$(container_id).show();
		setTimeout(function() {
			window.scrollTo(0, 0);
		  }, 1);
	})
	

	
	var hash = window.location.hash;
	
	if(hash!=''){
		$('.sld_click_handle').each(function(){
			
			$($(this).attr('href')).hide();
			if($(this).attr('href')==hash){
				$(this).removeClass('nav-tab-active').addClass('nav-tab-active');
			}else{
				$(this).removeClass('nav-tab-active');
			}
		})
		$(hash).show();
	}
	

	

});

function showfamodal(data){
	
	document.getElementById('ufaqsw-fa-field-modal').style.display = 'block';
	document.getElementById('ufaqsw-fa-field-modal').setAttribute("data", data.id);
	//jQuery.('#fa-field-modal').show();
}