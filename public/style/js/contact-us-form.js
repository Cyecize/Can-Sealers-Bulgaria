var ContactUsFormManager = (function () {

    /**
     *
     * @param docRoot - document or ModalManager
     * @param clickEventSupplier - supplier function for click event of send button
     */
    function initContactUsFormSpinner(docRoot, clickEventSupplier) {
        //Show spinner on submit and make so user can only submit the message once.
        var sendMsgForm = docRoot.querySelector('.send-msg-form');
        var msgSentSpinner = $(docRoot.querySelector('.message-sent-spinner'));
        var isMessageSentOnce = false;

        clickEventSupplier('.btn-send-message', function (eventArgs) {
            if (!sendMsgForm.checkValidity()) {
                return;
            }

            if (isMessageSentOnce) {
                eventArgs.preventDefault();
            }

            isMessageSentOnce = true;
            msgSentSpinner.parent().show();
        });
    }


    return {
        initContactUsFormSpinnerStandard: function () {
            initContactUsFormSpinner(document, function (selector, callback) {
                $(selector).on('click', callback);
            });
        },
        initContactUsFormSpinnerModal: function (modalManager) {
            initContactUsFormSpinner(modalManager, function(selector, callback) {
                modalManager.attachEventToElement(selector, 'click', callback);
            });
        }
    };
});