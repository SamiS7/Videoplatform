$(() => {
    post('mostSeen', 'mostSeen-box', null);
    post('recommended', 'recom-box', null);
    post('mostRecent', 'mostRecent-box', null, true);

    function post(name, id, dataToSend, loadMore = false, append = false) {
        let d = {};
        d[name] = dataToSend;
        $.post('../php/filterVideos.php', d, data => {
            console.log(name);
            console.log(data)
            data = JSON.parse(data);
            if (data.success) {
                if (loadMore) {
                    loadMore = data.more;
                }
                addVideos(id, data.data, loadMore, name, append);
            }
        });
    }


    function addVideos(htmlID, data, loadMore, name, append) {

        let html = '';
        data.forEach(v => {
            html += `<a href="./pVideo.html?v=${v.id}">
                    <img loading="lazy" src="../img/thumb/${v.poster}">
                    <h5 class="title" title="${v.title}">${v.title}</h5>
                    </a>`;
        });

        if (loadMore) {
            html += '<button id="loadMoreB" class="loadMore">Mehr laden</button>';
        }

        if (append) {
            $(`#${htmlID}`).append(html);
        } else {
            $(`#${htmlID}`).html(html);
        }

        if (loadMore) {
            $('#loadMoreB').click(event => {
                post(name, htmlID, null, loadMore);
            });
        }
    }
});