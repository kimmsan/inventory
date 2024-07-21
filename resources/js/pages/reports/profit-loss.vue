<template>
  <div>
    <!-- breadcrumbs Start -->
    <breadcrumbs :items="breadcrumbs" :current="breadcrumbsCurrent" />
    <!-- breadcrumbs end -->
    <div class="row no-print">
      <div class="col-lg-12">
        <div class="card">
          <!-- form start -->
          <form role="form" @submit.prevent="saveType" @keydown="form.onKeydown($event)">
            <div class="card-body">
              <div class="row">
                <div class="form-group col-md-12">
                  <label for="reportType">{{
                    $t("reports.report_type")
                  }}</label>
                  <select id="reportType" v-model="form.reportType" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('reportType') }">
                    <option value="1">
                      {{ $t("reports.gross_profit_Loss") }}
                    </option>
                    <option value="0">
                      {{ $t("reports.net_profit_Loss") }}
                    </option>
                  </select>
                  <has-error :form="form" field="reportType" />
                </div>
              </div>
              <div class="col-12">
                <template :class="w - 100">
                  <date-range-picker :from="form.fromDate" :to="form.toDate" :panel="$route.query.panel"
                    @update="update" />
                </template>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="row no-print mb-2">
      <div class="w-100 text-right float-right">
        <div class="btn-group" v-if="allData && allData.length > 0">
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

    <div v-if="allData && allData.length > 0" class="row">
      <div class="invoice p-3 mb-3 w-100" id="content-to-pdf">
        <!-- info row -->
        <div class="row invoice-info">
          <div class="m-auto invoice-col">
            <CompanyInfo class="text-center" />
          </div>
        </div>
        <!-- /.row -->
        <hr />

        <div v-if="reportType === 1" class="row mt-5 position-relative">
          <table-loading v-show="loading" />
          <div class="table-responsive table-custom mb-2" v-if="loading == false">
            <table class="table table-sm">
              <thead>
                <tr>
                  <th>{{ $t("common.s_no") }}</th>
                  <th>{{ $t("common.code") }}</th>
                  <th>{{ $t("common.name") }}</th>
                  <th>{{ $t("products.list.common.avg_purchase_price") }}</th>
                  <th>{{ $t("products.list.common.avg_selling_price") }}</th>
                  <th>{{ $t("reports.sold_qty") }}</th>
                  <th class="text-right">
                    <strong>
                      <span class="green">{{ $t("reports.profit") }}</span> /
                      <span class="red">{{ $t("reports.loss") }}</span>
                    </strong>
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(data, i) in grossItems" :key="i">
                  <td>{{ ++i }}</td>
                  <td>{{ data.itemCode | withPrefix(productPrefix) }}</td>
                  <td>{{ data.itemName }}</td>
                  <td>{{ data.avgPurchasePrice | withAbsoluteCurrency }}</td>
                  <td>{{ data.avgSalePrice | withAbsoluteCurrency }}</td>
                  <td>{{ data.currentQty }}</td>
                  <td class="text-right">
                    <strong>
                      <span v-if="data.profitOrLoss >= 0" class="green">{{
                        data.profitOrLoss
                      }}</span>
                      <span v-else class="red">{{ data.profitOrLoss }}</span>
                    </strong>
                  </td>
                </tr>
                <tr>
                  <td colspan="5" class="text-right">
                    <strong>{{ $t("common.total") }}</strong>
                  </td>
                  <td>
                    <strong>{{ totalQty }}</strong>
                  </td>
                  <td class="text-right">
                    <strong>{{
                      totalProfitOrLoss | withAbsoluteCurrency
                    }}</strong>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="table-responsive table-custom" v-if="loading == false">
            <table class="table">
              <tbody>
                <tr>
                  <th>{{ $t("reports.total_sales_avg") }}</th>
                  <td></td>
                  <td class="text-right">
                    <strong>{{ totalSold | withAbsoluteCurrency }}</strong>
                  </td>
                </tr>
                <tr>
                  <td colspan="3"></td>
                </tr>
                <tr>
                  <th>{{ $t("reports.total_purchased_avg") }}</th>
                  <td></td>
                  <td class="text-right">
                    <u><strong>({{ totalPurchased | withAbsoluteCurrency }})</strong></u>
                  </td>
                </tr>
                <tr :class="totalProfitOrLoss >= 0 ? 'green' : 'red'">
                  <th>
                    <span v-if="totalProfitOrLoss >= 0">{{
                      $t("reports.profit")
                    }}</span>
                    <span v-else>{{ $t("reports.loss") }}</span>
                  </th>
                  <td></td>
                  <td class="text-right">
                    <strong>{{
                      totalProfitOrLoss | withAbsoluteCurrency
                    }}</strong>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <div v-else class="row mt-5 position-relative">
          <table-loading v-show="loading" />
          <div class="table-responsive table-custom">
            <table class="table text-left">
              <thead>
                <tr class="success text-center">
                  <th colspan="3">
                    <h5>{{ $t("reports.income_statement") }} <br /></h5>
                  </th>
                </tr>
                <tr class="text-center">
                  <td colspan="3">
                    <strong>{{ $t("common.from") }}
                      {{ form.fromDate | moment("Do MMM, YYYY") }}
                      {{ $t("common.to") }}
                      {{ form.toDate | moment("Do MMM, YYYY") }}</strong>
                  </td>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <th>{{ $t("reports.total_sales") }}</th>
                  <td></td>
                  <td class="text-right">
                    <strong>{{
                      allData[0].totalSales | withAbsoluteCurrency
                    }}</strong>
                  </td>
                </tr>
                <tr>
                  <td colspan="3"></td>
                </tr>
                <tr>
                  <th>{{ $t("reports.cost_of_goods_sold") }}</th>
                  <td></td>
                  <td class="text-right">
                    <u>
                      <strong>({{
                        allData[0].costOfGoodsSold | withAbsoluteCurrency
                      }})</strong>
                    </u>
                  </td>
                </tr>
                <tr>
                  <th colspan="3">
                    {{ $t("sidebar.inventory_adjustment") }}
                  </th>
                </tr>
                <tr class="text-success">
                  <td>{{ $t("reports.positive_adjusted") }}</td>
                  <td class="text-right">
                    <u><strong>{{
                      allData[0].posAdjustment | withAbsoluteCurrency
                    }}</strong></u>
                  </td>
                  <td></td>
                </tr>
                <tr class="text-danger">
                  <td>{{ $t("reports.negative_adjusted") }}</td>
                  <td class="text-right">
                    <u><strong>({{
                      allData[0].negAdjustment | withAbsoluteCurrency
                    }})</strong>
                    </u>
                  </td>
                  <td></td>
                </tr>
                <tr>
                  <th>{{ $t("reports.total_adjusted") }}</th>
                  <td></td>
                  <td class="text-right" :class="allData[0].totalAdjustment >= 0
                    ? 'text-success'
                    : 'text-danger'
                    ">
                    <strong v-if="allData[0].totalAdjustment >= 0">{{
                      allData[0].totalAdjustment | withAbsoluteCurrency
                    }}</strong>
                    <strong v-else>({{
                      allData[0].totalAdjustment | withAbsoluteCurrency
                    }})</strong>
                  </td>
                </tr>
                <tr>
                  <th>
                    <span v-if="allData[0].grossProfitOrLoss > 0">{{
                      $t("reports.gross_profit")
                    }}</span>
                    <span v-else>{{ $t("reports.gross_loss") }}</span>
                  </th>
                  <td></td>
                  <td class="text-right">
                    <strong>{{
                      allData[0].grossProfitOrLoss | withAbsoluteCurrency
                    }}</strong>
                  </td>
                </tr>
                <tr>
                  <td colspan="3"></td>
                </tr>
                <tr>
                  <th colspan="3">{{ $t("reports.operating_expenses") }}</th>
                </tr>
                <tr>
                  <td>{{ $t("reports.salaries") }}</td>
                  <td class="text-right">
                    <strong>{{
                      allData[0].payrollAmount | withAbsoluteCurrency
                    }}</strong>
                  </td>
                  <td></td>
                </tr>
                <tr>
                  <td>{{ $t("reports.general_expenses") }}</td>
                  <td class="text-right">
                    <strong>{{
                      allData[0].expenseAmount | withAbsoluteCurrency
                    }}</strong>
                  </td>
                  <td></td>
                </tr>
                <tr>
                  <td>{{ $t("reports.loan_interest") }}</td>
                  <td class="text-right">
                    <strong>{{
                      allData[0].loanInterest | withAbsoluteCurrency
                    }}</strong>
                  </td>
                  <td></td>
                </tr>
                <tr>
                  <td>{{ $t("reports.asset_depriciation") }}</td>
                  <td class="text-right">
                    <strong>{{
                      allData[0].assetDepriciation | withAbsoluteCurrency
                    }}</strong>
                  </td>
                  <td></td>
                </tr>
                <tr>
                  <th>{{ $t("reports.total_expense") }}</th>
                  <td></td>
                  <td class="text-right">
                    <strong>({{
                      allData[0].totalExpense | withAbsoluteCurrency
                    }})</strong>
                  </td>
                </tr>
                <tr :class="allData[0].netProfitOrLoss >= 0
                      ? 'text-success'
                      : 'text-danger'
                    ">
                  <th>
                    <span v-if="allData[0].netProfitOrLoss >= 0">{{
                      $t("reports.net_profit")
                    }}</span>
                    <span v-else>{{ $t("reports.net_loss") }}</span>
                  </th>
                  <td></td>
                  <td class="text-right">
                    <strong>
                      {{ allData[0].netProfitOrLoss | withAbsoluteCurrency }}
                    </strong>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div v-else-if="allData && allData.length <= 0" class="row">
      <div class="col-lg-12 col-xl-10 offset-xl-1">
        <div class="alert alert-secondary">
          <h5>
            <i class="icon fas fa-info"></i>
            {{ $t("reports.profit_loss.empty_msg_title") }}
          </h5>
          {{ $t("reports.profit_loss.empty_msg_text") }}
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Form from "vform";
import { mapGetters } from "vuex";
import html2pdf from "html2pdf.js";
import "vue-mj-daterangepicker/dist/vue-mj-daterangepicker.css";

