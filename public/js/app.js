var menuScroller = $("#menuLeft");
menuScroller.mouseover(function () {
    $(this).css("overflow", "auto");
});
menuScroller.mouseout(function () {
    $(this).css("overflow", "hidden");
});

var contentScroller = $("#contentRight");
contentScroller.mouseover(function () {
    $(this).css("overflow", "auto");
});
contentScroller.mouseout(function () {
    $(this).css("overflow", "hidden");
});
