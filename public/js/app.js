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

function toggleCreateMenu() {
    d.getElementById("menuModal").classList.toggle("is-active");
}

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

function toggleMenu() {
    this.children[2].firstElementChild.classList.toggle("fa-caret-up");

    this.parentElement.nextElementSibling.firstElementChild.classList.toggle(
        "is-hidden"
    );
}

function goToPreviousPage() {
    return history.back();
}

function goToNextPage() {
    return history.forward();
}

function refreshPage() {
    return location.reload();
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

function disableDeleteForm(event) {
    event.preventDefault();

    swal({
        title: "Delete Permanently??",
        text: "The selected element will be deleted permanently!",
        icon: "error",
        buttons: ["Not now", "Yes, Delete Forever"],
        dangerMode: true,
    }).then((willDelete) => {
        if (willDelete) {
            let deleteButton = this.querySelector("button");
            deleteButton.innerText = "Deleting ...";
            deleteButton.disabled = true;
            this.submit();
        }
    });
}

const addGrnForm = (function () {
    const grnFormGroup = d.getElementsByName("grnFormGroup");
    const grnFormWrapper = d.getElementById("grnFormWrapper");
    const productList = d.getElementById("grn[0][product_id]");
    const warehouseList = d.getElementById("grn[0][warehouse_id]");
    const formLimit = 10;
    let index = grnFormGroup.length;

    if (!grnFormWrapper) {
        return false;
    }

    return function () {
        const createGrnForm = `
        <div class="has-text-weight-medium has-text-left">
            <span class="tag bg-green has-text-white is-medium radius-bottom-0">
                Item ${index + 1}
            </span>
        </div>
        <div class="box has-background-white-bis radius-top-0 mb-5">
            <div name="grnFormGroup" class="columns is-marginless is-multiline">
                <div class="column is-6">
                    <div class="field">
                        <label for="grn[${index}][product_id]" class="label text-green has-text-weight-normal"> Product <sup class="has-text-danger">*</sup> </label>
                        <div class="control has-icons-left">
                            <div class="select is-fullwidth">
                                <select id="grn[${index}][product_id]" name="grn[${index}][product_id]" class="select2-products" onchange="getProductSelected(this.id, this.value)">
                                    ${productList.innerHTML}
                                </select>
                            </div>
                            <div class="icon is-small is-left">
                                <i class="fas fa-th"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="column is-6">
                    <div class="field">
                        <label for="grn[${index}][warehouse_id]" class="label text-green has-text-weight-normal"> To <sup class="has-text-danger">*</sup> </label>
                        <div class="control has-icons-left">
                            <div class="select is-fullwidth">
                                <select id="grn[${index}][warehouse_id]" name="grn[${index}][warehouse_id]">
                                    ${warehouseList.innerHTML}
                                </select>
                            </div>
                            <div class="icon is-small is-left">
                                <i class="fas fa-warehouse"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="column is-6">
                    <label for="grn[${index}][quantity]" class="label text-green has-text-weight-normal">Quantity <sup class="has-text-danger">*</sup> </label>
                    <div class="field has-addons">
                        <div class="control has-icons-left is-expanded">
                            <input id="grn[${index}][quantity]" name="grn[${index}][quantity]" type="number" class="input" placeholder="Quantity">
                            <span class="icon is-small is-left">
                                <i class="fas fa-balance-scale"></i>
                            </span>
                        </div>
                        <div class="control">
                            <button id="grn[${index}][product_id]Quantity" class="button bg-green has-text-white" type="button"></button>
                        </div>
                    </div>
                </div>
                <div class="column is-6">
                    <div class="field">
                        <label for="grn[${index}][description]" class="label text-green has-text-weight-normal">Additional Notes <sup class="has-text-danger"></sup></label>
                        <div class="control has-icons-left">
                            <textarea name="grn[${index}][description]" id="grn[${index}][description]" cols="30" rows="3" class="textarea pl-6" placeholder="Description or note to be taken"></textarea>
                            <span class="icon is-large is-left">
                                <i class="fas fa-edit"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>`;

        grnFormWrapper.insertAdjacentHTML("beforeend", createGrnForm);

        index++;

        initializeSelect2Products();

        if (index == formLimit) {
            d.getElementById("addNewGrnForm").remove();
            return false;
        }
    };
})();

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
    $(".dataTables_filter label input").attr("placeholder", "Search");
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

const addTenderForm = (function () {
    const tenderFormGroup = d.getElementsByName("tenderFormGroup");
    const tenderFormWrapper = d.getElementById("tenderFormWrapper");
    const productList = d.getElementById("tender[0][product_id]");
    const formLimit = 10;
    let index = tenderFormGroup.length;

    if (!tenderFormWrapper) {
        return false;
    }

    return function () {
        const createTenderForm = `
        <div class="has-text-weight-medium has-text-left">
            <span class="tag bg-green has-text-white is-medium radius-bottom-0">
                Item ${index + 1}
            </span>
        </div>
        <div class="box has-background-white-bis radius-top-0 mb-5">
            <div name="tenderFormGroup" class="columns is-marginless is-multiline">
                <div class="column is-6">
                    <div class="field">
                        <label for="tender[${index}][product_id]" class="label text-green has-text-weight-normal"> Product <sup class="has-text-danger">*</sup> </label>
                        <div class="control has-icons-left">
                            <div class="select is-fullwidth">
                                <select id="tender[${index}][product_id]" name="tender[${index}][product_id]" class="select2-products" onchange="getProductSelected(this.id, this.value)">
                                    ${productList.innerHTML}
                                </select>
                            </div>
                            <div class="icon is-small is-left">
                                <i class="fas fa-th"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="column is-6">
                    <label for="tender[${index}][quantity]" class="label text-green has-text-weight-normal">Quantity <sup class="has-text-danger">*</sup> </label>
                    <div class="field has-addons">
                        <div class="control has-icons-left is-expanded">
                            <input id="tender[${index}][quantity]" name="tender[${index}][quantity]" type="number" class="input" placeholder="Quantity" value="0.00">
                            <span class="icon is-small is-left">
                                <i class="fas fa-balance-scale"></i>
                            </span>
                        </div>
                        <div class="control">
                            <button id="tender[${index}][product_id]Quantity" class="button bg-green has-text-white" type="button"></button>
                        </div>
                    </div>
                </div>
                <div class="column is-6">
                    <div class="field">
                        <label for="tender[${index}][description]" class="label text-green has-text-weight-normal">Additional Notes <sup class="has-text-danger"></sup></label>
                        <div class="control has-icons-left">
                            <textarea name="tender[${index}][description]" id="tender[${index}][description]" cols="30" rows="3" class="textarea pl-6" placeholder="Description or note to be taken"></textarea>
                            <span class="icon is-large is-left">
                                <i class="fas fa-edit"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>`;

        tenderFormWrapper.insertAdjacentHTML("beforeend", createTenderForm);

        index++;

        initializeSelect2Products();

        if (index == formLimit) {
            d.getElementById("addNewTenderForm").remove();
            return false;
        }
    };
})();

function showOnlineBox() {
    let backOffline = d.getElementById("backOffline");
    backOffline.classList.add("is-hidden");

    let backOnline = d.getElementById("backOnline");
    backOnline.classList.remove("is-hidden");

    setTimeout(() => backOnline.classList.add("is-hidden"), 2000);

    if (backOnline.dataset.offline === "true") {
        return setTimeout(() => goToPreviousPage(), 500);
    }
}

function showOfflineBox() {
    let backOnline = d.getElementById("backOnline");
    backOnline.classList.add("is-hidden");

    let backOffline = d.getElementById("backOffline");
    backOffline.classList.remove("is-hidden");
}

function showOfflineBoxPermanent() {
    if (!navigator.onLine) {
        showOfflineBox();
    }
}

function toggleNotificationBox() {
    d.getElementById("notificationBox").classList.toggle("is-hidden");
}

async function showNewNotifications() {
    const notificationBody = d.getElementById("notificationBody");
    const notificationCountDesktop = d.getElementById(
        "notificationCountDesktop"
    );
    const notificationCountMobile = d.getElementById("notificationCountMobile");
    const response = await axios.get("/api/notifications/unread");
    const unreadNotifications = response.data;
    let notification = "";

    if (unreadNotifications.length) {
        notificationCountDesktop.classList.remove("is-hidden");
        notificationCountMobile.classList.remove("is-hidden");

        notificationCountDesktop.innerText = unreadNotifications.length;
        notificationCountMobile.innerText = unreadNotifications.length;

        for (let index = 0; index < unreadNotifications.length; index++) {
            notification += `
                <div class="columns is-marginless has-background-white-bis text-green py-3 is-size-6-5 is-mobile">
                    <div class="column is-1">
                        <span class="icon is-small">
                            <i class="fas fa-${unreadNotifications[index].data.icon}"></i>
                        </span>
                    </div>
                    <div class="column is-11 pl-1">
                        <a data-notification-id="${unreadNotifications[index].id}" class="unreadNotifications is-not-underlined" href="${unreadNotifications[index].data.endpoint}">
                            ${unreadNotifications[index].data.message}
                        </a>
                        <br>
                        <span class="is-size-7 has-text-weight-bold">
                            ${unreadNotifications[index].diff_for_humans}
                        </span>
                    </div>
                </div>
                <hr class="mt-0 mb-0">`;
        }

        notificationBody.firstElementChild.innerHTML = notification;
    }
}

function markNotificationAsRead(event) {
    if (event.target.classList.contains("unreadNotifications")) {
        axios.patch(`/notifications/${event.target.dataset.notificationId}`);
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

const addSivForm = (function () {
    const sivFormGroup = d.getElementsByName("sivFormGroup");
    const sivFormWrapper = d.getElementById("sivFormWrapper");
    const productList = d.getElementById("siv[0][product_id]");
    const warehouseList = d.getElementById("siv[0][warehouse_id]");
    const formLimit = 10;
    let index = sivFormGroup.length;

    if (!sivFormWrapper) {
        return false;
    }

    return function () {
        const createSivForm = `
        <div class="has-text-weight-medium has-text-left">
            <span class="tag bg-green has-text-white is-medium radius-bottom-0">
                Item ${index + 1}
            </span>
        </div>
        <div class="box has-background-white-bis radius-top-0 mb-5">
            <div name="sivFormGroup" class="columns is-marginless is-multiline">
                <div class="column is-6">
                    <div class="field">
                        <label for="siv[${index}][product_id]" class="label text-green has-text-weight-normal"> Product <sup class="has-text-danger">*</sup> </label>
                        <div class="control has-icons-left">
                            <div class="select is-fullwidth">
                                <select id="siv[${index}][product_id]" name="siv[${index}][product_id]" class="select2-products" onchange="getProductSelected(this.id, this.value)">
                                    ${productList.innerHTML}
                                </select>
                            </div>
                            <div class="icon is-small is-left">
                                <i class="fas fa-th"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="column is-6">
                    <div class="field">
                        <label for="siv[${index}][warehouse_id]" class="label text-green has-text-weight-normal"> From <sup class="has-text-danger">*</sup> </label>
                        <div class="control has-icons-left">
                            <div class="select is-fullwidth">
                                <select id="siv[${index}][warehouse_id]" name="siv[${index}][warehouse_id]">
                                    ${warehouseList.innerHTML}
                                </select>
                            </div>
                            <div class="icon is-small is-left">
                                <i class="fas fa-warehouse"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="column is-6">
                    <label for="siv[${index}][quantity]" class="label text-green has-text-weight-normal">Quantity <sup class="has-text-danger">*</sup> </label>
                    <div class="field has-addons">
                        <div class="control has-icons-left is-expanded">
                            <input id="siv[${index}][quantity]" name="siv[${index}][quantity]" type="number" class="input" placeholder="Quantity">
                            <span class="icon is-small is-left">
                                <i class="fas fa-balance-scale"></i>
                            </span>
                        </div>
                        <div class="control">
                            <button id="siv[${index}][product_id]Quantity" class="button bg-green has-text-white" type="button"></button>
                        </div>
                    </div>
                </div>
                <div class="column is-6">
                    <div class="field">
                        <label for="siv[${index}][description]" class="label text-green has-text-weight-normal">Additional Notes <sup class="has-text-danger"></sup></label>
                        <div class="control has-icons-left">
                            <textarea name="siv[${index}][description]" id="siv[${index}][description]" cols="30" rows="3" class="textarea pl-6" placeholder="Description or note to be taken"></textarea>
                            <span class="icon is-large is-left">
                                <i class="fas fa-edit"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>`;

        sivFormWrapper.insertAdjacentHTML("beforeend", createSivForm);

        index++;

        initializeSelect2Products();

        if (index == formLimit) {
            d.getElementById("addNewSivForm").remove();
            return false;
        }
    };
})();

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

const addDamageForm = (function () {
    const damageFormGroup = d.getElementsByName("damageFormGroup");
    const damageFormWrapper = d.getElementById("damageFormWrapper");
    const productList = d.getElementById("damage[0][product_id]");
    const warehouseList = d.getElementById("damage[0][warehouse_id]");
    const formLimit = 10;
    let index = damageFormGroup.length;

    if (!damageFormWrapper) {
        return false;
    }

    return function () {
        const createDamageForm = `
        <div class="has-text-weight-medium has-text-left">
            <span class="tag bg-green has-text-white is-medium radius-bottom-0">
                Item ${index + 1}
            </span>
        </div>
        <div class="box has-background-white-bis radius-top-0 mb-5">
            <div name="damageFormGroup" class="columns is-marginless is-multiline">
                <div class="column is-6">
                    <div class="field">
                        <label for="damage[${index}][product_id]" class="label text-green has-text-weight-normal"> Product <sup class="has-text-danger">*</sup> </label>
                        <div class="control has-icons-left">
                            <div class="select is-fullwidth">
                                <select id="damage[${index}][product_id]" name="damage[${index}][product_id]" class="select2-products" onchange="getProductSelected(this.id, this.value)">
                                    ${productList.innerHTML}
                                </select>
                            </div>
                            <div class="icon is-small is-left">
                                <i class="fas fa-th"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="column is-6">
                    <div class="field">
                        <label for="damage[${index}][warehouse_id]" class="label text-green has-text-weight-normal"> From <sup class="has-text-danger">*</sup> </label>
                        <div class="control has-icons-left">
                            <div class="select is-fullwidth">
                                <select id="damage[${index}][warehouse_id]" name="damage[${index}][warehouse_id]">
                                    ${warehouseList.innerHTML}
                                </select>
                            </div>
                            <div class="icon is-small is-left">
                                <i class="fas fa-warehouse"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="column is-6">
                    <label for="damage[${index}][quantity]" class="label text-green has-text-weight-normal">Quantity <sup class="has-text-danger">*</sup> </label>
                    <div class="field has-addons">
                        <div class="control has-icons-left is-expanded">
                            <input id="damage[${index}][quantity]" name="damage[${index}][quantity]" type="number" class="input" placeholder="Quantity">
                            <span class="icon is-small is-left">
                                <i class="fas fa-balance-scale"></i>
                            </span>
                        </div>
                        <div class="control">
                            <button id="damage[${index}][product_id]Quantity" class="button bg-green has-text-white" type="button"></button>
                        </div>
                    </div>
                </div>
                <div class="column is-6">
                    <div class="field">
                        <label for="damage[${index}][description]" class="label text-green has-text-weight-normal">Additional Notes <sup class="has-text-danger"></sup></label>
                        <div class="control has-icons-left">
                            <textarea name="damage[${index}][description]" id="damage[${index}][description]" cols="30" rows="3" class="textarea pl-6" placeholder="Description or note to be taken"></textarea>
                            <span class="icon is-large is-left">
                                <i class="fas fa-edit"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>`;

        damageFormWrapper.insertAdjacentHTML("beforeend", createDamageForm);

        index++;

        initializeSelect2Products();

        if (index == formLimit) {
            d.getElementById("addNewDamageForm").remove();
            return false;
        }
    };
})();

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
