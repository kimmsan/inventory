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
                <div v-if="items" class="form-group col-md-6">
                  <label for="category">{{ $t("common.category") }}
                    <span class="required">*</span></label>
                  <v-select v-model="form.category" :options="items" label="name"
                    :class="{ 'is-invalid': form.errors.has('category') }" name="category"
                    :placeholder="$t('common.category_name_placeholder')" @input="getSubCategories" />
                  <has-error :form="form" field="category" />
                </div>
                <div v-if="items" class="form-group col-md-6">
                  <label for="subCategory">{{ $t("common.sub_category_name") }}
                    <span class="required">*</span></label>
                  <v-select v-model="form.subCategory" :options="subCategories" label="name"
                    :class="{ 'is-invalid': form.errors.has('subCategory') }" name="subCategory"
                    :placeholder="$t('common.category_name_placeholder')" @input="getProducts" />
                  <has-error :form="form" field="subCategory" />
                </div>
              </div>
              <div class="row">
                <div v-if="items" class="form-group col-md-12">
                  <label for="itemName">{{ $t("common.product_name") }}
                    <span class="required">*</span></label>
                  <v-select v-model="form.itemName" :options="products" label="name"
                    :class="{ 'is-invalid': form.errors.has('itemName') }" name="itemName"
                    :placeholder="$t('common.product_name_placeholder')" />
                  <has-error :form="form" field="itemName" />
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
        <div class="btn-group" v-if="inventoryData && inventoryItems(inventoryData) > 0">
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

    <div v-if="inventoryData && inventoryItems(inventoryData) > 0" class="row">
      <div class="invoice p-3 mb-3 w-100" id="content-to-pdf">
        <div class="row invoice-info">
          <div class="col-sm-4 invoice-col">
            <CompanyInfo />
          </div>
          <div class="col-sm-6 offset-sm-2 invoice-col float-right text-md-right">
            <h5>{{ $t("reports.inventory.page_title") }}</h5>
            <br />
            <span><strong>{{ $t("common.date") }}:</strong>
              {{ date | moment("Do MMM, YYYY") }}</span><br />
            <span v-if="form.itemName"><strong>{{ $t("common.product_name") }}:</strong>
              {{ form.itemName.name }}<br /></span>
            <strong>{{ $t("common.category_name") }}:</strong>
            {{ form.category.name }}<br />
            <span v-if="form.subCategory"><strong>{{ $t("common.sub_category_name") }}:</strong>
              {{ form.subCategory.name }}<br /></span>
            <strong>{{ $t("reports.date_range") }}:</strong>
            {{ form.fromDate | moment("Do MMM, YYYY") }} -
            {{ form.toDate | moment("Do MMM, YYYY") }} <br />
          </div>
        </div>

        <div class="row mt-5 position-relative">
          <table-loading v-show="loading" />
          <div v-if="loading == false" class="table-responsive table-custom">
            <table class="table table-sm">
              <thead>
                <tr>
                  <th>{{ $t("common.s_no") }}</th>
                  <th>{{ $t("common.code") }}</th>
                  <th>{{ $t("common.name") }}</th>
                  <th>{{ $t("reports.stock_in") }}</th>
                  <th>{{ $t("reports.stock_out") }}</th>
                  <th>{{ $t("reports.stock_in_hand") }}</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(data, i) in inventoryData" :key="i">
                  <td>{{ ++i }}</td>
                  <td>{{ data.productCode | withPrefix(prefix) }}</td>
                  <td>{{ data.productName }}</td>
                  <td>{{ data.stockIn }}</td>
                  <td>{{ data.stockOut }}</td>
                  <td>{{ data.availableStock }}</td>
                </tr>
                <tr>
                  <td colspan="3" class="text-right">
                    <strong>{{ $t("reports.total_quantity") }}</strong>
                  </td>
                  <td>
                    <strong>{{ stockIn }}</strong>
                  </td>
                  <td>
                    <strong>{{ stockOut }}</strong>
                  </td>
                  <td>
                    <strong>{{ stockInHand }}</strong>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div v-else-if="inventoryData && inventoryItems(inventoryData) <= 0" class="row">
      <div class="col-lg-12 col-xl-10 offset-xl-1">
        <EmptyTable />
      </div>
    </div>
  </div>
