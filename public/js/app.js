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

const addNewFormPairs = (function () {
    let index = 0;
    return function () {
        index++;
        $("#newForm").removeClass("is-hidden");

        $("#newForm").append(`
        <div class="column is-6">
            <div class="field">
                    <label for="property${index}" class="label text-green has-text-weight-normal">Property</label>
                    <div class="control has-icons-left">
                        <input id="property${index}" name="property${index}" type="text" class="input" placeholder="Color">
                    </div>
                </div>
            </div>
        <div class="column is-6">
            <div class="field">
                <label for="data${index}" class="label text-green has-text-weight-normal">Data</label>
                <div class="control has-icons-left">
                    <input id="data${index}" name="properties" type="text" class="input" placeholder="Green">
                </div>
            </div>
        </div>
    `);
    };
})();

$("#addNewForm").click(function () {
    addNewFormPairs();
});
