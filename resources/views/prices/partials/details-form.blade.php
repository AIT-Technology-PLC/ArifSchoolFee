 <x-content.main
     x-data="priceMasterDetailForm({{ Js::from($data) }})"
     x-init="$store.errors.setErrors({{ json_encode($errors->get('price.*')) }})"
 >
     <x-common.fail-message :message="session('failedMessage')" />
     <template
         x-for="(price, index) in prices"
         x-bind:key="index"
     >
         <div class="mx-3">
             <x-forms.field class="has-addons mb-0 mt-5">
                 <x-forms.control>
                     <span
                         class="tag bg-green has-text-white is-medium is-radiusless"
                         x-text="`Item ${index + 1}`"
                     ></span>
                 </x-forms.control>
                 <x-forms.control>
                     <x-common.button
                         tag="button"
                         mode="tag"
                         type="button"
                         class="bg-lightgreen has-text-white is-medium is-radiusless"
                         x-on:click="remove(index)"
                     >
                         <x-common.icon
                             name="fas fa-times-circle"
                             class="text-green"
                         />
                     </x-common.button>
                 </x-forms.control>
             </x-forms.field>
             <div class="box has-background-white-bis radius-top-0">
                 <div class="columns is-marginless is-multiline">
                     <div class="column is-6">
                         <x-forms.label x-bind:for="`price[${index}][product_id]`">
                             Product <sup class="has-text-danger">*</sup>
                         </x-forms.label>
                         <x-forms.field class="has-addons">
                             <x-forms.control
                                 class="has-icons-left"
                                 style="width: 30%"
                             >
                                 <x-common.category-list
                                     x-model="price.product_category_id"
                                     x-on:change="Product.changeProductCategory(getSelect2(index), price.product_id, price.product_category_id)"
                                 />
                             </x-forms.control>
                             <x-forms.control
                                 class="has-icons-left is-expanded"
                                 style="width: 70%"
                             >
                                 <x-common.new-product-list
                                     class="product-list"
                                     x-bind:id="`price[${index}][product_id]`"
                                     x-bind:name="`price[${index}][product_id]`"
                                     x-model="price.product_id"
                                     x-init="select2(index)"
                                 />
                                 <x-common.icon
                                     name="fas fa-th"
                                     class="is-large is-left"
                                 />
                                 <span
                                     class="help has-text-danger"
                                     x-text="$store.errors.getErrors(`price.${index}.product_id`)"
                                 ></span>
                             </x-forms.control>
                         </x-forms.field>
                     </div>
                     <div class="column is-6">
                         <x-forms.label x-bind:for="`price[${index}][fixed_price]`">
                             Price <sup
                                 class="has-text-weight-light"
                                 x-text="Product.taxName({{ userCompany()->isPriceBeforeTax() }}, price.product_id)"
                             ></sup>
                         </x-forms.label>
                         <x-forms.field class="has-addons">
                             <x-forms.control class="has-icons-left is-expanded">
                                 <x-forms.input
                                     x-bind:id="`price[${index}][fixed_price]`"
                                     x-bind:name="`price[${index}][fixed_price]`"
                                     type="number"
                                     placeholder="Price Amount"
                                     x-model="price.fixed_price"
                                 />
                                 <x-common.icon
                                     name="fas fa-money-bill"
                                     class="is-small is-left"
                                 />
                                 <span
                                     class="help has-text-danger"
                                     x-text="$store.errors.getErrors(`price.${index}.fixed_price`)"
                                 ></span>
                             </x-forms.control>
                             <x-forms.control>
                                 <x-common.button
                                     tag="button"
                                     mode="button"
                                     class="button bg-green has-text-white"
                                     type="button"
                                     x-text="Product.unitOfMeasurement(price.product_id, 'Per')"
                                 />
                             </x-forms.control>
                         </x-forms.field>
                     </div>
                     <div class="column is-6">
                         <x-forms.label x-bind:for="`price[${index}][name]`">
                             Price Description<sup class="has-text-danger"></sup>
                         </x-forms.label>
                         <x-forms.field>
                             <x-forms.control class="has-icons-left">
                                 <x-forms.input
                                     x-bind:id="`price[${index}][name]`"
                                     x-bind:name="`price[${index}][name]`"
                                     type="text"
                                     placeholder="Price Description"
                                     x-model="price.name"
                                 />
                                 <x-common.icon
                                     name="fas fa-tags"
                                     class="is-small is-left"
                                 />
                                 <span
                                     class="help has-text-danger"
                                     x-text="$store.errors.getErrors(`price.${index}.name`)"
                                 ></span>
                             </x-forms.control>
                         </x-forms.field>
                     </div>
                     <div class="column is-6">
                         <x-forms.field>
                             <x-forms.label x-bind:for="`price[${index}][is_active]`">
                                 Active or not <sup class="has-text-danger">*</sup>
                             </x-forms.label>
                             <x-forms.control>
                                 <label class="radio has-text-grey has-text-weight-normal">
                                     <input
                                         type="radio"
                                         x-bind:name="`price[${index}][is_active]`"
                                         value="1"
                                         class="mt-3"
                                         x-model="price.is_active"
                                     >
                                     Active
                                 </label>
                                 <label class="radio has-text-grey has-text-weight-normal mt-2">
                                     <input
                                         type="radio"
                                         x-bind:name="`price[${index}][is_active]`"
                                         value="0"
                                         x-model="price.is_active"
                                     >
                                     Not Active
                                 </label>
                                 <span
                                     class="help has-text-danger"
                                     x-text="$store.errors.getErrors(`price.${index}.is_active`)"
                                 ></span>
                             </x-forms.control>
                         </x-forms.field>
                     </div>
                 </div>
             </div>
         </div>
     </template>
     <x-common.button
         tag="button"
         type="button"
         mode="button"
         label="Add More Item"
         class="bg-purple has-text-white is-small ml-3 mt-6"
         x-on:click="add({{ $productId }})"
     />
 </x-content.main>

 @push('scripts')
     <script>
         document.addEventListener("alpine:init", () => {
             Alpine.data("priceMasterDetailForm", ({
                 price
             }) => ({
                 prices: [],

                 async init() {
                     await Product.init({{ Js::from($products) }});

                     if (price) {
                         this.prices = price;

                         await Promise.resolve(this.prices.forEach((price) => price.product_category_id = Product.productCategoryId(price.product_id)))

                         await Promise.resolve($(".product-list").trigger("change", [true]));

                         return;
                     }

                     this.add();
                 },
                 async add(productId = null) {
                     if (!productId) {
                         this.prices.push({});
                         return;
                     }

                     await Promise.resolve(
                         this.prices.push({
                             product_id: productId
                         })
                     );

                     await Promise.resolve($(".product-list").trigger("change"));
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
