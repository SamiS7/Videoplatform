$(() => {
    let urlP = new URLSearchParams(window.location.search);
    let video = urlP.get('v');
    let alert = false;

    $.post('../php/checkVideo.php', { 'uploadVideo': video }, data => {
        data = JSON.parse(data);
        if (data.exists) {
            let more = data.more;
            data = data.data;
            $('.pVideo-box').html(`
            <div>
                <video id="video" controls poster="../img/thumb/${data.poster}">
                    <source src="../videos/${data.vName}">
                </video>
                <p id="vTitle" title="${data.title}">${data.title}</p>
            </div>
            <div class="likeAbo-box">
                <div class="like">
                    <i id="likeB" class="far fa-heart"></i>
                    <i id="unlikeB" class="fas fa-heart" style="display: none;"></i>
                </div>
                <div class="abo-box">
                    <a href="./kanal.html?c=${data.kName}">
                        <img src="../img/profileImg/${data.pname}">
                        <h5 class="abo-title" title="${data.kName}">${data.kName}</h5>
                    </a>
                </div>
            </div>`);

            let title = '';
            if (more.moreVideos.length < 3) {
                title = 'Andere Videos';
                $('#moreFrom').html(getHTML(more.moreVideos) + getHTML(more.famVideos));
            } else {
                title = 'Andere Videos von ' + data.kName;
                $('#moreFrom').html(getHTML(more.moreVideos));
            }

            function getHTML(arr) {
                let html = '';
                arr.forEach(v => {
                    html += `
                    <a href="./pVideo.html?v=${v.id}">
                        <img src="../img/thumb/${v.poster}">
                        <h5 class="title" title="${v.title}">${v.title}</h5>
                    </a>`
                });
                return html
            }

            $('#moreFromHL').html(`<h4>${title}</h4>`);

            ['#likeB', '#unlikeB'].forEach(e => {
                $(e).click(() => {
                    likeAndCheck();
                    if (!alert) {
                        alert = true;
                    }
                });
            });

        } else {

        }
    });

    function likeAndCheck() {
        $.post('../php/checkVideo.php', { 'like': video }, data => {
            data = JSON.parse(data);
            displayLikeB(data.likes);
        })
    }

    checkLike();
    function checkLike() {
        $.post('../php/checkVideo.php', { 'checkLike': video }, data => {
            data = JSON.parse(data);
            displayLikeB(data.likes);
        });
    }

    function displayLikeB(likes) {
        if (likes) {
            $('.like .far').hide();
            $('.like .fas').show();
        } else if (likes.length == 0 && alert) {
            $('#cover-box-msg').html('<div id="uploadMsgBox"><p class="uploadMsg">Sie sind nicht angemeldet!</p><p id="removeMsg">x</p>')
            $('#cover-box-msg').show();

            $('#removeMsg').click(event => {
                $(`#cover-box-msg`).hide();
                $('#uploadMsgBox').remove();
            });
        } else {
            $('.like .fas').hide();
            $('.like .far').show();
        }

    }

    $('#video').on('play', event => {
        console.log('yo');
        $.post('../php/checkVideo.php', { 'checkHistory': video });
    });
});