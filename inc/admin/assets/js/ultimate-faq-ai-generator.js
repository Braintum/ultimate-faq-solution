jQuery(document).ready(function ($) {
    'use strict';

    $('#ufaq-ai-form').on('submit', function (e) {
        e.preventDefault();

        const data = {
            action: 'generate_faq_group_with_ai',
            security: UFAQ_AI.nonce,
            faq_title: $('input[name="faq_title"]').val(),
            faq_context: $('textarea[name="faq_context"]').val(),
            faq_count: $('input[name="faq_count"]').val()
        };

        $('#ufaq-ai-result').html(`<div id="ufaq-ai-loading" style="display:block; text-align:center; padding:20px;">
            <div style="display:inline-block; width:20px; height:20px; border:3px solid #f3f3f3; border-top:3px solid #3498db; border-radius:50%; animation:spin 1s linear infinite;"></div>
            <p style="margin-top:10px; color:#666;">Generating FAQs with AI...</p>
        </div>`);

        $('#ufaq-ai-form button[type="submit"]').prop('disabled', true);

        scrollThickboxToBottom();

        $.post(UFAQ_AI.ajax_url, data, function (response) {
            if (response.success) {
                $('#ufaq-ai-result').html(`<div id="ufaq-ai-success" style="display:block;">
                    <div style="background:#d4edda; border:1px solid #c3e6cb; color:#155724; padding:15px; border-radius:5px; margin-bottom:15px;">
                        <h4 style="margin:0 0 10px 0; display:flex; align-items:center;">
                            <span style="color:#28a745; margin-right:8px;">✓</span>
                            Success!
                        </h4>
                        <p id="ufaq-success-message" style="margin:0;"> ${response.data.message}</p>
                    </div>
                    <div style="text-align:center;">
                        <a id="ufaq-edit-link" href="${response.data.return_link.replace(/&amp;/g, '&')}" class="button button-primary" style="margin-right:10px;">Edit FAQ Group</a>
                        <button type="button" class="button" onclick="tb_remove();location.reload();">Close</button>
                    </div>
                </div>`);
                scrollThickboxToBottom();
            } else {
                $('#ufaq-ai-result').html('<p style="color:red;">' + response.data.message + '</p>');

                $('#ufaq-ai-result').html(`<div id="ufaq-ai-error" style="display:block;">
                    <div style="background:#f8d7da; border:1px solid #f5c6cb; color:#721c24; padding:15px; border-radius:5px; margin-bottom:15px;">
                        <h4 style="margin:0 0 10px 0; display:flex; align-items:center;">
                            <span style="color:#dc3545; margin-right:8px;">✗</span>
                            Error
                        </h4>
                        <p id="ufaq-error-message" style="margin:0;">${response.data.message}</p>
                    </div>
                    <div style="text-align:center;">
                        <button type="button" class="button button-primary" onclick="location.reload();">Try Again</button>
                    </div>
                </div>`);
                scrollThickboxToBottom();
            }
        });
    });

    function scrollThickboxToBottom() {
        // Target the Thickbox window content
        const $tb = $('#TB_ajaxContent');

        if ($tb.length) {
            $tb.animate({
                scrollTop: $tb.prop('scrollHeight')
            }, 300); // smooth scroll
        }
    }

});
