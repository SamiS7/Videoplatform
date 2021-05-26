$(() => {
    $('.fa-search').click(() => {
        if ($('.search-input').css('display') != 'block') {
            $('.search-input').css('display', 'block');

            $('body').on('keydown', event => {
                if (event.keyCode == 13) {
                    $('.fa-search').click();
                }
            });
        } else {
            let sVal = $('.search-input').val();
            if (sVal != '') {
                window.location = `../html/videos.html?sStr=${sVal}`;
            }
        }
        $('.search-input').focus();
    });
});