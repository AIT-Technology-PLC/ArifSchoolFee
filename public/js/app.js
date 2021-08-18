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
    const response = await axios.get("/product/uom/" + productId);
    const unitOfMeasurement = response.data;

    d.getElementById(elementId + "Quantity").innerText = unitOfMeasurement;
    d.getElementById(
        elementId + "Price"
    ).innerText = `Per ${unitOfMeasurement}`;
}

const addPurchaseForm = (function () {
    const purchaseFormGroup = d.getElementsByName("purchaseFormGroup");
    const purchaseFormWrapper = d.getElementById("purchaseFormWrapper");
    let productList = d.getElementById("purchase[0][product_id]");

    if (!purchaseFormWrapper) {
        return false;
    }

    const formLimit = productList.length - 1;

    if (formLimit == 1) {
        d.getElementById("addNewPurchaseForm").remove();
    }

    let index = purchaseFormGroup.length;

    return function () {
        const createPurchaseForm = `
            <div class="has-text-weight-medium has-text-left">
                <span class="tag bg-green has-text-white is-medium radius-bottom-0">
                    Item ${index + 1}
                </span>
            </div>
            <div class="box has-background-white-bis radius-top-0 mb-5">
                <div name="purchaseFormGroup" class="columns is-marginless is-multiline">
                    <div class="column is-6">
                        <div class="field">
                            <label for="purchase[${index}][product_id]" class="label text-green has-text-weight-normal"> Product <sup class="has-text-danger">*</sup> </label>
                            <div class="control has-icons-left">
                                <div class="select is-fullwidth">
                                    <select id="purchase[${index}][product_id]" name="purchase[${index}][product_id]" class="select2-products" onchange="getProductSelected(this.id, this.value)">
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
                        <label for="purchase[${index}][quantity]" class="label text-green has-text-weight-normal">Quantity <sup class="has-text-danger">*</sup> </label>
                        <div class="field has-addons">
                            <div class="control has-icons-left is-expanded">
                                <input id="purchase[${index}][quantity]" name="purchase[${index}][quantity]" type="number" class="input" placeholder="Purchase Quantity">
                                <span class="icon is-small is-left">
                                    <i class="fas fa-balance-scale"></i>
                                </span>
                            </div>
                            <div class="control">
                                <button id="purchase[${index}][product_id]Quantity" class="button bg-green has-text-white" type="button"></button>
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <label for="purchase[${index}][unit_price]" class="label text-green has-text-weight-normal">Unit Price<sup class="has-text-weight-light"> (Before VAT)</sup> <sup class="has-text-danger">*</sup> </label>
                        <div class="field has-addons">
                            <div class="control has-icons-left is-expanded">
                                <input id="purchase[${index}][unit_price]" name="purchase[${index}][unit_price]" type="number" class="input" placeholder="Purchase Price">
                                <span class="icon is-small is-left">
                                    <i class="fas fa-money-bill"></i>
                                </span>
                            </div>
                            <div class="control">
                                <button id="purchase[${index}][product_id]Price" class="button bg-green has-text-white" type="button"></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;

        purchaseFormWrapper.insertAdjacentHTML("beforeend", createPurchaseForm);

        let currentSelect = d.getElementById(`purchase[${index}][product_id]`);
        let previousSelect = d.getElementById(
            `purchase[${index - 1}][product_id]`
        );

        for (let j = 0; j < previousSelect.length; j++) {
            if (!previousSelect.options[j].selected)
                previousSelect.options[j].hidden = true;
        }

        for (let i = 0; i < currentSelect.length; i++) {
            if (currentSelect.options[i].value == previousSelect.value)
                currentSelect.remove(i);
        }

        productList = currentSelect;

        index++;

        initializeSelect2Products();

        if (index == formLimit) {
            d.getElementById("addNewPurchaseForm").remove();
            return false;
        }
    };
})();

const addSaleForm = (function () {
    const saleFormGroup = d.getElementsByName("saleFormGroup");
    const saleFormWrapper = d.getElementById("saleFormWrapper");
    let productList = d.getElementById("sale[0][product_id]");

    if (!saleFormWrapper) {
        return false;
    }

    const formLimit = productList.length - 1;

    if (formLimit == 1) {
        d.getElementById("addNewSaleForm").remove();
    }

    let index = saleFormGroup.length;

    return function () {
        const createSaleForm = `
            <div class="has-text-weight-medium has-text-left">
                <span class="tag bg-green has-text-white is-medium radius-bottom-0">
                    Item ${index + 1}
                </span>
            </div>
            <div class="box has-background-white-bis radius-top-0 mb-5">
                <div name="saleFormGroup" class="columns is-marginless is-multiline">
                    <div class="column is-6">
                        <div class="field">
                            <label for="sale[${index}][product_id]" class="label text-green has-text-weight-normal"> Product <sup class="has-text-danger">*</sup> </label>
                            <div class="control has-icons-left">
                                <div class="select is-fullwidth">
                                    <select id="sale[${index}][product_id]" name="sale[${index}][product_id]" class="select2-products" onchange="getProductSelected(this.id, this.value)">
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
                        <label for="sale[${index}][quantity]" class="label text-green has-text-weight-normal">Quantity <sup class="has-text-danger">*</sup> </label>
                        <div class="field has-addons">
                            <div class="control has-icons-left is-expanded">
                                <input id="sale[${index}][quantity]" name="sale[${index}][quantity]" type="number" class="input" placeholder="Sale Quantity">
                                <span class="icon is-small is-left">
                                    <i class="fas fa-balance-scale"></i>
                                </span>
                            </div>
                            <div class="control">
                                <button id="sale[${index}][product_id]Quantity" class="button bg-green has-text-white" type="button"></button>
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <label for="sale[${index}][unit_price]" class="label text-green has-text-weight-normal">Unit Price<sup class="has-text-weight-light"> (Before VAT)</sup> <sup class="has-text-danger">*</sup> </label>
                        <div class="field has-addons">
                            <div class="control has-icons-left is-expanded">
                                <input id="sale[${index}][unit_price]" name="sale[${index}][unit_price]" type="number" class="input" placeholder="Sale Price">
                                <span class="icon is-small is-left">
                                    <i class="fas fa-money-bill"></i>
                                </span>
                            </div>
                            <div class="control">
                                <button id="sale[${index}][product_id]Price" class="button bg-green has-text-white" type="button"></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;

        saleFormWrapper.insertAdjacentHTML("beforeend", createSaleForm);

        let currentSelect = d.getElementById(`sale[${index}][product_id]`);
        let previousSelect = d.getElementById(`sale[${index - 1}][product_id]`);

        for (let j = 0; j < previousSelect.length; j++) {
            if (!previousSelect.options[j].selected)
                previousSelect.options[j].hidden = true;
        }

        for (let i = 0; i < currentSelect.length; i++) {
            if (currentSelect.options[i].value == previousSelect.value)
                currentSelect.remove(i);
        }

        productList = currentSelect;

        index++;

        initializeSelect2Products();

        if (index == formLimit) {
            d.getElementById("addNewSaleForm").remove();
            return false;
        }
    };
})();

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

