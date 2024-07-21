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
              {{ $t('purchases.returns.create.form_title') }}
            </h3>
            <router-link :to="{ name: 'purchaseReturns.index' }" class="btn btn-dark float-right">
              <i class="fas fa-long-arrow-alt-left" /> {{ $t('common.back') }}
            </router-link>
          </div>
          <!-- /.card-header -->
          <!-- form start -->
          <form role="form" @submit.prevent="savePurchaseReturn" @keydown="form.onKeydown($event)">
            <div class="card-body">
              <div class="row" v-if="items">
                <div class="form-group col-md-6">
                  <label for="returnReason">{{ $t('purchases.returns.common.return_reason') }}
                    <span class="required">*</span></label>
                  <input id="returnReason" v-model="form.returnReason" type="text" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('returnReason') }" name="returnReason" :placeholder="$t('purchases.returns.common.return_reason_placeholder')
                      " />
                  <has-error :form="form" field="returnReason" />
                </div>
                <div class="form-group col-md-6">
                  <label for="supplier">{{ $t('common.supplier') }}
                    <span class="required">*</span></label>
                  <v-select v-model="form.supplier" :options="items" label="name"
                    :class="{ 'is-invalid': form.errors.has('supplier') }" name="supplier"
                    :placeholder="$t('common.supplier_placeholder')" @input="assignPurchases" />
                  <has-error :form="form" field="supplier" />
                </div>
              </div>
              <div v-if="products && !form.purchase" class="row">
                <div class="form-group col-md-12">
                  <label for="product">{{
                    $t('common.select_products')
                  }}</label>
                  <v-select :disabled="form.client == ''" multiple v-model="form.product" :options="products"
                    label="label" :class="{ 'is-invalid': form.errors.has('product') }" name="product"
                    :placeholder="$t('common.select_products_placeholder')" @input="assignPurchases" />
                  <has-error :form="form" field="product" />
                </div>
              </div>
              <div class="row" v-if="form.supplier && supplierPurchases">
                <div class="form-group col-md-12">
                  <label for="purchase">{{ $t('common.purchases') }}
                    <span class="required">*</span></label>
                  <v-select v-model="form.purchase" :options="supplierPurchases" label="purchaseNo"
                    :class="{ 'is-invalid': form.errors.has('purchase') }" name="purchase"
                    :placeholder="$t('common.purchases_placeholder')" @input="storeProducts" />
                  <has-error :form="form" field="purchase" />
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
                        <th>{{ $t('common.name') }}</th>
                        <th>{{ $t('purchases.list.common.purchased_qty') }}</th>
                        <th>{{ $t('purchases.list.common.current_qty') }}</th>
                        <th>{{ $t('purchases.list.common.returned_qty') }}</th>
                        <th>{{ $t('common.unit_cost') }}</th>
                        <th>{{ $t('common.total_price') }}</th>
                        <th>{{ $t('common.return_price') }}</th>
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
                        <td>{{ item.purchaseQty }} {{ item.unit }}</td>
                        <td>{{ item.totalReturnQty }} {{ item.unit }}</td>
                        <td>
                          <div class="input-group custom-qty-input">
                            <input type="button" value="-" class="button-minus icon-shape icon-sm btn-danger"
                              data-field="quantity" @click="
                                updateItem(Number(item.returnQty) - 1, i - 1)
                                " />
                            <input type="number" step="any" :id="`returnQty-${i}`" placeholder="Return Qty"
                              class="quantity-field border-0 incrementor" @change="updateItem($event.target.value, i - 1)"
                              @keyup="updateItem($event.target.value, i - 1)" :value="item.returnQty" min="0"
                              :max="item.purchaseQty" />
                            <input type="button" value="+" class="button-plus icon-shape icon-sm btn-primary"
                              data-field="quantity" @click="
                                updateItem(
                                  item.returnQty > 0
                                    ? Number(item.returnQty) + 1
                                    : 1,
                                  i - 1
                                )
                                " />
                          </div>
                        </td>
                        <td>{{ item.purchasePrice | withCurrency }}</td>
                        <td>{{ item.totalPrice | withCurrency }}</td>
                        <td>{{ item.returnTotal | withCurrency }}</td>
                      </tr>
                      <tr v-if="form.purchase">
                        <td colspan="7" class="text-right">
                          <strong>{{ $t('common.subtotal') }}:</strong>
                        </td>
                        <td>
                          <strong>{{
                            form.purchase.subTotal | withCurrency
                          }}</strong>
                        </td>
                        <td>
                          <strong>{{ form.totalReturn | withCurrency }}</strong>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div v-if="form.purchase" class="row">
                <div class="form-group col-md-4">
                  <label for="discount">{{
                    $t('purchases.list.common.discount')
                  }}</label>
                  <input id="discount" v-model="form.purchase.totalDiscount" type="number" step="any" class="form-control"
                    name="discount" readonly />
                </div>
                <div class="form-group col-md-4">
                  <label for="transportCost">{{
                    $t('purchases.list.common.transport_cost')
                  }}</label>
                  <input id="transportCost" v-model="form.purchase.transport" type="number" step="any"
                    class="form-control" name="transportCost" readonly />
                </div>
                <div class="form-group col-md-4">
                  <label for="purchaseTax">{{
                    $t('purchases.list.common.purchase_tax')
                  }}</label>
                  <input id="purchaseTax" v-model="form.newTax" type="number" step="any" class="form-control"
                    name="purchaseTax" readonly />
                </div>
                <div class="form-group col-md-4">
                  <label for="purchaseTotal">{{
                    $t('purchases.list.common.purchase_total')
                  }}</label>
                  <input id="purchaseTotal" v-model="form.purchaseTotal" type="number" step="any" class="form-control"
                    name="purchaseTotal" readonly />
                </div>
                <div class="form-group col-md-4">
                  <label for="totalPaid">{{ $t('common.total_paid') }}</label>
                  <input id="totalPaid" v-model="form.purchase.totalPaid" type="number" step="any" class="form-control"
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
                    $t('purchases.list.common.purchase_due')
                  }}</label>
                  <input id="newDueText" v-model="form.newDueText" type="text" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('newDueText') }" name="newDueText" readonly />
                  <has-error :form="form" field="newDueText" />
                </div>
              </div>

              <div v-if="accounts && form.returnAmount > 0" class="row">
                <div class="form-group col-md-6">
                  <label for="account">{{ $t('common.account') }}
                    <span class="required">*</span></label>
                  <v-select v-model="form.account" :options="accounts" label="label"
                    :class="{ 'is-invalid': form.errors.has('account') }" name="account"
                    :placeholder="$t('common.account_placeholder')" />
                  <has-error :form="form" field="account" />
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
                  <label for="date">{{
                    $t('purchases.returns.common.return_date')
                  }}</label>
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
    return { title: this.$t('purchases.returns.create.page_title') }
  },
  data: () => ({
    breadcrumbsCurrent: 'purchases.returns.create.breadcrumbs_current',
    breadcrumbs: [
      {
        name: 'purchases.returns.create.breadcrumbs_first',
        url: 'home',
      },
      {
        name: 'purchases.returns.create.breadcrumbs_second',
        url: 'purchaseReturns.index',
      },
      {
        name: 'purchases.returns.create.breadcrumbs_active',
        url: '',
      },
    ],
    form: new Form({
      returnReason: '',
      account: '',
      chequeNo: '',
      receiptNo: '',
      supplier: '',
      purchase: '',
      product: '',
      selectedProducts: [],
      totalReturn: 0,
      date: new Date().toISOString().slice(0, 10),
      note: '',
      status: 1,
      purchaseTotal: 0,
      newSubTotal: 0,
      purchaseTax: 0,
      purchaseTransport: 0,
      purchaseDiscount: 0,
      newTax: 0,
      taxRate: 0,
      purchaseDue: 0,
      newDue: 0,
      newDueText: '',
      returnAmount: 0,
      returnAmountText: 0,
    }),
    products: '',
    accounts: '',
    supplierPurchases: '',
  }),
  computed: {
    ...mapGetters('operations', ['items', 'appInfo']),
  },
  created() {
    this.getSuppliers()
    this.getProducts()
    this.getAccounts()
    this.prefix = this.appInfo.productPrefix
  },
  methods: {
    // get all suppliers
    async getSuppliers() {
      await this.$store.dispatch('operations/allData', {
        path: '/api/all-suppliers',
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

    // assign purchases
    async assignPurchases() {
      this.form.selectedProducts = []
      this.form.purchase = ''
      if (this.form.supplier) {
        axios
          .post(window.location.origin + '/api/supplier/filter-purchases', {
            products: this.form.product,
            supplierSlug: this.form.supplier.slug,
          })
          .then((response) => {
            this.supplierPurchases = response.data.data
          })
      } else {
        this.form.product = ''
        this.form.supplier = ''
      }
    },

    // store item in array
    storeProducts() {
      this.form.selectedProducts = []
      this.form.purchaseTotal = this.form.purchase.purchaseTotal
      this.form.purchaseDue = this.form.purchase.due
      this.form.purchaseTax = this.form.purchase.tax
      this.form.newTax = this.form.purchase.tax
      this.form.taxRate = this.form.purchase.taxRate
      this.form.purchaseTransport = this.form.purchase.transport
      this.form.purchaseDiscount = this.form.purchase.totalDiscount
      this.form.newDue = this.form.purchase.due
      this.form.newDueText = this.form.purchase.due
      for (var key in this.form.purchase.purchaseProducts) {
        let purchaseItem = this.form.purchase.purchaseProducts[key]
        this.form.selectedProducts.unshift({
          id: purchaseItem.productID,
          slug: purchaseItem.productSlug,
          name: purchaseItem.productName,
          code: purchaseItem.productCode,
          unit: purchaseItem.productUnit,
          purchaseQty: purchaseItem.quantity,
          totalReturnQty:
            Number(purchaseItem.quantity) - Number(purchaseItem.returnQty),
          returnQty: 0,
          returnTotal: 0,
          purchasePrice: purchaseItem.unitCost,
          totalPrice: purchaseItem.unitCostTotal,
        })
      }
      this.calculateSum()
      return
    },

    // update items
    updateItem(value, index) {
      let selectedProduct = this.form.selectedProducts[index]
      if (selectedProduct && value > 0) {
        selectedProduct.returnQty = Number(value)
        selectedProduct.returnTotal =
          selectedProduct.returnQty * selectedProduct.purchasePrice
        this.form.selectedProducts[index] = selectedProduct
      }
      this.calculateSum()
    },

    // calculate sum
    calculateSum() {
      this.form.totalReturn = this.form.selectedProducts.reduce(function (
        prev,
        cur
      ) {
        return Number((prev + cur.returnTotal).toFixed(2))
      },
        0)

      this.form.newSubTotal = this.form.selectedProducts.reduce(function (
        prev,
        cur
      ) {
        return Number(
          (
            prev +
            (cur.purchaseQty - cur.returnQty) * cur.purchasePrice
          ).toFixed(2)
        )
      },
        0)

      this.form.newTax = Number(
        ((this.form.taxRate / 100) * this.form.newSubTotal).toFixed(2)
      )
      this.form.purchaseTotal =
        this.form.newSubTotal +
        this.form.newTax +
        this.form.purchaseTransport -
        this.form.purchaseDiscount

      this.form.purchaseDue = Number(
        (this.form.purchaseTotal - this.form.purchase.totalPaid).toFixed(2)
      )
      if (this.form.purchaseDue >= 0) {
        this.form.newDue = Number(
          (this.form.purchaseTotal - this.form.purchase.totalPaid).toFixed(2)
        )
        this.form.newDueText =
          this.form.purchaseTotal +
          ' - ' +
          this.form.purchase.totalPaid +
          ' = ' +
          this.form.newDue
        this.form.returnAmount = 0
      } else {
        this.form.returnAmount = (
          this.form.purchase.totalPaid - this.form.purchaseTotal
        ).toFixed(2)
        this.form.returnAmountText =
          this.form.purchase.totalPaid +
          ' - ' +
          this.form.purchaseTotal +
          ' = ' +
          this.form.returnAmount
        this.form.purchaseDue = 0
      }
      return
    },

    // save return
    async savePurchaseReturn() {
      await this.form
        .post(window.location.origin + '/api/purchase-returns')
        .then(({ data }) => {
          toast.fire({
            type: 'success',
            title: this.$t('purchases.returns.create.success_msg'),
          })
          this.$router.push({ name: 'purchaseReturns.show', params: { slug: data.data.slug }, })
        })
        .catch(() => {
          toast.fire({ type: 'error', title: this.$t('common.error_msg') })
        })
    },
  },
}
</script>

