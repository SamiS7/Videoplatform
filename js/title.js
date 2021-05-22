changeTitleW();

window.addEventListener("resize", () => {
    changeTitleW()
});
function changeTitleW() {
    $(() => {
        let title = $('.title');

            let w = $($(title).siblings()).css('width');
            $(title).css('width', w);
    });
}

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