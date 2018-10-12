/**
 * TABLE OF CONTENT
 *
 * 0 -> Shared modal
 *
 * 1 -> Init navbar
 *
 * 2- > prevent default
 *
 * 3 -> Social Links
 *
 * 4 -> Contact info
 *
 * 5 -> About us
 */

$(function () {

    //0 -> Shared modal
    var sharedModal = new ModalManager('sharedModal', 'invisibleBtn');
    //!0 -> Shared modal

    //1 -> INIT navbar
    function initNavbar() {
        var navbar = $('.navbar');

        var widthBreakpoint = 991;

        $(window).scroll(function () {
            configNavbarSize();
        });

        function configNavbarSize() {
            if ($(document).scrollTop() > 70 && $(document).width() > widthBreakpoint) {
                navbar.addClass('shrink');
            } else {
                navbar.removeClass('shrink');
            }
        }
    }
    initNavbar();
    // !Navbar fixed or normal position

    //2 -> Prevent Default
    $(function () {
        $('.prevent-default').on('click', function (e) {
            e.preventDefault();
        });
    });
    //! Prevent default

    /*3 -> Social Links*/
    function initSocialLinks() {
        var socialLinks = $('.social-link-edit');
        socialLinks.on('click', function (e) {
            var linkId = $(this).attr('linkId');

            $.ajax({
                url: "/edit-social/" + linkId,
                success: function (data) {
                    sharedModal.addMainContent(data);
                    sharedModal.showModal();
                }
            });
        });
    }
    initSocialLinks();
    /*!Social Links*/

    /*4 -> Contact info*/
    function initContactInfo() {
        $('#edit-contact-info').on('click', function (e) {
            var self = $(this);
            $.ajax({
                url: "/address-phone-mail",
                success: function (data) {
                    self.parent().html(data);
                }
            });
        });
    }
    initContactInfo();
    /* ! Contact Info*/

    /*5 -> About Us*/

    function initAboutUs() {
        $('#edit-about-us').on('click', function (e) {
            var self = $(this);
            $.ajax({
                url: "/edit-about",
                success:function (data) {
                    self.parent().parent().html(data);
                }
            });
        });
    }
    initAboutUs();
    /*!5 -> About Us*/
});