@push('scripts')
    <script>
        document.addEventListener("alpine:init", () => {
            Alpine.data("priceMasterDetailForm", ({
                price
            }) => ({
                prices: [],

                async init() {
                    await Product.init();

                    if (price) {
                        this.prices = price;

                        await Promise.resolve(this.prices.forEach((price) => price.product_category_id = Product.productCategoryId(price.product_id)))

                        await Promise.resolve($(".product-list").trigger("change", [true]));

                        return;
                    }

                    this.add();
                },
                add() {
                    this.prices.push({});
                },
                async remove(index) {
                    if (this.prices.length <= 0) {
                        return;
                    }

                    await Promise.resolve(this.prices.splice(index, 1));

                    await Promise.resolve(
                        this.prices.forEach((price, i) => {
                            if (i >= index) {
                                Product.changeProductCategory(this.getSelect2(i), price.product_id, price.product_category_id);
                            }
                        })
                    );

                    Pace.restart();
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

                    select2.on("change", (event, haveData = false) => {
                        this.prices[index].product_id = event.target.value;

                        this.prices[index].product_category_id =
                            Product.productCategoryId(this.prices[index].product_id);

                        if (!haveData) {
                            Product.changeProductCategory(select2, this.prices[index].product_id, this.prices[index].product_category_id);
                        }
                    });
                },
                getSelect2(index) {
                    return $(".product-list").eq(index);
                }
            }));
        });
    </script>
@endpush
