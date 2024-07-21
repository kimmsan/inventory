<template>
  <div>
    <!-- breadcrumbs Start -->
    <breadcrumbs :items="breadcrumbs" :current="breadcrumbsCurrent" />
    <!-- breadcrumbs end -->

    <div class="row no-print mb-2">
      <div class="w-100 text-right float-right">
        <div class="btn-group" v-if="allData">
          <a @click="generatePDF()" href="#" class="btn btn-primary">
            <i class="fas fa-download"></i> {{ $t("download") }}
          </a>
          <a @click="printWindow()" href="#" class="btn btn-secondary">
            <i class="fas fa-print"></i> {{ $t("common.print") }}
          </a>
          <router-link v-if="$can('purchase-return-edit')" :to="{
            name: 'purchaseReturns.edit',
            params: { slug: allData.slug },
          }" class="btn btn-info">
            <i class="fas fa-edit" /> {{ $t("common.edit") }}
          </router-link>
          <router-link :to="{ name: 'purchaseReturns.index' }" class="btn btn-dark float-right">
            <i class="fas fa-long-arrow-alt-left" /> {{ $t("common.back") }}
          </router-link>
        </div>
      </div>
    </div>

    <div class="row">
      <!-- Main content -->
      <div class="invoice p-3 mb-3 w-100" id="content-to-pdf">
        <!-- info row -->
        <div class="row invoice-info">
          <div class="col-sm-4 invoice-col">
            <CompanyInfo />
          </div>
          <!-- /.col -->
          <div class="col-sm-6 offset-sm-2 invoice-col float-right text-md-right">
            <h5>{{ $t('common.supplier_details') }}</h5>
            <div v-if="allData.purchase && allData.supplier">
              <span v-if="allData.supplier.companyName"><strong>{{ $t('common.supplier_id') }}:</strong>
                {{ allData.supplier.supplierID | withPrefix(supplierPrefix)
                }}<br /></span>
              <strong>{{ $t('common.supplier_name') }}:</strong>
              {{ allData.supplier.name }}<br />
              <span v-if="allData.supplier.companyName"><strong>{{ $t('common.company_name') }}:</strong>
                {{ allData.supplier.companyName }}<br /></span>
              <span v-if="allData.supplier.email"><strong>{{ $t('common.email') }}:</strong>
                {{ allData.supplier.email }}<br /></span>
              <span v-if="allData.supplier.phoneNumber"><strong>{{ $t('common.contact_number') }}:</strong>
                {{ allData.supplier.phoneNumber }}<br /></span>
              <span v-if="allData.supplier.address"><strong>{{ $t('common.address') }}:</strong>
                {{ allData.supplier.address }}<br /></span>
            </div>
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->

        <div class="row mt-3">
          <div class="col-12">
            <div class="table-responsive table-custom">
              <table v-if="allData.purchase" class="table">
                <thead>
                  <tr>
                    <th v-if="allData.purchase.code">
                      {{ $t('purchases.list.common.purchase_no') }}
                    </th>
                    <th v-if="allData.returnNo">
                      {{ $t('purchases.list.common.return_no') }}
                    </th>
                    <th v-if="allData.purchase.purchaseDate">
                      {{ $t('purchases.list.common.purchase_date') }}
                    </th>
                    <th v-if="allData.returnDate">
                      {{ $t('purchases.returns.common.return_date') }}
                    </th>
                    <th v-if="allData.reason">
                      {{ $t('purchases.returns.common.return_reason') }}
                    </th>
                    <th v-if="allData.note">{{ $t('common.note') }}</th>
                    <th>{{ $t('common.status') }}</th>
                    <th v-if="allData.createdBy" class="text-right">
                      {{ $t('common.created_by') }}
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td v-if="allData.purchase.code">
                      {{ allData.purchase.code | withPrefix(purchasePrefix) }}
                    </td>
                    <td v-if="allData.returnNo">
                      {{ allData.returnNo | withPrefix(returnPrefix) }}
                    </td>
                    <td v-if="allData.purchase.purchaseDate">
                      {{
                        allData.purchase.purchaseDate | moment('Do MMM, YYYY')
                      }}
                    </td>
                    <td v-if="allData.returnDate">
                      {{ allData.returnDate | moment('Do MMM, YYYY') }}
                    </td>
                    <td v-if="allData.reason">{{ allData.reason }}</td>
                    <td v-if="allData.note">{{ allData.note }}</td>
                    <td>
                      <span v-if="allData.status === 1" class="badge bg-success">{{ $t('common.active') }}</span>
                      <span v-else class="badge bg-danger">{{
                        $t('common.in_active')
                      }}</span>
                    </td>
                    <td v-if="allData.createdBy" class="text-right">
                      {{ allData.createdBy }}
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Table row -->
        <div class="row position-relative mt-4">
          <table-loading v-show="loading" />
          <div v-if="allData.purchase" class="col-12 table-responsive">
            <strong class="mb-2 d-block">{{ $t('purchases.returns.common.return_products') }}:</strong>
            <div class="table-custom table-responsive text-center">
              <table class="table table-sm">
                <thead>
                  <tr>
                    <th>{{ $t('common.s_no') }}</th>
                    <th>{{ $t('common.code') }}</th>
                    <th>{{ $t('common.name') }}</th>
                    <th>{{ $t('purchases.list.common.purchased_qty') }}</th>
                    <th>{{ $t('purchases.list.common.returned_qty') }}</th>
                    <th>{{ $t('common.purchase_price') }}</th>
                    <th>{{ $t('common.total') }}</th>
                    <th class="text-right">
                      {{ $t('common.total_return') }}
                    </th>
                  </tr>
                </thead>
                <tbody v-if="returnProducts">
                  <tr v-for="(data, i) in returnProducts" :key="i">
                    <td>{{ ++i }}</td>
                    <td v-if="data.product">
                      {{ data.product.code | withPrefix(productPrefix) }}
                    </td>
                    <td v-if="data.product">{{ data.product.name }}</td>
                    <td v-if="data.product">
                      {{ data.purchasedQty }}
                      <span v-if="data.product.itemUnit">{{
                        data.product.itemUnit.code
                      }}</span>
                    </td>
                    <td v-if="data.product">
                      {{ data.returnQty }}
                      <span v-if="data.product.itemUnit">{{
                        data.product.itemUnit.code
                      }}</span>
                    </td>
                    <td>{{ data.purchasePrice | withCurrency }}</td>
                    <td>
                      {{
                        (data.purchasePrice * data.purchasedQty)
                        | withCurrency
                      }}
                    </td>
                    <td class="text-right">
                      {{
                        (data.purchasePrice * data.returnQty) | withCurrency
                      }}
                    </td>
                  </tr>
                  <tr>
                    <td colspan="6" class="text-right">
                      <strong>{{ $t('common.subtotal') }}</strong>
                    </td>
                    <td>
                      <strong>{{ purchaseSubTotal | withCurrency }}</strong>
                    </td>
                    <td class="text-right">
                      <strong>{{ purchaseReturn | withCurrency }}</strong>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- /.row -->
        <div v-if="allData.purchase" class="row mt-3">
          <div class="offset-xl-8 col-lg-12 col-xl-4 text-xl-right">
            <div class="table-responsive table-custom table-border-y-0">
              <table class="table">
                <tbody>
                  <tr class="bg-sub-light text-bold">
                    <th>{{ $t('common.subtotal') }}:</th>
                    <td>{{ allData.purchase.subTotal | withCurrency }}</td>
                  </tr>
                  <tr>
                    <th>{{ $t('common.return_cost') }}:</th>
                    <td>
                      <span class="minus-sign">-</span>
                      {{ purchaseReturn | withCurrency }}
                    </td>
                  </tr>
                  <tr>
                    <th>{{ $t('common.discount') }}:</th>
                    <td>
                      <span class="minus-sign">-</span>
                      {{ allData.purchase.totalDiscount | withCurrency }}
                    </td>
                  </tr>
                  <tr>
                    <th>{{ $t('common.transport') }}:</th>
                    <td>
                      <span class="plus-sign">+</span>
                      {{ allData.purchase.transport | withCurrency }}
                    </td>
                  </tr>
                  <tr>
                    <th>
                      {{ $t('common.tax') }}
                      <span>({{ allData.purchase.taxRate }}%):</span>
                    </th>
                    <td>
                      <span class="plus-sign">+</span>
                      {{ allData.purchase.tax | withCurrency }}
                    </td>
                  </tr>
                  <tr class="bg-indigo-light">
                    <th>{{ $t('common.total') }}:</th>
                    <td>
                      <span class="equal-sign">=</span>
                      {{ allData.purchase.purchaseTotal | withCurrency }}
                    </td>
                  </tr>
                  <tr>
                    <th>{{ $t('common.total_paid') }}:</th>
                    <td>
                      <span class="minus-sign">-</span>
                      {{ allData.purchase.totalPaid | withCurrency }}
                    </td>
                  </tr>
                  <tr class="bg-red-light">
                    <th>{{ $t('common.due') }}:</th>
                    <td>{{ allData.purchase.due | withCurrency }}</td>
                  </tr>
                  <tr class="bg-green-light" v-if="allData.accountReceivable">
                    <th>{{ $t('common.account_receivable') }}:</th>
                    <td>
                      {{ allData.accountReceivable.amount | withCurrency }}
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <!-- /.row -->
      </div>
      <!-- /.invoice -->
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import { mapGetters } from 'vuex'
import html2pdf from "html2pdf.js";

