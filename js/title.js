changeTitleW();

window.addEventListener("resize", () => {
    changeTitleW()
});
let s;
function changeTitleW() {
    $(() => {
        let title = $('.title');
        let w;
        for (s of $(title).siblings()) {
            w = s.clientWidth;
            if (w > 0) {
                break;
            }
        }
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