var FacebookChatManager = (function (env, greetingMessage) {
    var facebookInstance = null;

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
            facebookInstance = FB;
            facebookInstance.init({
                xfbml: false,
                version: 'v9.0'
            });

            facebookInstance.XFBML.parse(document, callback);
        };

        injectFacebookSdk(document, 'script', 'facebook-jssdk');
    }

    function initEvents() {
        facebookMessageButton.on('click', function (eventArgs) {
            facebookInstance.CustomerChat.show(true);
        });
    }

    function init(env, greetingMessage) {
        if (env !== 'prod') {
            console.warn('Facebook chat only works on prod!');
            return;
        }

        loadFacebookSdk(function () {
            facebookInstance.CustomerChat.hideDialog();
            facebookInstance.CustomerChat.update({
                logged_in_greeting: greetingMessage,
                logged_out_greeting: greetingMessage,
            });

            initEvents();
            facebookMessageButtonContainer.show();
        });
    }

    init(env, greetingMessage);

    return {};
});

var facebookChatManager = new FacebookChatManager(ENV, CONTACT_US_MESSAGE);