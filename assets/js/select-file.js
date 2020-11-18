+function ($) { "use strict";
    $(document).ready(function() {
        $(document).on('change', 'select.custom-select[name="files"]', function(ev) {
            var $form = $(this).closest('form')
            var language = $form.find('input[name="language"]').val()
            var file = $(this).val() ? $(this).find('option:selected').text() : null

            $form.find('input[name="language_file"]').val(file)
            $form.request('onLanguageGetStrings', {original_language:language, language_file:file}).always(function (data) {
                $.oc.builder.indexController.entityControllers.localization.updateLanguageFromServerDone($form, data)
            })
        });
    })
}(window.jQuery);
