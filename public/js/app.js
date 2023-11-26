const d = document;

const addKeyValueInputFields = (function() {
    let index = 0;
    const newForm = d.getElementById("newForm");

    if (!newForm) {
        return false;
    }

    newForm.classList.remove("is-hidden");

    return function() {
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
    if (!productId) {
        return;
    }

    const response = await axios.get(`/api/products/${productId}`);

    const unitOfMeasurement = response.data.unit_of_measurement;

    if (d.getElementById(elementId + "Quantity")) {
        d.getElementById(elementId + "Quantity").innerText = unitOfMeasurement;
    }

    if (d.getElementById(elementId + "Price")) {
        d.getElementById(
            elementId + "Price"
        ).innerText = `Per ${unitOfMeasurement}`;
    }
}

function disableSaveButton() {
    let saveButton = d.getElementById("saveButton");
    saveButton.classList.add("is-loading");
    saveButton.disabled = true;
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
    $(".dataTables_filter label input")
        .addClass("input m-top-10")
        .attr("style", "width:213px;height:30px;font-size:0.75em");
}

function hideColumns(id, columns = []) {
    if (!id) {
        return;
    }

    $("#" + id)
        .DataTable()
        .columns(columns)
        .visible(false);
}

function initiateDataTables() {
    const table = $("table.regular-datatable");

    table.DataTable({
        responsive: true,
        scrollCollapse: true,
        scrollY: "500px",
        scrollX: true,
        language: {
            search: "",
            searchPlaceholder: "Search",
        },
        columnDefs: [
            {
                type: "natural-nohtml",
                targets: JSON.parse(table.attr("data-numeric")),
            },
            { type: "date", targets: JSON.parse(table.attr("data-date")) },
        ],
        lengthMenu: JSON.parse(table.attr("data-length-menu")),
        dom: "lBfrtip",
        lengthChange: JSON.parse(table.attr("data-has-length-change")),
        searching: JSON.parse(table.attr("data-has-filter")),
        pagingType: table.attr("data-paging-type"),
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
                customize: function(doc) {
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
            ["insert", ["picture"]],
        ],
        callbacks: {
            onPaste: function(e) {
                var bufferText = (
                    (e.originalEvent || e).clipboardData || window.clipboardData
                ).getData("Text");

                e.preventDefault();

                // Firefox fix
                setTimeout(function() {
                    document.execCommand("insertText", false, bufferText);
                }, 10);
            },
        },
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

function disableInputTypeNumberMouseWheel() {
    if (d.activeElement.type === "number") {
        d.activeElement.blur();
    }
}

const initializeSelect2 = (element, placeholder = "Select a product") => {
    return $(element).select2({
        dropdownParent: $(element).parent(),
        placeholder: placeholder,
        allowClear: true,
        tags: $(element).attr("data-tags"),
        matcher: (params, data) => {
            if ($.trim(params.term) === "") {
                return data;
            }

            if (typeof data.text === "undefined") {
                return null;
            }

            if (
                data.text.toLowerCase().indexOf(params.term.toLowerCase()) > -1
            ) {
                return data;
            }

            if (
                data.element.dataset.code
                    ?.toLowerCase()
                    .indexOf(params.term.toLowerCase()) > -1
            ) {
                return data;
            }

            if (
                data.element.dataset.productCategoryName
                    ?.toLowerCase()
                    .indexOf(params.term.toLowerCase()) > -1
            ) {
                return data;
            }

            if (
                data.element.dataset.productDescription
                    ?.toLowerCase()
                    .indexOf(params.term.toLowerCase()) > -1
            ) {
                return data;
            }

            return null;
        },
    });
};

function initSelect2(element, placeholder) {
    return $(element).select2({
        placeholder: `Select ${placeholder}`,
        allowClear: true,
    });
}

function moneyFormat(number) {
    if (isNaN(number)) {
        return "0.00";
    }

    return number.toLocaleString("en-US", {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    });
}

document.addEventListener("alpine:init", () => {
    Alpine.data("inventoryTypeToggler", () => ({
        isOnHand: true,
        showOnHand() {
            this.isOnHand = true;
        },
        showOutOf() {
            this.isOnHand = false;

            setTimeout(() => {
                $("table.display").DataTable().columns.adjust().draw();
            });
        },
    }));

    Alpine.data("toggler", (value = true) => ({
        isHidden: true,

        init() {
            this.isHidden = value;
        },
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

    Alpine.data("sideMenuAccordion", () => ({
        isAccordionActive: false,
        isAccordionOpen: false,

        toggleAccordion() {
            this.isAccordionOpen = !this.isAccordionOpen;
        },
        activateAccordion() {
            this.isAccordionActive = true;
            this.isAccordionOpen = true;
        },
    }));

    Alpine.data("verifyCashMethod", () => ({
        isMethodCash: false,

        changeMethod() {
            if (this.$el.value === "Cash") {
                this.isMethodCash = true;
                this.$refs.bankName.value = "";
                this.$refs.referenceNumber.value = "";
            }

            if (this.$el.value !== "Cash") {
                this.isMethodCash = false;
            }
        },
    }));

    Alpine.data("permissionFilter", () => ({
        permissions: [],
        searchQuery: "",

        init() {
            this.searchQuery = sessionStorage.getItem("searchQuery") || "";
            this.$nextTick(() => this.filterPermissions());
        },
        addToPermissionList(permission) {
            this.permissions.push(permission);
        },
        filterPermissions() {
            let searchQuery = this.searchQuery.replace(/\s/g, "");
            searchQuery = searchQuery.toLowerCase(searchQuery);
            sessionStorage.setItem("searchQuery", searchQuery);

            if (searchQuery === "") {
                this.permissions.forEach((permission) => {
                    this.$refs[permission].classList.remove("is-hidden");
                });

                return;
            }

            this.permissions.forEach((permission) => {
                if (!permission.includes(searchQuery)) {
                    this.$refs[permission].classList.add("is-hidden");
                }

                if (permission.includes(searchQuery)) {
                    this.$refs[permission].classList.remove("is-hidden");
                }
            });
        },
    }));

    Alpine.data("changeWarehouse", () => ({
        change() {
            if (this.$el.value == 0) {
                location.href = "/merchandises/available";
            }

            if (this.$el.value != 0) {
                location.href = `/warehouses/${this.$el.value}/merchandises`;
            }
        },
    }));

    Alpine.data("connectionStatus", () => ({
        openOfflineBox() {
            this.$refs.offline.classList.remove("is-hidden");
            this.$refs.online.classList.add("is-hidden");
        },
        openOnlineBox() {
            this.$refs.online.classList.remove("is-hidden");
            this.$refs.offline.classList.add("is-hidden");

            setTimeout(
                () => this.$refs.online.classList.add("is-hidden"),
                5000
            );
        },
        isOffline() {
            if (!navigator.onLine) {
                this.openOfflineBox();
            }
        },
    }));

    Alpine.data("swal", (action, intention) => ({
        intention: intention,
        action: action,

        open() {
            swal({
                title: `Do you want to ${this.intention}?`,
                text: `By clicking 'Yes, ${action}', you are going to ${this.intention}.`,
                buttons: ["Not now", `Yes, ${action}`],
                dangerMode: true,
            }).then((willExecute) => {
                if (willExecute) {
                    this.$refs.submitButton.disabled = true;
                    this.$el.submit();
                }
            });
        },
    }));

    Alpine.data("toggleCheckboxes", () => ({
        isChecked: false,
        checkboxes: [],

        init() {
            this.checkboxes = this.$root.querySelectorAll(
                "input[type='checkbox']"
            );
        },
        toggle() {
            this.checkboxes.forEach(
                (checkbox) => (checkbox.checked = !this.isChecked || false)
            );
            this.isChecked = !this.isChecked;
        },
    }));
    Alpine.data(
        "cashReceivedType",
        (
            paymentType = "",
            cashReceivedType = "",
            cashReceived = "",
            dueDate = "",
            bankName = "",
            referenceNumber = ""
        ) => ({
            paymentType: "",
            cashReceivedType: "",
            cashReceived: "",
            dueDate: "",
            bankName: "",
            referenceNumber: "",

            init() {
                this.paymentType = paymentType;
                this.cashReceivedType = cashReceivedType;
                this.cashReceived = cashReceived;
                this.dueDate = dueDate;
                this.bankName = bankName;
                this.referenceNumber = referenceNumber;
            },
            changePaymentMethod() {
                if (this.paymentType != "Credit Payment") {
                    this.cashReceivedType = "percent";
                    this.cashReceived = 100;
                    this.dueDate = "";
                }

                if (
                    this.paymentType === "Cash Payment" ||
                    this.paymentType === "Deposits"
                ) {
                    this.bankName = "";
                    this.referenceNumber = "";
                }

                if (this.paymentType === "Credit Payment") {
                    this.cashReceivedType = "";
                    this.cashReceived = "";
                    this.dueDate = "";
                    this.bankName = "";
                    this.referenceNumber = "";
                }
            },

            isPaymentInCredit() {
                return (
                    this.paymentType === "" ||
                    this.paymentType === "Credit Payment" ||
                    this.paymentType === "Cash Payment" ||
                    this.paymentType === "Deposits" ||
                    this.paymentType === "TeleBirr"
                );
            },

            isPaymentNotCredit() {
                return (
                    this.paymentType === "" ||
                    this.paymentType != "Credit Payment"
                );
            },
        })
    );

    Alpine.data(
        "purchaseInformation",
        (
            purchaseType = "",
            taxId = "",
            currency = "",
            exchangeRate = "",
            paymentType = "",
            cashPaidType = "",
            cashPaid = "",
            dueDate = "",
            freightCost = "",
            freightInsuranceCost = "",
            freightUnit = "",
            otherCostsBeforeTax = "",
            otherCostsAfterTax = "",
            freightAmount = ""
        ) => ({
            purchaseType: "",
            taxId: "",
            currency: "",
            exchangeRate: "",
            paymentType: "",
            cashPaidType: "",
            cashPaid: "",
            dueDate: "",
            freightCost: "",
            freightInsuranceCost: "",
            freightUnit: "",
            otherCostsBeforeTax: "",
            otherCostsAfterTax: "",
            freightAmount: "",

            init() {
                this.purchaseType = purchaseType;
                this.taxId = taxId;
                this.currency = currency;
                this.exchangeRate = exchangeRate;
                this.paymentType = paymentType;
                this.cashPaidType = cashPaidType;
                this.cashPaid = cashPaid;
                this.dueDate = dueDate;
                this.freightCost = freightCost;
                this.freightInsuranceCost = freightInsuranceCost;
                this.freightUnit = freightUnit;
                this.otherCostsBeforeTax = otherCostsBeforeTax;
                this.otherCostsAfterTax = otherCostsAfterTax;
                this.freightAmount = freightAmount;
            },
            changePurchaseInformation() {
                this.taxId = "";
                this.currency = "";
                this.exchangeRate = "";
                this.freightCost = "";
                this.freightInsuranceCost = "";
                this.freightUnit = "";
                this.freightAmount = "";
                this.paymentType = "";
                this.otherCostsBeforeTax = "";
                this.otherCostsAfterTax = "";
            },
            changePaymentMethod() {
                if (this.paymentType != "Credit Payment") {
                    this.cashPaidType = "percent";
                    this.cashPaid = 100;
                    this.dueDate = "";
                    return;
                }

                this.cashPaidType = "";
                this.cashPaid = "";
                this.dueDate = "";
            },
            isPurchaseByLocal() {
                return this.purchaseType === "Local Purchase";
            },
            isPurchaseByImport() {
                return this.purchaseType === "Import";
            },
            isPaymentInCredit() {
                return this.paymentType === "Credit Payment";
            },
        })
    );

    Alpine.data("productDataProvider", (productId, unitPrice = "") => ({
        product: {
            name: "",
            category: "",
            code: "",
            min_on_hand: "",
            type: "",
            unit_of_measurement: "",
            price_type: "",
            price: "",
        },
        isDisabled: false,
        products: [],
        selectedCategory: "",
        select2: "",

        init() {
            this.getProduct(productId);
        },
        async getProduct(productId) {
            if (productId == "" || isNaN(productId)) {
                this.product.price = unitPrice;
                unitPrice = "";
                return;
            }

            const response = await axios.get(`/api/products/${productId}`);

            this.product = response.data;

            this.isDisabled = this.product.price_type === "fixed";

            this.selectedCategory = this.product.category;

            if (unitPrice != "") {
                this.product.price = unitPrice;
                unitPrice = "";
            }
        },
        async getProductsByCategory() {
            const response = await axios.get(
                `/api/products/${this.selectedCategory}/by-category`
            );

            this.products = response.data;

            this.appendProductsToSelect2();
        },
        appendProductsToSelect2() {
            let emptyOption = new Option("", "", true, true);
            emptyOption.dataset.code = "";
            emptyOption.dataset.category = "";

            this.select2.empty();

            this.select2.append(emptyOption);

            this.products.forEach((product) => {
                let productName = product.text;

                if (product.code) {
                    productName = `${productName} (${product.code})`;
                }

                let newOption = new Option(
                    productName,
                    product.id,
                    false,
                    (this.product.id || null) == product.id
                );

                newOption.dataset.code = product.code;
                newOption.dataset.category = product.category;

                this.select2.append(newOption).trigger("change");
            });
        },
        select2() {
            this.select2 = initializeSelect2(this.$el);

            this.select2.on("change", (event) => {
                this.getProduct(event.target.value);
            });
        },
    }));

    Alpine.data("laravelDatatableFilter", (...filters) => ({
        filters: {},
        parameters: {},

        init() {
            this.parameters = new URLSearchParams(location.search);

            filters.forEach((filter) => {
                this.filters[filter] = this.parameters.get(filter) || "";
            });
        },
        add(key) {
            this.parameters.set(key, this.$el.value);
        },
        filter() {
            location.search = `?${this.parameters}`;
        },
        clear() {
            this.parameters = "";
            location.search = "";
        },
    }));

    Alpine.data("tenderMasterDetailForm", ({ lot }) => ({
        lots: [],
        errors: {},

        init() {
            if (lot) {
                this.lots = lot;
                return;
            }

            this.addLot();
        },
        setErrors(errors) {
            this.errors = errors;
        },
        addLot() {
            this.lots.push({
                lotDetails: [
                    {
                        product_id: "",
                        quantity: ".00",
                        description: "",
                    },
                ],
            });
        },
        addLotDetail(lotIndex) {
            this.lots[lotIndex].lotDetails.push({
                product_id: "",
                quantity: ".00",
                description: "",
            });
        },
        removeLot(lotIndex) {
            if (this.lots.length === 1) {
                return;
            }

            this.lots.splice(lotIndex, 1);
        },
        removeLotDetail(lotIndex, lotDetailIndex) {
            if (this.lots[lotIndex].lotDetails.length === 1) {
                return;
            }

            this.lots[lotIndex].lotDetails.splice(lotDetailIndex, 1);
        },
        select2Tender(lotIndex, lotDetailIndex) {
            let select2 = initializeSelect2(this.$el);

            this.$nextTick(() => $(select2).trigger("change"));

            select2.on("change", (event) => {
                this.lots[lotIndex].lotDetails[lotDetailIndex].product_id =
                    event.target.value;
            });

            this.$watch(
                `lots[${lotIndex}].lotDetails[${lotDetailIndex}].product_id`,
                () => select2.trigger("change")
            );
        },
    }));

    Alpine.data("UploadedFileNameHandler", () => ({
        file: "",
        fileName: "",

        remove() {
            this.file = "";
            this.fileName = "";
        },
        getFileName() {
            this.fileName = this.file.slice(this.file.lastIndexOf("\\") + 1);
        },
    }));

    Alpine.data("sideMenu", () => ({
        isSideMenuOpenedOnLaptop: true,

        async toggleOnLaptop() {
            await Promise.resolve(
                (this.isSideMenuOpenedOnLaptop = !this.isSideMenuOpenedOnLaptop)
            );

            $("table.display").DataTable().columns.adjust().draw();
        },
    }));

    Alpine.store("errors", {
        errors: {},

        setErrors(errors) {
            this.errors = errors;
        },
        getErrors(property) {
            return this.errors[property];
        },
    });

    Alpine.data(
        "productType",
        (
            type = "",
            isBatchable = "0",
            batchPriority = "",
            isActive = "1",
            isProductSingle = "1",
            unitOfMeasurementSource = "Standard",
            unitOfMeasurement = "",
        ) => ({
            type: "",
            isBatchable: "0",
            batchPriority: "",
            isTypeService: false,
            isNonInventoryProduct: false,
            isActive: "1",
            isProductSingle: "1",
            unitOfMeasurementSource: "Standard",
            unitOfMeasurement: "",

            init() {
                this.type = type;
                this.isBatchable = isBatchable;
                this.batchPriority = batchPriority;
                this.isActive = isActive;
                this.isProductSingle = isProductSingle;
                this.unitOfMeasurementSource = unitOfMeasurementSource;
                this.unitOfMeasurement = unitOfMeasurement;
                this.changeProductType();
            },

            changeProductType() {
                if (
                    this.type === "Services" ||
                    this.type === "Non-inventory Product"
                ) {
                    this.isBatchable = "0";
                    this.batchPriority = "";
                    this.isTypeService = true;
                    this.isNonInventoryProduct = true;
                    return;
                }

                this.isTypeService = false;
                this.isNonInventoryProduct = false;
            },

            isSingle() {
                return this.isProductSingle == 1;
            },

            isUnitOfMeasurementCustom() {
                return this.unitOfMeasurementSource == "Custom";
            },
        })
    );

    Alpine.data("targetProducts", (targetProduct = "") => ({
        targetProduct: "",
        isNotSpecificProduct: false,

        init() {
            this.targetProduct = targetProduct;
            this.changeTargetProduct();
        },

        changeTargetProduct() {
            if (this.targetProduct != "Specific Products") {
                this.isNotSpecificProduct = true;
                return;
            }

            this.isNotSpecificProduct = false;
        },
    }));
});
