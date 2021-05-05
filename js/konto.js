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
                            $('.cover-box').append(`<p id="uploadMsg>${response.output}</p>`);
                            setTimeout(() => {
                                $('#uploadMsg').remove();
                            }, 3000);
                            let reader = new FileReader();
                            reader.readAsDataURL(e.target.files[0]);

                            reader.onload = readerEvent => {
                                let content = readerEvent.target.result;
                                $('.cover-box').css('background-image', `url('${content}')`);
                                $('#coverPic-inp').remove();
                                $('#edit-options').hide();
                            }
                        } else {

                        }
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
                            $('.cover-box').append(`<p id="uploadMsg>${response.output}</p>`);
                            setTimeout(() => {
                                $('#uploadMsg').remove();
                            }, 3000);
                            let reader = new FileReader();
                            reader.readAsDataURL(e.target.files[0]);

                            reader.onload = readerEvent => {
                                let content = readerEvent.target.result;
                                document.getElementById('profilPic').src = content;
                                $('#profilPic-inp').remove();
                                $('#edit-options').hide();
                            }
                        } else {

                        }
                    }
                };

                xhttp.send(data);
            }
        });
    });


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
            $('.videosInAcc-box .video-box').html('');
            for (element of data.output) {
                $('.videosInAcc-box .video-box').append(`<a href="./pVideo.html?v=${element.id}">
                <img src="../img/thumb/${element.poster}">
                <h5 class="title">${element.title}</h5>
            </a>`);
            };
            changeTitleW();
        });
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
        if (v[0].files.length != 0 && t[0].files.length != 0 && $('#titleOfV').val().length >= 5 && $('#catagories').val().length >= 2 && $('#tags').val().length >= 2) {

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
                        $('#meldung').html(`<p id="meldung1">${response.output}</p>`);
                        setTimeout(() => {
                            $('#meldung1').remove();
                        }, 5000);
                    }
                }
            };
            xhttp.send(data);
            document.getElementsByClassName('hochladenB')[0].disabled = false;
        } else {
            $('#meldung').html('<p id="meldung1">Bitte kontrollieren Sie Ihre Eingaben!</P>');
            setTimeout(() => {
                $('#meldung1').remove();
            }, 3000);
        }
    });
});