export default {
  middleware: ["auth", "check-permissions"],
  metaInfo() {
    return { title: this.$t("reports.profit_loss.page_title") };
  },
  data: () => ({
    breadcrumbsCurrent: "reports.profit_loss.breadcrumbs_current",
    breadcrumbs: [
      {
        name: "reports.profit_loss.breadcrumbs_first",
        url: "home",
      },
      {
        name: "reports.profit_loss.breadcrumbs_second",
        url: "",
      },
      {
        name: "reports.profit_loss.breadcrumbs_active",
        url: "",
      },
    ],
    form: new Form({
      fromDate: String(new Date(Date.now() - 7 * 24 * 60 * 60 * 1000)),
      toDate: String(new Date()),
      reportType: 1,
    }),
    loading: false,
    allData: "",
    reportType: "",
    totalQty: 0,
    grossItems: [],
    totalProfitOrLoss: 0,
    totalPurchased: 0,
    totalSold: 0,
    date: new Date(),
    productPrefix: "",
  }),

  // Map Getters
  computed: {
    ...mapGetters("operations", ["appInfo"]),
  },

  methods: {
    // get filtered data
    async update(values) {
      this.form.fromDate = values.from;
      this.form.toDate = values.to;
      this.productPrefix = this.appInfo.productPrefix;
      this.loading = true;
      await this.form
        .post(window.location.origin + "/api/reports/profit-loss")
        .then((response) => {
          this.allData = response.data.reportData;
          this.reportType = response.data.type;
          if (this.reportType == 1) {
            this.calculateTotal(this.allData);
            this.grossItems = this.allData;
            this.grossItems.sort(this.sortProducts);
          }
          this.loading = false;
        })
        .catch(() => {
          toast.fire({ type: "error", title: this.$t("common.delete_failed") });
        });
    },

    sortProducts(a, b) {
      if (a.code < b.code) {
        return -1;
      }
      if (a.code > b.code) {
        return 1;
      }
      return 0;
    },

    // calculate total
    calculateTotal(items) {
      [
        this.totalQty,
        this.totalProfitOrLoss,
        this.totalPurchased,
        this.totalSold,
      ] = [0, 0, 0, 0];
      items.forEach((item) => {
        this.totalQty += item.currentQty;
        this.totalProfitOrLoss += item.profitOrLoss;
        this.totalPurchased += item.avgPurchasePrice * item.currentQty;
        this.totalSold += item.avgSalePrice * item.currentQty;
      });
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
        filename: "Profit/Loss Report.pdf",
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

