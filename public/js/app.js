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

const initializeSelect2 = (element) => {
    return $(element).select2({
        placeholder: "Select a product",
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
};

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
                location.href = "/merchandises/on-hand";
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

    Alpine.data("priceMasterDetailForm", ({ price }) => ({
        prices: [],
        errors: {},

        init() {
            if (price) {
                this.prices = price;
                return;
            }

            this.add();
        },
        setErrors(errors) {
            this.errors = errors;
        },
        getErrors(property) {
            return this.errors[property];
        },
        add() {
            this.prices.push({
                product_id: "",
                type: "",
                fixed_price: "",
                min_price: "",
                max_price: "",
            });
        },
        remove(index) {
            if (this.prices.length === 1) {
                return;
            }

            this.prices.splice(index, 1);
        },
        isPriceFixed(priceType) {
            if (!priceType) {
                return true;
            }

            return priceType === "fixed";
        },
        changePriceType(price) {
            if (price.type === "fixed") {
                price.min_price = "";
                price.max_price = "";
            }

            if (price.type === "range") {
                price.fixed_price = "";
            }
        },
        select2(index) {
            let select2 = initializeSelect2(this.$el);

            this.$nextTick(() => $(select2).trigger("change"));

            select2.on("select2:select", (event) => {
                this.prices[index].product_id = event.target.value;
            });

            this.$watch(`prices`, () => select2.trigger("change"));
        },
    }));

    Alpine.data("productDataProvider", (productId, unitPrice = null) => ({
        product: {
            name: "",
            code: "",
            min_on_hand: "",
            type: "",
            unit_of_measurement: "",
            price_type: "",
            price: "",
        },
        isDisabled: false,

        init() {
            if (productId) {
                this.getProduct(productId);
            }
        },
        async getProduct(productId) {
            const response = await axios.get(`/api/products/${productId}`);

            this.product = response.data;

            this.isDisabled = this.product.price_type === "fixed";

            if (unitPrice !== null) {
                this.product.price = unitPrice;
                unitPrice = null;
            }
        },
        select2() {
            let select2 = initializeSelect2(this.$el);

            select2.on("select2:select", (event) => {
                this.getProduct(event.target.value);
            });
        },
    }));
});
