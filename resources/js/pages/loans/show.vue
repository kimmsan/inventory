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
          <router-link v-if="$can('loan-edit')" :to="{
            name: 'loans.edit',
            params: { slug: allData.slug },
          }" class="btn btn-info">
            <i class="fas fa-edit" /> {{ $t("common.edit") }}
          </router-link>
          <router-link :to="{ name: 'loans.index' }" class="btn btn-dark float-right">
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
            <h5>{{ $t("loans.common.authority_details") }}</h5>
            <div v-if="allData.authority">
              <strong>{{ $t("common.name") }}:</strong>
              {{ allData.authority.name }}<br />
              <strong>{{ $t("common.email") }}:</strong>
              {{ allData.authority.email }}<br />
              <strong>{{ $t("common.contact_number") }}:</strong>
              {{ allData.authority.contactNumber }}<br />
              <strong>{{ $t("common.address") }}:</strong>
              {{ allData.authority.address }}<br />
            </div>
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->

        <div class="row mt-3">
          <div class="col-12">
            <div class="table-responsive table-custom">
              <table class="table">
                <thead>
                  <tr>
                    <th>{{ $t("common.image") }}</th>
                    <th v-if="allData.reference">
                      {{ $t("common.ref_no") }}
                    </th>
                    <th v-if="allData.reason">{{ $t("common.reason") }}</th>
                    <th v-if="allData.account">
                      {{ $t("loans.common.Loan_account") }}
                    </th>
                    <th v-if="allData.loanType == 1">
                      {{ $t("loans.common.duration") }}
                    </th>
                    <th v-if="allData.loanType == 1">
                      {{ $t("common.installment") }}
                    </th>
                    <th v-if="allData.note">{{ $t("common.note") }}</th>
                    <th v-if="allData.date">
                      {{ $t("loans.common.loan_date") }}
                    </th>
                    <th>{{ $t("common.status") }}</th>
                    <th class="text-right">{{ $t("common.created_by") }}</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>
                      <a v-if="allData.image" href="#" id="show-modal" @click="previewModal(allData.image)">
                        <img :src="allData.image" class="rounded preview-sm" loading="lazy" />
                      </a>
                      <div v-else class="bg-secondary rounded no-preview-sm">
                        <small>{{ $t("common.no_preview") }}</small>
                      </div>
                    </td>
                    <td v-if="allData.reference">{{ allData.reference }}</td>
                    <td v-if="allData.reason">{{ allData.reason }}</td>
                    <td v-if="allData.account">
                      {{ allData.account.label }}
                    </td>
                    <td v-if="allData.loanType == 1">
                      {{ allData.durationStr }}
                    </td>
                    <td v-if="allData.loanType == 1">
                      {{ allData.installment }}
                    </td>
                    <td v-if="allData.note">{{ allData.note }}</td>
                    <td v-if="allData.date">
                      {{ allData.date | moment("Do MMM, YYYY") }}
                    </td>
                    <td>
                      <span v-if="allData.status === 1" class="badge bg-success">{{ $t("common.active") }}</span>
                      <span v-else class="badge bg-danger">{{
                        $t("common.in_active")
                      }}</span>
                    </td>
                    <td class="text-right">{{ allData.createdBy }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- /.row -->
        <div class="row mt-4">
          <div class="col-lg-12 col-xl-8">
            <div v-if="allData.loanPayments && allData.loanPayments.length > 0" class="col-12">
              <strong class="mb-2 d-block">
                {{ $t("loans.common.Loan_payments") }}:</strong>
              <div class="table-responsive table-custom">
                <table class="table table-sm">
                  <thead>
                    <tr>
                      <th>{{ $t("common.s_no") }}</th>
                      <th>{{ $t("common.ref_no") }}</th>
                      <th>{{ $t("common.account") }}</th>
                      <th>{{ $t("common.amount") }}</th>
                      <th>{{ $t("common.interest") }}</th>
                      <th>{{ $t("common.status") }}</th>
                      <th class="text-right">{{ $t("common.date") }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(data, i) in allData.loanPayments" :key="i">
                      <td>{{ ++i }}</td>
                      <td>{{ data.reference_no }}</td>
                      <td>
                        <span v-if="data.loan_payment_transaction &&
                          data.loan_payment_transaction.cashbook_account
                          ">
                          {{
                            data.loan_payment_transaction.cashbook_account
                              .account_number
                          }}
                        </span>
                      </td>
                      <td>{{ data.amount | withCurrency }}</td>
                      <td>{{ data.interest | withCurrency }}</td>
                      <td>
                        <span v-if="data.status === 1" class="badge bg-success">{{ $t("common.active") }}</span>
                        <span v-else class="badge bg-danger">{{
                          $t("common.in_active")
                        }}</span>
                      </td>
                      <td class="text-right">
                        <span v-if="data.date">{{
                          data.date | moment("Do MMM, YYYY")
                        }}</span>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="callout callout-danger mt-4 w-100 no-print" v-else>
              <h5>{{ $t("common.empty_payment") }}</h5>
              <p>
                {{ $t("loans.common.empty_payment_msg") }}
              </p>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-lg-12 col-xl-4 text-lg-right mt-4">
            <div class="table-responsive table-custom table-border-y-0">
              <table class="table">
                <tbody>
                  <tr class="bg-sub-light text-bold">
                    <th>{{ $t("loans.common.loan_amount") }}:</th>
                    <td v-if="allData.transaction">
                      {{ allData.transaction.amount | withCurrency }}
                    </td>
                  </tr>
                  <tr>
                    <th>
                      {{ $t("common.interest") }}
                      <span v-if="allData.loanType == 1">({{ allData.interestRate }}%)</span>:
                    </th>
                    <td>
                      <span class="plus-sign">+</span>
                      {{ allData.interestAmount | withCurrency }}
                    </td>
                  </tr>
                  <tr class="bg-indigo-light">
                    <th>{{ $t("common.payable") }}:</th>
                    <td v-if="allData.loanType == 1">
                      <span class="equal-sign">=</span>
                      {{ allData.payable | withCurrency }}
                    </td>
                    <td v-else>
                      <span class="equal-sign">=</span>
                      {{
                        (allData.payable + allData.interestAmount)
                        | withCurrency
                      }}
                    </td>
                  </tr>
                  <tr>
                    <th>{{ $t("common.total_paid") }}:</th>
                    <td>
                      <span class="minus-sign">-</span>
                      <span v-if="allData.loanType == 0">{{
                        allData.loanWithInterest | withCurrency
                      }}</span>
                      <span v-else>{{
                        (allData.totalPaid > 0 ? allData.totalPaid : 0)
                        | withCurrency
                      }}</span>
                    </td>
                  </tr>
                  <tr class="bg-red-light">
                    <th>{{ $t("common.due") }}:</th>
                    <td>{{ allData.due | withCurrency }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
    </div>
    <!-- use the modal component, pass in the prop -->
    <Modal v-if="showModal" @close="previewModal()">
      <h5 slot="header">{{ $t("common.modal_header") }}</h5>
      <div class="w-100" slot="body">
        <img :src="imageSrc" class="rounded img-fluid" loading="lazy" />
      </div>
    </Modal>
  </div>
</template>

<script>
import axios from "axios";
import html2pdf from "html2pdf.js";

export default {
  middleware: ["auth", "check-permissions"],
  metaInfo() {
    return { title: this.$t("loans.list.view.page_title") };
  },
  data: () => ({
    breadcrumbsCurrent: "loans.list.view.breadcrumbs_current",
    breadcrumbs: [
      {
        name: "loans.list.view.breadcrumbs_first",
        url: "home",
      },
      {
        name: "loans.list.view.breadcrumbs_second",
        url: "loans.index",
      },
      {
        name: "loans.list.view.breadcrumbs_active",
        url: "",
      },
    ],
    url: null,
    showModal: false,
    allData: "",
    imageSrc: "",
  }),

  created() {
    this.getLoan();
  },
  methods: {
    // get the loan
    async getLoan() {
      const { data } = await axios.get(
        window.location.origin + "/api/loans/" + this.$route.params.slug
      );
      this.allData = data.data;
    },

    // print
    printWindow() {
      window.print();
    },

    // preview modal
    previewModal(image) {
      this.imageSrc = image;
      if (this.showModal) {
        return (this.showModal = false);
      }
      return (this.showModal = true);
    },

    // download pdf
    generatePDF() {
      // Get the HTML content to be converted
      const element = document.getElementById("content-to-pdf");
      // Options for PDF generation
      const options = {
        margin: 5,
        filename: "Loan-" + this.$route.params.slug + ".pdf",
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
