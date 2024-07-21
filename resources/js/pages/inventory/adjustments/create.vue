<template>
  <div>
    <!-- breadcrumbs Start -->
    <breadcrumbs :items="breadcrumbs" :current="breadcrumbsCurrent" />
    <!-- breadcrumbs end -->
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">
              {{ $t("inventory.adjustments.create.form_title") }}
            </h3>
            <router-link :to="{ name: 'adjustments.index' }" class="btn btn-dark float-right">
              <i class="fas fa-long-arrow-alt-left" /> {{ $t("common.back") }}
            </router-link>
          </div>
          <!-- /.card-header -->
          <!-- form start -->
          <form role="form" @submit.prevent="saveAdjustment" @keydown="form.onKeydown($event)">
            <div class="card-body">
              <div v-if="products" class="row">
                <div class="form-group col-md-6">
                  <label for="adjustmentReason">{{ $t("inventory.common.adjustment_reason") }}
                    <span class="required">*</span></label>
                  <input id="adjustmentReason" v-model="form.adjustmentReason" type="text" class="form-control" :class="{
                    'is-invalid': form.errors.has('adjustmentReason'),
                  }" name="adjustmentReason" :placeholder="$t('common.return_reason_placeholder')" />
                  <has-error :form="form" field="adjustmentReason" />
                </div>
                <div class="form-group col-md-6">
                  <label for="product">{{ $t("common.select_products") }}
                    <span class="required">*</span></label>
                  <v-select v-model="form.product" :options="products" label="label" :class="{
                    'is-invalid': form.errors.has('selectedProducts'),
                  }" name="product" :placeholder="$t('common.select_products_placeholder')"
                    @input="storeProduct(form.product)" />
                  <has-error :form="form" field="selectedProducts" />
                </div>
              </div>
              <div v-if="form.selectedProducts && form.selectedProducts.length > 0" class="row mt-3 mb-2">
                <div class="table-responsive table-custom w-95 m-auto table-sm">
                  <table class="table table-hover">
                    <thead>
                      <tr>
                        <th>{{ $t("common.s_no") }}</th>
                        <th>{{ $t("common.code") }}</th>
                        <th>{{ $t("common.name") }}</th>
                        <th>{{ $t("products.list.common.stock") }}</th>
                        <th class="w-200px">
                          {{ $t("inventory.common.adjustment_type") }}
                        </th>
                        <th class="w-250px">{{ $t("common.quantity") }}</th>
                        <th class="text-right">{{ $t("common.action") }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(item, i) in form.selectedProducts" :key="i">
                        <td>{{ ++i }}</td>
                        <td>{{ item.itemCode | withPrefix(prefix) }}</td>
                        <td>{{ item.name }}</td>
                        <td>
                          <span class="btn btn-warning btn-sm">{{
                            item.stockQty
                          }}</span>
                        </td>
                        <td>
                          <select class="form-control" @change="
                            updateProduct(
                              $event.target.value,
                              'adjustType',
                              i - 1
                            )
                            " :id="`adjustType-${i}`" required>
                            <option value="Increment">
                              {{ $t("inventory.common.increment") }}
                            </option>
                            <option value="Decrement">
                              {{ $t("inventory.common.decrement") }}
                            </option>
                          </select>
                        </td>
                        <td>
                          <div class="input-group custom-qty-input">
                            <input type="button" value="-" class="button-minus icon-shape icon-sm btn-danger"
                              data-field="quantity" @click="
                                updateProduct(
                                  item.adjustQty > 1
                                    ? parseFloat(item.adjustQty) - 1
                                    : 1,
                                  'quantity',
                                  i - 1
                                )
                                " />
                            <input type="number" step="any" :id="`Qty-${i}`" name="quantity"
                              class="quantity-field border-0 incrementor" @change="
                                updateProduct(
                                  $event.target.value,
                                  'quantity',
                                  i - 1
                                )
                                " @keyup="
    updateProduct(
      $event.target.value,
      'quantity',
      i - 1
    )
    " :placeholder="$t('common.quantity')" min="1" :max="item.maxQty" :value="item.adjustQty" required />
                            <input type="button" value="+" class="button-plus icon-shape icon-sm btn-primary"
                              data-field="quantity" @click="
                                updateProduct(
                                  parseFloat(item.adjustQty) + 1,
                                  'quantity',
                                  i - 1
                                )
                                " />
                          </div>
                        </td>
                        <td class="text-right">
                          <button type="button" class="btn btn-danger" @click="removeItem(item)">
                            <i class="fas fa-times"></i>
                          </button>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="form-group">
                <label for="note">{{ $t("common.note") }}</label>
                <textarea id="note" v-model="form.note" class="form-control"
                  :class="{ 'is-invalid': form.errors.has('note') }" :placeholder="$t('common.note_placeholder')" />
                <has-error :form="form" field="note" />
              </div>
              <div class="row">
                <div class="form-group col-md-6">
                  <label for="adjustmentDate">{{
                    $t("inventory.common.adjustment_date")
                  }}</label>
                  <input id="adjustmentDate" v-model="form.adjustmentDate" type="date" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('adjustmentDate') }" name="adjustmentDate" />
                  <has-error :form="form" field="adjustmentDate" />
                </div>
                <div class="form-group col-md-6">
                  <label for="status">{{ $t("common.status") }}</label>
                  <select id="status" v-model="form.status" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('status') }">
                    <option value="1">{{ $t("common.active") }}</option>
                    <option value="0">{{ $t("common.in_active") }}</option>
                  </select>
                  <has-error :form="form" field="status" />
                </div>
              </div>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
              <v-button :loading="form.busy" class="btn btn-primary">
                <i class="fas fa-save" /> {{ $t("common.save") }}
              </v-button>
              <button type="reset" class="btn btn-secondary float-right" @click="form.reset()">
                <i class="fas fa-power-off" /> {{ $t("common.reset") }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Form from "vform";
import axios from "axios";
import { mapGetters } from "vuex";

export default {
  middleware: ["auth", "check-permissions"],
  metaInfo() {
    return { title: this.$t("inventory.adjustments.create.page_title") };
  },
  data: () => ({
    breadcrumbsCurrent: "inventory.adjustments.create.breadcrumbs_current",
    breadcrumbs: [
      {
        name: "inventory.adjustments.create.breadcrumbs_first",
        url: "home",
      },
      {
        name: "inventory.adjustments.create.breadcrumbs_second",
        url: "adjustments.index",
      },
      {
        name: "inventory.adjustments.create.breadcrumbs_active",
        url: "",
      },
    ],
    form: new Form({
      selectedProducts: [],
      adjustmentReason: "",
      adjustmentDate: new Date().toISOString().slice(0, 10),
      note: "",
      status: 1,
    }),
    products: "",
    prefix: "",
  }),
  computed: {
    ...mapGetters("operations", ["items", "appInfo"]),
  },
  created() {
    this.getProducts();
    this.prefix = this.appInfo.productPrefix;
  },
  methods: {
    // get products
    async getProducts() {
      const { data } = await axios.get(
        window.location.origin + "/api/all-products"
      );
      this.products = data.data;
    },

    // store item in array
    storeProduct(product) {
      var index = this.form.selectedProducts.findIndex(
        (x) => x.id == product.id
      );
      let quantity = 1;
      if (index === -1) {
        // store product
        this.form.selectedProducts.unshift({
          id: product.id,
          slug: product.slug,
          name: product.name,
          itemCode: product.code,
          purchasePrice: product.avgPurchasePrice,
          stockQty: product.inventoryCount,
          adjustType: "Increment",
          adjustQty: quantity,
          maxQty: 9999,
        });
      } else {
        // update qty
        if (this.form.selectedProducts[index]) {
          quantity = this.form.selectedProducts[index].adjustQty
            ? this.form.selectedProducts[index].adjustQty + 1
            : 1;
          this.form.selectedProducts[index].adjustQty = quantity;
        }
      }
      return;
    },

    // update array
    updateProduct(value, type, index) {
      let selectedProduct = this.form.selectedProducts[index];
      if (selectedProduct) {
        if (type == "quantity" && selectedProduct.adjustQty >= 1) {
          selectedProduct.adjustQty = value;
        } else if (type == "adjustType") {
          selectedProduct.adjustType = value;
          if (selectedProduct.adjustType == "Decrement") {
            selectedProduct.maxQty = selectedProduct.stockQty;
          } else {
            selectedProduct.maxQty = 9999;
          }
        } else {
          selectedProduct.purchasePrice = value;
          selectedProduct.adjustQty = 1;
        }
      }
      this.form.selectedProducts[index] = selectedProduct;
      return;
    },

    // remove item from array
    removeItem(item) {
      let index = this.form.selectedProducts.indexOf(item);
      if (index > -1) {
        this.form.selectedProducts.splice(index, 1);
      }
      return;
    },

    // save adjustment
    async saveAdjustment() {
      await this.form
        .post(window.location.origin + "/api/inventory-adjustments")
        .then(() => {
          toast.fire({
            type: "success",
            title: this.$t("inventory.adjustments.create.success_msg"),
          });
          this.$router.push({ name: "adjustments.index" });
        })
        .catch(() => {
          toast.fire({ type: "error", title: this.$t("common.error_msg") });
        });
    },
  },
};
</script>
