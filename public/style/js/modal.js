var ModalManager = (function (id, openBtnId) {

    var modal;

    var openBtn;

    function initModal(id, openBtnId) {
        modal = document.getElementById(id);
        openBtn = document.getElementById(openBtnId);
        initEvents(modal);
    }

    function showModal() {
        modal.style.display = "block";
    }

    function hideModal() {
        modal.style.display = "none";
    }

    function isModalOpen() {
        return modal.style.display == "block";
    }

    function addMainContent(content) {
        $(modal).find('.modal-main-content:first').html(content);
    }

    //private
    function initEvents(thisModal) {
        var thisModalId = $(thisModal).attr('id');

        $(openBtn).on('click', function () {
            showModal();
        });

        $(thisModal).find('.close-modal').on('click', function () {
            hideModal();
        });

        $('#' + thisModalId).on('click', '.btn-close-modal', function () {
            hideModal();
        });

        window.addEventListener('click', function (event) {
            if (event.target == thisModal) {
                hideModal();
            }
        });

        $(document).keyup(function (e) {
            if (e.keyCode == 27) { // escape key maps to keycode `27`
                hideModal();
            }
        });
    }

    function querySelector(selector) {
        return modal.querySelector(selector);
    }

    function attachEventToElement(selector, eventType, listener) {
        var element = querySelector(selector);
        if (element) {
            element.addEventListener(eventType, listener);
        }
    }

    //constructor
    initModal(id, openBtnId);

    return {
        initModal: initModal,
        showModal: showModal,
        hideModal: hideModal,
        isModalOpen: isModalOpen,
        addMainContent: addMainContent,
        querySelector: querySelector,
        attachEventToElement: attachEventToElement,
    };

});