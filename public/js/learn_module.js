isMobile = function () {
    const toMatch = [/Android/i, /webOS/i, /iPhone/i, /iPad/i, /iPod/i, /BlackBerry/i, /Windows Phone/i];

    return toMatch.some(toMatchItem => {
        return navigator.userAgent.match(toMatchItem);
    });
};
function showOrHide(learn_selector, learn_btn_selector) {
    if (isMobile()) {
        learn_selector.addClass('d-none');
        learn_selector.removeClass('d-inline-block');
        learn_btn_selector.removeClass('width-150');
    } else {
        learn_selector.addClass('d-inline-block');
        learn_selector.removeClass('d-none');
    }
}
function displayVideo(video_selector, video_id_selector){
    let $videoSrc;
    $('.video-btn').on('click', function () {
        $videoSrc = $(this).data("src");
    });

    video_selector.on('shown.bs.modal', function (e) {
        video_id_selector.attr('src', $videoSrc + "?autoplay=1&amp;modestbranding=1&amp;showinfo=0");
    })

    video_selector.on('hidden.bs.modal', function (e) {
        video_id_selector.attr('src', $videoSrc);
    })
}
