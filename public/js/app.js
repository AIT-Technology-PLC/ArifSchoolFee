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

function keyValueInputFields() {
    let index = 0;
    $("#newForm").removeClass("is-hidden");
    return function () {
        $("#newForm").append(`
        <div class="column is-6">
            <div class="field">
                    <label for="key${index}" class="label text-green has-text-weight-normal">Property</label>
                    <div class="control has-icons-left">
                        <input id="key${index}" name="properties[${index}][key]" type="text" class="input" placeholder="Color">
                    </div>
                </div>
            </div>
        <div class="column is-6">
            <div class="field">
                <label for="value${index}" class="label text-green has-text-weight-normal">Data</label>
                <div class="control has-icons-left">
                    <input id="value${index}" name="properties[${index}][value]" type="text" class="input" placeholder="Green">
                </div>
            </div>
        </div>`);

        index++;
    };
}

const createFields = keyValueInputFields();

$("#addNewForm").click(function () {
    createFields();
});

$("#createMenuButton").click(function () {
    $("#createMenu").toggleClass("is-hidden");
});
