const Product = {
    products: [],

    async init() {
        if (this.products.length) {
            return;
        }

        const response = await axios.get(`/api/products`);

        this.products = response.data;
    },
    all() {
        return this.products;
    },
    whereProductId(productId) {
        return this.products.find((product) => productId == product.id);
    },
    whereProductCategoryId(productCategoryId) {
        return this.products.filter(
            (product) => productCategoryId == product.product_category_id
        );
    },
    prices(productId) {
        let product = this.whereProductId(productId);

        return product?.prices || [];
    },
    isBatchable(productId) {
        let product = this.whereProductId(productId);

        return product?.is_batchable;
    },
    productCategoryId(productId) {
        let product = this.whereProductId(productId);

        return product?.product_category_id;
    },
    productCategoryName(productId) {
        let product = this.whereProductId(productId);

        return product?.product_category_name;
    },
    taxName(isPriceBeforeTax, productId) {
        let product = this.whereProductId(productId);

        if (!product?.tax_name || product?.tax_name == "NONE") {
            return;
        }

        return isPriceBeforeTax
            ? "(Before " + product?.tax_name + ")"
            : "(After " + product?.tax_name + ")";
    },
    unitOfMeasurement(productId, prefix = "") {
        let product = this.whereProductId(productId);

        if (prefix) {
            return `${prefix} ${product?.unit_of_measurement || ""}`;
        }

        return product?.unit_of_measurement;
    },
    changeProductCategory(select2, productId, productCategoryId) {
        this.appendProductsToSelect2(
            select2,
            productId,
            this.whereProductCategoryId(productCategoryId)
        );
    },
    appendProductsToSelect2(select2, productId = null, products = null) {
        products = products ?? this.products;

        let emptyOption = new Option("", "", true, true);
        emptyOption.dataset.code = "";
        emptyOption.dataset.product_category_name = "";

        select2.empty();

        select2.append(emptyOption);

        products.forEach((product) => {
            let productName = product.name;

            if (product.code) {
                productName = `${productName} (${product.code})`;
            }

            let newOption = new Option(
                productName,
                product.id,
                false,
                (productId || null) == product.id
            );

            newOption.dataset.code = product.code;
            newOption.dataset.product_category_name =
                product.product_category_name;

            select2.append(newOption);
        });

        select2.trigger("change.select2");
    },
    taxAmount(productId) {
        if (!productId) {
            return 1;
        }

        let product = this.whereProductId(productId);

        if (product?.tax_name == "NONE") {
            return 1;
        }
        return product?.tax_amount;
    },
    priceBeforeTax(unitPrice, quantity, productId = null, discount = 0) {
        if (unitPrice != null && quantity != null) {
            let discountValue = (unitPrice * quantity * discount) / 100;

            return Company.isPriceBeforeTax()
                ? unitPrice * quantity - discountValue
                : (unitPrice * quantity - discountValue) /
                this.taxAmount(productId);
        }

        return 0;
    },
    priceAfterTax(unitPrice, quantity, productId = null, discount = 0) {
        return this.priceBeforeTax(unitPrice, quantity, productId, discount) * this.taxAmount(productId);
    },
    async initForSale(products) {
        this.products = products;

        this.products = this.products.filter(
            (product) => product.is_active && product.is_active_for_sale
        );
    },
    async initForPurchase() {
        await Promise.resolve(Product.init());

        this.products = this.products.filter(
            (product) => product.is_active && product.is_active_for_purchase
        );
    },
};

const BillOfMaterial = {
    billOfMaterials: [],

    async init() {
        const response = await axios.get(`/api/bill-of-materials`);

        this.billOfMaterials = response.data;
    },
    all() {
        return this.billOfMaterials;
    },
    whereProductId(productId) {
        return this.billOfMaterials.filter(
            (billOfMaterial) => productId == billOfMaterial.product_id
        );
    },
    appendBillOfMaterials(
        select,
        billOfMaterialId = null,
        billOfMaterials = null
    ) {
        billOfMaterials = billOfMaterials ?? this.billOfMaterials;

        select.innerHTML = null;

        select.add(new Option("Select Bill of Material", "", false, ""));

        billOfMaterials.forEach((billOfMaterial) => {
            select.add(
                new Option(
                    billOfMaterial.name,
                    billOfMaterial.id,
                    false,
                    (billOfMaterialId || null) == billOfMaterial.id
                )
            );
        });
    },
};

