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
          <router-link v-if="$can('asset-edit')" :to="{
            name: 'assets.edit',
            params: { slug: allData.slug },
          }" class="btn btn-info">
            <i class="fas fa-edit" /> {{ $t("common.edit") }}
          </router-link>
          <router-link :to="{ name: 'assets.index' }" class="btn btn-dark float-right">
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
          <div v-if="allData.type" class="col-sm-6 offset-sm-2 invoice-col float-right text-md-right">
            <h5>{{ $t("assets.list.view.page_title") }}</h5>
            <strong>{{ $t("assets.common.asset_name") }}:</strong>
            {{ allData.name }}<br />
            <strong>{{ $t("assets.common.asset_type") }}:</strong>
            {{ allData.type.name }}<br />
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->

        <!-- Table row -->
        <div class="row">
          <div class="col-12">
            <div class="table-responsive table-custom">
              <table class="table">
                <thead>
                  <tr>
                    <th>{{ $t("common.preview") }}</th>
                    <th>{{ $t("assets.common.added_date") }}</th>
                    <th>{{ $t("assets.common.asset_cost") }}</th>
                    <th v-if="allData.depreciation">
                      {{ $t("assets.common.depreciation") }}
                    </th>
                    <th v-if="allData.depreciation">
                      {{ $t("assets.common.salvage_value") }}
                    </th>
                    <th v-if="allData.depreciation">
                      {{ $t("assets.common.useful_life") }}
                    </th>
                    <th v-if="allData.depreciation">
                      {{ $t("assets.common.remaining_life") }}
                    </th>
                    <th v-if="allData.depreciation == 1">
                      {{ $t("assets.common.expired_date") }}
                    </th>
                    <th>{{ $t("assets.common.current_value") }}</th>
                    <th v-if="allData.note">{{ $t("common.note") }}</th>
                    <th class="text-right">{{ $t("common.status") }}</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>
                      <a v-if="allData.image" href="#" id="show-modal" @click="showModal = true">
                        <img :src="allData.image" class="rounded preview-sm" loading="lazy" />
                      </a>
                      <div v-else class="bg-secondary rounded no-preview-sm">
                        <small>{{ $t("common.no_preview") }}</small>
                      </div>
                    </td>
                    <td v-if="allData.date">
                      {{ allData.date | moment("Do MMM, YYYY") }}
                    </td>
                    <td>{{ allData.amount | withCurrency }}</td>
                    <td v-if="allData.depreciation">
                      {{ allData.depreciationExpenseTxt }}
                    </td>
                    <td v-if="allData.depreciation">
                      {{ allData.salvageValue | withCurrency }}
                    </td>
                    <td v-if="allData.depreciation">
                      {{ allData.usefulLife }}
                      <span v-if="allData.depreciationType == 1">Year</span>
                      <span v-else>Month</span>
                    </td>
                    <td v-if="allData.depreciation">
                      {{ allData.remainingLife }}
                    </td>
                    <td v-if="allData.depreciation == 1 && allData.expireDate">
                      {{ allData.expireDate | moment("Do MMM, YYYY") }}
                    </td>
                    <td>{{ allData.currentValue | withCurrency }}</td>
                    <td v-if="allData.note">{{ allData.note }}</td>
                    <td class="text-right">
                      <span v-if="allData.status === 1" class="badge bg-success">{{ $t("common.active") }}</span>
                      <span v-else class="badge bg-danger">{{
                        $t("common.in_active")
                      }}</span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <!-- /.row -->
      </div>
    </div>
    <!-- use the modal component, pass in the prop -->
    <Modal v-if="showModal" @close="showModal = false">
      <h5 slot="header">{{ $t("common.modal_header") }}</h5>
      <div class="w-100" slot="body">
        <img :src="allData.image" class="rounded img-fluid" loading="lazy" />
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
    return { title: this.$t("assets.list.view.page_title") };
  },
  data: () => ({
    breadcrumbsCurrent: "assets.list.view.breadcrumbs_current",
    breadcrumbs: [
      {
        name: "assets.list.view.breadcrumbs_first",
        url: "home",
      },
      {
        name: "assets.list.view.breadcrumbs_second",
        url: "assets.index",
      },
      {
        name: "assets.list.view.breadcrumbs_active",
        url: "",
      },
    ],
    url: null,
    showModal: false,
    allData: "",
  }),

  created() {
    this.getAsset();
  },
  methods: {
    // get the asset
    async getAsset() {
      const { data } = await axios.get(
        window.location.origin + "/api/assets/" + this.$route.params.slug
      );
      this.allData = data.data;
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
        filename: "Asset-" + this.$route.params.slug + ".pdf",
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
