let chosenButton;
let urlP = new URLSearchParams(window.location.search);
$(() => {
    $('.nav-ellipsis').click(() => {
        if ($('.nav').css('display') == 'none') {
            $('.nav').css('display', 'grid');
            $('.content').css('grid-template-columns', '90px 1fr');
        } else {
            $('.nav').css('display', 'none');
            $('.content').css('grid-template-columns', '1% 1fr');
        }
    });

    $('.cover-ellipsis').click(() => {
        $('#edit-options').toggle();
        $('#clickedOption').html('');
        $('#options').show();
    });

    $('#changeName').click(() => {
        $('#options').hide();
        if (true) {
            $('#clickedOption').html('<input type="text" placeholder="Neuer Name" id="newName"><button id="newNameB">Ändern</button><div id="nameMsg"></div>');

            $('#newNameB').click(() => {
                let nName = $('#newName').val();

                if (nName.length >= 3 && nName.length <= 20) {

                    $.post('../php/kanal.php', { 'changedName': { 'name': nName } }, (data) => {
                        data = JSON.parse(data);
                        if (data.success) {
                            $('.kontoName').text(`${nName} |`);
                            $('#clickedOption').html('');
                            $('#clickedOption').append(`<p>${data.msg}</p>`);
                            $('#account').attr({ "href": `./kanal.html?c=${nName}` });
                            $('#account').text(`Angemeldet als ${nName}`);
                            setTimeout(() => {
                                $('#clickedOption').html('');
                                $('#options').show();

                            }, 3000);

                        } else {
                            $('#clickedOption #nameMsg').html(`<p>${data.msg}</p>`);
                        }
                    });



                } else {
                    $('#clickedOption #nameMsg').html('<p>Ungultiger Name!</p>');
                }
            });
        } else {

        }
    });

    $('#changeCPic').click(() => {
        $('#options').append('<input id="coverPic-inp" type="file" accept=".jpg, .jpeg, .png" style="display: none;">');
        $('#coverPic-inp').trigger('click');

        let input = $('#coverPic-inp');
        input.on('change', e => {
            let files = input[0].files;

            if (files.length > 0) {
                let data = new FormData();
                data.append('coverFile', files[0]);

                let xhttp = new XMLHttpRequest();
                xhttp.open('POST', '../php/konto.php');
                xhttp.onreadystatechange = () => {
                    if (xhttp.readyState == 4 && xhttp.status == 200) {
                        let response = JSON.parse(xhttp.responseText);
                        if (response.success) {

                            let reader = new FileReader();
                            reader.readAsDataURL(e.target.files[0]);

                            reader.onload = readerEvent => {
                                let content = readerEvent.target.result;
                                $('.cover-box').css('background-image', `url('${content}')`);
                                $('#coverPic-inp').remove();
                                $('#edit-options').hide();
                            }
                        }
                        uploadAlert(response.msg, response.success);
                    }
                };

                xhttp.send(data);
            }
        });

    });

    $('#changePPic').click(() => {
        $('#options').append('<input id="profilPic-inp" type="file" accept=".jpg, .jpeg, .png" style="display: none;">');
        $('#profilPic-inp').trigger('click');

        let input = $('#profilPic-inp');
        input.on('change', e => {
            let files = input[0].files;

            if (files.length > 0) {
                let data = new FormData();
                data.append('profileFile', files[0]);

                let xhttp = new XMLHttpRequest();
                xhttp.open('POST', '../php/konto.php');
                xhttp.onreadystatechange = () => {
                    if (xhttp.readyState == 4 && xhttp.status == 200) {
                        let response = JSON.parse(xhttp.responseText);
                        if (response.success) {
                            let reader = new FileReader();
                            reader.readAsDataURL(e.target.files[0]);

                            reader.onload = readerEvent => {
                                let content = readerEvent.target.result;
                                document.getElementById('profilPic').src = content;
                                $('#profilPic-inp').remove();
                                $('#edit-options').hide();
                            }
                        }
                        uploadAlert(response.msg, response.success);
                    }
                };

                xhttp.send(data);
            }
        });
    });

    function uploadAlert(msg, success = false, container = '#cover-box-msg') {
        let html = '<div id ="uploadMsgBox">';
        msg.forEach(m => {
            html += `<p class="uploadMsg">${m}</p>`;
        });
        html += '<p id="removeMsg">x</p>';
        $(`${container}`).html(html);
        $(`${container}`).show();

        $('#removeMsg').click(event => {
            removeMsgBox();
        });

        if (success) {
            setTimeout(() => {
                removeMsgBox();
            }, 5000);
        }

        function removeMsgBox() {
            $(`${container}`).toggle();
            $('#uploadMsgBox').remove();
        }

    }

    let buttons = [];
    buttons.push($('#videosInAcc'), $('#likedVideos'), $('#history'), $('#abos'), $('#uploadV'));

    let chosenId = urlP.get('box');
    if (chosenId == null) {
        chosenId = 'videosInAcc';
    }

    if (chosenId == 'videosInAcc') {
        let aboData = {
            'checkKonto': true
        };
        $.post('../php/kanal.php', aboData, receiveData);

        function receiveData(data) {
            data = JSON.parse(data);
            $('.kontoName').text(data.name + ' |');
            if (data.logedIn) {
                data.abos = data.abos + ' Abos';
            }
            $('#aboCount').text(`\u00A0${data.abos}`);
        }

        $.post('../php/profileAndCover.php', { 'checkProfileImg': '' }, data => {
            $('#profilPic').attr('src', `../img/profileImg/${data}`);
        });

        $.post('../php/profileAndCover.php', { 'checkCoverImg': '' }, data => {
            $('.cover-box').css('background-image', `url('../img/coverImg/${data}')`);
        });

        $.post('../php/konto.php', { 'myVideos': '' }, data => {
            data = JSON.parse(data);
            if (data.success) {
                $('.videosInAcc-box .video-box').html('');
                for (element of data.msg) {
                    $('.videosInAcc-box .video-box').append(`<a href="./pVideo.html?v=${element.id}">
                <img src="../img/thumb/${element.poster}">
                <h5 class="title" title="${element.title}">${element.title}</h5>
            </a>`);
                };
            } else {
                $('#cover-box-msg').append(`<div id ="uploadMsgBox"><p class="uploadMsg">${data.msg}</p><p id="removeMsg">x</p></div>`);
                $('#cover-box-msg').show();
                $('#uploadMsgBox').click(event => {
                    removeMsgBox();
                });

                function removeMsgBox() {
                    $('#cover-box-msg').toggle();
                    $('#uploadMsgBox').remove();
                }

            }
        });
    }

    if (chosenId == 'likedVideos') {
        postForVideos('likedVideos');
    }

    if (chosenId == 'history') {
        postForVideos('history');
    }

    if (chosenId == 'abos') {
        $.post('../php/konto.php', { 'abos': null }, data => {
            data = JSON.parse(data);
            if (data.success) {
                let html = '';
                data.msg.forEach(k => {
                    html += `
                    <div>
                        <a href="./kanal.html?c=${k.name}">
                            <img src="../img/profileImg/${k.pname}">
                            <h5 class="abo-title" title="${k.name}">${k.name}</h5>
                        </a>
                    </div>`;
                });

                $('#abos-boxID').html(html);
            } else {
                uploadAlert(data.msg, false, '#notLogedIn');
            }
        });
    }

    function postForVideos(name) {
        let json = {};
        json[name] = null;
        $.post('../php/konto.php', json, data => {
            data = JSON.parse(data);
            if (data.success) {
                addVideos(name + '-boxID', data.msg)
            } else {
                uploadAlert(data.msg, false, '#notLogedIn')
            }
        });
    }

    function addVideos(htmlID, data) {
        let html = '';
        data.forEach(v => {
            html += `<a href="./pVideo.html?v=${v.id}">
                    <img loading="lazy" src="../img/thumb/${v.poster}">
                    <h5 class="title" title="${v.title}">${v.title}</h5>
                    </a>`;
        });
        $(`#${htmlID}`).html(html);
    }

    chosenButton = $('#' + chosenId);

    chosenButton = chosenButton[0];
    $('.' + chosenButton.id + '-box').css('display', 'grid');
    $(chosenButton).css('background-color', 'rgb(25, 26, 26)');

    for (b of buttons) {
        b.click((e) => {
            if (chosenButton != e.target) {
                $(chosenButton).css('background-color', 'rgb(0, 0, 0)');
                $(e.target).css('background-color', 'rgb(25, 26, 26)');

                let cName = e.target.id;

                urlP.set('box', cName);
                window.location.search = urlP;
            }
        });
    }

    $('.hochladenB').click(() => {
        document.getElementsByClassName('hochladenB')[0].disabled = true;
        let v = $('#videoForUpl');
        let t = $('#thumbnail');
        let output = [];

        if (v[0].files.length == 0) {
            output.push('Sie haben kein Video ausgewählt!');
        }
        if (t[0].files.length == 0) {
            output.push('Sie haben kein Thumbnail ausgewählt!');
        }
        if ($('#titleOfV').val().length < 1) {
            output.push('Titel muss mindestens 1 Zeichen haben');
        }
        if ($('#catagories').val().length < 3) {
            output.push('Kategorie muss mindestens 3 Zeichen haben');
        }
        if ($('#tags').val().length < 3) {
            output.push('Tags muss mindestens 3 Zeichen haben');
        }

        if (output.length == 0) {

            let data = new FormData();
            data.append('video', v[0].files[0]);
            data.append('thumb', t[0].files[0]);
            data.append('title', $('#titleOfV').val());
            data.append('cat', $('#catagories').val());
            data.append('tags', $('#tags').val());

            let xhttp = new XMLHttpRequest();
            xhttp.open('POST', '../php/konto.php');
            xhttp.onreadystatechange = () => {
                if (xhttp.readyState == 4 && xhttp.status == 200) {
                    let response = JSON.parse(xhttp.responseText);
                    if (response.success) {
                        $('.hochladenBox').hide();
                        $('#uploadMsgBox').remove();
                        $('#meldung').append('<div id="meldung2"><p>Ihr Video wurde hochgeladen!</P><button id="uploadAg">Nochmal hochladen</button></div>');
                        $('#uploadAg').click(() => {
                            $('.hochladenBox').show();
                            $('#meldung2').remove();
                            v.val('');
                            t.val('');
                            $('#titleOfV').val('');
                            $('#catagories').val('');
                            $('#tags').val('');
                        });
                    } else {
                        uploadAlert(response.msg, response.success, '.uploadV-box #meldung');
                    }
                }
            };
            xhttp.send(data);
        } else {
            uploadAlert(output, false, '.uploadV-box #meldung');
        }
        document.getElementsByClassName('hochladenB')[0].disabled = false;
    });
});