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
    const productList = d.getElementById("purchase[0][product_id]");

    if (!purchaseFormWrapper) {
        return false;
    }

    let index = purchaseFormGroup.length;

    return function () {
        const createPurchaseForm = `
            <div class="mt-4">
                <span class="py-4 px-2 has-background-white-ter text-purple has-text-weight-medium">
                    Item ${index + 1}
                </span>
            </div>
            <div name="purchaseFormGroup" class="columns is-marginless is-multiline has-background-white-ter mb-5">
                <div class="column is-12">
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
            </div>`;

        purchaseFormWrapper.insertAdjacentHTML("beforeend", createPurchaseForm);

        index++;
    };
})();

const addSaleForm = (function () {
    const saleFormGroup = d.getElementsByName("saleFormGroup");
    const saleFormWrapper = d.getElementById("saleFormWrapper");
    let productList = d.getElementById("sale[0][product_id]");
    const formLimit = productList.length - 1;

    if (!saleFormWrapper) {
        return false;
    }

    let index = saleFormGroup.length;

    return function () {
        const createSaleForm = `
            <div class="mt-4">
                <span class="py-4 px-2 has-background-white-ter text-purple has-text-weight-medium">
                    Item ${index + 1}
                </span>
            </div>
            <div name="saleFormGroup" class="columns is-marginless is-multiline has-background-white-ter mb-5">
                <div class="column is-12">
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

    if (location.pathname.includes("/home") || !currentMenuTitle.length) {
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

function refreshPage() {
    return location.reload();
}

function openAddToInventoryModal() {
    d.getElementById("addToInventoryModal").classList.toggle("is-active");
}

function showOnHandMerchandise() {
    this.classList.add("is-active");
    d.getElementById("onHand").classList.remove("is-hidden");

    hideLimitedMerchandise();
    hideOutofMerchandise();
}

function showLimitedMerchandise() {
    this.classList.add("is-active");
    d.getElementById("limited").classList.remove("is-hidden");

    hideOutofMerchandise();
    hideOnHandMerchandise();
}

function showOutofMerchandise() {
    this.classList.add("is-active");
    d.getElementById("outOf").classList.remove("is-hidden");

    hideLimitedMerchandise();
    hideOnHandMerchandise();
}

function hideOnHandMerchandise() {
    d.getElementById("onHandTab").classList.remove("is-active");
    d.getElementById("onHand").classList.add("is-hidden");
}

function hideLimitedMerchandise() {
    d.getElementById("limitedTab").classList.remove("is-active");
    d.getElementById("limited").classList.add("is-hidden");
}

function hideOutofMerchandise() {
    d.getElementById("outOfTab").classList.remove("is-active");
    d.getElementById("outOf").classList.add("is-hidden");
}
