$(() => {
    $('#logIn').click(() => {
        let name = $('.logInBox .name').val();
        let pass = $('.logInBox .password').val();
        let output = [];

        if (name.length < 3) {
            output.push('Der Name muss mindestens 3 Zeichen haben!');
        }
        if (pass.length < 5) {
            output.push('Der Passwort muss mindestens 5 Zeichen haben!');
        }
        if (output.length == 0) {
            let data = {
                'logInData': {
                    'name': name,
                    'pass': pass
                }
            };
            data.logInData = JSON.stringify(data.logInData);
            $.post('../php/logIn.php', data, checkServer);

            function checkServer(receivedData) {
                receivedData = JSON.parse(receivedData);
                if (receivedData.success == true) {
                    $('.logInBox input').hide();
                    $('.logInBox button').hide();
                    $('#logInMessage').show();
                    $('#logInMessage').html(`<p>Willkommen ${name} zurück!</p>`);
                    setTimeout(() => {
                        window.location.replace('./index.html');
                    }, 1000);

                } else {
                    $('#logInMessage').html(``);
                    $('#logInMessage').show();
                    receivedData.msg.forEach(m => {
                        $('#logInMessage').append(`<p>${m}</p>`);
                    });
                }
            }
        } else {
            $('#logInMessage').html(``);
            $('#logInMessage').show();
            output.forEach(o => {
                $('#logInMessage').append(`<p>${o}</p>`);
            });
        }
    });

    $('#toSignUp').click(() => {
        $('.logInBox').css('display', 'none');
        $('.signUpBox').css('display', 'grid');
    });

    $('#signUp').click(() => {
        let data = {
            'signUpData': {
                'name': $('.signUpBox .name').val(),
                'bDay': $('.bDay').val(),
                'country': $('.country').val(),
                'email': $('.email').val(),
                'pass1': $('.password1').val(),
                'pass2': $('.password2').val()
            }
        };
        let output = [];

        let emailPat = /^[A-zäöüßÄÖÜ\.\_\-\d]{2,40}@([A-z\-\_]+\.){1,5}[A-z]{2,10}$/gm;

        if (!(data.signUpData.name.length >= 3 && data.signUpData.name.length <= 25)) {
            output.push('Die Länge des Namens muss zw. 3 - 25 sein!');
        }

        if (data.signUpData.bDay.length < 6) {
            output.push('Ihr Geburtsdatum stimmt nicht!');
        }

        if ((data.signUpData.country.length < 3 || data.signUpData.country.length > 30)) {
            output.push('Das Land muss eine Länge zw. 3 - 30 haben!');
        }

        if (!emailPat.test(data.signUpData.email)) {
            output.push('E-Mail muss in richtiger Form haben!');
        }

        if (!(data.signUpData.pass1.length >= 5)) {
            output.push('Passwort muss mind. 4 Zeichen haben!');
        } else if (!(data.signUpData.pass2 == data.signUpData.pass1)) {
            output.push('Passwörter stimmen nicht überein!');
        }

        if (output.length == 0) {
            data.signUpData = JSON.stringify(data.signUpData);
            $.post('../php/signUp.php', data, checkServer);
            data.signUpData = JSON.parse(data.signUpData);
            function checkServer(receivedData) {
                receivedData = JSON.parse(receivedData);
                if (receivedData.success == true) {
                    $('.signUpBox input').hide();
                    $('.signUpBox button').hide();
                    $('#message').html(`<p>Willkommen ${data.signUpData.name}! Sie sind registriert!</p>`);
                    setTimeout(() => {
                        window.location.replace('./index.html');
                    }, 1000);
                } else {
                    $('#message').html(``);
                    $('#message').show();
                    receivedData.msg.forEach(m => {
                        $('#message').append(`<p>${m}</p>`);
                    });
                }
            }

        } else {
            $('#message').html(``);
            $('#message').show();
            output.forEach(o => {
                $('#message').append(`<p>${o}</p>`);
            });
        }
    });
});