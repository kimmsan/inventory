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
          <router-link v-if="$can('invoice-return-edit')" :to="{
            name: 'invoiceReturns.edit',
            params: { slug: allData.slug },
          }" class="btn btn-info">
            <i class="fas fa-edit" /> {{ $t("common.edit") }}
          </router-link>
          <router-link :to="{ name: 'invoiceReturns.index' }" class="btn btn-dark float-right">
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
            <h5>{{ $t('common.client_details') }}</h5>
            <div v-if="allData.client">
              <span v-if="allData.client.companyName"><strong>{{ $t('common.client_id') }}:</strong>
                {{ allData.client.clientID | withPrefix(clientPrefix) }}<br /></span>
              <strong>{{ $t('common.client_name') }}:</strong>
              {{ allData.client.name }}<br />
              <span v-if="allData.client.companyName"><strong>{{ $t('common.company_name') }}:</strong>
                {{ allData.client.companyName }}<br /></span>
              <span v-if="allData.client.email"><strong>{{ $t('common.email') }}:</strong>
                {{ allData.client.email }}<br /></span>
              <span v-if="allData.client.contactNumber"><strong>{{ $t('common.contact_number') }}:</strong>
                {{ allData.client.contactNumber }}<br /></span>
              <span v-if="allData.client.address"><strong>{{ $t('common.address') }}:</strong>
                {{ allData.client.address }}<br /></span>
            </div>
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->

        <div class="row mt-3">
          <div class="col-12">
            <div class="table-custom table-responsive">
              <table v-if="allData.invoice" class="table table-bordered">
                <thead>
                  <tr>
                    <th v-if="allData.invoice.invoiceNo">
                      {{ $t('common.invoice_no') }}
                    </th>
                    <th v-if="allData.returnNo">
                      {{ $t('common.return_no') }}
                    </th>
                    <th v-if="allData.invoice.invoiceDate">
                      {{ $t('common.invoice_date') }}
                    </th>
                    <th v-if="allData.returnDate">
                      {{ $t('sales.common.return_date') }}
                    </th>
                    <th v-if="allData.reason">
                      {{ $t('common.return_reason') }}
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
                    <td v-if="allData.invoice.invoiceNo">
                      {{
                        allData.invoice.invoiceNo | withPrefix(invoicePrefix)
                      }}
                    </td>
                    <td v-if="allData.returnNo">
                      {{ allData.returnNo | withPrefix(returnPrefix) }}
                    </td>
                    <td v-if="allData.invoice.invoiceDate">
                      {{
                        allData.invoice.invoiceDate | moment('Do MMM, YYYY')
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
        <div class="row position-relative mt-4 mb-4">
          <table-loading v-show="loading" />
          <div class="col-12">
            <strong class="mt-3">{{ $t('common.return_products') }}:</strong>
            <div v-if="allData.invoice" class="table-custom table-responsive text-center">
              <table class="table table-sm">
                <thead>
                  <tr>
                    <th>{{ $t('common.s_no') }}</th>
                    <th>{{ $t('common.code') }}</th>
                    <th>{{ $t('common.product_name') }}</th>
                    <th>{{ $t('common.invoice_qty') }}</th>
                    <th>{{ $t('common.return_qty') }}</th>
                    <th>{{ $t('common.unit_price') }}</th>
                    <th>{{ $t('common.subtotal') }}</th>
                    <th class="text-right">
                      {{ $t('common.total_return') }}
                    </th>
                  </tr>
                </thead>
                <tbody v-if="returnProducts">
                  <tr v-for="(data, i) in returnProducts" :key="i">
                    <td>{{ ++i }}</td>
                    <td>
                      {{ data.productCode | withPrefix(productPrefix) }}
                    </td>
                    <td>{{ data.productName }}</td>
                    <td>{{ data.invoiceQty }} {{ data.productUnit }}</td>
                    <td>{{ data.returnQty }} {{ data.productUnit }}</td>
                    <td>{{ data.salePrice | withCurrency }}</td>
                    <td>
                      {{ (data.salePrice * data.invoiceQty) | withCurrency }}
                    </td>
                    <td class="text-right">
                      {{ (data.salePrice * data.returnQty) | withCurrency }}
                    </td>
                  </tr>
                  <tr>
                    <td colspan="6" class="text-right">
                      <strong>{{ $t('common.subtotal') }}</strong>
                    </td>
                    <td>
                      <strong>{{
                        allData.invoice.subTotal | withCurrency
                      }}</strong>
                    </td>
                    <td class="text-right">
                      <strong>{{ invoiceReturn | withCurrency }}</strong>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- /.row -->
        <div v-if="allData.invoice" class="row mt-3">
          <div class="offset-xl-8 col-lg-12 col-xl-4 text-lg-right">
            <div class="table-responsive table-custom table-border-y-0">
              <table class="table">
                <tbody>
                  <tr class="bg-sub-light text-bold">
                    <th>{{ $t('common.subtotal') }}:</th>
                    <td>{{ allData.invoice.subTotal | withCurrency }}</td>
                  </tr>
                  <tr>
                    <th>{{ $t('common.return_cost') }}:</th>
                    <td>
                      <span class="minus-sign">-</span>
                      {{ invoiceReturn | withCurrency }}
                    </td>
                  </tr>
                  <tr>
                    <th>{{ $t('common.discount') }}:</th>
                    <td>
                      <span class="minus-sign">-</span>
                      {{ allData.invoice.discount | withCurrency }}
                    </td>
                  </tr>
                  <tr>
                    <th>{{ $t('common.transport') }}:</th>
                    <td>
                      <span class="plus-sign">+</span>
                      {{ allData.invoice.transport | withCurrency }}
                    </td>
                  </tr>
                  <tr>
                    <th>{{ $t('common.tax') }}:</th>
                    <td>
                      <span class="plus-sign">+</span>
                      {{ allData.invoice.tax | withCurrency }}
                    </td>
                  </tr>
                  <tr class="bg-indigo-light">
                    <th>{{ $t('common.total') }}:</th>
                    <td>
                      <span class="equal-sign">=</span>
                      {{
                        (allData.invoice.subTotal -
                          invoiceReturn -
                          allData.invoice.discount +
                          allData.invoice.transport +
                          allData.invoice.tax)
                        | withCurrency
                      }}
                    </td>
                  </tr>
                  <tr>
                    <th>{{ $t('common.total_paid') }}:</th>
                    <td>
                      <span class="minus-sign">-</span>
                      {{ allData.invoice.totalPaid | withCurrency }}
                    </td>
                  </tr>
                  <tr class="bg-red-light">
                    <th>{{ $t('common.due') }}:</th>
                    <td>{{ allData.invoice.due | withCurrency }}</td>
                  </tr>
                  <tr v-if="allData.accountPayable" class="bg-green-light">
                    <th>{{ $t('common.account_payable') }}:</th>
                    <td>
                      {{ allData.accountPayable.amount | withCurrency }}
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <!-- /.row -->

        <!-- this row will not appear when printing -->
        <div class="row no-print mt-5">
          <div class="col-12">
            <router-link :to="{ name: 'invoiceReturns.index' }" class="btn btn-dark float-right">
              <i class="fas fa-long-arrow-alt-left" /> {{ $t('common.back') }}
            </router-link>
            <a href="#" @click="printWindow" class="btn btn-default"><i class="fas fa-print"></i> {{
              $t('common.print')
            }}</a>
          </div>
        </div>
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
    return { title: this.$t('sales.returns.view.page_title') }
  },
  data: () => ({
    breadcrumbsCurrent: 'sales.returns.view.breadcrumbs_current',
    breadcrumbs: [
      {
        name: 'sales.returns.view.breadcrumbs_first',
        url: 'home',
      },
      {
        name: 'sales.returns.view.breadcrumbs_second',
        url: 'invoiceReturns.index',
      },
      {
        name: 'sales.returns.view.breadcrumbs_active',
        url: '',
      },
    ],
    allData: '',
    invoiceSubTotal: 0,
    invoiceReturn: 0,
    returnProducts: [],
    productPrefix: '',
    invoicePrefix: '',
    returnPrefix: '',
    clientPrefix: '',
    loading: false,
  }),
  computed: mapGetters({
    appInfo: 'operations/appInfo',
  }),
  created() {
    this.getInvoiceReturn()
    this.productPrefix = this.appInfo.productPrefix
    this.invoicePrefix = this.appInfo.invoicePrefix
    this.returnPrefix = this.appInfo.invoiceReturnPrefix
    this.clientPrefix = this.appInfo.clientPrefix
  },
  methods: {
    // get the invoice
    async getInvoiceReturn() {
      this.loading = true
      const { data } = await axios.get(
        window.location.origin +
        '/api/invoice-returns/' +
        this.$route.params.slug
      )
      this.allData = data.data
      this.returnProducts = this.allData.invoiceReturnProducts
      this.returnProducts.sort(this.sortProducts)
      this.calculateTotalAmount()
      this.loading = false
    },

    sortProducts(a, b) {
      if (a.productCode < b.productCode) {
        return -1
      }
      if (a.productCode > b.productCode) {
        return 1
      }
      return 0
    },

    // calculate total return
    calculateTotalAmount() {
      let invoiceSubTotal = 0
      let invoiceReturn = 0
      if (this.allData.invoiceReturnProducts) {
        invoiceSubTotal = this.allData.invoiceReturnProducts.reduce(function (
          prev,
          next
        ) {
          return prev + Number(next.invoiceQty) * Number(next.salePrice)
        },
          0)
        invoiceReturn = this.allData.invoiceReturnProducts.reduce(function (
          prev,
          next
        ) {
          return prev + Number(next.returnQty) * Number(next.salePrice)
        },
          0)
      }
      this.invoiceSubTotal = invoiceSubTotal
      this.invoiceReturn = invoiceReturn
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
        filename: "Invoice Return -" + this.$route.params.slug + ".pdf",
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

<style lang="scss" scoped></style>