export default {
  middleware: ['auth', 'check-permissions'],
  metaInfo() {
    return { title: this.$t('purchases.returns.view.page_title') }
  },
  data: () => ({
    breadcrumbsCurrent: 'purchases.returns.view.breadcrumbs_current',
    breadcrumbs: [
      {
        name: 'purchases.returns.view.breadcrumbs_first',
        url: 'home',
      },
      {
        name: 'purchases.returns.view.breadcrumbs_second',
        url: 'purchaseReturns.index',
      },
      {
        name: 'purchases.returns.view.breadcrumbs_active',
        url: '',
      },
    ],
    allData: '',
    purchaseSubTotal: 0,
    purchaseReturn: 0,
    returnProducts: [],
    productPrefix: '',
    purchasePrefix: '',
    returnPrefix: '',
    supplierPrefix: '',
    loading: false,
  }),
  computed: mapGetters({
    appInfo: 'operations/appInfo',
  }),
  created() {
    this.getInvoiceReturn()
    this.productPrefix = this.appInfo.productPrefix
    this.purchasePrefix = this.appInfo.purchasePrefix
    this.returnPrefix = this.appInfo.purchaseReturnPrefix
    this.supplierPrefix = this.appInfo.supplierPrefix
  },
  methods: {
    // get the return
    async getInvoiceReturn() {
      this.loading = true
      const { data } = await axios.get(
        window.location.origin +
        '/api/purchase-returns/' +
        this.$route.params.slug
      )
      this.allData = data.data
      this.returnProducts = this.allData.returnProducts
      this.returnProducts.sort(this.sortProducts)
      this.calculateTotalAmount()
      this.loading = false
    },

    sortProducts(a, b) {
      if (a.product.code < b.product.code) {
        return -1
      }
      if (a.product.code > b.product.code) {
        return 1
      }
      return 0
    },

    // calculate total return
    calculateTotalAmount() {
      let purchaseSubTotal = 0
      let purchaseReturn = 0
      if (this.returnProducts) {
        purchaseSubTotal = this.returnProducts.reduce(function (prev, next) {
          return prev + Number(next.purchasedQty) * Number(next.purchasePrice)
        }, 0)
        purchaseReturn = this.returnProducts.reduce(function (prev, next) {
          return prev + Number(next.returnQty) * Number(next.purchasePrice)
        }, 0)
      }
      this.purchaseSubTotal = purchaseSubTotal
      this.purchaseReturn = purchaseReturn
      return
    },

    // print
    printWindow() {
      window.print()
    },

    // download pdf
    generatePDF() {
      // Get the HTML content to be converted
      const element = document.getElementById("content-to-pdf");
      // Options for PDF generation
      const options = {
        margin: 5,
        filename: "Purchase Return Invoice-" + this.$route.params.slug + ".pdf",
        image: { type: "jpeg", quality: 0.98 },
        pagebreak: { mode: "avoid-all", before: "#page-break" },
        html2canvas: { scale: 2 },
        jsPDF: { unit: "mm", format: "a4", orientation: "landscape" },
      };
      // Generate PDF from HTML content
      html2pdf().from(element).set(options).save();
    },
  },
}
</script>
