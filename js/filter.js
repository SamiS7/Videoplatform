$(() => {
    $.post('../php/search.php', { "catags": null }, data => {
        data = JSON.parse(data);
        $('#categorie').append(``);
        $('#categorie').html(`<option value="" disabled selected>Genre</option>`);
        data.forEach(d => {
            $('#categorie').append(`<option value="${d}">${d}</option>`);
        });
    });

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
    // if (searchWord != '' && searchWord != null) {
    $('.lastSeen-box').remove();

    let filterArr = {};
    for (k of urlP.keys()) {
        if (k != 'sStr') {
            filterArr[k] = urlP.get(k);
        }
    }

    let search = {
        'searchData': {
            'filter': filterArr,
            'sStr': urlP.get('sStr')
        }
    }

    $.post('../php/search.php', search, check);

    function check(data) {
        data = JSON.parse(data);
        if (data.channels != undefined) {
            for (c of data.channels) {
                $('#found #channels').append(`<div>
                    <a href="./kanal.html?c=${c.name}">
                        <img src="../img/profileimg/${c.pname}">
                        <h5 class="abo-title" title="${c.name}">${c.name}</h5>
                    </a>
                </div>`);
            }
        }
        if (data.videos != undefined) {
            for (v of data.videos) {
                $('#found #videos').append(`<a href="./pVideo.html?v=${v.id}">
                <img src="../img/thumb/${v.poster}">
                <h5 class="title" title="${v.title}">${v.title}</h5>
            </a>`);
            }
        }
    }
    // }
});