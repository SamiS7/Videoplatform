$(() => {

    let urlP = new URLSearchParams(window.location.search);
    let c = {
        'channel': urlP.get('c')

    };

    $('.abo-title').text(c.channel + ' |');

    $('#follow').click(() => {
        let data = {
            'following': {
                'channel': c.channel
            }
        }
        data.following = JSON.stringify(data.following);
        $.post('../php/kanal.php', data, checkAbo);
    });

    $.post('../php/profileAndCover.php', { 'checkProfileImg': c.channel }, data => {
        $('#profilPic').attr('src', `../img/profileImg/${data}`);
    });

    $.post('../php/profileAndCover.php', { 'checkCoverImg': c.channel }, data => {
        $('.cover-box').css('background-image', `url('../img/coverImg/${data}')`);
    });

    $.post('../php/kanal.php', { 'videos': c.channel }, data => {
        data = JSON.parse(data);
        if (data.success && data.data.length > 0) {
            let html = '';
            data.data.forEach(v => {
                html += `<a href="./pVideo.html?v=${v.id}">
                    <img loading="lazy" src="../img/thumb/${v.poster}">
                    <h5 class="title" title="${v.title}">${v.title}</h5>
                    </a>`;
            });
            $('#videosOF').html(html);
        } else {
            $('#videosOF').html('<p>' + c.channel + ' hat kein Video.');
        }
    });

    checkAbo();

    function checkAbo() {
        let data = {
            'followAndAboCounter': {
                'channel': c.channel
            }
        };
        data.followAndAboCounter = JSON.stringify(data.followAndAboCounter);
        $.post('../php/kanal.php', data, check);

        function check(data) {
            data = JSON.parse(data);
            $('#aboCount').text(`\u00A0${data.abos} Abos`);
            if (data.logedIn) {
                if (data.following) {
                    $('#follow').text('Folge ich');
                } else {
                    $('#follow').text('Folgen');
                }
            } else {
                $('#follow').text('Nicht angemeldet!');
            }

        }
    }
});