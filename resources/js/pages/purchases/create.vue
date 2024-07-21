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
              {{ $t("purchases.list.create.form_title") }}
            </h3>
            <router-link :to="{ name: 'purchases.index' }" class="btn btn-dark float-right">
              <i class="fas fa-long-arrow-alt-left" /> {{ $t("common.back") }}
            </router-link>
          </div>
          <!-- /.card-header -->
          <!-- form start -->
          <form role="form" @submit.prevent="savePurchase" @keydown="form.onKeydown($event)">
            <div class="card-body">
              <div class="row" v-if="items && products">
                <div class="form-group col-md-12 col-xl-6">
                  <label for="supplier">{{ $t("common.supplier") }}
                    <span class="required">*</span></label>
                  <div class="row">
                    <div class="col">
                      <div class="d-flex w-100">
                        <v-select class="flex-grow-1" v-model="form.supplier" :options="items" label="name"
                          :class="{ 'is-invalid': form.errors.has('supplier') }" name="supplier"
                          :placeholder="$t('common.supplier_placeholder')" />
                        <SupplierCreateModal @reloadSuppliers="getSuppliers">
                          <div class="input-group-text create-btn">
                            <i class="fas fa-solid fa-plus-circle"></i>
                          </div>
                        </SupplierCreateModal>
                      </div>
                      <has-error :form="form" field="supplier" />
                    </div>
                  </div>
                </div>
                <div class="form-group col-md-12 col-xl-6">
                  <label for="product">{{ $t("common.select_products") }}
                    <span class="required">*</span></label>
                  <div class="row">
                    <div class="col">
                      <div class="d-flex w-100">
                        <v-select v-model="form.product" :options="products" label="label" class="flex-grow-1" :class="{
                          'is-invalid': form.errors.has('selectedProducts'),
                        }" name="product" :placeholder="$t('common.select_products_placeholder')"
                          @input="storeProduct(form.product)" />
                        <ProductCreateModal @reloadProducts="getProducts">
                          <div class="input-group-text create-btn">
                            <i class="fas fa-solid fa-plus-circle"></i>
                          </div>
                        </ProductCreateModal>
                      </div>
                      <has-error :form="form" field="selectedProducts" />
                    </div>
                  </div>
                </div>
              </div>
              <div v-if="form.selectedProducts && form.selectedProducts.length > 0" class="row mt-3 mb-4">
                <div class="table-responsive table-custom w-95 m-auto">
                  <table class="table table-hover table-sm text-center">
                    <thead>
                      <tr>
                        <th>{{ $t("common.s_no") }}</th>
                        <th>{{ $t("common.code") }}</th>
                        <th>{{ $t("common.name") }}</th>
                        <th>{{ $t("common.quantity") }}</th>
                        <th>{{ $t("common.purchase_price") }}</th>
                        <th>{{ $t("common.unit_cost") }}</th>
                        <th>{{ $t("common.tax") }}</th>
                        <th>{{ $t("common.subtotal") }}</th>
                        <th class="text-right">{{ $t("common.action") }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(item, i) in form.selectedProducts" :key="i">
                        <td>{{ ++i }}</td>
                        <td>{{ item.code | withPrefix(prefix) }}</td>
                        <td>
                          <router-link v-if="$can('product-view')" :to="{
                            name: 'products.show',
                            params: { slug: item.slug },
                          }">
                            {{ item.name }}
                          </router-link>
                          <span v-else>{{ item.name }}</span>
                        </td>
                        <td>
                          <div class="input-group custom-qty-input">
                            <input type="button" value="-" class="button-minus icon-shape icon-sm btn-danger"
                              data-field="quantity" @click="
                                generateItemTotal(
                                  item.qty,
                                  'qty',
                                  i - 1,
                                  'decrement'
                                )
                                " />

                            <input type="number" step="any" :id="`purchaseQty-${i}`" :value="item.qty" name="quantity"
                              class="quantity-field border-0 incrementor" required min="1" @change="
                                generateItemTotal(
                                  $event.target.value,
                                  'qty',
                                  i - 1,
                                  ''
                                )
                                " @keyup="generateItemTotal($event.target.value, 'qty', i - 1, '')" />

                            <input type="button" value="+" class="button-plus icon-shape icon-sm btn-primary"
                              data-field="quantity" @click="
                                generateItemTotal(
                                  item.qty,
                                  'qty',
                                  i - 1,
                                  'increment'
                                )
                                " />
                          </div>
                        </td>
                        <td>
                          <div class="input-group custom-qty-input">
                            <input type="button" value="-" class="button-minus icon-shape icon-sm btn-danger"
                              data-field="quantity" @click="
                                generateItemTotal(
                                  item.unitPrice,
                                  'price',
                                  i - 1,
                                  'decrement'
                                )
                                " />

                            <input type="number" step="any" :id="`unitPrice-${i}`" :value="item.unitPrice"
                              name="unitPrice" class="quantity-field border-0 incrementor" required min="0" @change="
                                generateItemTotal(
                                  $event.target.value,
                                  'price',
                                  i - 1,
                                  ''
                                )
                                " @keyup="generateItemTotal($event.target.value, 'price', i - 1, '')" />

                            <input type="button" value="+" class="button-plus icon-shape icon-sm btn-primary"
                              data-field="quantity" @click="
                                generateItemTotal(
                                  item.unitPrice,
                                  'price',
                                  i - 1,
                                  'increment'
                                )
                                " />
                          </div>
                        </td>
                        <td>
                          {{ item.unitCost | withCurrency }}
                        </td>
                        <td>
                          {{ item.totalTax | withCurrency }}
                        </td>
                        <td>
                          {{ item.totalPrice | withCurrency }}
                        </td>
                        <td class="text-right">
                          <button type="button" class="btn btn-danger" @click="removeItem(item)">
                            <i class="fas fa-times"></i>
                          </button>
                        </td>
                      </tr>
                      <tr v-if="form.subTotal">
                        <td colspan="6" class="text-right">
                          <strong>{{ $t("common.subtotal") }}</strong>
                        </td>
                        <td>
                          <strong>{{
                            form.totalProductTax | withCurrency
                          }}</strong>
                        </td>
                        <td>
                          <strong>{{ form.subTotal | withCurrency }}</strong>
                        </td>
                        <td></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="row">
                <div class="form-group col-md-6 col-xl-3">
                  <label for="poReference">{{
                    $t("purchases.list.common.po_reference")
                  }}</label>
                  <input id="poReference" v-model="form.poReference" type="text" step="any" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('poReference') }" name="poReference" :placeholder="$t('purchases.list.common.po_reference_placeholder')
                      " />
                  <has-error :form="form" field="poReference" />
                </div>
                <div class="form-group col-md-6 col-xl-3">
                  <label for="paymentTerms">{{
                    $t("purchases.list.common.payment_terms")
                  }}</label>
                  <input id="paymentTerms" v-model="form.paymentTerms" type="text" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('paymentTerms') }" name="paymentTerms" :placeholder="$t('purchases.list.common.payment_terms_placeholder')
                      " />
                  <has-error :form="form" field="paymentTerms" />
                </div>
                <div v-if="taxes" class="form-group col-md-6 col-xl-3">
                  <label for="orderTax">{{ $t("purchases.list.common.purchase_tax") }}
                    <span class="required">*</span></label>
                  <v-select v-model="form.orderTax" :options="taxes" label="code"
                    :class="{ 'is-invalid': form.errors.has('orderTax') }" name="orderTax" :placeholder="$t('purchases.list.common.purchase_tax_placeholder')
                      " @input="updateTax" />
                  <has-error :form="form" field="orderTax" />
                </div>
                <div v-if="taxes" class="form-group col-md-6 col-xl-3">
                  <label for="totalTax">{{
                    $t("purchases.list.common.total_tax")
                  }}</label>
                  <input id="totalTax" v-model="form.totalTax" type="text" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('totalTax') }" name="totalTax" readonly />
                  <has-error :form="form" field="totalTax" />
                </div>
              </div>
              <div v-if="form.selectedProducts && form.selectedProducts.length > 0" class="row">
                <div class="form-group col-md-6 col-xl-3">
                  <label for="discount">{{
                    $t("purchases.list.common.discount")
                  }}</label>
                  <input id="discount" v-model="form.discount" type="number" step="any" min="1" :max="form.subTotal"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('discount') }" name="discount"
                    :placeholder="$t('purchases.list.common.discount_placeholder')
                      " @change="calculateSum" @keyup="calculateSum" />
                  <has-error :form="form" field="discount" />
                </div>
                <div class="form-group col-md-6 col-xl-3">
                  <label for="transportCost">{{
                    $t("purchases.list.common.transport_cost")
                  }}</label>
                  <input id="transportCost" v-model="form.transportCost" type="number" step="any" min="0"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('transportCost') }" name="transportCost"
                    :placeholder="$t('purchases.list.common.transport_cost_placeholder')
                        " @change="calculateSum" @keyup="calculateSum" />
                  <has-error :form="form" field="transportCost" />
                </div>
                <div class="form-group col-md-6 col-xl-3">
                  <label for="netTotal">{{ $t("common.net_total") }}</label>
                  <input id="netTotal" v-model="form.netTotal" type="number" step="any" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('netTotal') }" name="netTotal" readonly />
                  <has-error :form="form" field="netTotal" />
                </div>
                <div class="form-group col-md-6 col-xl-3">
                  <label for="addPayment">{{ $t("common.add_payment") }}</label>
                  <select id="addPayment" v-model="form.addPayment" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('addPayment') }" name="addPayment">
                    <option value="" selected disabled>
                      {{ $t("common.add_payment_placeholder") }}
                    </option>
                    <option value="1">{{ $t("common.yes") }}</option>
                    <option value="0">{{ $t("common.no") }}</option>
                  </select>
                  <has-error :form="form" field="addPayment" />
                </div>
              </div>
              <div class="row" v-if="form.addPayment == 1 &&
                accounts &&
                form.selectedProducts &&
                form.selectedProducts.length > 0
                ">
                <div class="form-group col-md-6">
                  <label for="account">{{ $t("common.account") }}
                    <span class="required">*</span></label>
                  <v-select v-model="form.account" :options="accounts" label="label"
                    :class="{ 'is-invalid': form.errors.has('account') }" name="account"
                    :placeholder="$t('common.account_placeholder')" @input="updateBalance" />
                  <has-error :form="form" field="account" />
                </div>
                <div class="form-group col-md-6">
                  <label for="availableBalance">{{
                    $t("common.available_balance")
                  }}</label>
                  <input id="availableBalance" v-model="form.availableBalance" type="number" step="any"
                    class="form-control" :class="{
                      'is-invalid': form.errors.has('availableBalance'),
                    }" name="availableBalance" readonly />
                  <has-error :form="form" field="availableBalance" />
                </div>
                <div class="form-group col-md-4">
                  <label for="totalPaid">{{ $t("common.total_paid") }}
                    <span class="required">*</span></label>
                  <input id="totalPaid" v-model="form.totalPaid" type="number" step="any" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('totalPaid') }" name="totalPaid" min="1" :max="form.netTotal"
                    :placeholder="$t('common.amount_placeholder')" />
                  <has-error :form="form" field="totalPaid" />
                </div>
                <div class="form-group col-md-4">
                  <label for="chequeNo">{{ $t("common.cheque_no") }}</label>
                  <input id="chequeNo" v-model="form.chequeNo" type="text" step="any" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('chequeNo') }" name="chequeNo"
                    :placeholder="$t('common.cheque_placeholder')" />
                  <has-error :form="form" field="chequeNo" />
                </div>
                <div class="form-group col-md-4">
                  <label for="receiptNo">{{ $t("common.receipt_no") }}</label>
                  <input id="receiptNo" v-model="form.receiptNo" type="text" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('receiptNo') }" name="receiptNo"
                    :placeholder="$t('common.receipt_no_placeholder')" />
                  <has-error :form="form" field="receiptNo" />
                </div>
              </div>
              <div class="form-group">
                <label for="note">{{ $t("common.note") }}</label>
                <textarea id="note" v-model="form.note" class="form-control"
                  :class="{ 'is-invalid': form.errors.has('note') }" :placeholder="$t('common.note_placeholder')" />
                <has-error :form="form" field="note" />
              </div>
              <div class="row">
                <div class="form-group col-md-4">
                  <label for="purchaseDate">{{
                    $t("purchases.list.common.purchase_date")
                  }}</label>
                  <input id="purchaseDate" v-model="form.purchaseDate" type="date" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('purchaseDate') }" name="purchaseDate" />
                  <has-error :form="form" field="purchaseDate" />
                </div>
                <div class="form-group col-md-4">
                  <label for="poDate">{{
                    $t("purchases.list.common.po_date")
                  }}</label>
                  <input id="poDate" v-model="form.poDate" type="date" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('poDate') }" name="poDate" />
                  <has-error :form="form" field="poDate" />
                </div>
                <div class="form-group col-md-4">
                  <label for="status">{{ $t("common.status") }}</label>
                  <select id="status" v-model="form.status" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('status') }">
                    <option value="1">{{ $t("common.active") }}</option>
                    <option value="0">{{ $t("common.in_active") }}</option>
                  </select>
                  <has-error :form="form" field="status" />
                </div>
              </div>
              <div class="form-group col-12 d-flex flex-wrap">
                <div class="pr-5">
                  <toggle-button v-model="form.isSendEmail" :disabled="isDemoMode" />
                  {{ $t("Send To Email") }}
                </div>
              </div>
              <div class="form-group col-12 d-flex flex-wrap">
                <div class="pr-5">
                  <toggle-button v-model="form.isSendSMS" :disabled="isDemoMode" />
                  {{ $t("Send To SMS") }}
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
import { mapGetters } from "vuex";
import { ToggleButton } from "vue-js-toggle-button";
import axios from "axios";

