const d = document;

const addKeyValueInputFields = (function () {
    let index = 0;
    const newForm = d.getElementById("newForm");

    if (!newForm) {
        return false;
    }

    newForm.classList.remove("is-hidden");

    return function () {
        const keyValueFieldPair = `
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
            </div>`;

        newForm.insertAdjacentHTML("beforeend", keyValueFieldPair);

        index++;
    };
})();

async function getProductSelected(elementId, productId) {
    const response = await axios.get(
        `/api/products/${productId}/unit-of-measurement`
    );

    const unitOfMeasurement = response.data;

    if (d.getElementById(elementId + "Quantity")) {
        d.getElementById(elementId + "Quantity").innerText = unitOfMeasurement;
    }

    if (d.getElementById(elementId + "Price")) {
        d.getElementById(
            elementId + "Price"
        ).innerText = `Per ${unitOfMeasurement}`;
    }
}

function jumpToCurrentPageMenuTitle() {
    let menuTitles = d.querySelector(".menu .is-active");

    if (location.pathname.endsWith("/")) {
        return;
    }

    if (!menuTitles) {
        d.getElementsByName("menuTitles").forEach((element) => {
            if (element.href.includes(location.pathname.split("/")[1])) {
                menuTitles = element;
                return;
            }
        });
    }

    if (!menuTitles) {
        return;
    }

    let targetMenu =
        menuTitles.parentElement.parentElement.parentElement
            .previousElementSibling.firstElementChild;

    let menuAccordion = menuTitles.parentElement.parentElement;

    menuAccordion.classList.remove("is-hidden");

    targetMenu.classList.add("is-active");

    targetMenu.children[2].firstElementChild.classList.toggle("fa-caret-up");

    menuTitles.classList.remove("is-active");

    menuTitles.classList.add("has-text-weight-bold", "text-green");

    targetMenu.scrollIntoView();
}

function openAddToInventoryModal() {
    d.getElementById("addToInventoryModal").classList.toggle("is-active");
}

function disableSaveButton() {
    let saveButton = d.getElementById("saveButton");
    saveButton.classList.add("is-loading");
    saveButton.disabled = true;
}

function changeWarehouse() {
    if (this.value == 0) {
        return (location.href = "/merchandises/on-hand");
    }

    location.href = `/warehouses/${this.value}/merchandises`;
}

function toggleLeftMenuOnMobile() {
    let menuLeft = d.getElementById("menuLeft");

    menuLeft.classList.toggle("is-hidden-mobile");

    d.getElementById("contentRight").classList.toggle("is-hidden-mobile");

    d.getElementById("burgerMenuBars").classList.toggle("fa-times");

    if (!menuLeft.classList.contains("is-hidden-mobile")) {
        document.body.scrollTop = 0;
        document.documentElement.scrollTop = 0;
    }
}

function showTablesAfterCompleteLoad(className) {
    $(className).css("display", "table");
    $(className).DataTable().columns.adjust().draw();
    changeDtButton();
    removeDtSearchLabel();
}

function changeDtButton() {
    $(".buttons-print").attr("class", "button is-small btn-green is-outlined");
    $(".buttons-pdf").attr("class", "button is-small btn-purple is-outlined");
    $(".buttons-excel").attr("class", "button is-small btn-blue is-outlined");
    $(".buttons-colvis:first-child").text("Hide Columns");
    $(".buttons-colvis").attr("class", "button is-small btn-gold is-outlined");
}

function removeDtSearchLabel() {
    input = $(".dataTables_filter label input");
    $(".dataTables_filter label").html(input);
    input.attr("placeholder", "Search");
    input.addClass("m-top-10");
}

function adjustDataTablesColumns(className) {
    $(className).DataTable().columns.adjust().draw();
}

function initiateDataTables() {
    const table = $("table.regular-datatable");

    table.DataTable({
        responsive: true,
        scrollCollapse: true,
        scrollY: "500px",
        scrollX: true,
        columnDefs: [
            {
                type: "natural-nohtml",
                targets: JSON.parse(table.attr("data-numeric")),
            },
            { type: "date", targets: JSON.parse(table.attr("data-date")) },
        ],
        lengthMenu: [
            [10, 25, 50, 75, 100, -1],
            [10, 25, 50, 75, 100, "All"],
        ],
        dom: "lBfrtip",
        buttons: [
            "colvis",
            {
                extend: "excelHtml5",
                exportOptions: {
                    columns: ":visible",
                },
            },
            {
                extend: "print",
                exportOptions: {
                    columns: ":visible",
                },
            },
            {
                extend: "pdfHtml5",
                orientation: "landscape",
                customize: function (doc) {
                    doc.content[1].margin = [0, 0, 0, 0];
                },
                exportOptions: {
                    columns: ":visible",
                },
            },
        ],
    });

    showTablesAfterCompleteLoad("table.regular-datatable");
}

