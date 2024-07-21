<template>
  <div>
    <!-- breadcrumbs Start -->
    <breadcrumbs :items="breadcrumbs" :current="breadcrumbsCurrent" />
    <!-- breadcrumbs end -->

    <div class="row no-print mb-2">
      <div class="w-100 text-right float-right">
        <div class="btn-group" v-if="balanceInfo">
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

    <div class="row">
      <div class="invoice p-3 mb-3 w-100" id="content-to-pdf">
        <!-- info row -->
        <div class="row invoice-info">
          <div class="col-sm-4 m-auto invoice-col">
            <CompanyInfo class="text-center" />
          </div>
        </div>
        <!-- /.row -->

        <!-- Table row -->
        <div class="row mt-3 position-relative">
          <table-loading v-show="loading" />
          <div class="table-responsive w-100">
            <table class="table table-striped table-bordered">
              <thead>
                <tr class="text-center">
                  <th colspan="2">
                    <h5>{{ $t("reports.incomes") }}</h5>
                  </th>
                  <th class="red" colspan="2">
                    <h5>{{ $t("reports.liabilities") }}</h5>
                  </th>
                </tr>
              </thead>
              <tbody v-if="balanceInfo">
                <tr>
                  <th>{{ $t("reports.security_asset") }}=></th>
                  <th class="text-right">
                    {{ balanceInfo.assets | withAbsoluteCurrency }}
                  </th>
                  <th>{{ $t("reports.supplier_dues") }}=></th>
                  <th class="text-right">
                    {{ balanceInfo.supplierDue | withAbsoluteCurrency }}
                  </th>
                </tr>
                <tr>
                  <th>{{ $t("products.list.common.inventory_value") }}=></th>
                  <th class="text-right">
                    {{ balanceInfo.inventoryValue | withAbsoluteCurrency }}
                  </th>
                  <th>{{ $t("reports.bank_loan") }}=></th>
                  <th class="text-right">
                    {{ balanceInfo.loanDue | withAbsoluteCurrency }}
                  </th>
                </tr>
                <tr>
                  <th>{{ $t("reports.client_dues") }}=></th>
                  <th class="text-right">
                    {{ balanceInfo.clientTotalDue | withAbsoluteCurrency }}
                  </th>
                </tr>
                <tr>
                  <th>{{ $t("reports.bank_balance") }}=></th>
                  <th class="text-right">
                    {{ balanceInfo.bankBalance | withAbsoluteCurrency }}
                  </th>
                </tr>
                <tr class="text-right">
                  <th>{{ $t("common.total") }}=></th>
                  <th>
                    {{ balanceInfo.buisnessTotal | withAbsoluteCurrency }}
                  </th>
                  <th>{{ $t("common.total") }}=></th>
                  <th>
                    {{ balanceInfo.liabilities | withAbsoluteCurrency }}
                  </th>
                </tr>
              </tbody>
              <tfoot>
                <tr>
                  <td colspan="4" class="text-center">
                    <strong>{{ $t("reports.income_liabilities") }}</strong><br />
                    <h4 class="mt-2">
                      {{ $t("reports.total_asset") }}:
                      <span v-if="balanceInfo.totalAsset > 0" class="text-success">{{
                        balanceInfo.totalAsset | withAbsoluteCurrency
                      }}</span>
                      <span v-else-if="balanceInfo.totalAsset < 0" class="text-danger">{{
                        balanceInfo.totalAsset | withAbsoluteCurrency
                      }}</span>
                      <span v-else class="text-success">{{ 0 | withAbsoluteCurrency }}
                      </span>
                    </h4>
                  </td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
        <!-- /.row -->
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import html2pdf from "html2pdf.js";

export default {
  middleware: ["auth", "check-permissions"],
  metaInfo() {
    return { title: this.$t("reports.balance_sheet.page_title") };
  },
  data: () => ({
    breadcrumbsCurrent: "reports.balance_sheet.breadcrumbs_current",
    breadcrumbs: [
      {
        name: "reports.balance_sheet.breadcrumbs_first",
        url: "home",
      },
      {
        name: "reports.balance_sheet.breadcrumbs_second",
        url: "",
      },
      {
        name: "reports.balance_sheet.breadcrumbs_active",
        url: "",
      },
    ],
    loading: false,
    balanceInfo: "",
  }),

  created() {
    this.getData();
  },
  methods: {
    // get data
    async getData() {
      this.loading = true;
      const { data } = await axios.get(
        window.location.origin + "/api/reports/balance-sheet"
      );
      this.balanceInfo = data;
      this.loading = false;
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
        filename: "Balance Sheet.pdf",
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

