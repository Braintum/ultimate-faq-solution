jQuery(document).ready(function($) {
	'use strict';
	
	$('.ufaqsw_admin_faq_shorcode_copy').on('click', function(){
		  $(this).select();		  
		  document.execCommand("copy");
	})

});