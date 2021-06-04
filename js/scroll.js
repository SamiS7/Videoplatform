$(() => {
    $('.scroll-button-to-right')[0].addEventListener('click', () => {
        scroll(0, $('.jump-box')[0], -1);
    });
    if ($('.scroll-button-to-right')[1] != undefined) {
        $('.scroll-button-to-right')[1].addEventListener('click', () => {
            scroll(1, $('.jump-box')[1], -1);
        });
    }

    $('.scroll-button-to-left')[0].addEventListener('click', () => {
        scroll(0, $('.jump-box')[0], 1);
    });
    if ($('.scroll-button-to-left')[1] != undefined) {
        $('.scroll-button-to-left')[1].addEventListener('click', () => {
            scroll(1, $('.jump-box')[1], 1);
        })
    }

    let winWBefore = null;
    window.addEventListener("resize", () => {
        let box = $('.jump-box');
        let bLen = box.length;
        let winWAfter = window.innerWidth;
        if (winWBefore == null) {
            winWBefore = winWAfter;
        }


        for (i = 0; i < bLen; i++) {
            let xPos = parseInt(translateX(box[i]));
            let boxWidth = parseInt($(box[i]).css('width'));

            wWDiff = winWAfter - winWBefore;

            if (wWDiff > 0 && xPos < (-1 * (boxWidth - winWAfter))) {
                if (wWDiff > 10) {
                    animatedScroll(box[i], xPos, xPos + wWDiff, 1);
                } else {
                    $(box[i]).css('transform', 'translate(' + (xPos + wWDiff) + 'px)');
                }
            } else if (wWDiff < 0) {
                if (xPos > (-1 * (boxWidth - winWAfter))) {
                    $('.scroll-button-to-right')[i].style = 'display: block;';
                }

            }
        }


        winWBefore = winWAfter;
    });

    function scroll(index, box, direction) {
        let xPosition = parseInt(translateX(box));
        let boxWidth = parseInt($(box).css('width'));
        let xBefore = xPosition;

        xPosition = xPosition + window.innerWidth * direction;
        if (xPosition < (-1 * (boxWidth - window.innerWidth))) {
            xPosition = (boxWidth - window.innerWidth) * direction;
            $('.scroll-button-to-right')[index].style = 'display: none;';
        } else {
            $('.scroll-button-to-right')[index].style = 'display: block;';
        }

        if (xPosition < 0) {
            $('.scroll-button-to-left')[index].style = 'display: block;';
        } else if (xPosition >= 0) {
            xPosition = 0;
            $('.scroll-button-to-left')[index].style = 'display: none;';
        }

        animatedScroll(box, xBefore, xPosition, direction);
    }

    function animatedScroll(box, xBefore, xAfter, dir) {
        let t = setInterval(() => {
            if (dir == -1 && xBefore <= xAfter) {
                clearInterval(t);
            } else if (dir == 1 && xBefore >= xAfter) {
                clearInterval(t);

            }
            $(box).css('transform', 'translate(' + xBefore + 'px)');
            xBefore += dir * 50;
        }, 20);
    }

    function translateX(box) {
        let transform = $(box).css('transform');
        return transform.split(',')[4];
    }
});