$(() => {
    $('.dropDown').mouseover(() => {
        $('.dropDownContent').css('display', 'grid');
    });
    $('.dropDown').mouseleave(() => {
        $('.dropDownContent').css('display', 'none');
    });
    $('.dropDown').click(() => {
        $('.dropDownContent').toggle();
    });
});