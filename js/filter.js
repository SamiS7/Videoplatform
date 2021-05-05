$(() => {
    let urlP = new URLSearchParams(window.location.search);
    $('.search-button').click(() => {
        let selectArr = [];
        selectArr.push($('#categorie'), $('#year'), $('#order'));

        for (s of selectArr) {
            if (s[0].selectedIndex != 0) {
                urlP.set(s[0].id, s[0].options[s[0].selectedIndex].innerText);
            }
        }
        window.location.search = urlP;
    });

    let searchWord = urlP.get('sStr');
    if (searchWord != '' && searchWord != null) {
        $('.lastSeen-box').remove();

        let arr = [];
        for (u of urlP) {
            if (u[0] != 'sStr') {
                arr.push(u);
            }
        }

        let search = {
            'searchData': {
                'filter': arr,
                'sStr': urlP.get('sStr')
            }
        }

        $.post('../php/search.php', search, check);

        function check(data) {
            data = JSON.parse(data);
            if (data.channels != undefined) {
                for (c of data.channels) {
                    $('#found #channels').append(`<div>
                    <a href="./kanal.html?c=${c}">
                        <img src="../img/pPic1.jpg">
                        <h5 class="abo-title">${c}</h5>
                    </a>
                </div>`);
                }
            }
            if (data.videos != undefined) {
                for (v of data.videos) {
                    $('#videos').append(`<a href="./pVideo.html?v=${v}">
                <img src="../img/placeholder1.jpg">
                <h5 class="title">${v}</h5>
            </a>`);
                }
            }
        }
    }
});