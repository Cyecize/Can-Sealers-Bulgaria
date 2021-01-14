var FacebookChatManager = (function () {
    var facebookInstance = null;
    var isInit = false;

    var facebookMessageButtonContainer = $('.facebook-message-button-container');
    var facebookMessageButton = $('.facebook-message-button');

    function injectFacebookSdk(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s);
        js.id = id;
        js.src = 'https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js';
        fjs.parentNode.insertBefore(js, fjs);
    }

    /**
     * @param callback returns FB object when it loads
     */
    function loadFacebookSdk(callback) {
        window.fbAsyncInit = function () {
            FB.init({
                xfbml: true,
                version: 'v9.0'
            });

            callback(FB);
        };
        injectFacebookSdk(document, 'script', 'facebook-jssdk');
    }

    function initEvents() {
        facebookMessageButton.on('click', function (eventArgs) {
            FB.CustomerChat.show();
        });
    }

    function init(env) {
        if (env !== 'prod') {
            console.warn('Facebook chat only works on prod!');
            return;
        }

        loadFacebookSdk(function(fb) {
            facebookInstance = fb;
            isInit = true;

            facebookMessageButtonContainer.show();
            initEvents();
        });
    }

    return {
        init: init
    };
});