function showOnlineBox() {
    d.getElementById("backOffline").classList.add("is-hidden");

    d.getElementById("backOnline").classList.remove("is-hidden");

    location.reload();
}

function showOfflineBox() {
    d.getElementById("backOnline").classList.add("is-hidden");

    d.getElementById("backOffline").classList.remove("is-hidden");
}

function showOfflineBoxPermanent() {
    if (!navigator.onLine) {
        showOfflineBox();
    }
}

function openMarkAllNotificationsAsReadModal(event) {
    event.preventDefault();
    swal({
        title: "Do you want to mark all notifications as read?",
        buttons: ["Not now", "Yes, mark all as read"],
    }).then((willMarkAllAsRead) => {
        if (willMarkAllAsRead) {
            d.getElementById("formOne").submit();
        }
    });
}

function openInNewTab(url) {
    open(url, "_blank").focus();
}

function showRowDetailsPage(event) {
    let targetElement = event.target;

    while (targetElement.tagName !== "TR") {
        if (targetElement.classList.contains("actions")) {
            return;
        }

        targetElement = targetElement.parentElement;
    }

    location.href = this.dataset.id;
}

function selectAllCheckboxes() {
    let checkboxes = document.querySelectorAll("input[type='checkbox']");

    for (let checkbox of checkboxes) {
        if (this.children[1].innerText === "Select All") {
            checkbox.checked = true;
        }

        if (this.children[1].innerText === "Undo Select") {
            checkbox.checked = false;
        }
    }

    this.children[1].innerText === "Select All"
        ? (this.children[1].innerText = "Undo Select")
        : (this.children[1].innerText = "Select All");
}

function initializeSummernote() {
    $(".summernote").summernote({
        placeholder: "Write description or other notes here",
        tabsize: 2,
        minHeight: 90,
        tabDisable: true,
        toolbar: [
            ["font", ["bold"]],
            ["table", ["table"]],
            ["forecolor", ["forecolor"]],
        ],
    });
}

function openSwalModal(event) {
    let action = this.dataset.action;
    let type = this.dataset.type;
    let description = this.dataset.description
        ? `${action} ${this.dataset.description}`
        : action;
    let form = this.parentElement;

    event.preventDefault();

    swal({
        title: `Do you want to ${action} this ${type}?`,
        text: `By clicking 'Yes, ${action}', you are going to ${description} this ${type}.`,
        buttons: ["Not now", `Yes, ${action}`],
        dangerMode: true,
    }).then((willExecute) => {
        if (willExecute) {
            form.submit();
        }
    });
}

function modifySummernoteTableClass() {
    for (let element of d.querySelectorAll(".summernote-table table")) {
        element.classList =
            "table is-fullwidth is-bordered is-narrow is-size-7";

        element.removeAttribute("style");

        element.firstElementChild.firstElementChild.classList =
            "has-text-centered has-text-weight-bold is-capitalized";
    }

    for (let element of d.querySelectorAll(".summernote-table td")) {
        element.classList = "p-1";
    }
}

function initializeSelect2Products() {
    $(document).ready(function () {
        $(".select2-products").select2({
            placeholder: "Select a product",
            allowClear: true,
            tags: $(".select2-products").attr("data-tags"),
            matcher: (params, data) => {
                if ($.trim(params.term) === "") {
                    return data;
                }

                if (typeof data.text === "undefined") {
                    return null;
                }

                if (
                    data.text.toLowerCase().indexOf(params.term.toLowerCase()) >
                    -1
                ) {
                    return data;
                }

                if (
                    data.element.dataset.code
                        .toLowerCase()
                        .indexOf(params.term.toLowerCase()) > -1
                ) {
                    return data;
                }

                if (
                    data.element.dataset.category
                        .toLowerCase()
                        .indexOf(params.term.toLowerCase()) > -1
                ) {
                    return data;
                }

                return null;
            },
        });
    });
}

function disableInputTypeNumberMouseWheel() {
    if (d.activeElement.type === "number") {
        d.activeElement.blur();
    }
}

document.addEventListener("alpine:init", () => {
    Alpine.data("inventoryTypeToggler", () => ({
        isOnHand: true,
        showOnHand() {
            this.isOnHand = true;
        },
        showOutOf() {
            this.isOnHand = false;
        },
    }));

    Alpine.data("toggler", () => ({
        isHidden: true,
        toggle() {
            this.isHidden = !this.isHidden;
        },
    }));

    Alpine.data("showRowDetails", () => ({
        showDetails(event) {
            let targetElement = event.target;

            while (targetElement.tagName !== "TR") {
                if (targetElement.classList.contains("actions")) {
                    return;
                }

                targetElement = targetElement.parentElement;
            }

            location.href = targetElement.dataset.url;
        },
    }));
});
