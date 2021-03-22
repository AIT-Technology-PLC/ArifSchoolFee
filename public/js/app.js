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
    d.getElementById("createMenu").classList.toggle("is-hidden");
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
                                    <select id="purchase[${index}][product_id]" name="purchase[${index}][product_id]" onchange="getProductSelected(this.id, this.value)">
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
                        <label for="purchase[${index}][unit_price]" class="label text-green has-text-weight-normal">Unit Price <sup class="has-text-danger">*</sup> </label>
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
                                    <select id="sale[${index}][product_id]" name="sale[${index}][product_id]" onchange="getProductSelected(this.id, this.value)">
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
                        <label for="sale[${index}][unit_price]" class="label text-green has-text-weight-normal">Unit Price <sup class="has-text-danger">*</sup> </label>
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

        if (index == formLimit) {
            d.getElementById("addNewSaleForm").remove();
            return false;
        }
    };
})();

function jumpToCurrentPageMenuTitle() {
    let menuTitles = d.getElementsByName("menuTitles");

    let currentMenuTitle = Object.values(menuTitles).filter(
        (menuTitle) => menuTitle.href == location.href
    );

    if (location.pathname.endsWith("/") || !currentMenuTitle.length) {
        return;
    }

    currentMenuTitle = currentMenuTitle.pop();
    currentMenuTitle = currentMenuTitle.parentElement.parentElement;

    if (currentMenuTitle.previousElementSibling) {
        currentMenuTitle.previousElementSibling.scrollIntoView();
    } else {
        currentMenuTitle.parentElement.parentElement.previousElementSibling.scrollIntoView();
    }
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

    hideHistoryMerchandise();
    hideOutofMerchandise();
}

function showHistoryMerchandise() {
    this.classList.add("is-active");
    d.getElementById("historyMerchandise").classList.remove("is-hidden");

    hideOnHandMerchandise();
}

function showOutofMerchandise() {
    this.classList.add("is-active");
    d.getElementById("outOf").classList.remove("is-hidden");

    hideOnHandMerchandise();
}

function hideOnHandMerchandise() {
    let onHandTab = d.getElementById("onHandTab");
    if (onHandTab) {
        onHandTab.classList.remove("is-active");
        d.getElementById("onHand").classList.add("is-hidden");
    }
}

function hideHistoryMerchandise() {
    let historyTab = d.getElementById("historyTab");
    if (historyTab) {
        historyTab.classList.remove("is-active");
        d.getElementById("historyMerchandise").classList.add("is-hidden");
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
        text:
            "By clicking 'Yes, Subtract', you are going to subtract the products from inventory.",
        buttons: ["Not now", "Yes, Subtract"],
    }).then((willCloseSale) => {
        if (willCloseSale) {
            d.getElementById("formOne").submit();
        }
    });
}

function changeWarehouse() {
    if (this.value == 0) {
        return (location.href = "/merchandises/level");
    }

    location.href = `/merchandises/level/warehouse/${this.value}`;
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
                                <select id="gdn[${index}][product_id]" name="gdn[${index}][product_id]" onchange="getProductSelected(this.id, this.value)">
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
                        <label for="gdn[${index}][unit_price]" class="label text-green has-text-weight-normal">Unit Price <sup class="has-text-danger"></sup> </label>
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

        if (index == formLimit) {
            d.getElementById("addNewGdnForm").remove();
            return false;
        }
    };
})();

function disableDeleteForm(event) {
    let target = event.target;
    let deleteId = target.dataset.delete;

    while (!deleteId) {
        if (target.tagName == "TABLE") {
            return;
        }
        deleteId = target.dataset.delete;
        target = target.parentElement;
    }

    if (deleteId) {
        event.preventDefault();
        swal({
            title: "Delete Permanently??",
            text: "The selected element will be deleted permanently!",
            icon: "error",
            buttons: ["Not now", "Yes, Delete Forever"],
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                let deleteForm = d.getElementById(`deleteForm${deleteId}`);
                let deleteButton = deleteForm.querySelector("button");
                deleteButton.innerText = "Deleting ...";
                deleteButton.disabled = true;
                deleteForm.submit();
            }
        });
    }
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
                                <select id="transfer[${index}][product_id]" name="transfer[${index}][product_id]" onchange="getProductSelected(this.id, this.value)">
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
        text:
            "By clicking 'Yes, Transfer', you are going to transfer the products.",
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
                                <select id="purchaseOrder[${index}][product_id]" name="purchaseOrder[${index}][product_id]" onchange="getProductSelected(this.id, this.value)">
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
                    <label for="purchaseOrder[${index}][unit_price]" class="label text-green has-text-weight-normal">Unit Price <sup class="has-text-danger">*</sup> </label>
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
        text:
            "By clicking 'Yes, Close', you are going to close this PO and the remaining quantities will be set to '0'.",
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
                                <select id="grn[${index}][product_id]" name="grn[${index}][product_id]" onchange="getProductSelected(this.id, this.value)">
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

        if (index == formLimit) {
            d.getElementById("addNewGrnForm").remove();
            return false;
        }
    };
})();

