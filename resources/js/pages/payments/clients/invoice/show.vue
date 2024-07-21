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
          <router-link v-if="$can('invoice-payment-edit')" :to="{
            name: 'invoicePayments.edit',
            params: { slug: allData.slug },
          }" class="btn btn-info">
            <i class="fas fa-edit" /> {{ $t("common.edit") }}
          </router-link>
          <router-link :to="{ name: 'invoicePayments.index' }" class="btn btn-dark float-right">
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
            <h5>{{ $t("common.client_details") }}</h5>
            <div v-if="allData.client">
              <span v-if="allData.client.clientID"><strong>{{ $t("common.client_id") }}:</strong>
                {{ allData.client.clientID | withPrefix(clientPrefix) }}<br /></span>
              <strong>{{ $t("common.client_name") }}:</strong>
              {{ allData.client.name }}<br />
              <span v-if="allData.client.companyName"><strong>{{ $t("common.company_name") }}:</strong>
                {{ allData.client.companyName }}<br /></span>
              <span v-if="allData.client.email"><strong>{{ $t("common.email") }}:</strong>
                {{ allData.client.email }}<br /></span>
              <span v-if="allData.client.phoneNumber"><strong>{{ $t("common.contact_number") }}:</strong>
                {{ allData.client.phoneNumber }}<br /></span>
              <span v-if="allData.client.address"><strong>{{ $t("common.address") }}:</strong>
                {{ allData.client.address }}<br /></span>
            </div>
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
        <!-- Table row -->
        <div class="row mt-4">
          <div class="col-12">
            <strong class="mt-4 d-block mb-2">{{ $t("common.payment_details") }}:</strong>
            <div class="table-responsive table-custom">
              <table class="table">
                <thead>
                  <tr>
                    <th>{{ $t("common.invoice_no") }}</th>
                    <th>{{ $t("common.invoice_date") }}</th>
                    <th>{{ $t("common.invoice_total") }}</th>
                    <th>{{ $t("common.paid_amount") }}</th>
                    <th>{{ $t("common.account") }}</th>
                    <th class="text-right">
                      {{ $t("common.payment_date") }}
                    </th>
                  </tr>
                </thead>
                <tbody v-if="allData.invoice">
                  <tr>
                    <td>
                      {{
                        allData.invoice.invoiceNo | withPrefix(invoicePrefix)
                      }}
                    </td>
                    <td>
                      {{
                        allData.invoice.invoiceDate | moment("Do MMM, YYYY")
                      }}
                    </td>
                    <td>{{ allData.invoice.invoiceTotal | withCurrency }}</td>
                    <td>{{ allData.amount | withCurrency }}</td>
                    <td>
                      <span v-if="allData.account">{{
                        allData.account.label
                      }}</span>
                    </td>
                    <td class="text-right">
                      <span v-if="allData.date">{{
                        allData.date | moment("Do MMM, YYYY")
                      }}</span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- /.row -->
        <div class="row mt-4">
          <div class="col-lg-12 col-xl-8">
            <div class="table-responsive table-custom">
              <table v-if="allData.transaction" class="table">
                <thead>
                  <tr>
                    <th v-if="allData.transaction && allData.transaction.cheque_no
                      ">
                      {{ $t("common.cheque_no") }}
                    </th>
                    <th v-if="allData.transaction && allData.transaction.receipt_no
                      ">
                      {{ $t("common.receipt_no") }}
                    </th>
                    <th v-if="allData.note">{{ $t("common.note") }}</th>
                    <th v-if="allData.createdBy">
                      {{ $t("common.created_by") }}
                    </th>
                    <th class="text-right">{{ $t("common.status") }}</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td v-if="allData.transaction && allData.transaction.cheque_no
                      ">
                      {{ allData.transaction.cheque_no }}
                    </td>
                    <td v-if="allData.transaction && allData.transaction.receipt_no
                      ">
                      {{ allData.transaction.receipt_no }}
                    </td>
                    <td v-if="allData.note">{{ allData.note }}</td>
                    <td v-if="allData.createdBy">{{ allData.createdBy }}</td>
                    <td class="text-right">
                      <span v-if="allData.status === 1" class="badge bg-success">{{ $t("common.active") }}</span>
                      <span v-else class="badge bg-danger">{{
                        $t("common.in_active")
                      }}</span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="col-lg-12 col-xl-4 text-lg-right">
            <div class="table-responsive table-custom table-border-y-0" v-if="allData.invoice">
              <table v-if="allData.invoice" class="table">
                <tbody>
                  <tr class="bg-sub-light text-bold">
                    <th>{{ $t("common.subtotal") }}:</th>
                    <td>{{ allData.invoice.subTotal | withCurrency }}</td>
                  </tr>
                  <tr v-if="allData.costOfReturn">
                    <th>{{ $t("common.return_cost") }}:</th>
                    <td>
                      <span class="minus-sign">-</span>
                      {{ allData.costOfReturn | withCurrency }}
                    </td>
                  </tr>
                  <tr>
                    <th>
                      {{ $t("common.discount") }}
                      <span v-if="allData.invoice.discountType == 1">({{ allData.invoice.discountPercentage }}%)</span>
                      :
                    </th>
                    <td>
                      <span class="minus-sign">-</span>
                      {{ allData.invoice.discount | withCurrency }}
                    </td>
                  </tr>
                  <tr>
                    <th>{{ $t("common.transport") }}:</th>
                    <td>
                      <span class="plus-sign">+</span>
                      {{ allData.invoice.transport | withCurrency }}
                    </td>
                  </tr>
                  <tr>
                    <th>
                      {{ $t("common.tax") }}
                      <span v-if="allData.invoice.taxRate">({{ allData.invoice.taxRate }}%)</span>:
                    </th>
                    <td>
                      <span class="plus-sign">+</span>
                      {{ allData.invoice.tax | withCurrency }}
                    </td>
                  </tr>
                  <tr class="bg-indigo-light">
                    <th>{{ $t("common.total") }}:</th>
                    <td>
                      <span class="equal-sign">=</span>

                      {{
                        (allData.invoice.subTotal -
                          allData.costOfReturn -
                          allData.invoice.discount +
                          allData.invoice.transport +
                          allData.invoice.tax)
                        | withCurrency
                      }}
                    </td>
                  </tr>
                  <tr>
                    <th>{{ $t("common.total_paid") }}:</th>
                    <td>
                      <span class="minus-sign">-</span>
                      {{ allData.invoice.totalPaid | withCurrency }}
                    </td>
                  </tr>
                  <tr class="bg-red-light">
                    <th>{{ $t("common.due") }}:</th>
                    <td>{{ allData.invoice.due | withCurrency }}</td>
                  </tr>
                  <tr class="bg-green-light" v-if="allData.accountPayable > 0">
                    <th>{{ $t("common.account_payable") }}:</th>
                    <td>{{ allData.accountPayable | withCurrency }}</td>
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
import axios from "axios";
import { mapGetters } from "vuex";
import html2pdf from "html2pdf.js";

