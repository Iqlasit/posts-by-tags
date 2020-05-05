(function($) {
    'use strict';
var tag_ids = [];
// Posts by tags
var singleTagItem = $(".iqlasit-tag-list .single-tag");
singleTagItem.on('click', function (e) {
    e.preventDefault();
    var tagId = $(this).data('tagid');
        tag_ids.push( tagId );

    var data = {
        action: "iqlasit_tag_info",
        tag_ids: tag_ids,
    };

    var postsWrapper = '.iqlasit-posts-by-tags-wrapper';

    $.ajax({
        url: iqlasit_pbtgs.ajax_url,
        cache: false,
        data: data,
        beforeSend: function(){
            $(postsWrapper).addClass('loading');
        },
        success: function(response){
            
            $(postsWrapper).empty();

            if ( response.length !== 0 ) {
                for(var i = 0; i < response.length; i++ ){
                    var html = `
                        <div class="iqlasit-single-post-item">
                            <h1>${response[i].title}</h1>
                            <p>${response[i].content}</p>
                        </div> <!-- end single-post-item -->
                    `
                    $(postsWrapper).removeClass('loading');
                    $(postsWrapper).append(html);
                }
            } else {
                var html = `
                    <div class="text-center col-sm-12">    
                        <h1 class="color-red">Sorry, No post found!</h1>
                    </div>
                `
                $(postsWrapper).append(html);
            }
        },
        complete: function(){
            $(postsWrapper).removeClass('loading');
        },
        error: function() {
            $(postsWrapper).empty();
            $(postsWrapper).removeClass('loading');
            
            var html = `
                <div class="text-center col-sm-12">    
                    <h1 class="color-red">Error Occurred!</h1>
                </div>
                `
            $(postsWrapper).append(html);
        }
    });

    // });
});

})(jQuery);