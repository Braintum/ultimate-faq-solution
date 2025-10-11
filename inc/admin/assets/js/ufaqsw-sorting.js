jQuery(function($) {
    'use strict';
    var $tbody = $('#the-list');
    $tbody.sortable({
        handle: '.ufaqsw-sort-handle',
        items: 'tr',
        cursor: 'move',
        axis: 'y',
        update: function(event, ui) {
            var order = [];
            $tbody.find('tr').each(function() {
                order.push($(this).attr('id').replace('post-', ''));
            });
            $.post(ufaqswSorting.ajax_url, {
                action: 'ufaqsw_sort',
                order: order,
                nonce: ufaqswSorting.nonce
            });
        },
        helper: function(e, tr) {
            var $originals = tr.children();
            var $helper = tr.clone();
            $helper.children().each(function(index) {
                $(this).width($originals.eq(index).width());
            });
            return $helper;
        },
        start: function(e, ui) {
            ui.item.addClass('ufaqsw-row-dragging');
        },
        stop: function(e, ui) {
            ui.item.removeClass('ufaqsw-row-dragging');
        }
    });
});
