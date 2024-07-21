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
          <router-link v-if="$can('purchase-payment-edit')" :to="{
            name: 'purchasePayments.edit',
            params: { slug: allData.slug },
          }" class="btn btn-info">
            <i class="fas fa-edit" /> {{ $t("common.edit") }}
          </router-link>
          <router-link :to="{ name: 'purchasePayments.index' }" class="btn btn-dark float-right">
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
            <div v-if="allData.supplier">
              <strong>{{ $t('common.supplier_id') }}:</strong>
              {{ allData.supplier.supplierID | withPrefix(supplierPrefix)
              }}<br />
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
        <!-- Table row -->
        <div class="row mb-2">
          <div class="col-12 table-responsive">
            <strong class="mt-4 d-block mb-2">{{ $t('common.payment_details') }}:</strong>
            <div class="table-responsive table-custom">
              <table class="table">
                <thead>
                  <tr>
                    <th>{{ $t('purchases.list.common.purchase_no') }}</th>
                    <th>{{ $t('purchases.list.common.purchase_date') }}</th>
                    <th>{{ $t('purchases.list.common.purchase_total') }}</th>
                    <th>{{ $t('common.paid_amount') }}</th>
                    <th>{{ $t('common.account') }}</th>
                    <th class="text-right">
                      {{ $t('common.payment_date') }}
                    </th>
                  </tr>
                </thead>
                <tbody v-if="allData.purchase">
                  <tr>
                    <td>{{ allData.purchase.purchaseNo }}</td>
                    <td>
                      {{
                        allData.purchase.purchaseDate | moment('Do MMM, YYYY')
                      }}
                    </td>
                    <td>
                      {{ allData.purchase.purchaseTotal | withCurrency }}
                    </td>
                    <td>{{ allData.amount | withCurrency }}</td>
                    <td>
                      <span v-if="allData.account">{{
                        allData.account.label
                      }}</span>
                    </td>
                    <td class="text-right">
                      {{ allData.date | moment('Do MMM, YYYY') }}
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- /.row -->
        <div class="row mt-3">
          <div class="col-lg-12 col-xl-8">
            <div class="table-responsive table-custom">
              <table v-if="allData.transaction" class="table">
                <thead>
                  <tr>
                    <th>
                      {{ $t('common.cheque_no') }}
                    </th>
                    <th>
                      {{ $t('common.receipt_no') }}
                    </th>
                    <th>{{ $t('common.note') }}</th>
                    <th v-if="allData.createdBy">
                      {{ $t('common.created_by') }}
                    </th>
                    <th class="text-right">{{ $t('common.status') }}</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>{{ allData.transaction.cheque_no }}</td>
                    <td>{{ allData.transaction.receipt_no }}</td>
                    <td>{{ allData.note }}</td>
                    <td v-if="allData.createdBy">
                      {{ allData.createdBy }}
                    </td>
                    <td class="text-right">
                      <span v-if="allData.status === 1" class="badge bg-success">{{ $t('common.active') }}</span>
                      <span v-else class="badge bg-danger">{{
                        $t('common.in_active')
                      }}</span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <div class="col-lg-12 col-xl-4 text-lg-right">
            <div class="table-responsive table-custom table-border-y-0" v-if="allData.purchase">
              <table class="table">
                <tbody>
                  <tr class="bg-sub-light text-bold">
                    <th>{{ $t('common.subtotal') }}:</th>
                    <td>{{ allData.purchase.subTotal | withCurrency }}</td>
                  </tr>
                  <tr v-if="allData.costOfReturn">
                    <th>{{ $t('common.return_cost') }}:</th>
                    <td>
                      <span class="minus-sign">-</span>
                      {{ allData.costOfReturn | withCurrency }}
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
                    <th>{{ $t('common.total_due') }}:</th>
                    <td>{{ allData.purchase.due | withCurrency }}</td>
                  </tr>
                  <tr class="bg-green-light" v-if="allData.purchase.accountReceivable">
                    <th>{{ $t('common.account_receivable') }}:</th>
                    <td>
                      {{ allData.purchase.accountReceivable | withCurrency }}
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <!-- /.row -->
      </div>
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
    return { title: this.$t('payments.suppliers.purchase.view.page_title') }
  },
  data: () => ({
    breadcrumbsCurrent: 'payments.suppliers.purchase.view.breadcrumbs_current',
    breadcrumbs: [
      {
        name: 'payments.suppliers.purchase.view.breadcrumbs_first',
        url: 'home',
      },
      {
        name: 'payments.suppliers.purchase.view.breadcrumbs_second',
        url: '',
      },
      {
        name: 'payments.suppliers.purchase.view.breadcrumbs_third',
        url: 'purchasePayments.index',
      },
      {
        name: 'payments.suppliers.purchase.view.breadcrumbs_active',
        url: '',
      },
    ],
    allData: '',
    supplierPrefix: '',
    purchasePrefix: '',
  }),
  // Map Getters
  computed: {
    ...mapGetters('operations', ['appInfo']),
  },
  created() {
    this.getPurchasePayment()
    this.supplierPrefix = this.appInfo.supplierPrefix
    this.purchasePrefix = this.appInfo.purchasePrefix
  },
  methods: {
    // get the purchase payment
    async getPurchasePayment() {
      const { data } = await axios.get(
        window.location.origin +
        '/api/payments/purchase/' +
        this.$route.params.slug
      )
      this.allData = data.data
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
        filename: "Purchase Payment-" + this.$route.params.slug + ".pdf",
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
