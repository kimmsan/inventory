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
              {{ $t('purchases.list.edit.form_title') }}
            </h3>
            <router-link :to="{ name: 'purchases.index' }" class="btn btn-dark float-right">
              <i class="fas fa-long-arrow-alt-left" /> {{ $t('common.back') }}
            </router-link>
          </div>
          <!-- /.card-header -->
          <!-- form start -->
          <form role="form" @submit.prevent="updatePurchase" @keydown="form.onKeydown($event)">
            <div class="card-body">
              <div class="row" v-if="items && products">
                <div class="form-group col-md-12 col-xl-3">
                  <label for="supplier">{{ $t('common.supplier') }}
                    <span class="required">*</span></label>
                  <v-select v-model="form.supplier" :options="items" label="name"
                    :class="{ 'is-invalid': form.errors.has('supplier') }" name="supplier"
                    :placeholder="$t('common.supplier_placeholder')" />
                  <has-error :form="form" field="supplier" />
                </div>
                <div class="form-group col-md-12 col-xl-3">
                  <label for="purchaseNo">{{
                    $t('purchases.list.common.purchase_no')
                  }}</label>
                  <input id="purchaseNo" v-model="form.purchaseNo" type="text" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('purchaseNo') }" name="purchaseNo" :placeholder="$t('purchases.list.common.purchase_no_placeholder')
                      " readonly />
                  <has-error :form="form" field="purchaseNo" />
                </div>
                <div v-if="products" class="form-group col-md-12 col-xl-6">
                  <label for="product">{{ $t('common.select_products') }}
                    <span class="required">*</span></label>
                  <v-select v-model="form.product" :options="products" label="label" :class="{
                    'is-invalid': form.errors.has('selectedProducts'),
                  }" name="product" :placeholder="$t('common.select_products_placeholder')"
                    @input="storeProduct(form.product)" />
                  <has-error :form="form" field="selectedProducts" />
                </div>
              </div>

              <div v-if="form.selectedProducts && form.selectedProducts.length > 0" class="row mt-3 mb-4">
                <div class="table-responsive table-custom w-95 m-auto">
                  <table class="table table-hover table-sm text-center">
                    <thead>
                      <tr>
                        <th>{{ $t('common.s_no') }}</th>
                        <th>{{ $t('common.code') }}</th>
                        <th>{{ $t('common.name') }}</th>
                        <th>{{ $t('purchases.list.common.purchased_qty') }}</th>
                        <th v-if="form.purchaseReturnData">
                          {{ $t('purchases.list.common.returned_qty') }}
                        </th>
                        <th>{{ $t('common.purchase_price') }}</th>
                        <th>{{ $t('common.unit_cost') }}</th>
                        <th>{{ $t('common.tax') }}</th>
                        <th>{{ $t('common.total_price') }}</th>
                        <th v-if="form.purchaseReturnData">
                          {{ $t('common.total_return') }}
                        </th>
                        <th class="text-right">{{ $t('common.action') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(item, i) in form.selectedProducts" :key="i" class="text-center">
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
                              data-field="quantity" @click="generateItemTotal(item.qty, 'qty', i - 1, 'decrement')" />

                            <input type="number" step="any" :id="`purchaseQty-${i}`" :value="item.qty" name="quantity"
                              class="quantity-field border-0 incrementor" required :min="item.minQty" @change="
                                generateItemTotal($event.target.value, 'qty', i - 1, '')"
                              @keyup="generateItemTotal($event.target.value, 'qty', i - 1, '')" />

                            <input type="button" value="+" class="button-plus icon-shape icon-sm btn-primary"
                              data-field="quantity" @click="generateItemTotal(item.qty, 'qty', i - 1, 'increment')" />
                          </div>
                        </td>
                        <td v-if="form.purchaseReturnData">
                          {{ item.returnQty }}
                        </td>
                        <td>
                          <div class="input-group custom-qty-input">
                            <input type="button" value="-" class="button-minus icon-shape icon-sm btn-danger"
                              data-field="quantity"
                              @click="generateItemTotal(item.unitPrice, 'price', i - 1, 'decrement')" />

                            <input type="number" step="any" :id="`unitPrice-${i}`" :value="item.unitPrice"
                              name="unitPrice" class="quantity-field border-0 incrementor" required min="1" @change="
                                generateItemTotal($event.target.value, 'price', i - 1, '')"
                              @keyup="generateItemTotal($event.target.value, 'price', i - 1, '')" />

                            <input type="button" value="+" class="button-plus icon-shape icon-sm btn-primary"
                              data-field="quantity"
                              @click="generateItemTotal(item.unitPrice, 'price', i - 1, 'increment')" />
                          </div>
                        </td>
                        <td>{{ item.unitCost | withCurrency }}</td>
                        <td>{{ item.totalTax | withCurrency }}</td>
                        <td>{{ item.totalPrice | withCurrency }}</td>
                        <td v-if="form.purchaseReturnData">
                          {{ item.totalReturn | withCurrency }}
                        </td>
                        <td class="text-right">
                          <button type="button" class="btn btn-danger" @click="removeItem(item)">
                            <i class="fas fa-times"></i>
                          </button>
                        </td>
                      </tr>
                      <tr v-if="form.subTotal">
                        <td :colspan="form.purchaseReturnData ? 7 : 6" class="text-right">
                          <strong>{{ $t('common.subtotal') }}:</strong>
                        </td>
                        <td>
                          <strong>{{
                            form.totalProductTax | withCurrency
                          }}</strong>
                        </td>
                        <td>
                          <strong>{{ form.subTotal | withCurrency }}</strong>
                        </td>
                        <td v-if="form.purchaseReturnData">
                          <strong>{{
                            form.purchaseReturn | withCurrency
                          }}</strong>
                        </td>
                        <td></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div v-if="form.selectedProducts && form.selectedProducts.length > 0" class="row">
                <div class="form-group col-md-6 col-xl-3">
                  <label for="poReference">{{
                    $t('purchases.list.common.po_reference')
                  }}</label>
                  <input id="poReference" v-model="form.poReference" type="text" step="any" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('poReference') }" name="poReference" :placeholder="$t('purchases.list.common.po_reference_placeholder')
                      " />
                  <has-error :form="form" field="poReference" />
                </div>
                <div class="form-group col-md-6 col-xl-3">
                  <label for="paymentTerms">{{
                    $t('purchases.list.common.payment_terms')
                  }}</label>
                  <input id="paymentTerms" v-model="form.paymentTerms" type="text" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('paymentTerms') }" name="paymentTerms" :placeholder="$t('purchases.list.common.payment_terms_placeholder')
                      " />
                  <has-error :form="form" field="paymentTerms" />
                </div>
                <div v-if="taxes" class="form-group col-md-6 col-xl-3">
                  <label for="orderTax">{{ $t('purchases.list.common.purchase_tax') }}
                    <span class="required">*</span></label>
                  <v-select v-model="form.orderTax" :options="taxes" label="code"
                    :class="{ 'is-invalid': form.errors.has('orderTax') }" name="orderTax" :placeholder="$t('purchases.list.common.purchase_tax_placeholder')
                      " @input="updateTax" />
                  <has-error :form="form" field="orderTax" />
                </div>
                <div v-if="taxes" class="form-group col-md-6 col-xl-3">
                  <label for="totalTax">{{
                    $t('purchases.list.common.total_tax')
                  }}</label>
                  <input id="totalTax" v-model="form.totalTax" type="text" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('totalTax') }" name="totalTax" readonly />
                  <has-error :form="form" field="totalTax" />
                </div>
              </div>
              <div class="row">
                <div class="form-group col-md-6" :class="form.purchaseReturnData ? 'col-lg-3' : 'col-lg-4'">
                  <label for="discount">{{
                    $t('purchases.list.common.discount')
                  }}</label>
                  <input id="discount" v-model="form.discount" type="number" step="any" min="1" :max="form.rowSubTotal"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('discount') }" name="discount"
                    :placeholder="$t('purchases.list.common.discount_placeholder')
                      " @change="calculateSum" @keyup="calculateSum" />
                  <has-error :form="form" field="discount" />
                </div>
                <div class="form-group col-md-6" :class="form.purchaseReturnData ? 'col-lg-3' : 'col-lg-4'">
                  <label for="transportCost">{{
                    $t('purchases.list.common.transport_cost')
                  }}</label>
                  <input id="transportCost" v-model="form.transportCost" type="number" step="any" min="0"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('transportCost') }" name="transportCost"
                    :placeholder="$t('purchases.list.common.transport_cost_placeholder')
                        " @change="calculateSum" @keyup="calculateSum" />
                  <has-error :form="form" field="transportCost" />
                </div>
                <div v-if="form.purchaseReturnData" class="form-group col-md-6"
                  :class="form.purchaseReturnData ? 'col-lg-3' : 'col-lg-4'">
                  <label for="purchaseReturn">{{
                    $t('common.return_cost')
                  }}</label>
                  <input id="purchaseReturn" v-model="form.purchaseReturn" type="number" step="any" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('purchaseReturn') }" name="purchaseReturn" readonly />
                  <has-error :form="form" field="purchaseReturn" />
                </div>

                <div class="form-group col-md-6" :class="form.purchaseReturnData ? 'col-lg-3' : 'col-lg-4'">
                  <label for="netTotal">{{ $t('common.net_total') }}</label>
                  <input id="netTotal" v-model="form.netTotal" type="number" step="any" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('netTotal') }" name="netTotal" readonly />
                  <has-error :form="form" field="netTotal" />
                </div>
              </div>

              <div class="form-group">
                <label for="note">{{ $t('common.note') }}</label>
                <textarea id="note" v-model="form.note" class="form-control"
                  :class="{ 'is-invalid': form.errors.has('note') }" :placeholder="$t('common.note_placeholder')" />
                <has-error :form="form" field="note" />
              </div>
              <div class="row">
                <div class="form-group col-md-4">
                  <label for="purchaseDate">{{
                    $t('purchases.list.common.purchase_date')
                  }}</label>
                  <input id="purchaseDate" v-model="form.purchaseDate" type="date" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('purchaseDate') }" name="purchaseDate" />
                  <has-error :form="form" field="purchaseDate" />
                </div>
                <div class="form-group col-md-4">
                  <label for="poDate">{{
                    $t('purchases.list.common.po_date')
                  }}</label>
                  <input id="poDate" v-model="form.poDate" type="date" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('poDate') }" name="poDate" />
                  <has-error :form="form" field="poDate" />
                </div>
                <div class="form-group col-md-4">
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
import { mapGetters } from 'vuex'
import Form from 'vform'
import axios from 'axios'

