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
    unitOfMeasurement(productId, prefix = "") {
        let product = this.whereProductId(productId);

        if (prefix) {
            return `${prefix} ${product?.unit_of_measurement || ""}`;
        }

        return product?.unit_of_measurement;
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
};
