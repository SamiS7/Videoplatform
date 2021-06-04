$(() => {
    $('.menu').html(`<a href="./index.html" class="col-lg-2 col-md-2 col-sm-6">
    <i class="fas fa-home" style="font-size: 150%;"></i>
</a>
<a href="./videos.html" class="col-lg-2 col-md-2 col-sm-6">Videos</a>

<div class="dropDown col-lg-2 col-md-4 col-sm-6 p-0">
    <h4 class="col-12">Mein Konto</h4>
    <div class="dropDownContent" style="display: none;">
        <a href="./konto.html?box=videosInAcc">Konto</a>
        <a href="konto.html?box=history">History</a>
        <a href="konto.html?box=likedVideos">Gelikte Videos</a>
        <a href="" id="account" style="display:none"></a>
        <a href="./logIn.html" id="logInLink">Anmelden</a>
        <a href="" id="logOut">Abmelden</a>
    </div>
</div>

<div class="search-box col-lg-6 col-md-4 col-sm-6">
    <input type="text" class="search-input col-10">
    <i class="fas fa-search"></i>
</div>`);

    $.post('../php/checkUser.php', checkUser);

    $('#logOut').click(() => {
        $.post('../php/checkUser.php', { 'destroySession': true }, checkUser);
    });

    function checkUser(data) {
        data = JSON.parse(data);
        if (data.logedIn) {
            $('#logInLink').hide();
            $('#account').show();
            $('#account').attr({ "href": `./kanal.html?c=${data.name}` });
            $('#account').text(`Angemeldet als ${data.name}`);
        } else {
            $('#logInLink').show();
            $('#account').hide();
        }
    }
});