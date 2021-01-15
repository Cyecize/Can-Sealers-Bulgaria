var FacebookChatManager = (function (env, lang, greetingMessage) {
    var facebookSdkByLang = {
        bg: 'https://connect.facebook.net/bg_BG/sdk/xfbml.customerchat.js',
        en: 'https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js'
    }

    var facebookInstance = null;

    var facebookMessageButtonContainer = $('.facebook-message-button-container');
    var facebookMessageButton = $('.facebook-message-button');

    function injectFacebookSdk(d, s, id, lang) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s);
        js.id = id;
        js.src = facebookSdkByLang[lang];
        fjs.parentNode.insertBefore(js, fjs);
    }

    /**
     * @param lang currently selected language
     * @param callback returns FB object when it loads
     */
    function loadFacebookSdk(lang, callback) {
        window.fbAsyncInit = function () {
            facebookInstance = FB;
            facebookInstance.init({
                xfbml: false,
                version: 'v9.0'
            });

            facebookInstance.XFBML.parse(document, callback);
        };

        injectFacebookSdk(document, 'script', 'facebook-jssdk', lang);
    }

    function initEvents() {
        facebookMessageButton.on('click', function (eventArgs) {
            facebookInstance.CustomerChat.show(true);
        });
    }

    function init(env, lang, greetingMessage) {
        if (env !== 'prod') {
            console.warn('Facebook chat only works on prod!');
            return;
        }

        loadFacebookSdk(lang, function () {
            facebookInstance.CustomerChat.hideDialog();
            facebookInstance.CustomerChat.update({
                logged_in_greeting: greetingMessage,
                logged_out_greeting: greetingMessage,
                ref: 'ref_'
            });

            initEvents();
            facebookMessageButtonContainer.show();
        });
    }

    init(env, lang, greetingMessage);

    return {};
});

var facebookChatManager = new FacebookChatManager(ENV, SELECTED_LANGUAGE, CONTACT_US_MESSAGE);