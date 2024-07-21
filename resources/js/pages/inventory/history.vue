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
          <router-link :to="{ name: 'inventory.index' }" class="btn btn-dark float-right">
            <i class="fas fa-long-arrow-alt-left" /> {{ $t("common.back") }}
          </router-link>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="invoice p-3 mb-3 w-100" id="content-to-pdf">
        <!-- info row -->
        <div class="row invoice-info">
          <div class="col-sm-4 invoice-col">
            <CompanyInfo />
          </div>

          <!-- /.col -->
          <div v-if="product &&
            product.category &&
            product.subCategory &&
            product.itemUnit
            " class="col-sm-6 offset-sm-2 invoice-col float-right text-md-right">
            <p>
              {{ $t('common.date') }}: {{ date | moment('Do MMM, YYYY') }}
            </p>
            <h5>{{ $t('products.list.view.page_title') }}</h5>
            <strong>{{ $t('common.code') }}:</strong>
            {{ product.code | withPrefix(productPrefix) }}<br />
            <strong>{{ $t('common.name') }}:</strong> {{ product.name }}<br />
            <strong>{{ $t('common.category') }}:</strong>
            {{ product.category.name }}<br />
            <strong>{{ $t('common.sub_category') }}:</strong>
            {{ product.subCategory.name }}<br />
            <strong>{{ $t('products.list.common.stock') }}:</strong>
            {{ product.availableQty }} {{ product.itemUnit.code }} <br />
          </div>
          <!-- /.col -->
        </div>
        <hr />
        <div class="row mt-5 position-relative">
          <table-loading v-show="loading" />
          <div class="col-lg-6">
            <h4 align="center">
              <i>{{ $t('reports.stock_in') }}</i>
            </h4>
            <div class="table-responsive table-custom">
              <table class="table table-sm">
                <thead>
                  <tr>
                    <th>{{ $t('common.s_no') }}</th>
                    <th>{{ $t('common.date') }}</th>
                    <th>{{ $t('reports.stock_in') }}</th>
                    <th>{{ $t('common.price') }}</th>
                    <th>{{ $t('common.type') }}</th>
                    <th>{{ $t('common.code') }}</th>
                    <th>
                      {{ $t('common.supplier') }}/{{ $t('common.client') }}
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(data, i) in allData.stockIns" :key="i">
                    <td>{{ i + 1 }}</td>
                    <td>{{ data.date | moment('Do MMM, YYYY') }}</td>
                    <td>{{ data.quantity }}</td>
                    <td>{{ data.price | withCurrency }}</td>
                    <td>
                      <span class="badge bg-success">{{ data.type }}</span>
                    </td>
                    <td>{{ data.code }}</td>
                    <td>
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
                      <strong>{{ $t('reports.total_quantity') }}</strong>
                    </td>
                    <td v-if="allData.stockIns" colspan="5">
                      <strong>{{ stockInQty(allData.stockIns) }}</strong>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-lg-6">
            <h4 align="center">
              <i>{{ $t('reports.stock_out') }}</i>
            </h4>
            <div class="table-responsive table-custom">
              <table class="table table-sm">
                <thead>
                  <tr>
                    <th>{{ $t('common.s_no') }}</th>
                    <th>{{ $t('common.date') }}</th>
                    <th>{{ $t('reports.stock_out') }}</th>
                    <th>{{ $t('common.price') }}</th>
                    <th>{{ $t('common.type') }}</th>
                    <th>{{ $t('common.code') }}</th>
                    <th>
                      {{ $t('common.client') }}/{{ $t('common.supplier') }}
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(data, i) in allData.stockOuts" :key="i">
                    <td>{{ i + 1 }}</td>
                    <td>{{ data.date | moment('Do MMM, YYYY') }}</td>
                    <td>-{{ data.quantity }}</td>
                    <td>{{ data.price | withCurrency }}</td>
                    <td>
                      <span class="badge bg-success">{{ data.type }}</span>
                    </td>
                    <td>{{ data.code }}</td>
                    <td>
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
                      <b><i>{{ $t('reports.total_quantity') }}: </i></b>
                    </td>
                    <td v-if="allData.stockOuts" colspan="5">
                      <b><i>- {{ stockOutQty(allData.stockOuts) }} </i></b>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
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
    return { title: this.$t('inventory.common.inventory_history') }
  },
  data: () => ({
    breadcrumbsCurrent: 'inventory.history.breadcrumbs_current',
    breadcrumbs: [
      {
        name: 'inventory.history.breadcrumbs_first',
        url: 'home',
      },
      {
        name: 'inventory.history.breadcrumbs_second',
        url: 'inventory.index',
      },
      {
        name: 'inventory.history.breadcrumbs_active',
        url: '',
      },
    ],
    allData: '',
    product: '',
    date: new Date(),
    loading: false,
    productPrefix: '',
  }),
  // Map Getters
  computed: {
    ...mapGetters('operations', ['appInfo']),
  },
  created() {
    this.getHistory()
    this.productPrefix = this.appInfo.productPrefix
  },
  methods: {
    // print
    printWindow() {
      window.print()
    },

    // get the product
    async getHistory() {
      this.loading = true
      const { data } = await axios.get(
        window.location.origin +
        '/api/inventory-history/' +
        this.$route.params.slug
      )
      this.allData = data
      this.product = data.product
      this.loading = false
    },

    // count stock in qty
    stockInQty(stockIns) {
      let total = stockIns.reduce(
        (accumulator, current) =>
          Number(accumulator) + Number(current.quantity),
        0
      )
      return total
    },

    // count stock out qty
    stockOutQty(stockOuts) {
      let total = stockOuts.reduce(
        (accumulator, current) =>
          Number(accumulator) + Number(current.quantity),
        0
      )
      return total
    },

    // download pdf
    generatePDF() {
      // Get the HTML content to be converted
      const element = document.getElementById("content-to-pdf");
      // Options for PDF generation
      const options = {
        margin: 5,
        filename: "Inventory History-" + this.$route.params.slug + ".pdf",
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

