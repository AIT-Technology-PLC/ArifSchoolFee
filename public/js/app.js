var menuScroller = $("#menuLeft");
menuScroller.mouseover(function () {
    $(this).css("overflow", "auto");
});
menuScroller.mouseout(function () {
    $(this).css("overflow", "hidden");
});

$(document).ready(function () {
    var contentScroller = $("#contentRight");
    contentScroller.css("overflow", "auto");
});
