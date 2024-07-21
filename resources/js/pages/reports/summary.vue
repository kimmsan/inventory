<template>
  <div>
    <!-- breadcrumbs Start -->
    <breadcrumbs :items="breadcrumbs" :current="breadcrumbsCurrent" />
    <!-- breadcrumbs end -->
    <div class="row no-print">
      <div class="col-lg-12">
        <div class="card">
          <!-- form start -->
          <form role="form" @submit.prevent="getReportData" @keydown="form.onKeydown($event)">
            <div class="card-body">
              <div class="row">
                <div class="form-group col-md-6">
                  <label for="month"> {{ $t("common.month") }} </label>
                  <select id="month" v-model="form.month" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('month') }">
                    <option value="1">January</option>
                    <option value="2">February</option>
                    <option value="3">March</option>
                    <option value="4">April</option>
                    <option value="5">May</option>
                    <option value="6">June</option>
                    <option value="7">July</option>
                    <option value="8">August</option>
                    <option value="9">September</option>
                    <option value="10">October</option>
                    <option value="11">November</option>
                    <option value="12">December</option>
                  </select>
                  <has-error :form="form" field="month" />
                </div>
                <div class="form-group col-md-6">
                  <label for="year">{{ $t("common.year") }}</label>
                  <select id="year" v-model="form.year" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('year') }">
                    <option v-for="year in years" :key="year" :value="year">
                      {{ year }}
                    </option>
                  </select>
                  <has-error :form="form" field="year" />
                </div>
              </div>
            </div>
            <div class="card-footer">
              <v-button :loading="form.busy" class="btn btn-primary">
                <i class="fas fa-eye" /> {{ $t("reports.view_report") }}
              </v-button>
              <button type="reset" class="btn btn-secondary float-right" @click="form.reset()">
                <i class="fas fa-power-off" /> {{ $t("common.reset") }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="row no-print mb-2">
      <div class="w-100 text-right float-right">
        <div class="btn-group" v-if="reportInfo">
          <a @click="generatePDF()" href="#" class="btn btn-primary">
            <i class="fas fa-download"></i> {{ $t("download") }}
          </a>
          <a @click="printWindow()" href="#" class="btn btn-secondary">
            <i class="fas fa-print"></i> {{ $t("common.print") }}
          </a>
          <router-link :to="{ name: 'home' }" class="btn btn-dark float-right">
            <i class="fas fa-long-arrow-alt-left" /> {{ $t("common.back") }}
          </router-link>
        </div>
      </div>
    </div>
    <div v-if="reportInfo" class="row">
      <div class="invoice p-3 mb-3 w-100" id="content-to-pdf">
        <!-- info row -->
        <div class="row invoice-info">
          <div class="m-auto invoice-col">
            <CompanyInfo class="text-center" />
          </div>
        </div>
        <!-- /.row -->

        <!-- Table row -->
        <div class="row mt-3 position-relative">
          <table-loading v-show="loading" />
          <div class="table-responsive col-xl-10 m-auto">
            <table class="table table-bordered table-striped table-sm">
              <thead>
                <tr class="success text-center">
                  <th colspan="3">
                    <h5>
                      {{ $t("reports.monthly_summery") }}:
                      {{ reportInfo.monthName }}, {{ reportInfo.year }}<br />
                    </h5>
                  </th>
                </tr>
              </thead>
              <thead>
                <tr>
                  <th>{{ $t("common.s_no") }}</th>
                  <th>{{ $t("reports.particulars") }}</th>
                  <th>{{ $t("common.balance") }}</th>
                </tr>
              </thead>
              <thead>
                <tr>
                  <th colspan="3">{{ $t("reports.opening_balance") }}</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(data, i) in reportInfo.openingBalances" :key="i">
                  <td>{{ ++i }}</td>
                  <td>{{ data.bank_name }} [{{ data.account_number }}]</td>
                  <td>{{ data.current_balance | withAbsoluteCurrency }}</td>
                </tr>
                <tr>
                  <td colspan="2" class="text-right">
                    <strong>{{ $t("common.total") }}</strong>
                  </td>
                  <td>
                    <strong>
                      {{ totalOpeningBalance | withAbsoluteCurrency }}
                    </strong>
                  </td>
                </tr>
              </tbody>
              <thead>
                <tr>
                  <th colspan="3">{{ $t("sidebar.sales") }}</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>1</td>
                  <td>{{ $t("reports.invoice_sales") }}</td>
                  <td>{{ reportInfo.invoiceSales | withAbsoluteCurrency }}</td>
                </tr>
                <tr>
                  <td>2</td>
                  <td>{{ $t("reports.invoice_dues") }}</td>
                  <td>{{ reportInfo.invoiceDue | withAbsoluteCurrency }}</td>
                </tr>
              </tbody>
              <thead>
                <tr>
                  <th colspan="3">{{ $t("reports.accounts_collection") }}</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(data, i) in reportInfo.accountCollections" :key="i">
                  <td>{{ ++i }}</td>
                  <td>{{ data.bank_name }} [{{ data.account_number }}]</td>
                  <td>{{ data.total_collection | withAbsoluteCurrency }}</td>
                </tr>
                <tr>
                  <td colspan="2" class="text-right">
                    <strong>{{ $t("common.total") }}</strong>
                  </td>
                  <td>
                    <strong>{{
                      totalCollection | withAbsoluteCurrency
                    }}</strong>
                  </td>
                </tr>
              </tbody>
              <thead>
                <tr>
                  <th colspan="3">{{ $t("sidebar.expenses") }}</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td colspan="2">{{ $t("sidebar.purchase") }}</td>
                  <td>{{ reportInfo.totalPurchase | withAbsoluteCurrency }}</td>
                </tr>
                <tr>
                  <td colspan="2">{{ $t("reports.general") }}</td>
                  <td>{{ reportInfo.expenses | withAbsoluteCurrency }}</td>
                </tr>
                <tr>
                  <td colspan="2">{{ $t("sidebar.payroll") }}</td>
                  <td>{{ reportInfo.payrolls | withAbsoluteCurrency }}</td>
                </tr>
                <tr>
                  <td colspan="2">{{ $t("reports.loan_interest") }}</td>
                  <td>{{ reportInfo.loanInterest | withAbsoluteCurrency }}</td>
                </tr>
                <tr>
                  <td colspan="2">{{ $t("reports.asset_depreciation") }}</td>
                  <td>
                    {{ reportInfo.assetDepriciation | withAbsoluteCurrency }}
                  </td>
                </tr>
                <tr>
                  <td colspan="2" class="text-right">
                    <strong>{{ $t("common.total") }}</strong>
                  </td>
                  <td>
                    <strong>{{ totalExpense | withAbsoluteCurrency }}</strong>
                  </td>
                </tr>
              </tbody>
              <thead>
                <tr>
                  <th colspan="3">{{ $t("reports.transfer") }}</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(data, i) in reportInfo.balanceTransfers" :key="i">
                  <td>{{ ++i }}</td>
                  <td>
                    {{ $t("reports.transfer_from") }} [{{
                      data.debit_transaction.cashbook_account.account_number
                    }}] {{ $t("common.to") }} [{{ data.credit_transaction.cashbook_account.account_number }}]
                  </td>
                  <td>{{ data.amount | withAbsoluteCurrency }}</td>
                </tr>
                <tr>
                  <td colspan="2" class="text-right">
                    <strong>{{ $t("common.total") }}</strong>
                  </td>
                  <td>
                    <strong>{{ totalTransfer | withAbsoluteCurrency }}</strong>
                  </td>
                </tr>
              </tbody>
              <thead>
                <tr>
                  <th colspan="3">{{ $t("reports.closing_balance") }}</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(data, i) in reportInfo.closingBalances" :key="i">
                  <td>{{ ++i }}</td>
                  <td>{{ data.bank_name }} [{{ data.account_number }}]</td>
                  <td>{{ data.current_balance | withAbsoluteCurrency }}</td>
                </tr>
                <tr>
                  <td colspan="2" class="text-right">
                    <strong>{{ $t("common.total") }}</strong>
                  </td>
                  <td>
                    <strong>{{
                      totalClosingBalance | withAbsoluteCurrency
                    }}</strong>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <!-- /.row -->
      </div>
    </div>
  </div>
</template>

<script>
import Form from "vform";
import html2pdf from "html2pdf.js";

export default {
  middleware: ["auth", "check-permissions"],
  metaInfo() {
    return { title: this.$t("reports.summary.page_title") };
  },
  data: () => ({
    breadcrumbsCurrent: "reports.summary.breadcrumbs_current",
    breadcrumbs: [
      {
        name: "reports.summary.breadcrumbs_first",
        url: "home",
      },
      {
        name: "reports.summary.breadcrumbs_second",
        url: "",
      },
      {
        name: "reports.summary.breadcrumbs_active",
        url: "",
      },
    ],
    form: new Form({
      month: new Date().getMonth() + 1,
      year: new Date().getFullYear(),
      currentYear: new Date().getFullYear(),
    }),
    reportInfo: "",
    totalOpeningBalance: 0,
    totalClosingBalance: 0,
    totalCollection: 0,
    totalExpense: 0,
    totalTransfer: 0,
    loading: false,
  }),
  computed: {
    years() {
      return Array.from(
        { length: this.form.currentYear - 2020 },
        (value, index) => 2021 + index
      );
    },
  },

  methods: {
    // submit form
    async getReportData() {
      this.loading = true;
      await this.form
        .post(window.location.origin + "/api/reports/summery")
        .then((response) => {
          this.reportInfo = response.data;
          this.calculateSum(this.reportInfo);
          this.loading = false;
        })
        .catch(() => {
          toast.fire({ type: "error", title: this.$t("common.delete_failed") });
        });
      this.form.currentYear = new Date().getFullYear();
    },
    // calculate sum
    calculateSum(allData) {
      [
        this.totalOpeningBalance,
        this.totalClosingBalance,
        this.totalCollection,
        this.totalExpense,
        this.totalTransfer,
      ] = [0, 0, 0, 0, 0];
      this.totalOpeningBalance = allData.openingBalances.reduce(
        (accumulator, current) =>
          Number(accumulator) + Number(current.current_balance),
        0
      );
      this.totalClosingBalance = allData.closingBalances.reduce(
        (accumulator, current) =>
          Number(accumulator) + Number(current.current_balance),
        0
      );
      this.totalCollection = allData.accountCollections.reduce(
        (accumulator, current) =>
          Number(accumulator) + Number(current.total_collection),
        0
      );
      this.totalExpense =
        allData.expenses +
        allData.payrolls +
        allData.loanInterest +
        allData.assetDepriciation;
      this.totalTransfer = allData.balanceTransfers.reduce(
        (accumulator, current) => Number(accumulator) + Number(current.amount),
        0
      );
      return;
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
        filename: "Summary Report.pdf",
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

