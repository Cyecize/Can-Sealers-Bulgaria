//editing content by Admin
$(function () {
    var characteristicsBoxes = $('.characteristic-edit');
    characteristicsBoxes.on('click', function (e) {
        var charId = $(this).attr('charId');
        var self = $(this);
        $.ajax({
            url: "/characteristic/" + charId,
            success: function (data) {
                self.parent().parent().html(data);
            }
        })
    });
});

$(function () {
    var visible = "rgba(60,62,74,0.9)";
    var transparent = "rgba(0,0,0,0.0)";
    var navbar = $('.navbar');

    function modifyHeaderColor() {
        if (document.body.clientWidth > 768) {
            if (window.pageYOffset > 95)
                navbar.css('background', visible);
            else
                navbar.css('background', transparent);
        }
        else
            navbar.css('background', visible);

    }

    navbar.css('background', transparent);
    $(window).on('scroll', function () {
        modifyHeaderColor();
    });

    $(window).on('resize', function () {
        modifyHeaderColor();
    });

    modifyHeaderColor();
});



