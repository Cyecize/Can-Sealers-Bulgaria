var ImagePreviewManager = (function () {

    var imgPrev = null;

    function attachEvent(inputId, imgSrcId) {
        imgPrev = $(document.getElementById(imgSrcId));
        document.getElementById(inputId).onchange = function (event) {
            console.log("image selected!");
            readUrl(this);
        };
    }

    function readUrl(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                imgPrev.attr('src', e.target['result']);
            };
            reader.readAsDataURL(input.files[0]);
            imgPrev.show();
        }
    }

    return {attachEvent: attachEvent};
})();
