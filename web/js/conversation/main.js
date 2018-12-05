$.fn.scrollBottom = function(scroll){
    if(typeof scroll === 'number'){
        window.scrollTo(0,$(document).height() - $(window).height() - scroll);
        return $(document).height() - $(window).height() - scroll;
    } else {
        return $(document).height() - $(window).height() - $(window).scrollTop();
    }
};

function updateChat() {
    let lastId = $('.dialog-messages div[data-key]:last').data('key');
    $.ajax({
        url: window.actionUrl.addUrlParam('lastId', lastId)
    }).done(function (isChanged) {
        if (isChanged) {
            $.pjax.reload({container: '#messages', async:false});
            $.pjax.reload({container: '#conversations', async:false});
        }
    });
}


jQuery(function () {
    setInterval(updateChat, 3000);

    var messages = $('#messages');

    function scrollDown() {
        var list = messages.parents('.dialog-messages');
        list.scrollTop(list[0].scrollHeight);
    }
    scrollDown();

    messages.on('pjax:end', scrollDown);
    $('#new_message').on('pjax:end', function () {
        $.pjax.reload({container: '#messages', async:false});
        $.pjax.reload({container: '#conversations', async:false});
    });


    $('#send_btn').on('click', function(){
       var btn = $(this);
       var i = btn.find('i');
       i.addClass('glyphicon-time').removeClass('glyphicon-send');
       setTimeout(function () {
           btn.prop('disabled', true);
       });
    })
});