function showOnHandMerchandise() {
    this.classList.add("is-active");
    d.getElementById("onHand").classList.remove("is-hidden");
    adjustDataTablesColumns("table.regular-datatable");

    hideAvailableMerchandise();
    hideReservedMerchandise();
    hideOutofMerchandise();
}

function showAvailableMerchandise() {
    this.classList.add("is-active");
    d.getElementById("available").classList.remove("is-hidden");
    adjustDataTablesColumns("table.regular-datatable");

    hideOnHandMerchandise();
    hideReservedMerchandise();
    hideOutofMerchandise();
}

function showReservedMerchandise() {
    this.classList.add("is-active");
    d.getElementById("reserved").classList.remove("is-hidden");
    adjustDataTablesColumns("table.regular-datatable");

    hideOnHandMerchandise();
    hideAvailableMerchandise();
    hideOutofMerchandise();
}

function showOutofMerchandise() {
    this.classList.add("is-active");
    d.getElementById("outOf").classList.remove("is-hidden");

    hideOnHandMerchandise();
    hideAvailableMerchandise();
    hideReservedMerchandise();
}

function hideOnHandMerchandise() {
    let onHandTab = d.getElementById("onHandTab");
    if (onHandTab) {
        onHandTab.classList.remove("is-active");
        d.getElementById("onHand").classList.add("is-hidden");
    }
}

function hideAvailableMerchandise() {
    let availableTab = d.getElementById("availableTab");
    if (availableTab) {
        availableTab.classList.remove("is-active");
        d.getElementById("available").classList.add("is-hidden");
    }
}

function hideReservedMerchandise() {
    let reservedTab = d.getElementById("reservedTab");
    if (reservedTab) {
        reservedTab.classList.remove("is-active");
        d.getElementById("reserved").classList.add("is-hidden");
    }
}

function hideOutofMerchandise() {
    let outOfTab = d.getElementById("outOfTab");
    if (outOfTab) {
        outOfTab.classList.remove("is-active");
        d.getElementById("outOf").classList.add("is-hidden");
    }
}

function disableSaveButton() {
    let saveButton = d.getElementById("saveButton");
    saveButton.classList.add("is-loading");
    saveButton.disabled = true;
}

function openCloseSaleModal(event) {
    event.preventDefault();
    swal({
        title: "Do you want to subtract?",
        text: "By clicking 'Yes, Subtract', you are going to subtract the products from inventory.",
        buttons: ["Not now", "Yes, Subtract"],
    }).then((willCloseSale) => {
        if (willCloseSale) {
            d.getElementById("formOne").submit();
        }
    });
}

