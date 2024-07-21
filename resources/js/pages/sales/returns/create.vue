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
              {{ $t('sales.returns.create.form_title') }}
            </h3>
            <router-link :to="{ name: 'invoiceReturns.index' }" class="btn btn-dark float-right">
              <i class="fas fa-long-arrow-alt-left" /> {{ $t('common.back') }}
            </router-link>
          </div>
          <!-- /.card-header -->
          <!-- form start -->
          <form role="form" @submit.prevent="saveInvoiceReturn" @keydown="form.onKeydown($event)">
            <div class="card-body">
              <div class="row" v-if="items">
                <div class="form-group col-md-6">
                  <label for="returnReason">{{ $t('common.return_reason') }}
                    <span class="required">*</span></label>
                  <input id="returnReason" v-model="form.returnReason" type="text" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('returnReason') }" name="returnReason"
                    :placeholder="$t('common.return_reason_placeholder')" />
                  <has-error :form="form" field="returnReason" />
                </div>
                <div class="form-group col-md-6">
                  <label for="client">{{ $t('common.client') }}
                    <span class="required">*</span></label>
                  <v-select v-model="form.client" :options="items" label="name"
                    :class="{ 'is-invalid': form.errors.has('client') }" name="client"
                    :placeholder="$t('common.client_placeholder')" @input="assignInvoices" />
                  <has-error :form="form" field="client" />
                </div>
              </div>
              <div v-if="products" class="row">
                <div class="form-group col-md-12">
                  <label for="product">{{
                    $t('common.select_products')
                  }}</label>
                  <v-select :disabled="form.client == ''" multiple v-model="form.product" :options="products"
                    label="label" :class="{ 'is-invalid': form.errors.has('product') }" name="product"
                    :placeholder="$t('common.select_products_placeholder')" @input="assignInvoices" />
                  <has-error :form="form" field="product" />
                </div>
              </div>
              <div class="row" v-if="form.client && clientInvoices">
                <div class="form-group col-md-12">
                  <label for="invoice">{{ $t('common.invoices') }}
                    <span class="required">*</span></label>
                  <v-select v-model="form.invoice" :options="clientInvoices" label="label"
                    :class="{ 'is-invalid': form.errors.has('invoice') }" name="invoice"
                    :placeholder="$t('common.invoices_placeholder')" @input="storeProducts" />
                  <has-error :form="form" field="invoice" />
                </div>
              </div>
              <div v-if="form.selectedProducts && form.selectedProducts.length > 0" class="row mt-3 mb-4">
                <div v-if="form.errors.errors && form.errors.errors.selectedProducts
                  " class="w-95 m-auto">
                  <div v-for="(msg, i) in form.errors.errors.selectedProducts" :key="i" class="callout callout-danger">
                    <p><i class="icon fas fa-ban"></i> {{ msg }}</p>
                  </div>
                </div>
                <div class="table-responsive table-custom w-95 m-auto">
                  <table class="table table-hover table-sm text-center">
                    <thead>
                      <tr>
                        <th>{{ $t('common.s_no') }}</th>
                        <th>{{ $t('common.code') }}</th>
                        <th>{{ $t('common.product_name') }}</th>
                        <th>{{ $t('common.invoice_qty') }}</th>
                        <th>{{ $t('common.current_qty') }}</th>
                        <th>{{ $t('common.return_qty') }}</th>
                        <th>{{ $t('common.unit_price') }}</th>
                        <th>{{ $t('common.total_price') }}</th>
                        <th class="text-right">
                          {{ $t('common.return_price') }}
                        </th>
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
                        <td>{{ item.qty }} {{ item.unit }}</td>
                        <td>{{ item.totalReturnQty }} {{ item.unit }}</td>
                        <td>
                          <div class="input-group custom-qty-input">
                            <input type="button" value="-" class="button-minus icon-shape icon-sm btn-danger"
                              data-field="quantity" @click="updateItem(item.returnQty - 1, i - 1)" />
                            <input type="number" step="any" :id="`returnQty-${i}`" :value="item.returnQty" name="quantity"
                              class="quantity-field border-0 incrementor" min="0" :max="item.maxQty"
                              @change="updateItem($event.target.value, i - 1)"
                              @keyup="updateItem($event.target.value, i - 1)" placeholder="Return Qty" />
                            <input type="button" value="+" class="button-plus icon-shape icon-sm btn-primary"
                              data-field="quantity" @click="
                                updateItem(Number(item.returnQty) + 1, i - 1)
                                " />
                          </div>
                        </td>
                        <td>{{ item.unitCost | withCurrency }}</td>
                        <td>{{ item.totalPrice | withCurrency }}</td>
                        <td class="text-right">
                          {{ item.returnTotal | withCurrency }}
                        </td>
                      </tr>
                      <tr v-if="form.invoice">
                        <td colspan="7" class="text-right">
                          <strong>{{ $t('common.subtotal') }}</strong>
                        </td>
                        <td class="text-center">
                          <strong>{{
                            form.invoice.subTotal | withCurrency
                          }}</strong>
                        </td>
                        <td class="text-right">
                          <strong>{{ form.totalReturn | withCurrency }}</strong>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div v-if="form.invoice" class="row">
                <div v-if="form.discountPercentage > 0" class="form-group col-md-2">
                  <label for="discountType">{{
                    $t('common.discount_type')
                  }}</label>
                  <select id="discountType" v-model="form.discountType" step="any" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('discountType') }" name="discountType" disabled>
                    <option value="0">{{ $t('common.fixed') }}</option>
                    <option value="1">{{ $t('common.percentage') }}(%)</option>
                  </select>
                  <has-error :form="form" field="discountType" />
                </div>
                <div class="form-group" :class="form.discountPercentage > 0 ? 'col-md-2' : 'col-md-4'">
                  <label for="invoiceDiscount">{{
                    $t('common.total_discount')
                  }}</label>
                  <input id="invoiceDiscount" v-model="form.invoiceDiscount" type="number" step="any" class="form-control"
                    name="invoiceDiscount" readonly />
                </div>
                <div class="form-group col-md-4">
                  <label for="invoiceTransport">{{
                    $t('common.transport_cost')
                  }}</label>
                  <input id="invoiceTransport" v-model="form.invoiceTransport" type="number" step="any"
                    class="form-control" name="invoiceTransport" readonly />
                </div>
                <div class="form-group col-md-4">
                  <label for="invoiceTax">{{
                    $t('sales.common.invoice_tax')
                  }}</label>
                  <input id="invoiceTax" v-model="form.newTax" type="number" step="any" class="form-control"
                    name="invoiceTax" readonly />
                </div>
              </div>
              <div class="row">
                <div class="form-group col-md-4">
                  <label for="invoiceTotal">{{
                    $t('sales.common.invoice_total')
                  }}</label>
                  <input id="invoiceTotal" v-model="form.invoiceTotal" type="number" step="any" class="form-control"
                    name="invoiceTotal" readonly />
                </div>
                <div class="form-group col-md-4">
                  <label for="totalPaid">{{ $t('common.total_paid') }}</label>
                  <input id="totalPaid" v-model="form.invoice.totalPaid" type="number" step="any" class="form-control"
                    name="totalPaid" readonly />
                </div>
                <div v-if="form.returnAmount > 0" class="form-group col-md-4">
                  <label for="returnAmountText">{{
                    $t('common.return_amount')
                  }}</label>
                  <input id="returnAmountText" v-model="form.returnAmountText" type="text" class="form-control" :class="{
                    'is-invalid': form.errors.has('returnAmountText'),
                  }" name="returnAmountText" readonly />
                  <has-error :form="form" field="returnAmountText" />
                </div>
                <div v-else class="form-group col-md-4">
                  <label for="newDueText">{{
                    $t('sales.common.new_due')
                  }}</label>
                  <input id="newDueText" v-model="form.newDueText" type="text" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('newDueText') }" name="newDueText" readonly />
                  <has-error :form="form" field="newDueText" />
                </div>
              </div>
              <div v-if="accounts && form.returnAmount > 0" class="row">
                <div class="form-group col-md-4">
                  <label for="account">{{ $t('common.account') }}
                    <span class="required">*</span></label>
                  <v-select v-model="form.account" :options="accounts" label="label"
                    :class="{ 'is-invalid': form.errors.has('account') }" name="account"
                    :placeholder="$t('common.account_placeholder')" @input="updateBalance" />
                  <has-error :form="form" field="account" />
                </div>
                <div class="form-group col-md-2">
                  <label for="availableBalance">{{
                    $t('common.available_balance')
                  }}</label>
                  <input id="availableBalance" v-model="form.availableBalance" type="number" step="any"
                    class="form-control" :class="{
                      'is-invalid': form.errors.has('availableBalance'),
                    }" name="availableBalance" readonly />
                  <has-error :form="form" field="availableBalance" />
                </div>
                <div class="form-group col-md-3">
                  <label for="chequeNo">{{ $t('common.cheque_no') }}</label>
                  <input id="chequeNo" v-model="form.chequeNo" type="text" step="any" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('chequeNo') }" name="chequeNo"
                    :placeholder="$t('common.cheque_placeholder')" />
                  <has-error :form="form" field="chequeNo" />
                </div>
                <div class="form-group col-md-3">
                  <label for="receiptNo">{{ $t('common.receipt_no') }}</label>
                  <input id="receiptNo" v-model="form.receiptNo" type="text" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('receiptNo') }" name="receiptNo"
                    :placeholder="$t('common.receipt_no_placeholder')" />
                  <has-error :form="form" field="receiptNo" />
                </div>
              </div>
              <div class="form-group">
                <label for="note">{{ $t('common.note') }}</label>
                <textarea id="note" v-model="form.note" class="form-control"
                  :class="{ 'is-invalid': form.errors.has('note') }" :placeholder="$t('common.note_placeholder')" />
                <has-error :form="form" field="note" />
              </div>
              <div class="row">
                <div class="form-group col-md-6">
                  <label for="date">{{ $t('sales.common.return_date') }}</label>
                  <input id="date" v-model="form.date" type="date" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('date') }" name="date" />
                  <has-error :form="form" field="date" />
                </div>
                <div class="form-group col-md-6">
                  <label for="status">{{ $t('common.status') }}</label>
                  <select id="status" v-model="form.status" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('status') }">
                    <option value="1">{{ $t('common.active') }}</option>
                    <option value="0">{{ $t('common.in_active') }}</option>
                  </select>
                  <has-error :form="form" field="status" />
                </div>
              </div>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
              <v-button :loading="form.busy" class="btn btn-primary">
                <i class="fas fa-save" /> {{ $t('common.save') }}
              </v-button>
              <button type="reset" class="btn btn-secondary float-right" @click="form.reset()">
                <i class="fas fa-power-off" /> {{ $t('common.reset') }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import Form from 'vform'
import axios from 'axios'
import { mapGetters } from 'vuex'

export default {
  middleware: ['auth', 'check-permissions'],
  metaInfo() {
    return { title: this.$t('sales.returns.create.page_title') }
  },
  data: () => ({
    breadcrumbsCurrent: 'sales.returns.create.breadcrumbs_current',
    breadcrumbs: [
      {
        name: 'sales.returns.create.breadcrumbs_first',
        url: 'home',
      },
      {
        name: 'sales.returns.create.breadcrumbs_second',
        url: 'invoiceReturns.index',
      },
      {
        name: 'sales.returns.create.breadcrumbs_active',
        url: '',
      },
    ],
    form: new Form({
      returnReason: '',
      account: '',
      availableBalance: 0,
      chequeNo: '',
      receiptNo: '',
      client: '',
      invoice: '',
      product: '',
      selectedProducts: [],
      totalReturn: 0,
      date: new Date().toISOString().slice(0, 10),
      note: '',
      status: 1,
      invoiceTax: 0,
      newTax: 0,
      invoiceDiscount: 0,
      discountType: 0,
      discountPercentage: 0,
      invoiceTransport: 0,
      invoiceTaxRate: 0,
      invoiceTotal: 0,
      invoiceDue: 0,
      totalPaid: 0,
      newDue: 0,
      newDueText: '',
      returnAmount: 0,
      returnAmountText: 0,
      newSubTotal: 0,
    }),
    products: '',
    accounts: '',
    clientInvoices: '',
    prefix: '',
  }),
  computed: {
    ...mapGetters('operations', ['items', 'appInfo']),
  },
  created() {
    this.getClients()
    this.getProducts()
    this.getAccounts()
    this.prefix = this.appInfo.productPrefix
  },
  methods: {
    // get all clients
    async getClients() {
      await this.$store.dispatch('operations/allData', {
        path: '/api/all-clients',
      })
    },

    // get products
    async getProducts() {
      const { data } = await axios.get(
        window.location.origin + '/api/all-products'
      )
      this.products = data.data
    },

    // get accounts
    async getAccounts() {
      const { data } = await axios.get(
        window.location.origin + '/api/all-accounts'
      )
      this.accounts = data.data
    },

    // update available balance
    updateBalance() {
      this.form.availableBalance = 0
      if (this.form.account) {
        this.form.availableBalance = this.form.account.availableBalance
      }
      return
    },

    // assign invoices
    async assignInvoices() {
      this.form.selectedProducts = []
      this.form.invoice = ''
      if (this.form.client) {
        axios
          .post(window.location.origin + '/api/client/filter-invoices', {
            products: this.form.product,
            clientSlug: this.form.client.slug,
          })
          .then((response) => {
            this.clientInvoices = response.data.data
          })
      } else {
        this.form.product = ''
        this.form.client = ''
      }
    },

    // store item in array
    storeProducts() {
      this.form.selectedProducts = []
      this.form.invoiceTax = this.form.invoice.tax
      this.form.newTax = this.form.invoice.tax
      this.form.invoiceTaxRate = this.form.invoice.taxRate
      this.form.invoiceTotal = this.form.invoice.invoiceTotal
      this.form.invoiceTransport = this.form.invoice.transport
      this.form.invoiceDiscount = this.form.invoice.discount
      this.form.discountType = this.form.invoice.discountType
      this.form.discountPercentage = this.form.invoice.discountPercentage
      this.form.invoiceDue = this.form.invoice.due
      this.form.newDue = this.form.invoice.due
      this.form.newDueText = this.form.invoice.due
      for (var key in this.form.invoice.invoiceProducts) {
        let invoiceItem = this.form.invoice.invoiceProducts[key]
        this.form.selectedProducts.unshift({
          id: invoiceItem.productID,
          slug: invoiceItem.productSlug,
          name: invoiceItem.productName,
          code: invoiceItem.productCode,
          unit: invoiceItem.productUnit,
          taxType: invoiceItem.taxType,
          taxRate: invoiceItem.taxRate,
          oldQty: invoiceItem.quantity,
          qty: invoiceItem.quantity,
          returnQty: invoiceItem.returnQty,
          totalReturnQty: invoiceItem.quantity - invoiceItem.returnQty,
          inventoryCount: invoiceItem.inventoryCount,
          avgPurchasePrice: invoiceItem.purchasePrice,
          unitPrice: invoiceItem.salePrice,
          unitCost: invoiceItem.unitCost,
          totalPrice: invoiceItem.unitCostTotal,
          returnTotal: 0,
          productTax: invoiceItem.unitTax,
          totalTax: invoiceItem.taxTotal,
          maxQty: invoiceItem.quantity - 1,
        })
      }
      return
    },

    // update items
    updateItem(value, index) {
      let selectedProduct = this.form.selectedProducts[index]
      if (selectedProduct && value >= 0 && value < selectedProduct.qty) {
        selectedProduct.returnQty = Number(value)
        selectedProduct.returnTotal =
          selectedProduct.returnQty * selectedProduct.unitCost
        this.form.selectedProducts[index] = selectedProduct
      }
      this.calculateSum()
    },

    // calculate sum
    calculateSum() {
      // calculate total
      let length = this.form.selectedProducts.length
      this.form.newSubTotal = this.form.totalReturn = 0
      for (let i = 0; i < length; i++) {
        let looProduct = this.form.selectedProducts[i]
        this.form.newSubTotal += Number(
          (
            (looProduct.qty - looProduct.returnQty) *
            looProduct.unitCost
          ).toFixed(2)
        )
        this.form.totalReturn += Number(looProduct.returnTotal.toFixed(2))
      }

      // update discount
      if (this.form.discountType == 1) {
        this.form.invoiceDiscount = Number(
          (this.form.discountPercentage / 100) * this.form.newSubTotal
        ).toFixed(2)
      }

      // update tax, total and due
      this.form.newTax = Number(
        ((this.form.invoiceTaxRate.rate / 100) * this.form.newSubTotal).toFixed(
          2
        )
      )
      this.form.invoiceTotal = Number(
        (
          this.form.newSubTotal +
          this.form.newTax +
          this.form.invoiceTransport -
          this.form.invoiceDiscount
        ).toFixed(2)
      )
      this.form.invoiceDue = Number(
        (this.form.invoiceTotal - this.form.invoice.totalPaid).toFixed(2)
      )

      // calculate new due or payable
      if (this.form.invoiceDue >= 0) {
        this.form.newDue = this.form.invoiceTotal - this.form.invoice.totalPaid
        this.form.newDueText =
          this.form.invoiceTotal +
          ' - ' +
          this.form.invoice.totalPaid +
          ' = ' +
          Number(this.form.newDue).toFixed(2)
        this.form.returnAmount = 0
      } else {
        this.form.returnAmount = Number(
          (this.form.invoice.totalPaid - this.form.invoiceTotal).toFixed(2)
        )
        this.form.returnAmountText =
          this.form.invoice.totalPaid +
          ' - ' +
          this.form.invoiceTotal +
          ' = ' +
          this.form.returnAmount
        this.form.invoiceDue = 0
        this.form.newDue = 0
      }
      return
    },

    // save return
    async saveInvoiceReturn() {
      await this.form
        .post(window.location.origin + '/api/invoice-returns')
        .then(({ data }) => {
          toast.fire({
            type: 'success',
            title: this.$t('sales.returns.create.success_msg'),
          })
          this.$router.push({ name: 'invoiceReturns.show', params: { slug: data.data.slug }, })
        })
        .catch(() => {
          toast.fire({ type: 'error', title: this.$t('common.error_msg') })
        })
    },
  },
}
</script>

