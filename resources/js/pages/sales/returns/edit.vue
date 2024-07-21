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
              {{ $t('sales.returns.edit.form_title') }}
            </h3>
            <router-link :to="{ name: 'invoiceReturns.index' }" class="btn btn-dark float-right">
              <i class="fas fa-long-arrow-alt-left" /> {{ $t('common.back') }}
            </router-link>
          </div>
          <!-- /.card-header -->
          <!-- form start -->
          <form role="form" @submit.prevent="updateInvoiceReturn" @keydown="form.onKeydown($event)">
            <div class="card-body">
              <div class="row">
                <div class="form-group col-md-6">
                  <label for="invoiceNo">{{ $t('common.invoice_no') }}</label>
                  <input id="invoiceNo" v-model="form.invoiceNo" type="text" class="form-control" name="invoiceNo"
                    readonly />
                </div>
                <div class="form-group col-md-6">
                  <label for="invoiceReturnNo">{{
                    $t('common.invoice_return_no')
                  }}</label>
                  <input id="invoiceReturnNo" v-model="form.invoiceReturnNo" type="text" class="form-control"
                    name="invoiceReturnNo" readonly />
                </div>
              </div>
              <div class="row">
                <div class="form-group col-md-6">
                  <label for="returnReason">{{ $t('common.return_reason') }}
                    <span class="required">*</span></label>
                  <input id="returnReason" v-model="form.returnReason" type="text" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('returnReason') }" name="returnReason"
                    :placeholder="$t('common.return_reason_placeholder')" />
                  <has-error :form="form" field="returnReason" />
                </div>
                <div class="form-group col-md-6">
                  <label for="clientName">{{ $t('common.client') }}
                    <span class="required">*</span></label>
                  <input v-model="form.clientName" type="text" class="form-control" name="clientName" readonly />
                </div>
              </div>
              <div v-if="form.selectedProducts && form.selectedProducts.length > 0" class="row mt-3 mb-2">
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
                        <td>{{ item.invoiceQty }} {{ item.unit }}</td>
                        <td>
                          {{ item.invoiceQty - item.oldQty }} {{ item.unit }}
                        </td>
                        <td>
                          <div class="input-group custom-qty-input">
                            <input type="button" value="-" class="button-minus icon-shape icon-sm btn-danger"
                              data-field="quantity" @click="updateItem(item.returnQty - 1, i - 1)" />
                            <input type="number" step="any" :id="`returnQty-${i}`" min="0" :value="item.returnQty"
                              :max="item.maxQty" name="quantity" class="quantity-field border-0 incrementor"
                              @change="updateItem($event.target.value, i - 1)"
                              @keyup="updateItem($event.target.value, i - 1)" placeholder="Return Qty" />
                            <input type="button" value="+" class="button-plus icon-shape icon-sm btn-primary"
                              data-field="quantity" @click="updateItem(item.returnQty + 1, i - 1)" />
                          </div>
                        </td>
                        <td>{{ item.sellingPrice | withCurrency }}</td>
                        <td>{{ item.totalPrice | withCurrency }}</td>
                        <td class="text-right">
                          {{ item.returnTotal | withCurrency }}
                        </td>
                      </tr>
                      <tr v-if="form.invoice">
                        <td colspan="7" class="text-right">
                          <strong>{{ $t('common.subtotal') }}</strong>
                        </td>
                        <td>
                          <strong>{{
                            form.returnSubtotal | withCurrency
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
                  <input id="invoiceTax" v-model="form.invoiceTax" type="number" step="any" class="form-control"
                    name="invoiceTax" readonly />
                </div>
              </div>
              <div class="row" v-if="form.invoice">
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
              <div v-if="accounts && form.returnAmount > 0 && form.account" class="row">
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
                <i class="fas fa-edit" /> {{ $t('common.save_changes') }}
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
import axios from 'axios'
import Form from 'vform'
import { mapGetters } from 'vuex'

export default {
  middleware: ['auth', 'check-permissions'],
  metaInfo() {
    return { title: this.$t('sales.returns.edit.page_title') }
  },
  data: () => ({
    breadcrumbsCurrent: 'sales.returns.edit.breadcrumbs_current',
    breadcrumbs: [
      {
        name: 'sales.returns.edit.breadcrumbs_first',
        url: 'home',
      },
      {
        name: 'sales.returns.edit.breadcrumbs_second',
        url: 'invoiceReturns.index',
      },
      {
        name: 'sales.returns.edit.breadcrumbs_active',
        url: '',
      },
    ],
    form: new Form({
      invoiceNo: '',
      invoiceReturnNo: '',
      returnReason: '',
      account: '',
      availableBalance: 0,
      chequeNo: '',
      receiptNo: '',
      client: '',
      clientName: '',
      invoice: '',
      invoiceTotal: 0,
      product: '',
      selectedProducts: [],
      totalReturn: 0,
      invoiceTax: 0,
      invoiceTaxRate: 0,
      invoiceTransport: 0,
      invoiceDiscount: 0,
      discountPercentage: 0,
      discountType: '',
      invoiceDue: 0,
      newSubTotal: 0,
      returnSubtotal: 0,
      invoicePaid: 0,
      newDue: 0,
      newDueText: '',
      returnAmount: 0,
      returnAmountText: 0,
      date: new Date().toISOString().slice(0, 10),
      note: '',
      status: 1,
    }),
    products: '',
    accounts: '',
    clientInvoices: '',
    prefix: '',
    purchasePrefix: '',
    purchaseReturnPrefix: '',
  }),
  computed: {
    ...mapGetters('operations', ['items', 'appInfo']),
  },
  created() {
    this.getInvoiceReturn()
    this.getAccounts()
    this.prefix = this.appInfo.productPrefix
    this.purchasePrefix = this.appInfo.purchasePrefix
    this.purchaseReturnPrefix = this.appInfo.purchaseReturnPrefix
  },
  methods: {
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
    // get the invoice return
    async getInvoiceReturn() {
      const { data } = await axios.get(
        window.location.origin +
        '/api/invoice-returns/' +
        this.$route.params.slug
      )
      this.form.invoiceNo = data.data.invoice.invoiceLabel
      this.form.invoiceReturnNo = this.$options.filters.withPrefix(
        data.data.returnNo,
        this.purchaseReturnPrefix
      )
      this.form.returnReason = data.data.reason
      this.form.availableBalance = data.data.account ? data.data.account.availableBalance : null
      this.form.account = data.data.account
      this.form.chequeNo = data.data.accountPayable
        ? data.data.accountPayable.cheque_no
        : ''
      this.form.receiptNo = data.data.accountPayable
        ? data.data.accountPayable.receipt_no
        : ''
      this.form.clientName = data.data.client.name
      this.form.date = data.data.returnDate
      this.form.note = data.data.note
      this.form.status = data.data.status
      this.form.client = data.data.client
      this.form.invoice = data.data.invoice
      this.form.invoiceTaxRate = data.data.invoice.taxRate
      this.form.invoiceTax = data.data.invoice.tax
      this.form.clientAdvance = data.data.creditAmount
      this.form.clientAdvanceText = data.data.creditAmount
      this.form.invoiceDue = data.data.invoice.due
      this.form.newDueText = data.data.invoice.due
      this.form.invoiceTransport = data.data.invoice.transport
      this.form.discountType = data.data.invoice.discountType
      this.form.discountPercentage = data.data.invoice.discountPercentage
      this.form.invoiceDiscount = data.data.invoice.discount
      this.form.invoicePaid = data.data.invoice.invoicePaid
      this.form.invoiceTotal = data.data.invoice.invoiceTotal
      this.form.selectedProducts = this.assignProducts(
        data.data.invoiceReturnProducts
      )
    },
    // assign products
    assignProducts(products) {
      this.form.selectedProducts = []
      for (var key in products) {
        let invoiceReturnItem = products[key]
        this.form.selectedProducts.unshift({
          id: invoiceReturnItem.productID,
          slug: invoiceReturnItem.productSlug,
          name: invoiceReturnItem.productName,
          code: invoiceReturnItem.productCode,
          unit: invoiceReturnItem.productUnit,
          oldQty: invoiceReturnItem.returnQty,
          invoiceQty: invoiceReturnItem.invoiceQty,
          maxQty: invoiceReturnItem.invoiceQty - 1,
          returnQty: invoiceReturnItem.returnQty,
          returnTotal:
            invoiceReturnItem.returnQty * invoiceReturnItem.salePrice,
          sellingPrice: invoiceReturnItem.salePrice,
          unitCost: invoiceReturnItem.salePrice,
          purchasePrice: invoiceReturnItem.avgPurchasePrice,
          totalPrice:
            invoiceReturnItem.invoiceQty * invoiceReturnItem.salePrice,
        })
      }
      this.calculateSum()
      return this.form.selectedProducts
    },
    // updateItems
    updateItem(value, index) {
      let selectedProduct = this.form.selectedProducts[index]
      if (selectedProduct && value >= 0 && value < selectedProduct.maxQty) {
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
      this.form.newSubTotal =
        this.form.returnSubtotal =
        this.form.totalReturn =
        0
      for (let i = 0; i < length; i++) {
        let looProduct = this.form.selectedProducts[i]
        this.form.newSubTotal += Number(
          (
            (looProduct.qty - looProduct.returnQty) *
            looProduct.unitCost
          ).toFixed(2)
        )
        this.form.totalReturn += Number(looProduct.returnTotal.toFixed(2))
        this.form.returnSubtotal += Number(
          (looProduct.returnQty * looProduct.unitCost).toFixed(2)
        )
      }
      // update discount
      if (this.form.discountType == 1) {
        this.form.invoiceDiscount = Number(
          (
            (this.form.discountPercentage / 100) *
            this.form.newSubTotal
          ).toFixed(2)
        )
      }
      // update tax, total and due
      this.form.invoiceTax = Number(
        (
          (this.form.invoiceTaxRate / 100) *
          (this.form.invoice.subTotal - this.form.totalReturn)
        ).toFixed(2)
      )
      this.form.invoiceTotal = Number(
        (
          this.form.invoice.subTotal -
          this.form.returnSubtotal +
          this.form.invoiceTax +
          this.form.invoiceTransport -
          this.form.invoiceDiscount
        ).toFixed(2)
      )
      this.form.invoiceDue = Number(
        (this.form.invoiceTotal - this.form.invoice.totalPaid).toFixed(2)
      )
      // calculate new due or payable
      if (this.form.invoiceDue >= 0) {
        this.form.newDue = this.form.invoiceDue
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
        this.form.invoiceDue = this.form.newDue = 0
      }
      return
    },
    // update invoice return
    async updateInvoiceReturn() {
      await this.form
        .patch(
          window.location.origin +
          '/api/invoice-returns/' +
          this.$route.params.slug
        )
        .then(() => {
          toast.fire({
            type: 'success',
            title: this.$t('sales.returns.edit.success_msg'),
          })
          this.$router.push({ name: 'invoiceReturns.index' })
        })
        .catch(() => {
          toast.fire({
            type: 'error',
            title: this.$t('common.error_msg'),
          })
        })
    },
  },
}
</script>

<style lang="scss" scoped></style>
