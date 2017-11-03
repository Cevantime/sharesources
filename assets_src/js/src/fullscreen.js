window.document.exitFullscreen = window.document.exitFullscreen || function () {
    if (window.document.webkitExitFullscreen) {
        window.document.webkitExitFullscreen();
    } else if (window.document.mozCancelFullScreen) {
        window.document.mozCancelFullScreen();
    } else if (window.document.msExitFullscreen) {
        window.document.msExitFullscreen();
    }
};

window.document.isFullScreen = window.document.isFullScreen || function() {
    return (window.document.fullscreenElement && window.document.fullscreenElement !== null) ||
        (window.document.webkitFullscreenElement && window.document.webkitFullscreenElement !== null) ||
        (window.document.mozFullScreenElement && window.document.mozFullScreenElement !== null) ||
        (window.document.msFullscreenElement && window.document.msFullscreenElement !== null);

}