function showTablesAfterCompleteLoad() {
    let table = d.getElementById("table_id");
    table.style.display = "table";
    d.getElementById("firstTarget").click();
    changeDtButton();
    removeDtSearchLabel();
}

function initiateDataTables() {
    const table = d.getElementById("table_id");
    let dateTargets = JSON.parse(table.dataset.date);
    let numericTargets = JSON.parse(table.dataset.numeric);
    $("#table_id").DataTable({
        order: [[0, "desc"]],
        responsive: true,
        scrollCollapse: true,
        scrollY: "500px",
        scrollX: true,
        columnDefs: [
            { type: "natural", targets: numericTargets },
            { type: "date", targets: dateTargets },
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

    showTablesAfterCompleteLoad();
}

function openApproveGdnModal(event) {
    event.preventDefault();
    swal({
        title: "Do you want to approve this DO/GDN?",
        text:
            "By clicking 'Yes, Approve', you are going to approve this DO/GDN.",
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
        text:
            "By clicking 'Yes, Approve', you are going to approve this Transfer.",
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
                                <select id="tender[${index}][product_id]" name="tender[${index}][product_id]" onchange="getProductSelected(this.id, this.value)">
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

        if (index == formLimit) {
            d.getElementById("addNewTenderForm").remove();
            return false;
        }
    };
})();

function changeDtButton() {
    d.getElementsByClassName("buttons-print")[0].classList =
        "button is-small btn-green is-outlined";
    d.getElementsByClassName("buttons-pdf")[0].classList =
        "button is-small btn-purple is-outlined";
    d.getElementsByClassName("buttons-excel")[0].classList =
        "button is-small btn-blue is-outlined";
    d.getElementsByClassName("buttons-colvis")[0].firstChild.innerText =
        "Hide Columns";
    d.getElementsByClassName("buttons-colvis")[0].classList =
        "button is-small btn-gold is-outlined";
}

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

function removeDtSearchLabel() {
    let tableFilter = d.getElementById("table_id_filter");
    tableFilter.firstElementChild.childNodes[1].placeholder = "Search";
    tableFilter.firstElementChild.firstChild.remove();
}

function toggleNotificationBox() {
    d.getElementById("notificationBox").classList.toggle("is-hidden");
}

async function getReadNotifications() {
    const response = await axios.get("/notifications/read");
    const readNotifications = response.data;

    return readNotifications;
}

async function getUnreadNotifications() {
    const response = await axios.get("/notifications/unread");
    const unreadNotifications = response.data;

    return unreadNotifications;
}

async function showNotifications() {
    const notificationBody = d.getElementById("notificationBody");
    const unreadNotifications = await getUnreadNotifications();
    const readNotifications = await getReadNotifications();
    const totalReadNotifications =
        readNotifications.length > 5 ? 5 : readNotifications.length;
    let notification = "";

    if (unreadNotifications.length) {
        for (let index = 0; index < unreadNotifications.length; index++) {
            notification += `
                <div class="columns is-marginless has-background-white-bis text-green py-3 is-size-6-5 is-mobile">
                    <div class="column is-1">
                        <span class="icon is-small">
                            <i class="fas fa-${unreadNotifications[index].data.icon}"></i>
                        </span>
                    </div>
                    <div class="column is-11 pl-1">
                        <a class="is-not-underlined" href="${unreadNotifications[index].data.endpoint}">
                            ${unreadNotifications[index].data.message}
                        </a>
                    </div>
                </div>
                <hr class="mt-0 mb-0"></hr>`;
        }
    }

    if (readNotifications.length) {
        for (let index = 0; index < totalReadNotifications; index++) {
            notification += `
                <div class="columns is-marginless has-background-white has-text-grey py-3 is-size-6-5 is-mobile">
                    <div class="column is-1">
                        <span class="icon is-small">
                            <i class="fas fa-${readNotifications[index].data.icon}"></i>
                        </span>
                    </div>
                    <div class="column is-11 pl-1">
                        <a class="is-not-underlined" href="${readNotifications[index].data.endpoint}">
                            ${readNotifications[index].data.message}
                        </a>
                    </div>
                </div>
                <hr class="mt-0 mb-0"></hr>`;
        }
    }

    if (!notification) {
        notification += `
            <div class="columns is-marginless has-background-white has-text-black py-3 is-size-6-5 is-mobile">
                <div class="column is-12">
                    <span>
                        No notifications
                    </span>
                </div>
            </div>`;
    }

    notificationBody.innerHTML = notification;
}
