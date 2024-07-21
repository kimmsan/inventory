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
          <router-link v-if="$can('account-transfer-balance-edit')" :to="{
            name: 'transferBalances.edit',
            params: { slug: allData.slug },
          }" class="btn btn-info">
            <i class="fas fa-edit" /> {{ $t("common.edit") }}
          </router-link>
          <router-link :to="{ name: 'transferBalances.index' }" class="btn btn-dark float-right">
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
            <h5>{{ $t('cashbook.transfers.view.breadcrumbs_current') }}</h5>
            <strong v-if="allData.date">{{ $t('common.date') }}:</strong>
            {{ allData.date | moment('Do MMM, YYYY') }}<br />
            <strong>{{ $t('common.created_by') }}:</strong>
            {{ allData.createdBy }}
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->

        <!-- Table row -->
        <div class="row mt-5">
          <div class="table-responsive table-custom text-center">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>{{ $t('common.reason') }}</th>
                  <th>{{ $t('cashbook.common.from_account') }}</th>
                  <th>{{ $t('cashbook.common.to_account') }}</th>
                  <th>{{ $t('common.amount') }}</th>
                  <th v-if="allData.date">{{ $t('common.date') }}</th>
                  <th v-if="allData.note">{{ $t('common.note') }}</th>
                  <th>{{ $t('common.status') }}</th>
                  <th class="text-right">{{ $t('common.created_by') }}</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>{{ allData.reason }}</td>
                  <td>
                    <span v-if="allData.fromAccount">{{
                      allData.fromAccount.label
                    }}</span>
                  </td>
                  <td>
                    <span v-if="allData.toAccount">{{
                      allData.toAccount.label
                    }}</span>
                  </td>
                  <td>{{ allData.amount | withCurrency }}</td>
                  <td>
                    <span v-if="allData.date">{{
                      allData.date | moment('Do MMM, YYYY')
                    }}</span>
                  </td>
                  <td v-if="allData.note">{{ allData.note }}</td>
                  <td>
                    <span v-if="allData.status === 1" class="badge bg-success">{{ $t('common.active') }}</span>
                    <span v-else class="badge bg-danger">{{
                      $t('common.in_active')
                    }}</span>
                  </td>
                  <td class="text-right">{{ allData.createdBy }}</td>
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
import axios from 'axios'
import html2pdf from "html2pdf.js";

export default {
  middleware: ['auth', 'check-permissions'],
  metaInfo() {
    return { title: this.$t('cashbook.transfers.view.page_title') }
  },
  data: () => ({
    breadcrumbsCurrent: 'cashbook.transfers.view.breadcrumbs_current',
    breadcrumbs: [
      {
        name: 'cashbook.transfers.view.breadcrumbs_first',
        url: 'home',
      },
      {
        name: 'cashbook.transfers.view.breadcrumbs_second',
        url: '',
      },
      {
        name: 'cashbook.transfers.view.breadcrumbs_third',
        url: 'transferBalances.index',
      },
      {
        name: 'cashbook.transfers.view.breadcrumbs_active',
        url: '',
      },
    ],
    showModal: false,
    allData: '',
  }),
  created() {
    this.getTransfer()
  },
  methods: {
    // get the transfer
    async getTransfer() {
      const { data } = await axios.get(
        window.location.origin +
        '/api/balance-transfers/' +
        this.$route.params.slug
      )
      this.allData = data.data
    },

    // print
    printWindow() {
      window.print()
    },

    // download pdf
    generatePDF() {
      // Get the HTML content to be converted
      const element = document.getElementById("content-to-pdf");
      // Options for PDF generation
      const options = {
        margin: 5,
        filename: "Balance Transfer-" + this.$route.params.slug + ".pdf",
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