export default {
  middleware: ["auth", "check-permissions"],
  metaInfo() {
    return { title: this.$t("payments.clients.invoice.view.page_title") };
  },
  data: () => ({
    breadcrumbsCurrent: "payments.clients.invoice.view.breadcrumbs_current",
    breadcrumbs: [
      {
        name: "payments.clients.invoice.view.breadcrumbs_first",
        url: "home",
      },
      {
        name: "payments.clients.invoice.view.breadcrumbs_second",
        url: "",
      },
      {
        name: "payments.clients.invoice.view.breadcrumbs_third",
        url: "invoicePayments.index",
      },
      {
        name: "payments.clients.invoice.view.breadcrumbs_active",
        url: "",
      },
    ],
    allData: "",
    clientPrefix: "",
    invoicePrefix: "",
  }),
  // Map Getters
  computed: {
    ...mapGetters("operations", ["appInfo"]),
  },
  created() {
    this.getInvoicePayment();
    this.clientPrefix = this.appInfo.clientPrefix;
    this.invoicePrefix = this.appInfo.invoicePrefix;
  },
  methods: {
    // get the invoice payment
    async getInvoicePayment() {
      const { data } = await axios.get(
        window.location.origin +
        "/api/payments/invoice/" +
        this.$route.params.slug
      );
      this.allData = data.data;
    },

    // print
    printWindow() {
      window.print();
    },

    // download pdf
    generatePDF() {
      // Get the HTML content to be converted
      const element = document.getElementById("content-to-pdf");
      // Options for PDF generation
      const options = {
        margin: 5,
        filename: "Invoice Payment-" + this.$route.params.slug + ".pdf",
        image: { type: "jpeg", quality: 0.98 },
        pagebreak: { mode: "avoid-all", before: "#page-break" },
        html2canvas: { scale: 2 },
        jsPDF: { unit: "mm", format: "a4", orientation: "landscape" },
      };
      // Generate PDF from HTML content
      html2pdf().from(element).set(options).save();
    },
  },
};
</script>