export default {
  middleware: ['auth', 'check-permissions'],
  metaInfo() {
    return { title: this.$t('purchases.list.edit.page_title') }
  },
  data: () => ({
    breadcrumbsCurrent: 'purchases.list.edit.breadcrumbs_current',
    breadcrumbs: [
      {
        name: 'purchases.list.edit.breadcrumbs_first',
        url: 'home',
      },
      {
        name: 'purchases.list.edit.breadcrumbs_second',
        url: 'purchases.index',
      },
      {
        name: 'purchases.list.edit.breadcrumbs_active',
        url: '',
      },
    ],
    form: new Form({
      supplier: '',
      purchaseNo: '',
      selectedProducts: [],
      orderTax: '',
      totalTax: 0,
      discount: '',
      transportCost: '',
      subTotal: 0,
      rowSubTotal: 0,
      netTotal: 0,
      poReference: '',
      paymentTerms: '',
      totalProductTax: 0,
      poDate: new Date().toISOString().slice(0, 10),
      purchaseDate: new Date().toISOString().slice(0, 10),
      purchaseReturnData: '',
      purchaseReturn: 0,
      note: '',
      status: 1,
    }),
    products: '',
    accounts: '',
    taxes: '',
    prefix: '',
    purchasePrefix: '',
  }),
  computed: {
    ...mapGetters('operations', ['items', 'appInfo']),
  },
  created() {
    this.getPurcahse()
    this.getSuppliers()
    this.getProducts()
    this.getTaxes()
    this.prefix = this.appInfo.productPrefix
    this.purchasePrefix = this.appInfo.purchasePrefix
  },
  methods: {
    // get purchase
    async getPurcahse() {
      const { data } = await axios.get(
        window.location.origin + '/api/purchases/' + this.$route.params.slug
      )
      this.form.purchaseNo = this.$options.filters.withPrefix(
        data.data.purchaseNo,
        this.purchasePrefix
      )
      this.form.supplier = data.data.supplier
      this.form.poReference = data.data.poReference
      this.form.transportCost = data.data.transport
      this.form.totalTax = data.data.tax
      this.form.orderTax = data.data.taxType
      this.form.discount = data.data.totalDiscount
      this.form.paymentTerms = data.data.paymentTerms
      this.form.poDate = data.data.poDate
      this.form.purchaseDate = data.data.purchaseDate
      this.form.purchaseReturnData = data.data.purchaseReturn
      this.form.status = data.data.status
      this.form.note = data.data.note
      this.form.selectedProducts = this.assignProducts(data.data.products)
    },

    // get all local suppliers
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

    // get taxes
    async getTaxes() {
      const { data } = await axios.get(
        window.location.origin + '/api/all-vat-rates'
      )
      this.taxes = data.data
    },

    // store item in array
    storeProduct(product) {
      var index = this.form.selectedProducts.findIndex(
        (x) => x.id == product.id
      )
      let quantity = 1
      if (index === -1) {
        let purchasePrice =
          product.avgPurchasePrice > 0 ? product.avgPurchasePrice : 1
        let productTax =
          product.taxType == 'Exclusive'
            ? purchasePrice * (product.taxRate / 100)
            : purchasePrice - purchasePrice / (1 + product.taxRate / 100)
        let totalTax = productTax * quantity
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
            product.taxType == 'Exclusive'
              ? purchasePrice + totalTax
              : purchasePrice,
          totalPrice:
            product.taxType == 'Exclusive'
              ? 1 * (purchasePrice + totalTax)
              : 1 * purchasePrice,
          totalPrice: 1,
          returnQty: 0,
          totalReturn: 0,
          minQty: 1,
          oldQty: 0,
        })
      }
      this.generateItemTotal(quantity, 'qty', index, '')
      this.updateTax()
      return
    },

    // update array
    generateItemTotal(value, type, index, action) {
      let item = this.form.selectedProducts[index]
      if (item) {
        if (type == 'qty') {
          item.qty = value
          if (action == 'increment') {
            item.qty = Number(item.qty) + 1
          } else if (action == 'decrement') {
            if (item.qty > 0) {
              item.qty = Number(item.qty) - 1
            }
          }
        } else {
          this.form.selectedProducts[index].unitPrice = value
          item.unitPrice = value
          if (action == 'increment') {
            item.unitPrice = Number(item.unitPrice) + 1
          } else if (action == 'decrement') {
            if (item.qty > 0) {
              item.unitPrice = Number(item.unitPrice) - 1
            }
          }
        }
        item.productTax =
          item.taxType == 'Exclusive'
            ? item.unitPrice * (item.taxRate / 100)
            : item.unitPrice - item.unitPrice / (1 + item.taxRate / 100)
        item.totalTax = item.productTax * item.qty
        item.totalPrice =
          item.taxType == 'Exclusive'
            ? item.qty * item.unitPrice + item.totalTax
            : item.qty * item.unitPrice
        item.unitCost =
          item.taxType == 'Exclusive'
            ? Number(item.unitPrice) + Number(item.productTax)
            : item.unitPrice
        this.form.selectedProducts[index] = item
      }
      this.updateTax()
      return
    },

    // remove item from array
    removeItem(item) {
      let index = this.form.selectedProducts.indexOf(item)
      if (index > -1) {
        this.form.selectedProducts.splice(index, 1)
      }
      this.updateTax()
      return
    },

    // update tax
    updateTax() {
      this.form.totalTax = 0
      if (
        this.form.orderTax &&
        this.form.orderTax.rate > 0 &&
        this.form.netTotal > 0
      ) {
        this.form.totalTax = Number(
          (
            (this.form.orderTax.rate / 100) *
            (this.form.subTotal - this.form.purchaseReturn)
          ).toFixed(2)
        )
      }
      this.calculateSum()
      return
    },

    // calculate sum
    calculateSum() {
      let length = this.form.selectedProducts.length
      this.form.subTotal =
        this.form.totalProductTax =
        this.form.purchaseReturn =
        0
      for (let i = 0; i < length; i++) {
        let looProduct = this.form.selectedProducts[i]
        this.form.subTotal += Number(looProduct.totalPrice.toFixed(2))
        this.form.totalProductTax += Number(looProduct.totalTax.toFixed(2))
        this.form.purchaseReturn += Number(looProduct.totalReturn.toFixed(2))
      }
      if (this.form.subTotal > 0) {
        this.form.netTotal =
          this.form.subTotal +
          Number(this.form.totalTax) +
          Number(this.form.transportCost) -
          Number(this.form.discount) -
          Number(this.form.purchaseReturn)
        this.form.rowSubTotal = this.form.subTotal - this.form.purchaseReturn
      }
      return
    },

    // get purchase products
    assignProducts(purchaseProducts) {
      for (var key in purchaseProducts) {
        let purchaseProduct = purchaseProducts[key]
        let minQty =
          purchaseProduct.returnQty > 0 ? purchaseProduct.returnQty : 1
        this.form.selectedProducts.unshift({
          id: purchaseProduct.productID,
          slug: purchaseProduct.productSlug,
          name: purchaseProduct.productName,
          code: purchaseProduct.productCode,
          qty: purchaseProduct.quantity,
          taxType: purchaseProduct.taxType,
          taxRate: purchaseProduct.taxRate,
          productTax: purchaseProduct.taxAmount,
          totalTax: purchaseProduct.taxTotal,
          unitCost: purchaseProduct.unitCost,
          totalPrice: purchaseProduct.unitCostTotal,
          returnQty: purchaseProduct.returnQty,
          unitPrice: purchaseProduct.purchasePrice,
          totalReturn: purchaseProduct.totalReturn,
          minQty:
            purchaseProduct.stockQty >= purchaseProduct.quantity
              ? minQty
              : minQty <= purchaseProduct.stockQty
                ? minQty + 1
                : purchaseProduct.stockQty,
          oldQty: purchaseProduct.quantity,
        })
      }
      this.calculateSum()
      this.updateTax()
      return this.form.selectedProducts
    },

    // update purchase
    async updatePurchase() {
      await this.form
        .patch(
          window.location.origin + '/api/purchases/' + this.$route.params.slug
        )
        .then(({ data }) => {
          toast.fire({
            type: 'success',
            title: this.$t('purchases.list.edit.success_msg'),
          })
          this.$router.push({ name: 'purchases.show', params: { slug: data.data.slug }, })
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
