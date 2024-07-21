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
          <router-link v-if="$can('product-edit')" :to="{
            name: 'products.edit',
            params: { slug: allData.slug },
          }" class="btn btn-info">
            <i class="fas fa-edit" /> {{ $t("common.edit") }}
          </router-link>
          <router-link :to="{ name: 'products.index' }" class="btn btn-dark float-right">
            <i class="fas fa-long-arrow-alt-left" /> {{ $t("common.back") }}
          </router-link>
        </div>
      </div>
    </div>

    <div class="row">
      <table-loading v-show="loading" />
      <div v-if="!loading" class="invoice p-3 mb-3 w-100" id="content-to-pdf">
        <div class="row align-items-center flex-row-reverse">
          <div class="col-lg-6">
            <table class="table table-hover table-bordered table-md">
              <tbody>
                <tr>
                  <th>{{ $t('sidebar.barcode') }}</th>
                  <td>
                    <barcode :format="allData.symbology" width="1" height="25" fontSize="15" :value="allData.code">
                      {{ $t('common.rendering_fails') }}
                    </barcode>
                  </td>
                </tr>
                <tr>
                  <th>{{ $t('common.name') }}</th>
                  <td>{{ allData.name }}</td>
                </tr>
                <tr>
                  <th>{{ $t('common.code') }}</th>
                  <td>{{ allData.code | withPrefix(prefix) }}</td>
                </tr>
                <tr>
                  <th>{{ $t('common.item_model') }}</th>
                  <td>{{ allData.itemModel }}</td>
                </tr>
                <tr>
                  <th>{{ $t('products.list.common.barcode_symbology') }}</th>
                  <td>{{ allData.symbology }}</td>
                </tr>
                <tr v-if="allData.category">
                  <th>{{ $t('common.category') }}</th>
                  <td>
                    {{ allData.category.name }} [{{
                      allData.category.code | withPrefix(catPrefix)
                    }}]
                  </td>
                </tr>
                <tr v-if="allData.subCategory">
                  <th>{{ $t('common.sub_category') }}</th>
                  <td>
                    {{ allData.subCategory.name }} [{{
                      allData.subCategory.code | withPrefix(subCatPrefix)
                    }}]
                  </td>
                </tr>
                <tr v-if="allData.itemBrand">
                  <th>{{ $t('common.brand') }}</th>
                  <td>{{ allData.itemBrand.name }}</td>
                </tr>
                <tr v-if="allData.itemUnit">
                  <th>{{ $t('common.unit') }}</th>
                  <td>{{ allData.itemUnit.code }}</td>
                </tr>
                <tr v-if="allData.itemTax">
                  <th>{{ $t('products.list.common.product_tax') }}</th>
                  <td>{{ allData.itemTax.code }}</td>
                </tr>
                <tr>
                  <th>{{ $t('products.list.common.tax_type') }}</th>
                  <td>{{ allData.taxType }}</td>
                </tr>
                <tr>
                  <th>{{ $t('products.list.common.tax_amount') }}</th>
                  <td>{{ allData.taxAmount }}</td>
                </tr>
                <tr>
                  <th>{{ $t('products.list.common.regular_price') }}</th>
                  <td>{{ allData.regularPrice | withCurrency }}</td>
                </tr>
                <tr>
                  <th>{{ $t('products.list.common.selling_price') }}</th>
                  <td>
                    <span v-if="allData.discount > 0">
                      <del>{{ allData.regularPrice | withCurrency }}</del>
                      {{ allData.sellingPrice | withCurrency }} ({{
                        allData.discount
                      }}%)
                    </span>
                    <span v-else>{{ allData.regularPrice | withCurrency }}
                    </span>
                  </td>
                </tr>
                <tr>
                  <th>{{ $t('products.list.common.avg_purchase_price') }}</th>
                  <td>{{ allData.avgPurchasePrice | withCurrency }}</td>
                </tr>
                <tr v-if="allData.itemUnit">
                  <th>{{ $t('products.list.common.stock') }}</th>
                  <td>
                    {{ allData.availableQty }} {{ allData.itemUnit.code }}
                  </td>
                </tr>
                <tr>
                  <th>{{ $t('products.list.common.inventory_value') }}</th>
                  <td>
                    {{
                      (allData.avgPurchasePrice * allData.availableQty)
                      | withCurrency
                    }}
                  </td>
                </tr>
                <tr>
                  <th>{{ $t('products.list.common.alert_quantity') }}</th>
                  <td>
                    {{ allData.alertQty }}
                    <span v-if="allData.itemUnit">{{
                      allData.itemUnit.code
                    }}</span>
                  </td>
                </tr>
                <tr>
                  <th>{{ $t('common.note') }}</th>
                  <td>{{ allData.note }}</td>
                </tr>
                <tr>
                  <th>{{ $t('common.status') }}</th>
                  <td>
                    <span v-if="allData.status === 1" class="badge bg-success">{{ $t('common.active') }}</span>
                    <span v-else class="badge bg-danger">{{
                      $t('common.in_active')
                    }}</span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="col-lg-6 no-print">
            <div class="about-avatar text-center">
              <img v-if="allData.image" :src="allData.image" class="img-fluid" loading="lazy" />
              <img v-else src="https://via.placeholder.com/800x1000" class="img-fluid" loading="lazy" />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import VueBarcode from 'vue-barcode'
import { mapGetters } from 'vuex'
import axios from 'axios'
import html2pdf from "html2pdf.js";

export default {
  middleware: ['auth', 'check-permissions'],
  metaInfo() {
    return { title: this.$t('products.list.view.page_title') }
  },
  components: {
    barcode: VueBarcode,
  },
  data: () => ({
    breadcrumbsCurrent: 'products.list.view.breadcrumbs_current',
    breadcrumbs: [
      {
        name: 'products.list.view.breadcrumbs_first',
        url: 'home',
      },
      {
        name: 'products.list.view.breadcrumbs_second',
        url: 'products.index',
      },
      {
        name: 'products.list.view.breadcrumbs_active',
        url: '',
      },
    ],
    allData: '',
    loading: false,
  }),
  // Map Getters
  computed: {
    ...mapGetters('operations', ['appInfo']),
  },
  created() {
    this.getProduct()
    this.prefix = this.appInfo.productPrefix
    this.catPrefix = this.appInfo.proCatPrefix
    this.subCatPrefix = this.appInfo.proSubCatPrefix
  },

  methods: {
    // print
    printWindow() {
      window.print()
    },
    // get the product
    async getProduct() {
      this.loading = true
      const { data } = await axios.get(
        window.location.origin + '/api/products/' + this.$route.params.slug
      )
      this.allData = data.data
      this.loading = false
    },
    // download pdf
    generatePDF() {
      // Get the HTML content to be converted
      const element = document.getElementById("content-to-pdf");
      // Options for PDF generation
      const options = {
        margin: 5,
        filename: "Product-" + this.$route.params.slug + ".pdf",
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
