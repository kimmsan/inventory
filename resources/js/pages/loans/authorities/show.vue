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
          <router-link v-if="$can('loan-authority-edit')" :to="{
            name: 'authorities.edit',
            params: { slug: allData.slug },
          }" class="btn btn-info">
            <i class="fas fa-edit" /> {{ $t("common.edit") }}
          </router-link>
          <router-link :to="{ name: 'authorities.index' }" class="btn btn-dark float-right">
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
            <h5>{{ $t('loans.common.authority_details') }}</h5>
            <strong>{{ $t('common.name') }}:</strong> {{ allData.name }}<br />
            <strong>{{ $t('common.email') }}:</strong> {{ allData.email
            }}<br />
            <strong>{{ $t('common.contact_number') }}:</strong>
            {{ allData.contactNumber }}<br />
            <strong>{{ $t('common.address') }}:</strong> {{ allData.address
            }}<br />
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->

        <!-- Table row -->
        <div class="row">
          <div v-if="allData.loans && allData.loans.length > 0" class="col-12">
            <strong class="mt-4 mb-2 d-block">{{ $t('loans.common.all_loans') }}:</strong>
            <div class="table-responsive table-custom">
              <table class="table">
                <thead>
                  <tr>
                    <th>{{ $t('common.s_no') }}</th>
                    <th>{{ $t('common.ref_no') }}</th>
                    <th>{{ $t('common.account') }}</th>
                    <th>{{ $t('common.amount') }}</th>
                    <th>{{ $t('common.payable') }}</th>
                    <th>{{ $t('common.interest') }}</th>
                    <th>{{ $t('common.due') }}</th>
                    <th>{{ $t('common.installment') }}</th>
                    <th>{{ $t('common.status') }}</th>
                    <th class="text-right">{{ $t('common.date') }}</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(data, i) in allData.loans" :key="i">
                    <td>{{ ++i }}</td>
                    <td>{{ data.reference }}</td>
                    <td>
                      {{ data.transaction.cashbook_account.account_number }}
                    </td>
                    <td>{{ data.transaction.amount | withCurrency }}</td>
                    <td>{{ data.payable | withCurrency }}</td>
                    <td>
                      <span v-if="data.loanType == 1">
                        {{ data.interestAmount | withCurrency }} ({{
                          data.interestRate
                        }}%)
                      </span>
                      <span v-else>{{ 0 | withCurrency }}</span>
                    </td>
                    <td>{{ data.due | withCurrency }}</td>
                    <td>{{ data.installment }}</td>
                    <td>
                      <span v-if="data.status === 1" class="badge bg-success">{{ $t('common.active') }}</span>
                      <span v-else class="badge bg-danger">{{
                        $t('common.in_active')
                      }}</span>
                    </td>
                    <td class="text-right">
                      {{ data.date | moment('Do MMM, YYYY') }}
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="callout callout-danger mt-3 w-100" v-else>
            <h5>{{ $t('loans.common.empty_loan') }}</h5>
            <p>{{ $t('loans.common.empty_loan_msg') }}</p>
          </div>
        </div>

        <!-- /.row -->
        <div class="row mt-4">
          <div class="col-lg-12 col-xl-8">
            <div class="table-responsive table-custom">
              <table class="table">
                <thead>
                  <tr>
                    <th v-if="allData.createdAt">
                      {{ $t('common.created_at') }}
                    </th>
                    <th v-if="allData.note">{{ $t('common.note') }}</th>
                    <th class="text-right">{{ $t('common.status') }}</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td v-if="allData.createdAt">
                      {{ allData.createdAt | moment('Do MMM, YYYY') }}
                    </td>
                    <td v-if="allData.note">{{ allData.note }}</td>
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
          <!-- /.col -->
          <div class="col-lg-12 col-xl-4 text-lg-right">
            <div class="table-responsive table-custom table-border-y-0">
              <table class="table">
                <tbody>
                  <tr class="bg-sub-light text-bold">
                    <th>{{ $t('loans.common.cc_limit') }}:</th>
                    <td>{{ allData.ccLimit | withCurrency }}</td>
                  </tr>
                  <tr>
                    <th>{{ $t('loans.common.cc_taken') }}:</th>
                    <td>
                      <span class="minus-sign">-</span>{{ allData.ccLoanTaken | withCurrency }}
                    </td>
                  </tr>
                  <tr class="bg-indigo-light">
                    <th>{{ $t('loans.common.available_cc') }}:</th>
                    <td>
                      <span class="equal-sign">=</span>
                      {{ allData.availableAmount | withCurrency }}
                    </td>
                  </tr>
                  <tr>
                    <th>
                      {{ $t('loans.common.total_loan') }}:<br />
                      <small>({{ $t('loans.common.cc_term') }})</small>
                    </th>
                    <td>{{ allData.totalLoan | withCurrency }}</td>
                  </tr>
                  <tr>
                    <th>{{ $t('loans.common.total_payable') }}:</th>
                    <td>{{ allData.totalPayable | withCurrency }}</td>
                  </tr>
                  <tr>
                    <th>{{ $t('loans.common.interetest_paid') }}:</th>
                    <td>{{ allData.interestPaid | withCurrency }}</td>
                  </tr>
                  <tr>
                    <th>{{ $t('common.total_paid') }}:</th>
                    <td>
                      <span class="minus-sign">-</span>
                      {{ allData.totalPaid | withCurrency }}
                    </td>
                  </tr>
                  <tr class="bg-red-light">
                    <th>{{ $t('common.total_due') }}:</th>
                    <td>{{ allData.totalDue | withCurrency }}</td>
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
  </div>
</template>

<script>
import axios from 'axios'
import html2pdf from "html2pdf.js";

export default {
  middleware: ['auth', 'check-permissions'],
  metaInfo() {
    return { title: this.$t('loans.authorities.view.page_title') }
  },
  data: () => ({
    breadcrumbsCurrent: 'loans.authorities.view.breadcrumbs_current',
    breadcrumbs: [
      {
        name: 'loans.authorities.view.breadcrumbs_first',
        url: 'home',
      },
      {
        name: 'loans.authorities.view.breadcrumbs_second',
        url: 'authorities.index',
      },
      {
        name: 'loans.authorities.view.breadcrumbs_active',
        url: '',
      },
    ],
    allData: '',
  }),

  created() {
    this.getLoanAuthority()
  },
  methods: {
    // get the Loan Authority
    async getLoanAuthority() {
      const { data } = await axios.get(
        window.location.origin +
        '/api/loan-authorities/' +
        this.$route.params.slug
      )
      this.allData = data.data
    },
    // print
    printWindow() {
      window.print()
    },

    // display modal
    previewModal(image) {
      this.imagePath = image
      if (this.showModal) {
        return (this.showModal = false)
      }
      return (this.showModal = true)
    },

    // download pdf
    generatePDF() {
      // Get the HTML content to be converted
      const element = document.getElementById("content-to-pdf");
      // Options for PDF generation
      const options = {
        margin: 5,
        filename: "Loan Authority-" + this.$route.params.slug + ".pdf",
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