export default {
  middleware: ["auth", "check-permissions"],
  metaInfo() {
    return { title: this.$t("purchases.list.create.page_title") };
  },
  components: {
    ToggleButton,
  },
  data: () => ({
    breadcrumbsCurrent: "purchases.list.create.breadcrumbs_current",
    breadcrumbs: [
      {
        name: "purchases.list.create.breadcrumbs_first",
        url: "home",
      },
      {
        name: "purchases.list.create.breadcrumbs_second",
        url: "purchases.index",
      },
      {
        name: "purchases.list.create.breadcrumbs_active",
        url: "",
      },
    ],
    form: new Form({
      supplier: "",
      selectedProducts: [],
      subTotal: 0,
      netTotal: 0,
      discount: "",
      transportCost: "",
      account: "",
      availableBalance: "",
      totalProductTax: 0,
      orderTax: "",
      totalTax: 0,
      totalPaid: "",
      poReference: "",
      paymentTerms: "",
      addPayment: "",
      chequeNo: "",
      receiptNo: "",
      poDate: new Date().toISOString().slice(0, 10),
      purchaseDate: new Date().toISOString().slice(0, 10),
      note: "",
      status: 1,
      isSendEmail: false,
      isSendSMS: false,
    }),
    products: "",
    accounts: "",
    taxes: "",
    isDemoMode: window.config.isDemoMode,
  }),
  computed: {
    ...mapGetters("operations", ["items", "appInfo"]),
  },
  created() {
    this.getSuppliers();
    this.getProducts();
    this.getAccounts();
    this.getTaxes();
    this.prefix = this.appInfo.productPrefix;
  },
  methods: {
    // get all local suppliers
    async getSuppliers() {
      await this.$store.dispatch("operations/allData", {
        path: "/api/all-suppliers",
      });
      this.form.supplier = this.items[0];
    },
    // get products
    async getProducts() {
      const { data } = await axios.get(
        window.location.origin + "/api/all-products"
      );
      this.products = data.data;
      this.products.sort(this.sortProducts);
    },
    // sort products
    sortProducts(a, b) {
      if (Number(a.code) < Number(b.code)) {
        return -1;
      }
      if (Number(a.code) > Number(b.code)) {
        return 1;
      }
      return 0;
    },
    // get taxes
    async getTaxes() {
      const { data } = await axios.get(
        window.location.origin + "/api/all-vat-rates"
      );
      this.taxes = data.data;
      // assign default vat rate
      if (this.taxes && this.taxes.length > 0) {
        let defaultVatRateSlug = this.appInfo.defaultVatRateSlug;
        this.form.orderTax = this.taxes.find(
          (tax) => tax.slug === defaultVatRateSlug
        );
      }
      this.updateTax();
    },
    // get accounts
    async getAccounts() {
      const { data } = await axios.get(
        window.location.origin + "/api/all-accounts"
      );
      this.accounts = data.data;
      // assign default account
      if (this.accounts && this.accounts.length > 0) {
        let defaultAccountSlug = this.appInfo.defaultAccountSlug;
        this.form.account = this.accounts.find(
          (account) => account.slug === defaultAccountSlug
        );
      }
      this.updateBalance();
    },
    // update available balance
    updateBalance() {
      this.form.availableBalance = 0;
      if (this.form.account) {
        this.form.availableBalance = this.form.account.availableBalance;
      }
      return;
    },
    // store item in array
    storeProduct(product) {
      var index = this.form.selectedProducts.findIndex(
        (x) => x.id == product.id
      );
      let quantity = 1;
      if (index === -1) {
        let purchasePrice =
          product.avgPurchasePrice > 0 ? product.avgPurchasePrice : 1;
        let productTax =
          product.taxType == "Exclusive"
            ? purchasePrice * (product.taxRate / 100)
            : purchasePrice - purchasePrice / (1 + product.taxRate / 100);
        let totalTax = productTax * quantity;
        // store product
        this.form.selectedProducts.unshift({
          id: product.id,
          slug: product.slug,
          name: product.name,
          code: product.code,
          qty: quantity,
          taxType: product.taxType,
          taxRate: product.taxRate,
          productTax: productTax,
          totalTax: productTax * quantity,
          unitPrice: purchasePrice,
          unitCost:
            product.taxType == "Exclusive"
              ? purchasePrice + totalTax
              : purchasePrice,
          totalPrice:
            product.taxType == "Exclusive"
              ? 1 * (purchasePrice + totalTax)
              : 1 * purchasePrice,
        });
      }
      this.generateItemTotal(quantity, "qty", index, "");
      this.updateTax();
      return;
    },
    // update array
    generateItemTotal(value, type, index, action) {
      let item = this.form.selectedProducts[index];
      if (item) {
        if (type == "qty") {
          item.qty = value;
          if (action == "increment") {
            item.qty = Number(item.qty) + 1;
          } else if (action == "decrement") {
            if (item.qty > 0) {
              item.qty = Number(item.qty) - 1;
            }
          }
        } else {
          item.unitPrice = value;
          if (action == "increment") {
            item.unitPrice = Number(item.unitPrice) + 1;
          } else if (action == "decrement") {
            if (item.unitPrice > 0) {
              item.unitPrice = Number(item.unitPrice) - 1;
            }
          }
        }
        item.productTax =
          item.taxType == "Exclusive"
            ? item.unitPrice * (item.taxRate / 100)
            : item.unitPrice - item.unitPrice / (1 + item.taxRate / 100);
        item.totalTax = item.productTax * item.qty;
        item.totalPrice =
          item.taxType == "Exclusive"
            ? item.qty * item.unitPrice + item.totalTax
            : item.qty * item.unitPrice;
        item.unitCost =
          item.taxType == "Exclusive"
            ? Number(item.unitPrice) + Number(item.productTax)
            : item.unitPrice;
        this.form.selectedProducts[index] = item;
      }
      this.updateTax();
      return;
    },
    // remove item from array
    removeItem(item) {
      let index = this.form.selectedProducts.indexOf(item);
      if (index > -1) {
        this.form.selectedProducts.splice(index, 1);
      }
      this.updateTax();
      return;
    },
    // update tax
    updateTax() {
      this.form.totalTax = 0;
      if (
        this.form.orderTax &&
        this.form.orderTax.rate > 0 &&
        this.form.netTotal > 0
      ) {
        this.form.totalTax = Number(
          ((this.form.orderTax.rate / 100) * this.form.subTotal).toFixed(2)
        );
      }
      this.calculateSum();
      return;
    },
    // calculate sum
    calculateSum() {
      this.form.subTotal = this.form.selectedProducts.reduce(function (
        prev,
        cur
      ) {
        return Number((prev + cur.totalPrice).toFixed(2));
      },
        0);
      this.form.totalProductTax = this.form.selectedProducts.reduce(function (
        prev,
        cur
      ) {
        return Number((prev + cur.totalTax).toFixed(2));
      },
        0);
      if (this.form.subTotal > 0) {
        this.form.netTotal = Number(
          (
            this.form.subTotal +
            Number(this.form.totalTax) +
            Number(this.form.transportCost) -
            Number(this.form.discount)
          ).toFixed(2)
        );
      }
      return;
    },
    // save purchase
    async savePurchase() {
      await this.form
        .post(window.location.origin + "/api/purchases")
        .then(({ data }) => {
          toast.fire({
            type: "success",
            title: this.$t("purchases.list.create.success_msg"),
          });
          this.$router.push({
            name: "purchases.show",
            params: { slug: data.data.slug },
          });
        })
        .catch(() => {
          toast.fire({ type: "error", title: this.$t("common.error_msg") });
        });
    },
  },
};
</script>

<style scoped>
.create-btn {
  padding: 11px;
}
</style>
