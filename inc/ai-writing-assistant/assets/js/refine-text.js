jQuery(function ($) {
  // Toggle dropdown visibility
  $(document).on('click', '.chatgpt-refine-dropdown button', function (e) {
    e.stopPropagation();
    $(this).siblings('ul').toggle();
  });

  // Hide dropdown when clicking outside
  $(document).on('click', function (e) {
    $('.chatgpt-refine-dropdown ul').hide();
  });

  // Prevent dropdown from closing when clicking inside the ul
  $(document).on('click', '.chatgpt-refine-dropdown ul', function (e) {
    e.stopPropagation();
  });

  // Handle action click
  $(document).on('click', '.chatgpt-refine-dropdown ul li', function (e) {
    e.preventDefault();

    const $link = $(this);
    const instruction = $link.data('action');
    const $wrapper = $link.closest('.chatgpt-refine-dropdown');
    const targetId = $wrapper.data('target');
    const $field = $('#' + targetId);
    const originalText = $field.val();

    if (!originalText) return;

    $wrapper.find('ul').hide();

    jQuery("#" + targetId).LoadingOverlay("show")

    $.post(RefinerData.ajax_url, {
      action: 'refine_text',
      content: originalText,
      cmd: instruction,
      nonce: RefinerData.nonce,
    }, function (response) {
      if (response.success) {
        $field.val(response.data).trigger('change');
      } else {
        alert('Error: ' + response.data);
      }
      jQuery("#" + targetId).LoadingOverlay("hide");
    });
  });
});
