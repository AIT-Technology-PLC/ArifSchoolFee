const Product = {
    products: [],

    async init() {
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
    price(productId) {
        let product = this.whereProductId(productId);

        if (this.isPriceFixed(productId)) {
            return product.price.fixed_price;
        }

        if (this.isPriceRange(productId)) {
            return product.price.max_price;
        }

        return "";
    },
    isPriceFixed(productId) {
        let product = this.whereProductId(productId);

        return product?.price?.type == "fixed";
    },
    isPriceRange(productId) {
        let product = this.whereProductId(productId);

        return product?.price?.type == "range";
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