</template>

<script>
import Form from "vform";
import axios from "axios";
import { mapGetters } from "vuex";
import html2pdf from "html2pdf.js";
import "vue-mj-daterangepicker/dist/vue-mj-daterangepicker.css";

export default {
  middleware: ["auth", "check-permissions"],
  metaInfo() {
    return { title: this.$t("reports.inventory.page_title") };
  },
  data: () => ({
    breadcrumbsCurrent: "reports.inventory.breadcrumbs_current",
    breadcrumbs: [
      {
        name: "reports.inventory.breadcrumbs_first",
        url: "home",
      },
      {
        name: "reports.inventory.breadcrumbs_second",
        url: "",
      },
      {
        name: "reports.inventory.breadcrumbs_active",
        url: "",
      },
    ],
    form: new Form({
      fromDate: String(new Date(Date.now() - 7 * 24 * 60 * 60 * 1000)),
      toDate: String(new Date()),
      category: "",
      subCategory: "",
      itemName: "",
    }),
    loading: false,
    subCategories: [],
    products: [],
    date: new Date(),
    inventoryData: "",
    stockIn: 0,
    stockOut: 0,
    stockInHand: 0,
    prefix: "",
  }),

  computed: {
    ...mapGetters("operations", ["items", "appInfo"]),
  },

  created() {
    this.getCategories();
    this.prefix = this.appInfo.productPrefix;
  },
  methods: {
    // get all categories
    async getCategories() {
      await this.$store.dispatch("operations/allData", {
        path: "/api/all-product-categories",
      });
      this.items.unshift({
        id: 0,
        name: "All Categories",
        slug: "all",
      });
    },

    // get sub categories for a category
    async getSubCategories() {
      this.subCategories = [];
      this.form.subCategory = "";

      let slug = this.form.category.slug;
      const { data } = await axios.get(
        window.location.origin + "/api/pro-sub-categories-by-category/" + slug
      );
      this.subCategories = data.cats;
      this.products = data.products;

      if (this.subCategories.length > 0) {
        this.subCategories.unshift({
          id: 0,
          name: "All Sub Categories",
          slug: "all",
        });
      }

      if (this.products.length > 0) {
        this.products.unshift({
          id: 0,
          name: "All Items",
          slug: "all",
        });
      }
    },

    // get products for a sub category
    async getProducts() {
      this.products = [];
      this.form.itemName = "";
      let catSlug = this.form.category.slug;
      let subCatSlug = this.form.subCategory.slug;
      const { data } = await axios.get(
        window.location.origin +
        "/api/products-by-sub-categories/" +
        catSlug +
        "/" +
        subCatSlug
      );
      this.products = data.data;
      if (this.products.length > 0) {
        this.products.unshift({
          id: 0,
          name: "All Items",
          slug: "all",
        });
      }
    },

    // get filtered data
    async update(values) {
      this.loading = true;
      this.form.fromDate = values.from;
      this.form.toDate = values.to;
      await this.form
        .post(window.location.origin + "/api/reports/inventory")
        .then((response) => {
          this.inventoryData = response.data;
          this.calculateSum(this.inventoryData);
        })
        .catch(() => {
          toast.fire({ type: "error", title: this.$t("common.delete_failed") });
        });
      this.loading = false;
    },

    // count inventory items
    inventoryItems(obj) {
      var size = 0,
        key;
      for (key in obj) {
        if (obj.hasOwnProperty(key)) size++;
      }
      return size;
    },

    // calculate sum qty
    calculateSum(inventory) {
      let [itemIn, itemOut, itemInHand, key] = [0, 0, 0, 0];
      for (key in inventory) {
        if (inventory.hasOwnProperty(key)) {
          itemIn += Number(inventory[key].stockIn);
          itemOut += Number(inventory[key].stockOut);
          itemInHand += Number(inventory[key].availableStock);
        }
      }
      this.stockIn = itemIn;
      this.stockOut = itemOut;
      this.stockInHand = itemInHand;
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
        filename: "Inventory Report.pdf",
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
