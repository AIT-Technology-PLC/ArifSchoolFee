 <x-content.main
     x-data="productBundleMasterDetailForm({{ Js::from($data) }})"
     x-init="$store.errors.setErrors({{ json_encode($errors->get('productBundle.*')) }})"
 >
     <x-common.fail-message :message="session('failedMessage')" />
     <template
         x-for="(productBundle, index) in productBundles"
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
                         <x-forms.label x-bind:for="`productBundle[${index}][component_id]`">
                             Component <sup class="has-text-danger">*</sup>
                         </x-forms.label>
                         <x-forms.field class="has-addons">
                             <x-forms.control
                                 class="has-icons-left"
                                 style="width: 30%"
                             >
                                 <x-common.category-list
                                     x-model="productBundle.product_category_id"
                                     x-on:change="Product.changeProductCategory(getSelect2(index), productBundle.component_id, productBundle.product_category_id)"
                                 />
                             </x-forms.control>
                             <x-forms.control
                                 class="has-icons-left is-expanded"
                                 style="width: 70%"
                             >
                                 <x-common.new-product-list
                                     class="product-list"
                                     x-bind:id="`productBundle[${index}][component_id]`"
                                     x-bind:name="`productBundle[${index}][component_id]`"
                                     x-model="productBundle.component_id"
                                     x-init="select2(index)"
                                     only-non-batchables
                                 />
                                 <x-common.icon
                                     name="fas fa-th"
                                     class="is-large is-left"
                                 />
                                 <span
                                     class="help has-text-danger"
                                     x-text="$store.errors.getErrors(`productBundle.${index}.component_id`)"
                                 ></span>
                             </x-forms.control>
                         </x-forms.field>
                     </div>
                     <div class="column is-6">
                         <x-forms.label x-bind:for="`productBundle[${index}][quantity]`">
                             Quantity <sup class="has-text-danger">*</sup>
                         </x-forms.label>
                         <x-forms.field class="has-addons">
                             <x-forms.control class="has-icons-left is-expanded">
                                 <x-forms.input
                                     x-bind:id="`productBundle[${index}][quantity]`"
                                     x-bind:name="`productBundle[${index}][quantity]`"
                                     x-model="productBundle.quantity"
                                     type="number"
                                     x-bind:placeholder="Product.unitOfMeasurement(productBundle.product_id) || ''"
                                 />
                                 <x-common.icon
                                     name="fas fa-balance-scale"
                                     class="is-large is-left"
                                 />
                                 <span
                                     class="help has-text-danger"
                                     x-text="$store.errors.getErrors(`productBundle.${index}.quantity`)"
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
             Alpine.data("productBundleMasterDetailForm", ({
                 productBundle
             }) => ({
                 productBundles: [],

                 async init() {
                     await Product.init({{ Js::from($products) }}).nonBatchable();

                     if (productBundle) {
                         this.productBundles = productBundle;

                         await Promise.resolve(this.productBundles.forEach((productBundle) => productBundle.product_category_id = Product.productCategoryId(productBundle.product_id)))

                         await Promise.resolve($(".product-list").trigger("change", [true]));

                         return;
                     }
                 },
                 add() {
                     this.productBundles.push({});
                 },
                 async add(productId = null) {
                     if (!productId) {
                         this.productBundles.push({});
                         return;
                     }

                     await Promise.resolve(
                         this.productBundles.push({
                             product_id: productId
                         })
                     );

                     await Promise.resolve($(".product-list").trigger("change"));
                 },
                 async remove(index) {
                     if (this.productBundles.length <= 0) {
                         return;
                     }

                     await Promise.resolve(this.productBundles.splice(index, 1));

                     await Promise.resolve(
                         this.productBundles.forEach((productBundle, i) => {
                             if (i >= index) {
                                 Product.changeProductCategory(this.getSelect2(i), productBundle.product_id, productBundle.product_category_id);
                             }
                         })
                     );

                     Pace.restart();
                 },
                 select2(index) {
                     let select2 = initializeSelect2(this.$el);

                     select2.on("change", (event, haveData = false) => {
                         this.productBundles[index].product_id = event.target.value;

                         this.productBundles[index].product_category_id =
                             Product.productCategoryId(this.productBundles[index].product_id);

                         if (!haveData) {
                             Product.changeProductCategory(select2, this.productBundles[index].product_id, this.productBundles[index].product_category_id);
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
