changeTitleW();

window.addEventListener("resize", () => {
    changeTitleW()
});
function changeTitleW() {
    $(() => {
        let titles = $('.title');

        for (t of titles) {
            let w = $($(t).siblings()).css('width');
            $(t).css('width', w);
        }
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