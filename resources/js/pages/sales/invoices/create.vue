<template>
  <div>
    <!-- breadcrumbs Start -->
    <breadcrumbs :items="breadcrumbs" :current="breadcrumbsCurrent" />
    <!-- breadcrumbs end -->
    <div class="row">
      <div class="col-lg-12 col-xl-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">
              {{ $t("sales.invoices.create.form_title") }}
            </h3>
            <router-link :to="{ name: 'invoices.index' }" class="btn btn-dark float-right">
              <i class="fas fa-long-arrow-alt-left" /> {{ $t("common.back") }}
            </router-link>
          </div>
          <!-- /.card-header -->
          <!-- form start -->
          <form role="form" @submit.prevent="saveInvoice" @keydown="form.onKeydown($event)">
            <div class="card-body">
              <div class="row" v-if="items">
                <div class="form-group col-md-6">
                  <label for="client">{{ $t("common.client") }}
                    <span class="required">*</span></label>
                  <div class="row">
                    <div class="col">
                      <div class="d-flex w-100">
                        <v-select class="flex-grow-1" v-model="form.client" :options="items" label="name"
                          :class="{ 'is-invalid': form.errors.has('client') }" name="client"
                          :placeholder="$t('common.client_placeholder')" />
                        <ClientCreateModal @reloadClients="getClients">
                          <div class="input-group-text create-btn">
                            <i class="fas fa-solid fa-plus-circle"></i>
                          </div>
                        </ClientCreateModal>
                      </div>
                      <has-error :form="form" field="client" />
                    </div>
                  </div>
                </div>
                <div class="form-group col-md-6">
                  <label for="reference">
                    {{ $t("common.reference") }}
                  </label>
                  <input id="reference" v-model="form.reference" type="text" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('reference') }" name="reference"
                    :placeholder="$t('common.reference_placeholder')" />
                  <has-error :form="form" field="reference" />
                </div>
              </div>

              <div class="row" v-if="products">
                <div class="form-group col-md-12">
                  <label for="product">{{ $t("common.select_products") }}
                    <span class="required">*</span></label>
                  <div class="row">
                    <div class="col">
                      <div class="d-flex w-100">
                        <v-select class="flex-grow-1" v-model="form.product" :options="products" label="label" :class="{
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
                        <th>{{ $t("common.product_name") }}</th>
                        <th>{{ $t("common.quantity") }}</th>
                        <th>{{ $t("common.price") }}</th>
                        <th>{{ $t("common.unit_price") }}</th>
                        <th>{{ $t("common.tax") }}</th>
                        <th>{{ $t("common.subtotal") }}</th>
                        <th class="text-right">{{ $t("common.action") }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(item, i) in form.selectedProducts" :key="i">
                        <td>{{ ++i }}</td>
                        <td>
                          {{ item.code | withPrefix(prefix) }}
                        </td>
                        <td>
                          <span v-if="Number(item.inventoryCount) < Number(item.qty)
                            " v-tooltip="$t('common.insufficient_stock')" class="badge badge-danger p-2">
                            <i class="fas fa-exclamation"></i>
                          </span>
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

                            <input type="number" step="any" :id="`Qty-${i}`" :value="item.qty" name="quantity"
                              class="quantity-field border-0 incrementor" required min="1" :max="item.inventoryCount"
                              @change="
                                generateItemTotal(
                                  $event.target.value,
                                  'qty',
                                  i - 1,
                                  ''
                                )
                                " @keyup="generateItemTotal($event.target.value, 'qty', i - 1, '')"
                              placeholder="Quantity" />

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
                              data-field="unitPrice" @click="
                                generateItemTotal(
                                  item.unitPrice,
                                  'price',
                                  i - 1,
                                  'decrement'
                                )
                                " />
                            <input type="unitPrice" step="any" :id="`unitPrice-${i}`" :value="item.unitPrice"
                              name="unitPrice" class="quantity-field border-0 incrementor" required min="0" @change="
                                generateItemTotal(
                                  $event.target.value,
                                  'price',
                                  i - 1,
                                  ''
                                )
                                " @keyup="generateItemTotal($event.target.value, 'price', i - 1, '')" />

                            <input type="button" value="+" class="button-plus icon-shape icon-sm btn-primary"
                              data-field="unitPrice" @click="
                                generateItemTotal(
                                  item.unitPrice,
                                  'price',
                                  i - 1,
                                  'increment'
                                )
                                " />
                          </div>
                        </td>
                        <td>{{ item.unitCost | withCurrency }}</td>
                        <td>{{ item.totalTax | withCurrency }}</td>
                        <td>{{ item.totalPrice | withCurrency }}</td>
                        <td class="text-right">
                          <button type="button" class="btn btn-danger" @click="removeItem(item)">
                            <i class="fas fa-times"></i>
                          </button>
                        </td>
                      </tr>
                      <tr>
                        <td colspan="6" class="text-right">
                          <strong>{{ $t("common.total") }}</strong>
                        </td>
                        <td>
                          <strong>{{
                            form.productTotalTax | withCurrency
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
                <div class="form-group col-md-4">
                  <label for="discountType">{{
                    $t("common.discount_type")
                  }}</label>
                  <select id="discountType" v-model="form.discountType" step="any" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('discountType') }" name="discountType" @change="calculateSum"
                    @keyup="calculateSum">
                    <option value="0">{{ $t("common.fixed") }}</option>
                    <option value="1">{{ $t("common.percentage") }}(%)</option>
                  </select>
                  <has-error :form="form" field="discountType" />
                </div>
                <div class="form-group" :class="form.discountType == 1 ? 'col-md-2' : 'col-md-4'">
                  <label for="discount">{{ $t("common.discount") }}
                    <span v-if="form.discountType == 1">(%)</span></label>
                  <input id="discount" v-model="form.discount" type="number" step="any" min="1"
                    :max="form.discountType == 1 ? 100 : form.subTotal" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('discount') }" name="discount"
                    :placeholder="$t('common.discount_placeholder')" @change="calculateSum" @keyup="calculateSum" />
                  <has-error :form="form" field="discount" />
                </div>
                <div v-if="form.discountType == 1" class="form-group col-md-2">
                  <label for="totalDiscount">{{
                    $t("common.total_discount")
                  }}</label>
                  <input id="totalDiscount" v-model="form.totalDiscount" type="number" step="any" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('totalDiscount') }" name="totalDiscount" readonly />
                  <has-error :form="form" field="totalDiscount" />
                </div>
                <div class="form-group col-md-4">
                  <label for="transportCost">{{
                    $t("common.transport_cost")
                  }}</label>
                  <input id="transportCost" v-model="form.transportCost" type="number" step="any" min="1"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('transportCost') }" name="transportCost"
                    :placeholder="$t('common.transport_cost_placeholder')" @change="calculateSum" @keyup="calculateSum" />
                  <has-error :form="form" field="transportCost" />
                </div>
              </div>

              <div class="row">
                <div v-if="taxes" class="form-group col-md-4">
                  <label for="orderTax">{{ $t("common.invoice_tax") }}
                    <span class="required">*</span></label>
                  <v-select v-model="form.orderTax" :options="taxes" label="code"
                    :class="{ 'is-invalid': form.errors.has('orderTax') }" name="orderTax" placeholder="Select a tax type"
                    @input="calculateSum" />
                  <has-error :form="form" field="orderTax" />
                </div>
                <div v-if="taxes" class="form-group col-md-4">
                  <label for="totalTax">{{ $t("common.total_tax") }}</label>
                  <input id="totalTax" v-model="form.totalTax" type="text" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('totalTax') }" name="totalTax" readonly />
                  <has-error :form="form" field="totalTax" />
                </div>
                <div class="form-group col-md-4">
                  <label for="netTotal">{{ $t("common.net_total") }}</label>
                  <input id="netTotal" v-model="form.netTotal" type="number" step="any" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('netTotal') }" name="netTotal" readonly />
                  <has-error :form="form" field="netTotal" />
                </div>
              </div>

              <div class="row">
                <div class="form-group col-md-4">
                  <label for="poReference">{{
                    $t("common.po_reference")
                  }}</label>
                  <input id="poReference" v-model="form.poReference" type="text" step="any" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('poReference') }" name="poReference"
                    :placeholder="$t('common.po_reference_placeholder')" />
                  <has-error :form="form" field="poReference" />
                </div>
                <div class="form-group col-md-4">
                  <label for="paymentTerms">{{
                    $t("common.payment_terms")
                  }}</label>
                  <input id="paymentTerms" v-model="form.paymentTerms" type="text" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('paymentTerms') }" name="paymentTerms"
                    :placeholder="$t('common.payment_terms_placeholder')" />
                  <has-error :form="form" field="paymentTerms" />
                </div>
                <div class="form-group col-md-4">
                  <label for="addPayment">{{ $t("common.add_payment") }}</label>
                  <select id="addPayment" v-model="form.addPayment" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('addPayment') }" name="addPayment">
                    <option value="" selected disabled>
                      {{ $t("common.add_payment_placeholder") }}
                    </option>
                    <option :disabled="!form.selectedProducts" value="1">
                      {{ $t("common.yes") }}
                    </option>
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
                <div class="form-group col-md-4">
                  <label for="account">{{ $t("common.account") }}
                    <span class="required">*</span></label>
                  <v-select v-model="form.account" :options="accounts" label="label"
                    :class="{ 'is-invalid': form.errors.has('account') }" name="account"
                    :placeholder="$t('common.account_placeholder')" />
                  <has-error :form="form" field="account" />
                </div>
                <div class="form-group col-md-2">
                  <label for="paidAmount">{{ $t("common.paid_amount")
                  }}<span class="required">*</span></label>
                  <input id="paidAmount" v-model="form.paidAmount" type="number" step="any" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('paidAmount') }" name="paidAmount" min="1"
                    :max="form.netTotal" :placeholder="$t('common.paid_amount_placeholder')" />
                  <has-error :form="form" field="paidAmount" />
                </div>
                <div class="form-group col-md-3">
                  <label for="chequeNo">{{ $t("common.cheque_no") }}</label>
                  <input id="chequeNo" v-model="form.chequeNo" type="text" step="any" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('chequeNo') }" name="chequeNo"
                    :placeholder="$t('common.cheque_placeholder')" />
                  <has-error :form="form" field="chequeNo" />
                </div>
                <div class="form-group col-md-3">
                  <label for="receiptNo">{{ $t("common.receipt_no") }}</label>
                  <input id="receiptNo" v-model="form.receiptNo" type="text" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('receiptNo') }" name="receiptNo"
                    :placeholder="$t('common.receipt_no_placeholder')" />
                  <has-error :form="form" field="receiptNo" />
                </div>
              </div>
              <div class="row">
                <div class="form-group col-md-4">
                  <label for="deliveryPlace">{{
                    $t("sales.common.delivery_place")
                  }}</label>
                  <input id="deliveryPlace" v-model="form.deliveryPlace" type="text" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('deliveryPlace') }" name="deliveryPlace"
                    :placeholder="$t('sales.common.delivery_place_placeholder')" />
                  <has-error :form="form" field="deliveryPlace" />
                </div>
                <div class="form-group col-md-4">
                  <label for="date">{{ $t("common.date") }}</label>
                  <input id="date" v-model="form.date" type="date" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('date') }" name="date" />
                  <has-error :form="form" field="date" />
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

              <div class="form-group">
                <label for="note">{{ $t("common.note") }}</label>
                <textarea id="note" v-model="form.note" class="form-control"
                  :class="{ 'is-invalid': form.errors.has('note') }" :placeholder="$t('common.note_placeholder')" />
                <has-error :form="form" field="note" />
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
    return { title: this.$t("sales.invoices.create.page_title") };
  },
  components: {
    ToggleButton,
  },
  data: () => ({
    breadcrumbsCurrent: "sales.invoices.create.breadcrumbs_current",
    breadcrumbs: [
      {
        name: "sales.invoices.create.breadcrumbs_first",
        url: "home",
      },
      {
        name: "sales.invoices.create.breadcrumbs_second",
        url: "invoices.index",
      },
      {
        name: "sales.invoices.create.breadcrumbs_active",
        url: "",
      },
    ],
    form: new Form({
      invoiceNo: "",
      client: "",
      reference: "",
      selectedProducts: [],
      subTotal: 0,
      netTotal: 0,
      discountType: 0,
      discount: "",
      totalDiscount: "",
      transportCost: "",
      orderTax: "",
      totalTax: 0,
      productTotalTax: 0,
      account: "",
      totalPaid: "",
      dueAmount: "",
      poReference: "",
      paymentTerms: "",
      deliveryPlace: "",
      addPayment: "",
      chequeNo: "",
      receiptNo: "",
      date: new Date().toISOString().slice(0, 10),
      note: "",
      status: 1,
      isSendEmail: false,
      isSendSMS: false,
    }),
    products: "",
    accounts: "",
    taxes: "",
    prefix: "",
    isDemoMode: window.config.isDemoMode,
  }),
  computed: {
    ...mapGetters("operations", ["items", "appInfo"]),
  },
  created() {
    this.getClients();
    this.getProducts();
    this.getAccounts();
    this.getTaxes();
    this.prefix = this.appInfo.productPrefix;
  },
  methods: {
    // get all clients
    async getClients() {
      await this.$store.dispatch("operations/allData", {
        path: "/api/all-clients",
      });
      // assign default client
      if (this.items && this.items.length > 0) {
        let defaultClientSlug = this.appInfo.defaultClientSlug;
        this.form.client = this.items.find(
          (item) => item.slug === defaultClientSlug
        );
      }
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
          (item) => item.slug === defaultAccountSlug
        );
      }
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
      this.calculateSum();
    },

    // store item in array
    storeProduct(product) {
      var index = this.form.selectedProducts.findIndex(
        (x) => x.id == product.id
      );
      let quantity = 1;
      if (index === -1) {
        let productTax =
          product.taxType == "Exclusive"
            ? product.priceWithDiscount * (product.taxRate / 100)
            : product.priceWithDiscount -
            product.priceWithDiscount / (1 + product.taxRate / 100);
        let totalTax = productTax * quantity;

        this.form.selectedProducts.unshift({
          id: product.id,
          slug: product.slug,
          name: product.name,
          code: product.code,
          taxType: product.taxType,
          taxRate: product.taxRate,
          qty: quantity,
          inventoryCount: product.inventoryCount,
          avgPurchasePrice: product.avgPurchasePrice,
          unitPrice: product.priceWithDiscount,
          unitCost:
            product.taxType == "Exclusive"
              ? product.priceWithDiscount + productTax
              : product.priceWithDiscount,
          totalPrice:
            product.taxType == "Exclusive"
              ? 1 * (product.priceWithDiscount + totalTax)
              : 1 * product.priceWithDiscount,
          productTax: product.productTax > 0 ? product.productTax : 0,
          totalTax: totalTax,
        });
      }
      this.generateItemTotal(quantity, "qty", index, "");
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
      this.calculateSum();
      return;
    },

    // remove item from array
    removeItem(item) {
      let index = this.form.selectedProducts.indexOf(item);
      if (index > -1) {
        this.form.selectedProducts.splice(index, 1);
      }
      this.calculateSum();
      return;
    },

    // calculate sum
    calculateSum() {
      // calculate subtotal
      this.form.subTotal = this.form.selectedProducts.reduce(function (
        prev,
        cur
      ) {
        return Number((prev + cur.totalPrice).toFixed(2));
      },
        0);

      // calculate product tax
      this.form.productTotalTax = this.form.selectedProducts.reduce(function (
        prev,
        cur
      ) {
        return Number((prev + cur.totalTax).toFixed(2));
      },
        0);

      this.form.netTotal = this.form.subTotal;

      // calculate quatation tax
      this.form.totalTax = 0;
      if (this.form.orderTax) {
        this.form.totalTax =
          (this.form.orderTax.rate / 100) * this.form.subTotal;
      }

      // calculate discount and total
      if (this.form.subTotal > 0) {
        let discount = Number(this.form.discount);
        if (this.form.discountType == 1) {
          discount = (discount / 100) * this.form.subTotal;
          this.form.totalDiscount = Number(discount.toFixed(2));
        } else {
          discount = Number(this.form.discount);
        }
        this.form.netTotal =
          this.form.subTotal +
          Number(this.form.transportCost) -
          discount +
          this.form.totalTax;
      }
      return;
    },

    // vue file upload
    onFileChange(e) {
      const file = e.target.files[0];
      const reader = new FileReader();
      if (
        file.size < 2111775 &&
        (file.type === "image/jpeg" ||
          file.type === "image/png" ||
          file.type === "image/gif")
      ) {
        reader.onloadend = (file) => {
          this.formClient.image = reader.result;
        };
        reader.readAsDataURL(file);
        this.url = URL.createObjectURL(file);
      } else {
        Swal.fire(
          this.$t("common.error"),
          this.$t("common.image_error"),
          "error"
        );
      }
    },

    // save invoice
    async saveInvoice() {
      await this.form
        .post(window.location.origin + "/api/invoices")
        .then(({ data }) => {
          toast.fire({
            type: "success",
            title: this.$t("sales.invoices.create.success_msg"),
          });
          this.$router.push({
            name: "invoices.show",
            params: { slug: data.data.slug },
          });
        })
        .catch(() => {
          toast.fire({ type: "error", title: this.$t("common.error_msg") });
        });
    },

    // save client
    async saveClient() {
      await this.formClient
        .post(window.location.origin + "/api/clients")
        .then(() => {
          toast.fire({
            type: "success",
            title: this.$t("clients.create.success_msg"),
          });
          this.showClientCreateModal = false;
          this.getClients();
          this.formClient.reset();
          this.url = null;
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
