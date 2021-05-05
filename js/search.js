$(() => {
    $('.fa-search').click(() => {
        if ($('.search-input').css('display') != 'block') {
            $('.search-input').css('display', 'block');
        } else {
            let sVal = $('.search-input').val();
            if (sVal != '') {
                window.location = `../html/videos.html?sStr=${sVal}`;
            }
        }
    });
});