const d = document;

function hideMainMenuScroller() {
    d.getElementById("menuLeft").style.overflow = "hidden";
}

function hideMainMenuScrollerOnMouseOut() {
    this.style.overflow = "hidden";
}

function showMainMenuScrollerOnMouseOver() {
    this.style.overflow = "auto";
}

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

        newForm.innerHTML += keyValueFieldPair;

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
    let index = 1;
    const purchaseFormWrapper = d.getElementById("purchaseFormWrapper");
    const productList = d.getElementById("purchase[0][product_id]");
    const supplierList = d.getElementById("purchase[0][supplier_id]");

    if (!purchaseFormWrapper) {
        return false;
    }

    return function () {
        const createPurchaseForm = `
        <div class="columns is-marginless is-multiline">
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
        <div class="field">
            <label for="purchase[${index}][supplier_id]" class="label text-green has-text-weight-normal"> Supplier <sup class="has-text-danger">*</sup> </label>
            <div class="control has-icons-left">
                <div class="select is-fullwidth">
                    <select id="purchase[${index}][supplier_id]" name="purchase[${index}][supplier_id]">
                    ${supplierList.innerHTML}
                    </select>
                </div>
                <div class="icon is-small is-left">
                    <i class="fas fa-address-card"></i>
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
                <button id="purchase[${index}][product_id]Quantity" class="button text-green" type="button"></button>
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
                <button id="purchase[${index}][product_id]Price" class="button text-green" type="button"></button>
            </div>
        </div>
    </div>
    </div>`;

        purchaseFormWrapper.innerHTML += createPurchaseForm;

        index++;
    };
})();
