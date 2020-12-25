$(function () {

    /**
     * Remove placeholder on focus and bring it back on blur
     */
    function inputFieldsPlaceHolder() {
        var fieldIdPlaceholderMap = {};
        var allFieldsForThePage = $('input');

        var getFieldPlaceholder = function (fieldId) {
            if (fieldIdPlaceholderMap[fieldId]) {
                return fieldIdPlaceholderMap[fieldId];
            }

            return null;
        };

        var cacheFieldPlaceholder = function (fieldId, placeHolder) {
            if (fieldIdPlaceholderMap[fieldId]) {
                return;
            }

            fieldIdPlaceholderMap[fieldId] = placeHolder;
        };

        var getPlaceholder = function (field) {
            return $(field).attr('placeholder');
        };

        var setFieldPlaceholder = function (field, placeholer) {
            $(field).attr('placeholder', placeholer);
        };

        allFieldsForThePage.on('focus', function (e) {
            var inputId = $(this).attr('id');
            if (!inputId) {
                return;
            }
            cacheFieldPlaceholder(inputId, getPlaceholder(this));
            setFieldPlaceholder(this, '');
        });

        allFieldsForThePage.on('blur', function (e) {
            var inputId = $(this).attr('id');
            if (!inputId) {
                return;
            }
            setFieldPlaceholder(this, getFieldPlaceholder(inputId));
        });
    }

    inputFieldsPlaceHolder();
});