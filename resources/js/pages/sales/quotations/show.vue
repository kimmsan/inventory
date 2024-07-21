<template>
  <div>
    <!-- breadcrumbs Start -->
    <breadcrumbs :items="breadcrumbs" :current="breadcrumbsCurrent" />
    <!-- breadcrumbs end -->

    <div class="row no-print mb-2">
      <div class="w-100 text-right float-right">
        <div class="btn-group" v-if="allData">
          <a @click="notify((form.isSendSMS = true))" href="#" class="btn btn-secondary">
            <i class="fas fa-sms"></i> {{ $t("common.sms") }}
          </a>
          <a @click="notify((form.isSendEmail = true))" href="#" class="btn btn-success"><i
              class="fas fa-paper-plane"></i> {{ $t("email") }}</a>
          <a @click="generatePDF()" href="#" class="btn btn-primary">
            <i class="fas fa-download"></i> {{ $t("download") }}
          </a>
          <a @click="printWindow()" href="#" class="btn btn-secondary">
            <i class="fas fa-print"></i> {{ $t("common.print") }}
          </a>
          <router-link v-if="$can('quotation-edit')" :to="{
            name: 'quotations.edit',
            params: { slug: allData.slug },
          }" class="btn btn-info">
            <i class="fas fa-edit" /> {{ $t("common.edit") }}
          </router-link>
          <router-link :to="{ name: 'quotations.index' }" class="btn btn-dark float-right">
            <i class="fas fa-long-arrow-alt-left" /> {{ $t("common.back") }}
          </router-link>
        </div>
      </div>
    </div>

    <div class="row">
      <!-- Main content -->
      <div class="invoice p-3 mb-3 w-100" id="content-to-pdf">
        <table-loading v-show="loading" />
        <!-- info row -->
        <div class="row invoice-info">
          <div class="col-sm-4 invoice-col">
            <CompanyInfo />
          </div>
          <!-- /.col -->
          <div class="col-sm-6 offset-sm-2 invoice-col float-right text-md-right">
            <h5>{{ $t("common.client_details") }}</h5>
            <div v-if="allData.client">
              <span v-if="allData.client.companyName"><strong>{{ $t("common.client_id") }}:</strong>
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

        <div class="row mt-3">
          <div class="col-12">
            <div class="table-responsive table-custom">
              <table class="table">
                <thead>
                  <tr>
                    <th v-if="allData.quotationNo">
                      {{ $t("sales.common.quotation_no") }}
                    </th>
                    <th v-if="allData.reference">
                      {{ $t("common.reference") }}
                    </th>
                    <th v-if="allData.date">
                      {{ $t("sales.common.quotation_date") }}
                    </th>
                    <th v-if="allData.deliveryPlace">
                      {{ $t("sales.common.delivery_place") }}
                    </th>
                    <th v-if="allData.note">{{ $t("common.note") }}</th>
                    <th>{{ $t("common.status") }}</th>
                    <th class="text-right">{{ $t("common.created_by") }}</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td v-if="allData.quotationNo">
                      {{ allData.quotationNo | withPrefix(quotationPrefix) }}
                    </td>
                    <td v-if="allData.reference">
                      {{ allData.reference }}
                    </td>
                    <td v-if="allData.date">
                      {{ allData.date | moment("Do MMM, YYYY") }}
                    </td>
                    <td v-if="allData.deliveryPlace">
                      {{ allData.deliveryPlace }}
                    </td>
                    <td v-if="allData.note">{{ allData.note }}</td>
                    <td>
                      <span v-if="allData.status === 1" class="badge bg-success">{{ $t("common.active") }}</span>
                      <span v-else class="badge bg-danger">{{
                        $t("common.in_active")
                      }}</span>
                    </td>
                    <td class="text-right">
                      {{ allData.createdBy }}
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Table row -->
        <div class="row position-relative mt-4">
          <div class="col-12">
            <strong class="mb-2 d-block">{{ $t("common.products") }}:</strong>
            <div class="table-custom table-responsive">
              <table class="table table-sm">
                <thead>
                  <tr>
                    <th>{{ $t("common.s_no") }}</th>
                    <th>{{ $t("common.code") }}</th>
                    <th>{{ $t("common.product_name") }}</th>
                    <th>{{ $t("common.quantity") }}</th>
                    <th>{{ $t("common.unit_price") }}</th>
                    <th>{{ $t("common.unit_tax") }}</th>
                    <th>{{ $t("common.unit_cost") }}</th>
                    <th class="text-right">{{ $t("common.subtotal") }}</th>
                  </tr>
                </thead>
                <tbody v-if="allData.products">
                  <tr v-for="(data, i) in allData.products" :key="i">
                    <td>{{ ++i }}</td>
                    <td>
                      {{ data.productCode | withPrefix(productPrefix) }}
                    </td>
                    <td>{{ data.productName }}</td>
                    <td>{{ data.quantity }} {{ data.productUnit }}</td>
                    <td>{{ data.salePrice | withCurrency }}</td>
                    <td>{{ data.taxAmount | withCurrency }}</td>
                    <td>{{ data.unitCost | withCurrency }}</td>
                    <td class="text-right">
                      {{ data.unitCostTotal | withCurrency }}
                    </td>
                  </tr>
                  <tr>
                    <td class="text-right" colspan="7">
                      <strong>{{ $t("common.subtotal") }}</strong>
                    </td>
                    <td class="text-right">
                      <strong>{{ allData.subTotal | withCurrency }}</strong>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <!-- /.row -->
        <div class="row mt-3">
          <div class="offset-xl-8 col-lg-12 col-xl-4 text-xl-right">
            <div class="table-responsive table-custom table-border-y-0">
              <table class="table">
                <tbody>
                  <tr class="bg-sub-light text-bold">
                    <th>{{ $t("common.subtotal") }}:</th>
                    <td>{{ allData.subTotal | withCurrency }}</td>
                  </tr>
                  <tr>
                    <th>{{ $t("common.transport") }}:</th>
                    <td>
                      <span class="plus-sign">+</span>
                      {{ allData.transport | withCurrency }}
                    </td>
                  </tr>
                  <tr>
                    <th>
                      {{ $t("common.discount") }}
                      <span v-if="allData.discountPercentage > 0">({{ allData.discountPercentage }}%)</span>:
                    </th>
                    <td>
                      <span class="minus-sign">-</span>
                      {{ allData.discount | withCurrency }}
                    </td>
                  </tr>
                  <tr>
                    <th>{{ $t("common.tax") }}:</th>
                    <td>
                      <span class="plus-sign">+</span>
                      {{ allData.totalTax | withCurrency }}
                    </td>
                  </tr>
                  <tr class="bg-indigo-light">
                    <th>{{ $t("common.total") }}:</th>
                    <td>
                      <span class="equal-sign">=</span>
                      {{ allData.total | withCurrency }}
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <!-- /.row -->
      </div>
      <!-- /.invoice -->
    </div>
  </div>