const MerchandiseBatch = {
    merchandiseBatches: [],

    async init() {
        if (this.merchandiseBatches.length) {
            return;
        }

        const response = await axios.get(`/api/merchandise-batches`);

        this.merchandiseBatches = response.data;
    },
    all() {
        return this.merchandiseBatches;
    },
    where(productId, warehouseId = null) {
        return this.merchandiseBatches.filter(
            (merchandiseBatch) =>
                productId == merchandiseBatch.product_id &&
                (!warehouseId || warehouseId == merchandiseBatch.warehouse_id)
        );
    },
    async initAvailable() {
        await Promise.resolve(MerchandiseBatch.init());

        if (!this.merchandiseBatches.length) {
            return;
        }

        this.merchandiseBatches = this.merchandiseBatches.filter(
            (merchandiseBatch) => merchandiseBatch.quantity > 0
        );
    },
    appendMerchandiseBatches(
        select,
        merchandiseBatchId = null,
        merchandiseBatches = null
    ) {
        merchandiseBatches = merchandiseBatches ?? this.merchandiseBatches;

        select.innerHTML = null;

        let firstOption = new Option("Select Batch", "", false, "");
        firstOption.disabled = true;
        select.add(firstOption);

        merchandiseBatches.forEach((merchandiseBatch) => {
            let BatchNo = merchandiseBatch.name;

            if (merchandiseBatch.expires_on) {
                BatchNo = `${BatchNo} (EXP. ${merchandiseBatch.expires_on})`;
            }

            select.add(
                new Option(
                    BatchNo,
                    merchandiseBatch.id,
                    false,
                    (merchandiseBatchId || null) == merchandiseBatch.id
                )
            );
        });
    },
};

const Customer = {
    customers: [],

    async init() {
        const response = await axios.get(`/api/customers`);

        this.customers = response.data;
    },
    all() {
        return this.customers;
    },
    whereTin(tin) {
        return this.customers.find((customer) => tin == customer.tin);
    },
    whereCompanyName(companyName) {
        return this.customers.find(
            (customer) =>
                companyName?.trim().toLowerCase() ==
                customer.company_name?.trim().toLowerCase()
        );
    },
};

const Supplier = {
    suppliers: [],

    async init() {
        const response = await axios.get(`/api/suppliers`);

        this.suppliers = response.data;
    },
    all() {
        return this.suppliers;
    },
    whereTin(tin) {
        return this.suppliers.find((supplier) => tin == supplier.tin);
    },
    whereCompanyName(companyName) {
        return this.suppliers.find(
            (supplier) =>
                companyName?.trim().toLowerCase() ==
                supplier.company_name?.trim().toLowerCase()
        );
    },
};

const Merchandise = {
    merchandise: {},

    async init(productId, warehouseId) {
        const response = await axios.get(
            `/api/merchandises/products/${productId}/warehouses/${warehouseId}`
        );

        this.merchandise = response.data;

        this.merchandise;
    },
};

const Pricing = {
    subTotal(items) {
        if (!items.length) {
            return 0;
        }

        return items.reduce((total, item) => {
            return (
                total +
                Product.priceBeforeTax(
                    item.unit_price,
                    item.quantity,
                    item.product_id,
                    item.discount
                )
            );
        }, 0);
    },
    grandTotal(items) {
        if (!items.length) {
            return 0;
        }

        return items.reduce((total, item) => {
            return (
                total +
                Product.priceAfterTax(
                    item.unit_price,
                    item.quantity,
                    item.product_id,
                    item.discount
                )
            );
        }, 0);
    },
};

const Company = {
    company: {},

    async init() {
        if (Object.getOwnPropertyNames(this.company).length) {
            return;
        }

        const response = await axios.get(`/api/my-company`);

        this.company = response.data;
    },

    isPriceBeforeTax() {
        return this.company.is_price_before_vat;
    },

    canSelectBatchNumberOnForms() {
        return this.company.can_select_batch_number_on_forms;
    }
};

const Compensation = {
    compensations: [],

    async init() {
        const response = await axios.get(`/api/compensations`);

        this.compensations = response.data;
    },
    all() {
        return this.compensations;
    },
    whereId(id) {
        return this.compensations.find((compensation) => id == compensation.id);
    },
    isOvertimeByFormula(id) {
        let overtimeCompensation = this.whereId(id);

        return (
            overtimeCompensation?.name == "Overtime" &&
            overtimeCompensation?.has_formula == 1
        );
    },
};