function changeWarehouse() {
    if (this.value == 0) {
        return (location.href = "/merchandises");
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

const addGdnForm = (function () {
    const gdnFormGroup = d.getElementsByName("gdnFormGroup");
    const gdnFormWrapper = d.getElementById("gdnFormWrapper");
    const productList = d.getElementById("gdn[0][product_id]");
    const warehouseList = d.getElementById("gdn[0][warehouse_id]");
    const formLimit = 10;
    let index = gdnFormGroup.length;

    if (!gdnFormWrapper) {
        return false;
    }

    return function () {
        const createGdnForm = `
        <div class="has-text-weight-medium has-text-left">
            <span class="tag bg-green has-text-white is-medium radius-bottom-0">
                Item ${index + 1}
            </span>
        </div>
        <div class="box has-background-white-bis radius-top-0 mb-5">
            <div name="gdnFormGroup" class="columns is-marginless is-multiline">
                <div class="column is-6">
                    <div class="field">
                        <label for="gdn[${index}][product_id]" class="label text-green has-text-weight-normal"> Product <sup class="has-text-danger">*</sup> </label>
                        <div class="control has-icons-left">
                            <div class="select is-fullwidth">
                                <select id="gdn[${index}][product_id]" name="gdn[${index}][product_id]" class="select2-products" onchange="getProductSelected(this.id, this.value)">
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
                        <label for="gdn[${index}][warehouse_id]" class="label text-green has-text-weight-normal"> From <sup class="has-text-danger">*</sup> </label>
                        <div class="control has-icons-left">
                            <div class="select is-fullwidth">
                                <select id="gdn[${index}][warehouse_id]" name="gdn[${index}][warehouse_id]">
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
                    <label for="gdn[${index}][quantity]" class="label text-green has-text-weight-normal">Quantity <sup class="has-text-danger">*</sup> </label>
                    <div class="field has-addons">
                        <div class="control has-icons-left is-expanded">
                            <input id="gdn[${index}][quantity]" name="gdn[${index}][quantity]" type="number" class="input" placeholder="Quantity">
                            <span class="icon is-small is-left">
                                <i class="fas fa-balance-scale"></i>
                            </span>
                        </div>
                        <div class="control">
                            <button id="gdn[${index}][product_id]Quantity" class="button bg-green has-text-white" type="button"></button>
                        </div>
                    </div>
                </div>
                <div class="column is-6">
                        <label for="gdn[${index}][unit_price]" class="label text-green has-text-weight-normal">Unit Price<sup class="has-text-weight-light"> (Before VAT)</sup> <sup class="has-text-danger"></sup> </label>
                        <div class="field has-addons">
                            <div class="control has-icons-left is-expanded">
                                <input id="gdn[${index}][unit_price]" name="gdn[${index}][unit_price]" type="number" class="input" placeholder="Sale Price" value="0.00">
                                <span class="icon is-small is-left">
                                    <i class="fas fa-money-bill"></i>
                                </span>
                            </div>
                            <div class="control">
                                <button id="gdn[${index}][product_id]Price" class="button bg-green has-text-white" type="button"></button>
                            </div>
                        </div>
                    </div>
                <div class="column is-6">
                    <div class="field">
                        <label for="gdn[${index}][description]" class="label text-green has-text-weight-normal">Additional Notes <sup class="has-text-danger"></sup></label>
                        <div class="control has-icons-left">
                            <textarea name="gdn[${index}][description]" id="gdn[${index}][description]" cols="30" rows="3" class="textarea pl-6" placeholder="Description or note to be taken"></textarea>
                            <span class="icon is-large is-left">
                                <i class="fas fa-edit"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>`;

        gdnFormWrapper.insertAdjacentHTML("beforeend", createGdnForm);

        index++;

        initializeSelect2Products();

        if (index == formLimit) {
            d.getElementById("addNewGdnForm").remove();
            return false;
        }
    };
})();

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

const addTransferForm = (function () {
    const transferFormGroup = d.getElementsByName("transferFormGroup");
    const transferFormWrapper = d.getElementById("transferFormWrapper");
    const productList = d.getElementById("transfer[0][product_id]");
    const warehouseList = d.getElementById("transfer[0][warehouse_id]");
    const formLimit = 10;
    let index = transferFormGroup.length;

    if (!transferFormWrapper) {
        return false;
    }

    return function () {
        const createTransferForm = `
        <div class="has-text-weight-medium has-text-left">
            <span class="tag bg-green has-text-white is-medium radius-bottom-0">
                Item ${index + 1}
            </span>
        </div>
        <div class="box has-background-white-bis radius-top-0 mb-5">
            <div name="transferFormGroup" class="columns is-marginless is-multiline">
                <div class="column is-6">
                    <div class="field">
                        <label for="transfer[${index}][product_id]" class="label text-green has-text-weight-normal"> Product <sup class="has-text-danger">*</sup> </label>
                        <div class="control has-icons-left">
                            <div class="select is-fullwidth">
                                <select id="transfer[${index}][product_id]" name="transfer[${index}][product_id]" class="select2-products" onchange="getProductSelected(this.id, this.value)">
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
                    <label for="transfer[${index}][quantity]" class="label text-green has-text-weight-normal">Quantity <sup class="has-text-danger">*</sup> </label>
                    <div class="field has-addons">
                        <div class="control has-icons-left is-expanded">
                            <input id="transfer[${index}][quantity]" name="transfer[${index}][quantity]" type="number" class="input" placeholder="Quantity">
                            <span class="icon is-small is-left">
                                <i class="fas fa-balance-scale"></i>
                            </span>
                        </div>
                        <div class="control">
                            <button id="transfer[${index}][product_id]Quantity" class="button bg-green has-text-white" type="button"></button>
                        </div>
                    </div>
                </div>
                <div class="column is-6">
                    <div class="field">
                        <label for="transfer[${index}][warehouse_id]" class="label text-green has-text-weight-normal"> From <sup class="has-text-danger">*</sup> </label>
                        <div class="control has-icons-left">
                            <div class="select is-fullwidth">
                                <select id="transfer[${index}][warehouse_id]" name="transfer[${index}][warehouse_id]">
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
                    <div class="field">
                        <label for="transfer[${index}][to_warehouse_id]" class="label text-green has-text-weight-normal"> To <sup class="has-text-danger">*</sup> </label>
                        <div class="control has-icons-left">
                            <div class="select is-fullwidth">
                                <select id="transfer[${index}][to_warehouse_id]" name="transfer[${index}][to_warehouse_id]">
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
                    <div class="field">
                        <label for="transfer[${index}][description]" class="label text-green has-text-weight-normal">Additional Notes <sup class="has-text-danger"></sup></label>
                        <div class="control has-icons-left">
                            <textarea name="transfer[${index}][description]" id="transfer[${index}][description]" cols="30" rows="3" class="textarea pl-6" placeholder="Description or note to be taken"></textarea>
                            <span class="icon is-large is-left">
                                <i class="fas fa-edit"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>`;

        transferFormWrapper.insertAdjacentHTML("beforeend", createTransferForm);

        index++;

        initializeSelect2Products();

        if (index == formLimit) {
            d.getElementById("addNewTransferForm").remove();
            return false;
        }
    };
})();

function openTransferModal(event) {
    event.preventDefault();
    swal({
        title: "Do you want to transfer?",
        text: "By clicking 'Yes, Transfer', you are going to transfer the products.",
        buttons: ["Not now", "Yes, Transfer"],
        dangerMode: true,
    }).then((willTransfer) => {
        if (willTransfer) {
            d.getElementById("formOne").submit();
        }
    });
}

const addPurchaseOrderForm = (function () {
    const purchaseOrderFormGroup = d.getElementsByName(
        "purchaseOrderFormGroup"
    );
    const purchaseOrderFormWrapper = d.getElementById(
        "purchaseOrderFormWrapper"
    );
    const productList = d.getElementById("purchaseOrder[0][product_id]");
    const formLimit = 10;
    let index = purchaseOrderFormGroup.length;

    if (!purchaseOrderFormWrapper) {
        return false;
    }

    return function () {
        const createPurchaseOrderForm = `
        <div class="has-text-weight-medium has-text-left">
            <span class="tag bg-green has-text-white is-medium radius-bottom-0">
                Item ${index + 1}
            </span>
        </div>
        <div class="box has-background-white-bis radius-top-0 mb-5">
            <div name="purchaseOrderFormGroup" class="columns is-marginless is-multiline">
                <div class="column is-6">
                    <div class="field">
                        <label for="purchaseOrder[${index}][product_id]" class="label text-green has-text-weight-normal"> Product <sup class="has-text-danger">*</sup> </label>
                        <div class="control has-icons-left">
                            <div class="select is-fullwidth">
                                <select id="purchaseOrder[${index}][product_id]" name="purchaseOrder[${index}][product_id]" class="select2-products" onchange="getProductSelected(this.id, this.value)">
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
                    <label for="purchaseOrder[${index}][quantity]" class="label text-green has-text-weight-normal">Quantity <sup class="has-text-danger">*</sup> </label>
                    <div class="field has-addons">
                        <div class="control has-icons-left is-expanded">
                            <input id="purchaseOrder[${index}][quantity]" name="purchaseOrder[${index}][quantity]" type="number" class="input" placeholder="Quantity">
                            <span class="icon is-small is-left">
                                <i class="fas fa-balance-scale"></i>
                            </span>
                        </div>
                        <div class="control">
                            <button id="purchaseOrder[${index}][product_id]Quantity" class="button bg-green has-text-white" type="button"></button>
                        </div>
                    </div>
                </div>
                <div class="column is-6">
                    <label for="purchaseOrder[${index}][unit_price]" class="label text-green has-text-weight-normal">Unit Price<sup class="has-text-weight-light"> (Before VAT)</sup> <sup class="has-text-danger">*</sup> </label>
                    <div class="field has-addons">
                        <div class="control has-icons-left is-expanded">
                            <input id="purchaseOrder[${index}][unit_price]" name="purchaseOrder[${index}][unit_price]" type="number" class="input" placeholder="Unit Price">
                            <span class="icon is-small is-left">
                                <i class="fas fa-balance-scale"></i>
                            </span>
                        </div>
                        <div class="control">
                            <button id="purchaseOrder[${index}][product_id]Price" class="button bg-green has-text-white" type="button"></button>
                        </div>
                    </div>
                </div>
                <div class="column is-6">
                    <div class="field">
                        <label for="purchaseOrder[${index}][description]" class="label text-green has-text-weight-normal">Additional Notes <sup class="has-text-danger"></sup></label>
                        <div class="control has-icons-left">
                            <textarea name="purchaseOrder[${index}][description]" id="purchaseOrder[${index}][description]" cols="30" rows="3" class="textarea pl-6" placeholder="Description or note to be taken"></textarea>
                            <span class="icon is-large is-left">
                                <i class="fas fa-edit"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>`;

        purchaseOrderFormWrapper.insertAdjacentHTML(
            "beforeend",
            createPurchaseOrderForm
        );

        index++;

        initializeSelect2Products();

        if (index == formLimit) {
            d.getElementById("addNewPurchaseOrderForm").remove();
            return false;
        }
    };
})();

function closePurchaseOrderModal(event) {
    event.preventDefault();
    swal({
        title: "Do you want to close this PO?",
        text: "By clicking 'Yes, Close', you are going to close this PO and the remaining quantities will be set to '0'.",
        buttons: ["Not now", "Yes, Close"],
        dangerMode: true,
    }).then((willClose) => {
        if (willClose) {
            d.getElementById("formOne").submit();
        }
    });
}

function openAddGrnModal(event) {
    event.preventDefault();
    swal({
        title: "Do you want to add to inventory?",
        text: "By clicking 'Yes, Add', you are going to add the products.",
        buttons: ["Not now", "Yes, Add"],
        dangerMode: true,
    }).then((willTransfer) => {
        if (willTransfer) {
            d.getElementById("formOne").submit();
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

function openApproveGdnModal(event) {
    event.preventDefault();
    swal({
        title: "Do you want to approve this DO/GDN?",
        text: "By clicking 'Yes, Approve', you are going to approve this DO/GDN.",
        buttons: ["Not now", "Yes, Approve"],
        dangerMode: true,
    }).then((willTransfer) => {
        if (willTransfer) {
            d.getElementById("formOne").submit();
        }
    });
}

function openApproveGrnModal(event) {
    event.preventDefault();
    swal({
        title: "Do you want to approve this GRN?",
        text: "By clicking 'Yes, Approve', you are going to approve this GRN.",
        buttons: ["Not now", "Yes, Approve"],
        dangerMode: true,
    }).then((willTransfer) => {
        if (willTransfer) {
            d.getElementById("formOne").submit();
        }
    });
}

function openApproveTransferModal(event) {
    event.preventDefault();
    swal({
        title: "Do you want to approve this Transfer?",
        text: "By clicking 'Yes, Approve', you are going to approve this Transfer.",
        buttons: ["Not now", "Yes, Approve"],
        dangerMode: true,
    }).then((willTransfer) => {
        if (willTransfer) {
            d.getElementById("formOne").submit();
        }
    });
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

async function getUnreadNotifications() {
    const response = await axios.get("/notifications/unread");
    const unreadNotifications = response.data;

    return unreadNotifications;
}

async function showNewNotifications() {
    const notificationBody = d.getElementById("notificationBody");
    const notificationCountDesktop = d.getElementById(
        "notificationCountDesktop"
    );
    const notificationCountMobile = d.getElementById("notificationCountMobile");
    const unreadNotifications = await getUnreadNotifications();
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
                            just now
                        </span>
                    </div>
                </div>
                <hr class="mt-0 mb-0">`;
        }

        notificationBody.innerHTML = notification;
    }
}

function markNotificationAsRead(event) {
    if (event.target.classList.contains("unreadNotifications")) {
        axios.get(
            `/notifications/${event.target.dataset.notificationId}/mark-as-read`
        );
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

function openApproveSivModal(event) {
    event.preventDefault();
    swal({
        title: "Do you want to approve this SIV?",
        text: "By clicking 'Yes, Approve', you are going to approve this SIV.",
        buttons: ["Not now", "Yes, Approve"],
        dangerMode: true,
    }).then((willApprove) => {
        if (willApprove) {
            d.getElementById("formOne").submit();
        }
    });
}

function openExecuteSivModal(event) {
    event.preventDefault();
    swal({
        title: "Do you want to execute this SIV?",
        text: "By clicking 'Yes, Execute', you are going to execute this SIV.",
        buttons: ["Not now", "Yes, Execute"],
        dangerMode: true,
    }).then((willExecute) => {
        if (willExecute) {
            d.getElementById("formOne").submit();
        }
    });
}

const addProformaInvoiceForm = (function () {
    const proformaInvoiceFormGroup = d.getElementsByName(
        "proformaInvoiceFormGroup"
    );
    const proformaInvoiceFormWrapper = d.getElementById(
        "proformaInvoiceFormWrapper"
    );
    let productList = d.getElementById("proformaInvoice[0][product_id]");
    let index = proformaInvoiceFormGroup.length;

    if (!proformaInvoiceFormWrapper) {
        return false;
    }

    return function () {
        const createProformaInvoiceForm = `
            <div class="has-text-weight-medium has-text-left">
                <span class="tag bg-green has-text-white is-medium radius-bottom-0">
                    Item ${index + 1}
                </span>
            </div>
            <div class="box has-background-white-bis radius-top-0 mb-5">
                <div name="proformaInvoiceFormGroup" class="columns is-marginless is-multiline">
                    <div class="column is-6">
                        <div class="field">
                            <label for="proformaInvoice[${index}][product_id]" class="label text-green has-text-weight-normal"> Product <sup class="has-text-danger">*</sup> </label>
                            <div class="control has-icons-left">
                                <div class="select is-fullwidth">
                                    <select id="proformaInvoice[${index}][product_id]" name="proformaInvoice[${index}][product_id]" class="select2-products" onchange="getProductSelected(this.id, this.value)">
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
                        <label for="proformaInvoice[${index}][quantity]" class="label text-green has-text-weight-normal">Quantity <sup class="has-text-danger">*</sup> </label>
                        <div class="field has-addons">
                            <div class="control has-icons-left is-expanded">
                                <input id="proformaInvoice[${index}][quantity]" name="proformaInvoice[${index}][quantity]" type="number" class="input" placeholder="Product Quantity">
                                <span class="icon is-small is-left">
                                    <i class="fas fa-balance-scale"></i>
                                </span>
                            </div>
                            <div class="control">
                                <button id="proformaInvoice[${index}][product_id]Quantity" class="button bg-green has-text-white" type="button"></button>
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <label for="proformaInvoice[${index}][unit_price]" class="label text-green has-text-weight-normal">Unit Price<sup class="has-text-weight-light"> (Before VAT)</sup> <sup class="has-text-danger">*</sup> </label>
                        <div class="field has-addons">
                            <div class="control has-icons-left is-expanded">
                                <input id="proformaInvoice[${index}][unit_price]" name="proformaInvoice[${index}][unit_price]" type="number" class="input" placeholder="Unit Price">
                                <span class="icon is-small is-left">
                                    <i class="fas fa-money-bill"></i>
                                </span>
                            </div>
                            <div class="control">
                                <button id="proformaInvoice[${index}][product_id]Price" class="button bg-green has-text-white" type="button"></button>
                            </div>
                        </div>
                    </div>
                    <div class="column is-6">
                        <label for="proformaInvoice[${index}][discount]" class="label text-green has-text-weight-normal">Discount <sup class="has-text-danger"></sup> </label>
                        <div class="field">
                            <div class="control has-icons-left is-expanded">
                                <input id="proformaInvoice[${index}][discount]" name="proformaInvoice[${index}][discount]" type="number" class="input" placeholder="Discount in Percentage">
                                <span class="icon is-small is-left">
                                    <i class="fas fa-percent"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="column is-12">
                        <div class="field">
                            <label for="proformaInvoice[${index}][specification]" class="label text-green has-text-weight-normal">Specifications <sup class="has-text-danger"></sup> </label>
                            <div class="control">
                                <textarea name="proformaInvoice[${index}][specification]" id="proformaInvoice[${index}][specification]" cols="30" rows="5" class="summernote textarea" placeholder="Specification about the product"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;

        proformaInvoiceFormWrapper.insertAdjacentHTML(
            "beforeend",
            createProformaInvoiceForm
        );

        index++;

        initializeSelect2Products();

        initializeSummernote();
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

const addAdjustmentForm = (function () {
    const adjustmentFormGroup = d.getElementsByName("adjustmentFormGroup");
    const adjustmentFormWrapper = d.getElementById("adjustmentFormWrapper");
    const productList = d.getElementById("adjustment[0][product_id]");
    const warehouseList = d.getElementById("adjustment[0][warehouse_id]");
    const typeList = d.getElementById("adjustment[0][is_subtract]");
    let index = adjustmentFormGroup.length;

    if (!adjustmentFormWrapper) {
        return false;
    }

    return function () {
        const createAdjustmentForm = `
        <div class="has-text-weight-medium has-text-left">
            <span class="tag bg-green has-text-white is-medium radius-bottom-0">
                Item ${index + 1}
            </span>
        </div>
        <div class="box has-background-white-bis radius-top-0 mb-5">
            <div name="adjustmentFormGroup" class="columns is-marginless is-multiline">
                <div class="column is-6">
                    <div class="field">
                        <label for="adjustment[${index}][product_id]" class="label text-green has-text-weight-normal"> Product <sup class="has-text-danger">*</sup> </label>
                        <div class="control has-icons-left">
                            <div class="select is-fullwidth">
                                <select id="adjustment[${index}][product_id]" name="adjustment[${index}][product_id]" class="select2-products" onchange="getProductSelected(this.id, this.value)">
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
                    <label for="adjustment[${index}][quantity]" class="label text-green has-text-weight-normal">Quantity <sup class="has-text-danger">*</sup> </label>
                    <div class="field has-addons">
                        <div class="control has-icons-left is-expanded">
                            <input id="adjustment[${index}][quantity]" name="adjustment[${index}][quantity]" type="number" class="input" placeholder="Quantity">
                            <span class="icon is-small is-left">
                                <i class="fas fa-balance-scale"></i>
                            </span>
                        </div>
                        <div class="control">
                            <button id="adjustment[${index}][product_id]Quantity" class="button bg-green has-text-white" type="button"></button>
                        </div>
                    </div>
                </div>
                <div class="column is-6">
                    <div class="field">
                        <label for="adjustment[${index}][is_subtract]" class="label text-green has-text-weight-normal"> Operation <sup class="has-text-danger">*</sup> </label>
                        <div class="control has-icons-left">
                            <div class="select is-fullwidth">
                                <select id="adjustment[${index}][is_subtract]" name="adjustment[${index}][is_subtract]">
                                    ${typeList.innerHTML}
                                </select>
                            </div>
                            <div class="icon is-small is-left">
                                <i class="fas fa-sort"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="column is-6">
                    <div class="field">
                        <label for="adjustment[${index}][warehouse_id]" class="label text-green has-text-weight-normal"> Warehouse <sup class="has-text-danger">*</sup> </label>
                        <div class="control has-icons-left">
                            <div class="select is-fullwidth">
                                <select id="adjustment[${index}][warehouse_id]" name="adjustment[${index}][warehouse_id]">
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
                    <div class="field">
                        <label for="adjustment[${index}][reason]" class="label text-green has-text-weight-normal">Reason <sup class="has-text-danger">*</sup></label>
                        <div class="control has-icons-left">
                            <textarea name="adjustment[${index}][reason]" id="adjustment[${index}][reason]" cols="30" rows="3" class="textarea pl-6" placeholder="Describe reason for adjusting this product level"></textarea>
                            <span class="icon is-large is-left">
                                <i class="fas fa-edit"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>`;

        adjustmentFormWrapper.insertAdjacentHTML(
            "beforeend",
            createAdjustmentForm
        );

        index++;

        initializeSelect2Products();
    };
})();

const addReturnForm = (function () {
    const returnFormGroup = d.getElementsByName("returnFormGroup");
    const returnFormWrapper = d.getElementById("returnFormWrapper");
    const productList = d.getElementById("return[0][product_id]");
    const warehouseList = d.getElementById("return[0][warehouse_id]");
    let index = returnFormGroup.length;

    if (!returnFormWrapper) {
        return false;
    }

    return function () {
        const createReturnForm = `
        <div class="has-text-weight-medium has-text-left">
            <span class="tag bg-green has-text-white is-medium radius-bottom-0">
                Item ${index + 1}
            </span>
        </div>
        <div class="box has-background-white-bis radius-top-0 mb-5">
            <div name="returnFormGroup" class="columns is-marginless is-multiline">
                <div class="column is-6">
                    <div class="field">
                        <label for="return[${index}][product_id]" class="label text-green has-text-weight-normal"> Product <sup class="has-text-danger">*</sup> </label>
                        <div class="control has-icons-left">
                            <div class="select is-fullwidth">
                                <select id="return[${index}][product_id]" name="return[${index}][product_id]" class="select2-products" onchange="getProductSelected(this.id, this.value)">
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
                        <label for="return[${index}][warehouse_id]" class="label text-green has-text-weight-normal"> To <sup class="has-text-danger">*</sup> </label>
                        <div class="control has-icons-left">
                            <div class="select is-fullwidth">
                                <select id="return[${index}][warehouse_id]" name="return[${index}][warehouse_id]">
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
                    <label for="return[${index}][quantity]" class="label text-green has-text-weight-normal">Quantity <sup class="has-text-danger">*</sup> </label>
                    <div class="field has-addons">
                        <div class="control has-icons-left is-expanded">
                            <input id="return[${index}][quantity]" name="return[${index}][quantity]" type="number" class="input" placeholder="Quantity">
                            <span class="icon is-small is-left">
                                <i class="fas fa-balance-scale"></i>
                            </span>
                        </div>
                        <div class="control">
                            <button id="return[${index}][product_id]Quantity" class="button bg-green has-text-white" type="button"></button>
                        </div>
                    </div>
                </div>
                <div class="column is-6">
                        <label for="return[${index}][unit_price]" class="label text-green has-text-weight-normal">Unit Price<sup class="has-text-weight-light"> (Before VAT)</sup> <sup class="has-text-danger"></sup> </label>
                        <div class="field has-addons">
                            <div class="control has-icons-left is-expanded">
                                <input id="return[${index}][unit_price]" name="return[${index}][unit_price]" type="number" class="input" placeholder="Sale Price" value="0.00">
                                <span class="icon is-small is-left">
                                    <i class="fas fa-money-bill"></i>
                                </span>
                            </div>
                            <div class="control">
                                <button id="return[${index}][product_id]Price" class="button bg-green has-text-white" type="button"></button>
                            </div>
                        </div>
                    </div>
                <div class="column is-6">
                    <div class="field">
                        <label for="return[${index}][description]" class="label text-green has-text-weight-normal">Additional Notes <sup class="has-text-danger"></sup></label>
                        <div class="control has-icons-left">
                            <textarea name="return[${index}][description]" id="return[${index}][description]" cols="30" rows="3" class="textarea pl-6" placeholder="Description or note to be taken"></textarea>
                            <span class="icon is-large is-left">
                                <i class="fas fa-edit"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>`;

        returnFormWrapper.insertAdjacentHTML("beforeend", createReturnForm);

        index++;

        initializeSelect2Products();
    };
})();

const addReservationForm = (function () {
    const reservationFormGroup = d.getElementsByName("reservationFormGroup");
    const reservationFormWrapper = d.getElementById("reservationFormWrapper");
    const productList = d.getElementById("reservation[0][product_id]");
    const warehouseList = d.getElementById("reservation[0][warehouse_id]");
    const formLimit = 10;
    let index = reservationFormGroup.length;

    if (!reservationFormWrapper) {
        return false;
    }

    return function () {
        const createReservationForm = `
        <div class="has-text-weight-medium has-text-left">
            <span class="tag bg-green has-text-white is-medium radius-bottom-0">
                Item ${index + 1}
            </span>
        </div>
        <div class="box has-background-white-bis radius-top-0 mb-5">
            <div name="reservationFormGroup" class="columns is-marginless is-multiline">
                <div class="column is-6">
                    <div class="field">
                        <label for="reservation[${index}][product_id]" class="label text-green has-text-weight-normal"> Product <sup class="has-text-danger">*</sup> </label>
                        <div class="control has-icons-left">
                            <div class="select is-fullwidth">
                                <select id="reservation[${index}][product_id]" name="reservation[${index}][product_id]" class="select2-products" onchange="getProductSelected(this.id, this.value)">
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
                        <label for="reservation[${index}][warehouse_id]" class="label text-green has-text-weight-normal"> From <sup class="has-text-danger">*</sup> </label>
                        <div class="control has-icons-left">
                            <div class="select is-fullwidth">
                                <select id="reservation[${index}][warehouse_id]" name="reservation[${index}][warehouse_id]">
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
                    <label for="reservation[${index}][quantity]" class="label text-green has-text-weight-normal">Quantity <sup class="has-text-danger">*</sup> </label>
                    <div class="field has-addons">
                        <div class="control has-icons-left is-expanded">
                            <input id="reservation[${index}][quantity]" name="reservation[${index}][quantity]" type="number" class="input" placeholder="Quantity">
                            <span class="icon is-small is-left">
                                <i class="fas fa-balance-scale"></i>
                            </span>
                        </div>
                        <div class="control">
                            <button id="reservation[${index}][product_id]Quantity" class="button bg-green has-text-white" type="button"></button>
                        </div>
                    </div>
                </div>
                <div class="column is-6">
                        <label for="reservation[${index}][unit_price]" class="label text-green has-text-weight-normal">Unit Price<sup class="has-text-weight-light"> (Before VAT)</sup> <sup class="has-text-danger"></sup> </label>
                        <div class="field has-addons">
                            <div class="control has-icons-left is-expanded">
                                <input id="reservation[${index}][unit_price]" name="reservation[${index}][unit_price]" type="number" class="input" placeholder="Sale Price" value="0.00">
                                <span class="icon is-small is-left">
                                    <i class="fas fa-money-bill"></i>
                                </span>
                            </div>
                            <div class="control">
                                <button id="reservation[${index}][product_id]Price" class="button bg-green has-text-white" type="button"></button>
                            </div>
                        </div>
                    </div>
                <div class="column is-6">
                    <div class="field">
                        <label for="reservation[${index}][description]" class="label text-green has-text-weight-normal">Additional Notes <sup class="has-text-danger"></sup></label>
                        <div class="control has-icons-left">
                            <textarea name="reservation[${index}][description]" id="reservation[${index}][description]" cols="30" rows="3" class="textarea pl-6" placeholder="Description or note to be taken"></textarea>
                            <span class="icon is-large is-left">
                                <i class="fas fa-edit"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>`;

        reservationFormWrapper.insertAdjacentHTML(
            "beforeend",
            createReservationForm
        );

        index++;

        initializeSelect2Products();

        if (index == formLimit) {
            d.getElementById("addNewReservationForm").remove();
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
