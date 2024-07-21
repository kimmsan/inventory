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
                <div v-if="items" class="form-group col-md-12">
                  <label for="productName">{{ $t("common.product_name") }}
                    <span class="required">*</span></label>
                  <v-select v-model="form.productName" :options="items" label="label"
                    :class="{ 'is-invalid': form.errors.has('productName') }" name="productName"
                    :placeholder="$t('common.product_name_placeholder')" />
                  <has-error :form="form" field="productName" />
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
        <div class="btn-group" v-if="allData &&
          allData.stockIns &&
          allData.stockOuts &&
          (allData.stockIns.length > 0 || allData.stockIns.stockOuts > 0)
          ">
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

    <div v-if="allData &&
      allData.stockIns &&
      allData.stockOuts &&
      (allData.stockIns.length > 0 || allData.stockIns.stockOuts > 0)
      " class="row">
      <div class="invoice p-3 mb-3 w-100" id="content-to-pdf">
        <!-- info row -->
        <div class="row invoice-info">
          <div class="col-sm-4 invoice-col">
            <CompanyInfo />
          </div>
          <!-- /.col -->
          <div v-if="allData.product &&
            allData.product.category &&
            allData.product.subCategory &&
            allData.product.itemUnit
            " class="col-sm-6 offset-sm-2 invoice-col float-right text-md-right">
            <h5>
              {{ $t("common.date") }}: {{ date | moment("Do MMM, YYYY") }}
            </h5>
            <strong>{{ $t("common.code") }}:</strong>
            {{ allData.product.code | withPrefix(prfix) }}<br />
            <strong>{{ $t("common.category") }}:</strong>
            {{ allData.product.category.name }}<br />
            <strong>{{ $t("common.sub_category") }}:</strong>
            {{ allData.product.subCategory.name }}<br />
            <strong>{{ $t("products.list.common.stock") }}:</strong>
            {{ allData.product.availableQty }}
            {{ allData.product.itemUnit.code }} <br />
          </div>
        </div>
        <hr />
        <div class="row mt-5 position-relative">
          <table-loading v-show="loading" />
          <div v-if="loading == false" class="col-lg-6 table-responsive mb-5">
            <h4>
              <i>{{ $t("reports.stock_in") }}</i>
            </h4>
            <table class="table table-bordered table-sm">
              <thead>
                <tr>
                  <th>{{ $t("common.s_no") }}</th>
                  <th>{{ $t("common.date") }}</th>
                  <th>{{ $t("reports.stock_in") }}</th>
                  <th>{{ $t("common.price") }}</th>
                  <th>{{ $t("common.type") }}</th>
                  <th>{{ $t("common.code") }}</th>
                  <th class="text-right">
                    {{ $t("common.supplier") }}/{{ $t("common.client") }}
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(data, i) in allData.stockIns" :key="i">
                  <td>{{ i + 1 }}</td>
                  <td>{{ data.date | moment("Do MMM, YYYY") }}</td>
                  <td>{{ data.quantity }}</td>
                  <td>{{ data.price | withCurrency }}</td>
                  <td>
                    <span class="badge bg-success">{{ data.type }}</span>
                  </td>
                  <td>{{ data.code }}</td>
                  <td class="text-right">
                    <span v-if="data.type === 'Purchase'">{{
                      data.supplier
                    }}</span>
                    <span v-else-if="data.type === 'Invoice Return'">{{
                      data.client
                    }}</span>
                  </td>
                </tr>
                <tr>
                  <td colspan="2" align="right">
                    <strong>{{ $t("reports.total_quantity") }}</strong>
                  </td>
                  <td v-if="allData.stockIns">
                    <strong>{{ stockInQty(allData.stockIns) }}</strong>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div v-if="loading == false" class="col-lg-6 table-responsive">
            <h4>
              <i>{{ $t("reports.stock_out") }}</i>
            </h4>
            <table class="table table-bordered table-sm">
              <thead>
                <tr>
                  <th>{{ $t("common.s_no") }}</th>
                  <th>{{ $t("common.date") }}</th>
                  <th>{{ $t("reports.stock_out") }}</th>
                  <th>{{ $t("common.price") }}</th>
                  <th>{{ $t("common.type") }}</th>
                  <th>{{ $t("common.code") }}</th>
                  <th class="text-right">{{ $t("common.client") }}</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(data, i) in allData.stockOuts" :key="i">
                  <td>{{ i + 1 }}</td>
                  <td>{{ data.date | moment("Do MMM, YYYY") }}</td>
                  <td>-{{ data.quantity }}</td>
                  <td>{{ data.price | withCurrency }}</td>
                  <td>
                    <span class="badge bg-success">{{ data.type }}</span>
                  </td>
                  <td>{{ data.code }}</td>
                  <td class="text-right">
                    <span v-if="data.type === 'Invoice'">{{
                      data.client
                    }}</span>
                    <span v-else-if="data.type === 'Purchase Return'">{{
                      data.supplier
                    }}</span>
                  </td>
                </tr>
                <tr>
                  <td colspan="2" align="right">
                    <strong>{{ $t("reports.total_quantity") }}</strong>
                  </td>
                  <td v-if="allData.stockOuts">
                    <strong>{{ stockOutQty(allData.stockOuts) }}</strong>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div v-else-if="allData.stockIns &&
      allData.stockOuts &&
      (allData.stockIns.length <= 0 || allData.stockIns.stockOuts <= 0)
      " class="row">
      <div class="col-lg-12">
        <EmptyTable />
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
    return { title: this.$t("reports.product.page_title") };
  },
  data: () => ({
    breadcrumbsCurrent: "reports.product.breadcrumbs_current",
    breadcrumbs: [
      {
        name: "reports.product.breadcrumbs_first",
        url: "home",
      },
      {
        name: "reports.product.breadcrumbs_second",
        url: "",
      },
      {
        name: "reports.product.breadcrumbs_active",
        url: "",
      },
    ],
    form: new Form({
      fromDate: String(new Date(Date.now() - 7 * 24 * 60 * 60 * 1000)),
      toDate: String(new Date()),
      productName: "",
    }),
    loading: false,
    allData: "",
    date: new Date(),
    prefix: "",
  }),

  computed: {
    ...mapGetters("operations", ["items", "appInfo"]),
  },

  created() {
    this.getItems();
    this.prfix = this.appInfo.productPrefix;
  },
  methods: {
    // get all categories
    async getItems() {
      await this.$store.dispatch("operations/allData", {
        path: "/api/all-products",
      });
    },

    // get filtered data
    async update(values) {
      this.loading = true;
      this.form.fromDate = values.from;
      this.form.toDate = values.to;
      await this.form
        .post(window.location.origin + "/api/reports/items")
        .then((response) => {
          this.allData = response.data;
          this.loading = false;
        })
        .catch(() => {
          toast.fire({ type: "error", title: this.$t("common.delete_failed") });
        });
    },

    // count stock in qty
    stockInQty(stockIns) {
      let total = stockIns.reduce(
        (accumulator, current) =>
          Number(accumulator) + Number(current.quantity),
        0
      );
      return total;
    },

    // count stock out qty
    stockOutQty(stockOuts) {
      let total = stockOuts.reduce(
        (accumulator, current) =>
          Number(accumulator) + Number(current.quantity),
        0
      );
      return total;
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
        filename: "Item Report.pdf",
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

