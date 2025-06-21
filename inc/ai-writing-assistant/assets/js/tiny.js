(function () {
    // Use a history map keyed by editor id
    var ufaqsw_ai_tools_history = {};
    var ufaqsw_ai_tools_history_index = {};

    tinymce.PluginManager.add('ufaqsw_ai_assistant_menu', function (editor) {
        var plugin_menu = [];
        var options = JSON.parse(ufaqsw_tiny_plugin_options);

        options.forEach(function (option) {
            plugin_menu.push({
                text: option.title,
                value: option.name,
                onclick: function () {

                    var editor_id = getEditorId(editor);

                    // Initialize history for this editor if not present
                    if (!ufaqsw_ai_tools_history[editor_id]) {
                        ufaqsw_ai_tools_history[editor_id] = [];
                        ufaqsw_ai_tools_history_index[editor_id] = -1;
                    }

                    jQuery("#" + editor_id).LoadingOverlay("show");
                    var data = {
                        action: 'refine_text',
                        nonce: ufaqsw_tiny_ajax_nonce,
                        cmd: option.name,
                        content: editor.getContent(),
                    };
                    jQuery.post(ajaxurl, data, function (response) {
                        if (response.success) {
                            // Save history for this editor only
                            ufaqsw_ai_tools_history[editor_id].push(editor.getContent());
                            ufaqsw_ai_tools_history_index[editor_id]++;
                            editor.setContent(response.data);
                        } else {
                            alert(response.data);
                        }
                        jQuery("#" + editor_id).LoadingOverlay("hide");
                    }, "json");
                }
            });
        });

        // Add Button to Visual Editor Toolbar        
        editor.addButton("ufaqsw_ai_assistant_menu", {
            text: ufaqsw_tiny_plugin_texts.ufaqsw_tiny_text_ai_tools,
            tooltip: ufaqsw_tiny_plugin_texts.ufaqsw_tiny_text_available_ai_tools,
            type: "menubutton",
            menu: plugin_menu
        });

        editor.addButton('ufaqsw_ai_assistant_undo', {
            title: ufaqsw_tiny_plugin_texts.ufaqsw_tiny_text_undo,
            image: ufaqsw_tiny_plugin_url + 'assets/images/undo_icon.png',
            cmd: 'ufaqsw_ai_assistant_undo',
        });

        // Add Command when Button Clicked
        editor.addCommand('ufaqsw_ai_assistant_undo', function () {

            var editor_id = getEditorId(editor);

            if (ufaqsw_ai_tools_history_index[editor_id] > -1) {
                editor.setContent(ufaqsw_ai_tools_history[editor_id].pop());
                ufaqsw_ai_tools_history_index[editor_id]--;
            }
        });
    });
})();

// Helper to get unique editor id
function getEditorId(editor) {
    var editor_id = "" ;
    if (typeof editor.bodyElement != "undefined") {                        
        editor_id = editor.bodyElement.id;                      
    }
    else if (typeof editor.id != "undefined") {
        editor_id = editor.iframeElement.id;                                         
    }
    return editor_id ;
}