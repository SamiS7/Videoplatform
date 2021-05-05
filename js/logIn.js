$(() => {
    $('#logIn').click(() => {
        let name = $('.logInBox .name').val();
        let pass = $('.logInBox .password').val();
        if (name.length > 2 && pass.length >= 5) {
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
                if (receivedData.news == true) {
                    $('.logInBox input').hide();
                    $('.logInBox button').hide();
                    $('#logInMessage').text(`Willkommen ${name} zurück!`);
                    setTimeout(() => {
                        window.location.replace('./index.html');
                    }, 1000);

                } else {
                    $('#logInMessage').text(receivedData.message);
                }
            }
        } else {
            $('#logInMessage').text(`Kontrollieren Sie bitte nochmal Ihre Eingaben!`);
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

        if (data.signUpData.name.length >= 3 && data.signUpData.bDay.length >= 6 && data.signUpData.country.length > 3 && data.signUpData.email.length > 5 && data.signUpData.pass1.length >= 5 && data.signUpData.pass2 == data.signUpData.pass1) {
            data.signUpData = JSON.stringify(data.signUpData);
            $.post('../php/signUp.php', data, checkServer);
            data.signUpData = JSON.parse(data.signUpData);
            function checkServer(receivedData) {
                receivedData = JSON.parse(receivedData);
                if (receivedData.news == true) {
                    $('.signUpBox input').hide();
                    $('.signUpBox button').hide();
                    $('#message').text(`Willkommen ${data.signUpData.name}! Sie sind registriert!`);
                    setTimeout(() => {
                        window.location.replace('./index.html');
                    }, 1000);
                } else {
                    $('#message').text(receivedData.message);
                }
            }

        } else {
            if (data.signUpData.pass1 != data.pass2) {
                $('#message').text(`Passwörter stimmen nicht überein!`);
            } else {
                $('#message').text(`Kontrollieren Sie bitte nochmal Ihre Eingaben!`);

            }
        }
    });
});