</template>

<script>
import Form from "vform";
import axios from "axios";
import { mapGetters } from "vuex";
import html2pdf from "html2pdf.js";

export default {
  middleware: ["auth", "check-permissions"],
  metaInfo() {
    return { title: this.$t("sales.quotations.view.page_title") };
  },
  data: () => ({
    allData: "",
    breadcrumbsCurrent: "sales.quotations.view.breadcrumbs_current",
    breadcrumbs: [
      {
        name: "sales.quotations.view.breadcrumbs_first",
        url: "home",
      },
      {
        name: "sales.quotations.view.breadcrumbs_second",
        url: "quotations.index",
      },
      {
        name: "sales.quotations.view.breadcrumbs_active",
        url: "",
      },
    ],
    quotationProducts: [],
    quotationPrefix: "",
    clientPrefix: "",
    productPrefix: "",
    loading: false,
    form: new Form({
      isSendEmail: false,
      isSendSMS: false,
    }),
    isDemoMode: window.config.isDemoMode,
  }),
  // Map Getters
  computed: {
    ...mapGetters("operations", ["appInfo"]),
  },
  created() {
    this.getQuotation();
    this.quotationPrefix = this.appInfo.quotationPrefix;
    this.clientPrefix = this.appInfo.clientPrefix;
    this.productPrefix = this.appInfo.productPrefix;
  },
  methods: {
    // get the quotation
    async getQuotation() {
      this.loading = true;
      const { data } = await axios.get(
        window.location.origin + "/api/quotations/" + this.$route.params.slug
      );
      this.allData = data.data;
      this.quotationProducts = this.allData.products;
      this.quotationProducts.sort(this.sortProducts);
      this.loading = false;
    },
    sortProducts(a, b) {
      if (a.productCode < b.productCode) {
        return -1;
      }
      if (a.productCode > b.productCode) {
        return 1;
      }
      return 0;
    },
    // download pdf
    generatePDF() {
      // Get the HTML content to be converted
      const element = document.getElementById("content-to-pdf");

      // Options for PDF generation
      const options = {
        margin: 5,
        filename: "Quotation-" + this.$route.params.slug + ".pdf",
        image: { type: "jpeg", quality: 0.98 },
        html2canvas: { scale: 2 },
        jsPDF: { unit: "mm", format: "a4", orientation: "portrait" },
      };
      // Generate PDF from HTML content
      html2pdf().from(element).set(options).save();
      toast.fire({
        type: "success",
        title: this.$t("Download started."),
      });
    },
    // notify
    async notify() {
      if (!this.isDemoMode) {
        this.loading = true;
        await this.form
          .post(
            window.location.origin +
            "/api/quotation/notify/" +
            this.$route.params.slug
          )
          .then(() => {
            toast.fire({
              type: "success",
              title: this.$t("Notification sent successfully."),
            });
          })
          .catch(() => {
            toast.fire({ type: "error", title: this.$t("common.error_msg") });
          });
        this.loading = false;
      }
      else {
        toast.fire({
          type: "warning",
          title: this.$t("You are not allowed to do this in demo version."),
        });
      }
    },
    // print
    printWindow() {
      window.print();
    },
  },
};
</script>
