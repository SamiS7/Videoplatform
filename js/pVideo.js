$(() => {
    $('.like .far').click(() => {
        $('.like .far').hide();
        $('.like .fas').show();
    });
    $('.like .fas').click(() => {
        $('.like .fas').hide();
        $('.like .far').show();
    });
});