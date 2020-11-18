+function ($) { "use strict";
    $(document).ready(function() {
        $(document).on('change', 'select.custom-select[name="files"]', function(ev) {
            var $form = $(this).closest('form')
            var language = $form.find('input[name="language"]').val();
            var file = $(this).find('option:selected').text()

            $form.find('input[name="language_file"]').val(file);
            $form.request('onLanguageGetStrings', {original_language:language, language_file:file}).done(function (data) {
                $.oc.builder.indexController.entityControllers.localization.updateLanguageFromServerDone($form, data)
            })
        });
    })
}(window.jQuery);
