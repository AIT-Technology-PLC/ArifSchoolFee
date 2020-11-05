var menuScroller = $("#menuLeft");

$(document).ready(function () {
    menuScroller.css("overflow", "hidden");
});

menuScroller.mouseover(function () {
    $(this).css("overflow", "auto");
});
menuScroller.mouseout(function () {
    $(this).css("overflow", "hidden");
});
