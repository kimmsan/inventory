<template>
  <div id="pos">
    <!-- breadcrumbs Start -->
    <breadcrumbs :items="breadcrumbs" :current="breadcrumbsCurrent" />
    <!-- breadcrumbs end -->

    <div class="row sm-col-reverse">
      <!-- pos left area start -->
      <div class="col-12 col-md-5">
        <div class="card">
          <div class="card-body-l p-0">
            <div class="form-group pl-3 pt-3 pr-3">
              <div class="d-flex w-100">
                <v-select class="flex-grow-1" v-model="form.client" :options="clients" label="name"
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

            <div class="table-responsive table-wrap">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th scope="col">{{ $t("common.product") }}</th>
                    <th scope="col">{{ $t("common.price") }}</th>
                    <th scope="col" class="text-center">
                      {{ $t("common.quantity") }}
                    </th>
                    <th scope="col" class="text-center">
                      {{ $t("common.subtotal") }}
                    </th>
                    <th scope="col" class="text-center">
                      {{ $t("common.action") }}
                    </th>
                  </tr>
                </thead>
                <tbody v-if="form.selectedProducts && form.selectedProducts.length > 0
                  ">
                  <tr v-for="(product, i) in form.selectedProducts" :key="i">
                    <td>{{ product.name }}</td>
                    <td>{{ product.unitPrice | withCurrency }}</td>
                    <td>
                      <div class="d-flex custom-qty-input">
                        <input type="button" value="-" class="button-minus icon-shape icon-sm btn-danger"
                          data-field="quantity" @click="
                            generateItemTotal(
                              product.qty,
                              'qty',
                              i,
                              'decrement'
                            )
                            " />
                        <input type="number" step="any" :id="`Qty-${i}`" :value="product.qty" name="quantity"
                          class="quantity-field border-0 incrementor" required min="1" :max="product.inventoryCount"
                          @change="
                            generateItemTotal($event.target.value, 'qty', i, '')
                            " @keyup="generateItemTotal($event.target.value, 'qty', i, '')" placeholder="Quantity" />
                        <input type="button" value="+" class="button-plus icon-shape icon-sm btn-primary"
                          data-field="quantity" @click="
                            generateItemTotal(
                              product.qty,
                              'qty',
                              i,
                              'increment'
                            )
                            " />
                      </div>
                    </td>
                    <td>{{ product.totalPrice | withCurrency }}</td>
                    <td class="text-right">
                      <button type="button" class="btn btn-danger" @click="removeItem(product)">
                        <i class="fas fa-times"></i>
                      </button>
                    </td>
                  </tr>
                </tbody>
                <tbody v-else>
                  <tr class="text-center">
                    <td colspan="5">{{ $t("no_data_found") }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="pos-card-footer bg-white">
          <div>
            <div class="row pt-3 pl-3 pr-3">
              <div class="form-group col-md-6 col-lg-6">
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
              <div class="form-group col-md-6 col-lg-6">
                <label for="discount">{{ $t("common.discount") }}
                  <span v-if="form.discountType == 1">(%)</span></label>
                <div class="input-group">
                  <input id="discount" v-model="form.discount" type="number" step="any" min="1"
                    :max="form.discountType == 1 ? 100 : form.subTotal" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('discount') }" name="discount"
                    :placeholder="$t('common.discount_placeholder')" @change="calculateSum" @keyup="calculateSum" />
                  <div v-if="form.discountType == 1" class="input-group-append">
                    <span class="input-group-text">{{
                      form.totalDiscount | withCurrency
                    }}</span>
                  </div>
                </div>
                <has-error :form="form" field="discount" />
              </div>
              <div class="form-group col-md-6 col-lg-6">
                <label for="transportCost">{{
                  $t("common.transport_cost")
                }}</label>
                <input id="transportCost" v-model="form.transportCost" type="number" step="any" min="1"
                  class="form-control" :class="{ 'is-invalid': form.errors.has('transportCost') }" name="transportCost"
                  :placeholder="$t('common.transport_cost_placeholder')" @change="calculateSum" @keyup="calculateSum" />
                <has-error :form="form" field="transportCost" />
              </div>

              <div v-if="taxes" class="form-group col-md-6 col-lg-6">
                <label for="orderTax">{{ $t("common.invoice_tax") }} </label>
                <div class="input-group select-input-group">
                  <v-select class="w-85" v-model="form.orderTax" :options="taxes" label="code"
                    :class="{ 'is-invalid': form.errors.has('orderTax') }" name="orderTax"
                    :placeholder="$t('sales.common.invoice_tax_placeholder')" @input="calculateSum" />
                  <div class="input-group-prepend input-c-margin">
                    <div class="input-group-text">
                      <span v-if="form.orderTax">{{
                        form.totalTax | withCurrency
                      }}</span>
                      <span v-else>{{ 0 | withCurrency }}</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="pos-net-total noi-print">
              {{ $t("common.net_total") }}: {{ form.netTotal | withCurrency }}
            </div>
          </div>
        </div>

        <div class="row no-print">
          <div class="col-12 col-lg-5 mb-1">
            <button class="btn btn-primary btn-block" @click="saveInvoice" @keydown="form.onKeydown($event)">
              <i class="fas fa-save" /> {{ $t("pos.complete_order") }}
            </button>
          </div>
          <div class="col-12 col-lg-5 mb-1">
            <button class="btn btn-primary btn-block" @click="completeOrderAndAddPayment">
              <i class="fas fa-credit-card" />
              {{ $t("pos.complete_order_and_add_payment") }}
            </button>
          </div>
          <div class="col-12 col-lg-2">
            <button type="reset" class="btn btn-secondary float-right btn-block" @click="form.reset()">
              <i class="fas fa-power-off" /> {{ $t("common.reset") }}
            </button>
          </div>
        </div>
      </div>
      <!-- pos left area end -->

      <!-- POS Right area start -->
      <div class="col-12 col-md-7">
        <div class="card bg-transparent">
          <div class="pos-r-head bg-white">
            <div class="row">
              <div v-if="categories" class="form-group col-md-6">
                <v-select v-model="form.category" :options="categories" label="name"
                  :class="{ 'is-invalid': form.errors.has('category') }" name="category"
                  :placeholder="$t('common.category_name_placeholder')" @input="getSubCategoriesByCategory" />
                <has-error :form="form" field="category" />
              </div>
              <div v-if="subCategories" class="form-group col-md-6">
                <v-select v-model="form.subCategory" :options="subCategories" label="name"
                  :class="{ 'is-invalid': form.errors.has('subCategory') }" name="subCategory"
                  :placeholder="$t('common.category_name_placeholder')" @input="getProductsBySubCategory" />
                <has-error :form="form" field="subCategory" />
              </div>
              <div v-if="products" class="col-md-12 form-group">
                <div class="d-flex w-100">
                  <search class="flex-grow-1" :isPosSearch="true" v-model="query" @reset-pagination="resetPagination()"
                    @reload="reload" />
                  <ProductCreateModal @reloadProducts="getProducts">
                    <div class="input-group-text create-btn-2">
                      <i class="fas fa-solid fa-plus-circle"></i>
                    </div>
                  </ProductCreateModal>
                </div>
                <has-error :form="form" field="selectedProducts" />
              </div>
            </div>
          </div>

          <div class="card-body bg-white mt-3 pos-body">
            <div class="pos-item-grid">
              <div v-for="product in products" :key="product.id" @click="storeProduct(product)" :class="Number(product.inventoryCount) < 1 ? 'pos-item-grid-red' : ''
                ">
                <div class="pos-box">
                  <div class="relative">
                    <div class="pos-box-img">
                      <div v-if="product.image">
                        <img class="pos-box-icon" :src="product.image" alt="product image" />
                      </div>
                      <div v-else>{{ $t("common.no_preview") }}</div>
                    </div>
                    <span class="box-qty" :class="Number(product.inventoryCount) < 1 ? 'qty-red' : ''
                      ">{{ product.inventoryCount }}</span>
                  </div>
                  <div class="pos-box-content">
                    <span>{{ product.code | withPrefix(productPrefix) }}</span>
                    <p class="pos-box-text">{{ product.name }}</p>
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-12 d-flex justify-content-center">
                <!-- pagination-start -->
                <pagination v-if="pagination && pagination.last_page > 1" :pagination="pagination" :offset="5"
                  class="justify-flex-end mt-3" @paginate="paginate" />
                <!-- pagination-end -->
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- POS Right area end -->
    </div>

    <!-- use the modal component, pass in the prop -->
    <Modal class="pay-modal" v-if="showModal" :form="form">
      <h5 slot="header">{{ $t("pos.add_payment") }}</h5>
      <div class="w-100" slot="body">
        <div>
          <div class="row" v-if="accounts &&
            form.selectedProducts &&
            form.selectedProducts.length > 0
            ">
            <div class="form-group col-md-8">
              <label for="account">{{ $t("common.account") }}
                <span class="required">*</span></label>
              <v-select v-model="form.account" :options="accounts" label="label"
                :class="{ 'is-invalid': form.errors.has('account') }" name="account"
                :placeholder="$t('common.account_placeholder')" />
              <has-error :form="form" field="account" />
            </div>
            <div class="form-group col-md-4">
              <label for="paidAmount">{{ $t("common.amount") }}<span class="required">*</span></label>
              <input ref="paidAmountInput" id="paidAmount" v-model="form.paidAmount" type="number" step="any"
                class="form-control" :class="{ 'is-invalid': form.errors.has('paidAmount') }" name="paidAmount" min="1"
                :max="form.netTotal" :placeholder="$t('common.paid_amount_placeholder')" />
              <has-error :form="form" field="paidAmount" />
            </div>
          </div>
          <div class="row">
            <div class="form-group col-md-6">
              <label for="chequeNo">{{ $t("common.cheque_no") }}</label>
              <input id="chequeNo" v-model="form.chequeNo" type="text" step="any" class="form-control"
                :class="{ 'is-invalid': form.errors.has('chequeNo') }" name="chequeNo"
                :placeholder="$t('common.cheque_placeholder')" />
              <has-error :form="form" field="chequeNo" />
            </div>
            <div class="form-group col-md-6">
              <label for="receiptNo">{{ $t("common.receipt_no") }}</label>
              <input id="receiptNo" v-model="form.receiptNo" type="text" class="form-control"
                :class="{ 'is-invalid': form.errors.has('receiptNo') }" name="receiptNo"
                :placeholder="$t('common.receipt_no_placeholder')" />
              <has-error :form="form" field="receiptNo" />
            </div>
          </div>
          <div class="row">
            <div class="form-group col-md-6">
              <label for="poReference">{{ $t("common.po_reference") }}</label>
              <input id="poReference" v-model="form.poReference" type="text" step="any" class="form-control"
                :class="{ 'is-invalid': form.errors.has('poReference') }" name="poReference"
                :placeholder="$t('common.po_reference_placeholder')" />
              <has-error :form="form" field="poReference" />
            </div>
            <div class="form-group col-md-6">
              <label for="paymentTerms">{{ $t("common.payment_terms") }}</label>
              <input id="paymentTerms" v-model="form.paymentTerms" type="text" class="form-control"
                :class="{ 'is-invalid': form.errors.has('paymentTerms') }" name="paymentTerms"
                :placeholder="$t('common.payment_terms_placeholder')" />
              <has-error :form="form" field="paymentTerms" />
            </div>
          </div>
          <div class="row">
            <div class="form-group col-md-6">
              <label for="reference">{{ $t("common.reference") }}</label>
              <input id="reference" v-model="form.reference" type="text" class="form-control"
                :class="{ 'is-invalid': form.errors.has('reference') }" name="reference"
                :placeholder="$t('common.reference_placeholder')" />
              <has-error :form="form" field="reference" />
            </div>
            <div class="form-group col-md-6">
              <label for="deliveryPlace">{{
                $t("sales.common.delivery_place")
              }}</label>
              <input id="deliveryPlace" v-model="form.deliveryPlace" type="text" class="form-control"
                :class="{ 'is-invalid': form.errors.has('deliveryPlace') }" name="deliveryPlace"
                :placeholder="$t('sales.common.delivery_place_placeholder')" />
              <has-error :form="form" field="deliveryPlace" />
            </div>
          </div>
          <div class="row">
            <div class="form-group col-md-6">
              <label for="date">{{ $t("common.date") }}</label>
              <input id="date" v-model="form.date" type="date" class="form-control"
                :class="{ 'is-invalid': form.errors.has('date') }" name="date" />
              <has-error :form="form" field="date" />
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
          <div class="form-group">
            <label for="note">{{ $t("common.note") }}</label>
            <textarea id="note" v-model="form.note" class="form-control"
              :class="{ 'is-invalid': form.errors.has('note') }" :placeholder="$t('common.note_placeholder')" />
            <has-error :form="form" field="note" />
          </div>
        </div>
      </div>
      <div class="payment-modal-footer" slot="modal-footer">
        <div class="pos-modal-footer no-print">
          <button class="btn btn-primary" @click="addPayment" @keydown="form.onKeydown($event)">
            <i class="fas fa-save" /> {{ $t("common.save") }}
          </button>
          <button class="modal-default-button btn btn-danger" @click="closeModalAndClearFormData">
            {{ $t("common.close") }}
          </button>
        </div>
      </div>
    </Modal>

    <Modal v-if="showSmallInvoiceModal" :allData="allData">
      <h5 slot="header" class="no-print">{{ $t("pos.invoice_receipt") }}</h5>
      <div class="w-100" slot="body">
        <div id="invoice-POS">
          <div style="max-width: 400px; margin: 0px auto">
            <div class="info">
              <div v-if="appInfo.blackLogo" class="pos-logo">
                <img :src="appInfo.blackLogo" width="100px" />
              </div>
              <h2 v-else class="text-center">{{ appInfo.companyName }}</h2>
              <p>
                <span>{{ $t("common.date") }} : {{ allData.invoiceDate }} <br /></span>
                <span v-show="appInfo.address">{{ $t("common.address") }} : {{ appInfo.address }} <br /></span>
                <span v-show="appInfo.email">{{ $t("common.email") }} : {{ appInfo.email }} <br /></span>
                <span v-show="appInfo.phone">{{ $t("common.phone") }} : {{ appInfo.phone }} <br /></span>
                <span v-show="allData.client.name">{{ $t("common.client") }} : {{ allData.client.name }} <br /></span>
                <span v-show="allData.createdBy">{{ $t("common.sold_by") }} : {{ allData.createdBy }} <br /></span>
              </p>
            </div>

            <table class="table_data">
              <tbody>
                <tr v-for="(data, i) in invoiceProducts" :key="i">
                  <td colspan="3">
                    <span>
                      {{ data.productName }}<br />
                      <span class="pqty">{{ data.quantity }} {{ data.productUnit }} x
                        {{ data.unitCost | withCurrency }}</span>
                    </span>
                  </td>
                  <td style="text-align: right; vertical-align: bottom">
                    {{ (data.unitCost * data.quantity) | withCurrency }}
                  </td>
                </tr>

                <tr style="margin-top: 10px">
                  <td colspan="3" class="total">{{ $t("common.subtotal") }}</td>
                  <td style="text-align: right" class="total">
                    {{ allData.subTotal | withCurrency }}
                  </td>
                </tr>
                <tr v-if="allData.discount" style="margin-top: 10px">
                  <td colspan="3" class="total">{{ $t("common.discount") }}</td>
                  <td style="text-align: right" class="total">
                    {{ allData.discount | withCurrency }}
                  </td>
                </tr>
                <tr v-if="allData.tax" style="margin-top: 10px">
                  <td colspan="3" class="total">{{ $t("common.tax") }}(%)</td>
                  <td style="text-align: right" class="total">
                    {{ allData.tax | withCurrency }}
                  </td>
                </tr>
                <tr style="margin-top: 10px">
                  <td colspan="3" class="total">{{ $t("common.total") }}</td>
                  <td style="text-align: right" class="total">
                    {{
                      (allData.subTotal -
                        allData.totalInvoiceReturn -
                        allData.discount +
                        allData.transport +
                        allData.tax)
                      | withCurrency
                    }}
                  </td>
                </tr>
                <tr>
                  <td colspan="3" class="total">{{ $t("common.paid") }}</td>
                  <td style="text-align: right" class="total">
                    {{ allData.totalPaid | withCurrency }}
                  </td>
                </tr>
                <tr>
                  <td colspan="3" class="total">{{ $t("common.due") }}</td>
                  <td style="text-align: right" class="total">
                    {{ allData.due | withCurrency }}
                  </td>
                </tr>
              </tbody>
            </table>
            <div id="legalcopy" class="ml-2 mb-4">
              <p class="legal">
                <strong>{{ $t("pos.receipt_text") }}</strong>
              </p>
              <div id="bar">
                <barcode width="2" height="25" fontSize="15" :value="allData.invoiceNo | withPrefix(invoicePrefix)">
                  {{ $t("common.rendering_fails") }}
                </barcode>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="pos-modal-footer no-print" slot="modal-footer">
        <div>
          <button @click="printInvoice()" class="modal-default-button btn btn-info">
            {{ $t("common.print") }}
          </button>
        </div>
        <button class="modal-default-button btn btn-danger" @click="closeReceiptModal">
          {{ $t("common.close") }}
        </button>
      </div>
    </Modal>
  </div>
