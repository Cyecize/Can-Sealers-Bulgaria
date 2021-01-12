$(function () {
   function initGalleryLayout(){
       var IMAGE_HIGHLIGHT = $('#productHighlightedPicture');
       var ITEMS = $('.gallery-item');
       initGallery();

       ITEMS.find('img').on('click',function () {
           clearGallery();
           IMAGE_HIGHLIGHT.hide();
           IMAGE_HIGHLIGHT.attr('src', $(this).attr('src'));
           IMAGE_HIGHLIGHT.attr('alt', $(this).attr('alt'));
           IMAGE_HIGHLIGHT.fadeIn(400);
           initGallery();
       });

       function initGallery() {
           ITEMS.each(function (i, el) {
               var img = $(el).find('img:first');
               if(img.attr('src') === IMAGE_HIGHLIGHT.attr('src')){
                   img.parent().addClass('active-image');
               }
           });
       }

       function clearGallery() {
           ITEMS.each(function (i, el) {
               $(el).removeClass('active-image');
           });
       }
   }

    initGalleryLayout();
});