</template>

<script>
import Form from "vform";
import axios from "axios";
import { mapGetters } from "vuex";
import VueBarcode from "vue-barcode";
import sound from "../../../audio/beep.wav";

export default {
  middleware: ["auth"],
  metaInfo() {
    return { title: this.$t("pos.page_title") };
  },
  components: {
    barcode: VueBarcode,
  },
  data: () => ({
    breadcrumbsCurrent: "pos.breadcrumbs_current",
    breadcrumbs: [
      {
        name: "pos.breadcrumbs_first",
        url: "home",
      },
      {
        name: "pos.breadcrumbs_second",
        url: "invoices.index",
      },
      {
        name: "pos.breadcrumbs_active",
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
      category: "",
      invoice_id: null,
      invoice_slug: null,
    }),
    taxes: [],
    audio: "",
    products: "",
    accounts: "",
    categories: [],
    subCategories: [],
    productPrefix: "",
    invoicePrefix: "",
    showModal: false,
    allData: {},
    showSmallInvoiceModal: false,
    printMe: false,
    perPage: 10,
    pagination: "",
    query: "",
    generateOrder: false,
    clickCount: 0,
    clients: [],
  }),
  computed: {
    ...mapGetters("operations", ["items", "appInfo"]),
  },
  mounted() {
    window.addEventListener("keypress", (e) => {
      if (
        this.form.netTotal > 0 &&
        this.showModal == false &&
        this.generateOrder == false
      ) {
        if (e.key === "Enter") {
          this.generateOrder = true;
          this.completeOrderAndAddPayment();
        }
      }
      if (
        this.form.netTotal > 0 &&
        this.form.paidAmount > 0 &&
        this.generateOrder == true
      ) {
        if (e.key === "Enter") {
          this.clickCount++;
          this.showModal = false;
          console.log("from second click", this.clickCount);
          if (this.clickCount == 1) {
            this.addPayment();
          } else {
            this.printInvoice();
          }
        }
      }
    });
  },
  created() {
    this.getClients();
    this.getProducts();
    this.getAccounts();
    this.getTaxes();
    this.getCategories();
    this.getSubCategories();
    this.audio = new Audio(sound);
    this.productPrefix = this.appInfo.productPrefix;
    this.invoicePrefix = this.appInfo.invoicePrefix;
    document.body.classList.add("sidebar-collapse");
  },
  watch: {
    // watch search data
    query: function (newQ, oldQ) {
      if (newQ == "") {
        this.getProducts();
      } else {
        this.searchProducts();
      }
    },
  },
  methods: {
    doThis() {
      console.log("do this");
    },
    // get all clients
    async getClients() {
      await axios
        .get("/api/all-clients")
        .then(({ data }) => {
          this.clients = data.data;
          // assign default client
          if (this.clients && this.clients.length > 0) {
            let defaultClientSlug = this.appInfo.defaultClientSlug;
            this.form.client = this.clients.find(
              (item) => item.slug === defaultClientSlug
            );
          }
        })
        .catch((error) => console.log(error));
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
          (account) => account.slug == defaultAccountSlug
        );
      }
    },

    // get taxes
    async getTaxes() {
      const { data } = await axios.get(
        window.location.origin + "/api/all-vat-rates"
      );
      this.taxes = data.data;

      // assign default
      if (this.taxes && this.taxes.length > 0) {
        let defaultVatRateSlug = this.appInfo.defaultVatRateSlug;
        this.form.orderTax = this.taxes.find(
          (item) => item.slug === defaultVatRateSlug
        );
      }
    },

    // get categories
    async getCategories() {
      const { data } = await axios.get(
        window.location.origin + "/api/all-product-categories"
      );
      this.categories = data.data;
    },

    // get sub categories
    async getSubCategories() {
      const { data } = await axios.get(
        window.location.origin + "/api/all-product-sub-categories"
      );
      this.subCategories = data.data;
    },

    // get the invoice info by invoice slug
    async getInvoice(invoice_slug) {
      this.loading = true;
      const { data } = await axios.get(
        window.location.origin + "/api/invoices/" + invoice_slug
      );
      this.allData = data.data;
      this.invoiceProducts = this.allData.invoiceProducts;
      this.invoiceProducts.sort(this.sortProducts);
      this.loading = false;
    },

    // get products
    async getProducts() {
      let currentPage = this.pagination ? this.pagination.current_page : 1;
      const { data } = await axios.get(
        window.location.origin +
        "/api/all-products-paginated?page=" +
        currentPage
      );
      this.products = data.data;
      this.products.sort(this.sortProducts);
      this.pagination = data.meta;
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

    // get sub categories for a category
    async getSubCategoriesByCategory() {
      let currentPage = this.pagination ? this.pagination.current_page : 1;

      this.subCategories = [];
      this.form.subCategory = "";

      let slug = this.form.category?.slug;
      if (slug) {
        const { data } = await axios.get(
          window.location.origin +
          "/api/all-pro-sub-categories-by-category/" +
          slug +
          "?page=" +
          currentPage
        );
        this.subCategories = data.cats;
        this.products = data.products;
      } else {
        await this.getSubCategories();
        await this.getProducts();
      }
    },

    // get products for a sub category
    async getProductsBySubCategory() {
      let currentPage = this.pagination ? this.pagination.current_page : 1;
      this.products = [];
      this.form.itemName = "";
      let catSlug = this.form.category?.slug;
      let subCatSlug = this.form.subCategory?.slug;
      if (catSlug && subCatSlug) {
        const { data } = await axios.get(
          window.location.origin +
          "/api/all-products-by-sub-categories/" +
          catSlug +
          "/" +
          subCatSlug +
          "?page=" +
          currentPage
        );
        this.products = data.data;
        this.pagination = data.meta;
      } else {
        await this.getProducts();
      }
    },

    // pagination
    async paginate() {
      let catSlug = this.form.category?.slug;
      let subCatSlug = this.form.subCategory?.slug;
      if (this.query === "") {
        if (catSlug) {
          await this.getSubCategoriesByCategory();
        } else if (catSlug && subCatSlug) {
          await this.getProductsBySubCategory();
        } else {
          await this.getProducts();
        }
      } else {
        await this.searchProducts();
      }
    },

    // Reset pagination
    async resetPagination() {
      this.pagination ? (this.pagination.current_page = 1) : "";
      await this.searchProducts();
    },

    // search data
    async searchProducts() {
      let catSlug = this.form.category ? this.form.category.slug : "";
      let subCatSlug = this.form.subCategory ? this.form.subCategory.slug : "";
      this.$store.state.operations.loading = true;
      let currentPage = this.pagination ? this.pagination.current_page : 1;
      const { data } = await axios.get(
        window.location.origin +
        "/api/products/search-from-pos" +
        "?term=" +
        this.query +
        "&page=" +
        currentPage +
        "&perPage=" +
        this.perPage +
        "&catSlug=" +
        catSlug +
        "&SubCatSlug=" +
        subCatSlug
      );
      this.products = data.data;
      this.products.sort(this.sortProducts);
      this.pagination = data.meta;
      console.log(this.pagination);
    },

    // Reload after search
    async reload() {
      this.query = "";
      await this.searchProducts();
    },

    // store item in array
    storeProduct(product) {
      var index = this.form.selectedProducts.findIndex(
        (x) => x.id == product.id
      );
      let quantity = 1;
      if (product.inventoryCount >= quantity) {
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
          // play sound if added
          this.audio.play();
        } else {
          quantity = this.form.selectedProducts[index].qty;
          // play sound if added
          this.audio.play();
          this.generateItemTotal(quantity, "qty", index, "increment");
          let unitPrice = this.form.selectedProducts[index].unitPrice;
          this.generateItemTotal(unitPrice, "price", index, "increment");
          return;
        }
      } else {
        toast.fire({
          type: "error",
          title: this.$t("common.insufficient_stock"),
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
          if (item.inventoryCount >= value) {
            if (action == "increment") {
              item.qty = Number(item.qty) + 1;
            } else if (action == "decrement") {
              item.qty = Number(item.qty) - 1;
            }
          } else {
            item.qty = 1;
            toast.fire({
              type: "error",
              title: this.$t("pos.not_enough_items"),
            });
          }
        } else {
          item.unitPrice = value;
          if (action == "increment") {
            item.unitPrice = Number(item.unitPrice);
          } else if (action == "decrement") {
            if (item.unitPrice > 0) {
              item.unitPrice = Number(item.unitPrice);
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

      // calculate invoice tax
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

    // save invoice
    async saveInvoice(isDirect = true) {
      await this.form
        .post(window.location.origin + "/api/invoices")
        .then(({ data }) => {
          this.form.invoice_id = data.data.invoice_id;
          this.form.invoice_slug = data.data.invoice_slug;
          if (isDirect) {
            this.showInvoiceAndPrint();
          }
        })
        .catch(() => {
          toast.fire({ type: "error", title: this.$t("common.error_msg") });
        });
    },

    // save payment
    async addPayment() {
      if (this.form.invoice_id != null) {
        await this.form
          .post(window.location.origin + "/api/invoices-pay")
          .then(async () => {
            this.showModal = false;
            await this.showInvoiceAndPrint();
            this.form.reset();
            this.againDefaultSettings();
          })
          .catch(() => {
            toast.fire({ type: "error", title: this.$t("common.error_msg") });
          });
      } else {
        await toast.fire({ type: "error", title: this.$t("common.error_msg") });
      }
    },

    // close add payment modal and clear form data
    closeModalAndClearFormData() {
      this.showModal = false;
      this.generateOrder = false;
      this.form.reset();
      this.againDefaultSettings();
    },

    // close receipt modal
    closeReceiptModal() {
      this.showSmallInvoiceModal = false;
      this.form.reset();
      this.againDefaultSettings();
      this.clickCount = 0; // reset click count
      console.log("from close" + this.clickCount);
    },

    // complete order and add payment
    async completeOrderAndAddPayment() {
      await this.saveInvoice(false);
      if (this.form.invoice_id != null) {
        this.showModal = true;
        this.form.paidAmount = this.form.netTotal.toFixed(2);

        this.$nextTick(() => this.$refs.paidAmountInput.focus());
      }
    },

    // show invoice and print
    async showInvoiceAndPrint() {
      await this.getInvoice(this.form.invoice_slug);
      this.form.reset();
      this.againDefaultSettings();

      this.showSmallInvoiceModal = true;
      setTimeout(() => this.printInvoice(), 500);
    },

    // print invoice
    printInvoice() {
      var divContents = document.getElementById("invoice-POS").innerHTML;
      var a = window.open("", "", "height=500, width=500");
      a.document.write(
        '<link rel="stylesheet" href="/css/pos_print.css"><html>'
      );
      a.document.write("<body >");
      a.document.write(divContents);
      a.document.write("</body></html>");
      a.document.close();
      a.print();
    },
    // again default settings
    againDefaultSettings() {
      this.getAccounts();
      this.getClients();
      this.getTaxes();
      this.showModal = false;
      this.generateOrder = false;
    },
  },
};
</script>

<style lang="scss" scoped>
.pos-r-head {
  box-shadow: 0px 0px 3px #0003;
  padding: 20px;
  box-sizing: border-box;
  border-radius: 5px;
  border-bottom: 1px solid #f3f3f3;
}

.pos-logo {
  text-align: center;
}

.pos-item-grid {
  display: grid;
  grid-template-columns: 1fr 1fr 1fr 1fr 1fr 1fr;
  grid-gap: 10px;
}

.pos-item-grid>div {
  border: 0;
  border-radius: 10px;
  box-shadow: 0 4px 20px 1px rgb(0 0 0 / 6%), 0 1px 4px rgb(0 0 0 / 8%);
  overflow: hidden;
  cursor: pointer;
  border: 1px solid #fff;
  position: relative;
}

.pos-item-grid>div:hover {
  border-color: #6366f1;
}

.pos-item-grid>div .box-qty {
  position: absolute;
  width: 50px;
  height: 30px;
  display: block;
  background: #6366f1;
  top: 0;
  left: 0px;
  text-align: center;
  line-height: 30px;
  font-size: 12px;
  font-weight: bold;
  color: #fff;
  border-bottom-right-radius: 10px;
}

.qty-red {
  background: red !important;
}

.pos-body {
  border-radius: 5px;
  min-height: 240px;
}

.pos-box-img {
  width: 100%;
  height: 100px;
  border-bottom: 1px solid #f1f1f1;
  background: #ebebeb;
  line-height: 100px;
  text-align: center;
  font-size: 13px;
  font-weight: bold;
}

.pos-box-img img {
  width: 100%;
  height: 100px;
  object-fit: cover;
}

.pos-box-content p {
  font-size: 14px;
  font-weight: bold;
  margin-bottom: 0px;
}

.pos-box-content span {
  font-size: 12px;
  margin-bottom: 2px;
}

.pos-box-content {
  padding: 5px 10px;
}

.pos-item-grid-red {
  border-color: red !important;
}

.card-client-search {
  padding: 20px;
  border-bottom: 1px solid #ddd;
}

.table-wrap {
  padding: 15px;
}

.table-responsive.table-wrap>table {
  border: 1px solid #ddd;
}

.table-wrap .table thead tr {
  border-bottom: 0;
  background: #6366f11f !important;
}

.table-wrap .incrementor {
  width: 80px;
}

.table-wrap .custom-qty-input {
  display: inline-flex !important;
  justify-content: center;
  border: 1px solid #ececfdb8;
  padding: 0;
  border-radius: 18px;
  /* background: #ddd; */
}

.table-wrap .btn-danger {
  width: 25px;
  height: 25px;
  font-size: 10px;
  padding: 0px;
}

.table-wrap .icon-sm {
  width: 25px;
  height: 25px;
  line-height: 23px;
}

.pos-card-footer {
  border: 1px solid #ddd;
  background: #fff;
  border-radius: 4px;
  margin-bottom: 15px;
}

.pos-net-total {
  background: #6366f133;
  width: 100%;
  padding: 10px 10px;
  text-align: center;
  font-size: 22px;
  font-weight: bold;
}

.product {
  cursor: pointer;

  .info-box {
    &:hover {
      background: #e0e0e0;
    }
  }
}

.dark-mode .pos-body,
.dark-mode .pos-r-head {
  background: #111827 !important;
  border-color: #000;
}

.dark-mode .pos-item-grid>div {
  border-color: #6c757d !important;
}

.dark-mode .pos-box-content {
  padding: 5px 10px;
  color: #fff;
}

.dark-mode .pos-item-grid>div.pos-item-grid-red {
  border-color: red !important;
}

.dark-mode .card-client-search {
  border-color: #6c757d;
}

.dark-mode .table-striped tbody tr:nth-of-type(odd) {
  background-color: #1f2937;
}

.dark-mode .table-responsive.table-wrap>table {
  border: 1px solid #6c757d;
}

.dark-mode .table-wrap .incrementor {
  border: none !important;
}

.dark-mode .pos-card-footer.bg-white {
  background: #111827 !important;
  border-color: #6c757d;
}

.dark-mode .pos-card-footer label {
  color: #fff;
}

.dark-mode .pos-net-total {
  background: rgb(99 169 241);
  color: #fff;
}

#invoice-POS td,
#invoice-POS th,
#invoice-POS tr,
#invoice-POS table {
  border-collapse: collapse;
}

#invoice-POS tr {
  border-bottom: 2px dotted #05070b;
}

#invoice-POS table {
  width: 100%;
}

#invoice-POS tfoot tr th:first-child {
  text-align: left;
}

#invoice-POS .info {
  margin-bottom: 20px;
}

#invoice-POS .info>p {
  margin-top: 20px;
}

#legalcopy {
  margin-top: 5mm;
}

#legalcopy p {
  text-align: center;
}

#bar {
  text-align: center;
}

.total {
  font-weight: bold;
  font-size: 12px;
}

span.pqty {
  display: block;
  line-height: 15px;
  font-size: 12px;
  font-weight: 500;
  margin-bottom: 5px;
}

@media only screen and (max-width: 1250px) {
  .pos-item-grid {
    grid-template-columns: 1fr 1fr 1fr 1fr;
  }
}

@media only screen and (max-width: 991px) {
  .pos-item-grid {
    grid-template-columns: 1fr 1fr 1fr;
  }
}

@media only screen and (max-width: 767px) {
  .sm-col-reverse {
    flex-direction: column-reverse;
  }

  .pos-item-grid {
    grid-template-columns: 1fr 1fr 1fr 1fr;
  }
}

.create-btn {
  padding: 11px;
}

.create-btn-2 {
  padding: 10px;
}
</style>
