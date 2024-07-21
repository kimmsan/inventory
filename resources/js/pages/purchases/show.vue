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
          <router-link v-if="$can('purchase-edit')" :to="{
            name: 'purchases.edit',
            params: { slug: allData.slug },
          }" class="btn btn-info">
            <i class="fas fa-edit" /> {{ $t("common.edit") }}
          </router-link>
          <router-link :to="{ name: 'purchases.index' }" class="btn btn-dark float-right">
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
            <h5>{{ $t("common.supplier_details") }}</h5>
            <div v-if="allData.supplier">
              <strong>{{ $t("common.supplier_id") }}:</strong>
              {{ allData.supplier.supplierID | withPrefix(supplierPrefix)
              }}<br />
              <strong>{{ $t("common.supplier_name") }}:</strong>
              {{ allData.supplier.name }}<br />
              <span v-if="allData.supplier.companyName"><strong>{{ $t("common.company_name") }}:</strong>
                {{ allData.supplier.companyName }}<br /></span>
              <span v-if="allData.supplier.email"><strong>{{ $t("common.email") }}:</strong>
                {{ allData.supplier.email }}<br /></span>
              <span v-if="allData.supplier.phoneNumber"><strong>{{ $t("common.contact_number") }}:</strong>
                {{ allData.supplier.phoneNumber }}<br /></span>
              <span v-if="allData.supplier.address"><strong>{{ $t("common.address") }}:</strong>
                {{ allData.supplier.address }}<br /></span>
            </div>
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->

        <div class="row mt-3">
          <div class="col-12">
            <div class="table-responsive table-custom">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th v-if="allData.purchaseNo">
                      {{ $t("purchases.list.common.purchase_no") }}
                    </th>
                    <th v-if="allData.poReference">
                      {{ $t("purchases.list.common.po_reference") }}
                    </th>
                    <th v-if="allData.paymentTerms">
                      {{ $t("purchases.list.common.payment_terms") }}
                    </th>
                    <th v-if="allData.poDate">
                      {{ $t("purchases.list.common.po_date") }}
                    </th>
                    <th v-if="allData.purchaseDate">
                      {{ $t("purchases.list.common.purchase_date") }}
                    </th>
                    <th v-if="allData.note">{{ $t("common.note") }}</th>
                    <th>{{ $t("common.status") }}</th>
                    <th class="text-right">{{ $t("common.created_by") }}</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td v-if="allData.purchaseNo">
                      {{ allData.purchaseNo | withPrefix(purchasePrefix) }}
                    </td>
                    <td v-if="allData.poReference">
                      {{ allData.poReference }}
                    </td>
                    <td v-if="allData.paymentTerms">
                      {{ allData.paymentTerms }}
                    </td>
                    <td v-if="allData.poDate">
                      {{ allData.poDate | moment("Do MMM, YYYY") }}
                    </td>
                    <td v-if="allData.purchaseDate">
                      {{ allData.purchaseDate | moment("Do MMM, YYYY") }}
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
        <div class="row position-relative mt-4 mb-4">
          <div class="col-12">
            <strong class="mb-2 d-block">{{ $t("purchases.list.common.purchase_products") }}:</strong>
            <div class="table-custom table-responsive">
              <table class="table table-sm text-center">
                <thead>
                  <tr>
                    <th>{{ $t("common.s_no") }}</th>
                    <th>{{ $t("common.code") }}</th>
                    <th>{{ $t("common.product_name") }}</th>
                    <th>{{ $t("purchases.list.common.purchased_qty") }}</th>
                    <th v-if="allData.purchaseReturn">
                      {{ $t("purchases.list.common.returned_qty") }}
                    </th>
                    <th>{{ $t("common.unit_price") }}</th>
                    <th>{{ $t("common.unit_tax") }}</th>
                    <th>{{ $t("common.unit_cost") }}</th>
                    <th :class="!allData.purchaseReturn ? 'text-right' : ''">
                      {{ $t("common.total") }}
                    </th>
                    <th v-if="allData.purchaseReturn" :class="allData.purchaseReturn ? 'text-right' : ''">
                      {{ $t("common.total_return") }}
                    </th>
                  </tr>
                </thead>
                <tbody v-if="purchaseProducts">
                  <tr v-for="(data, i) in purchaseProducts" :key="i">
                    <td>{{ ++i }}</td>
                    <td>
                      {{ data.productCode | withPrefix(productPrefix) }}
                    </td>
                    <td>{{ data.productName }}</td>
                    <td>{{ data.quantity }} {{ data.productUnit }}</td>
                    <td v-if="allData.purchaseReturn">
                      {{ data.returnQty > 0 ? data.returnQty : 0 }}
                      {{ data.productUnit }}
                    </td>
                    <td>{{ data.purchasePrice | withCurrency }}</td>
                    <td>{{ data.taxAmount | withCurrency }}</td>
                    <td>{{ data.unitCost | withCurrency }}</td>
                    <td :class="!allData.purchaseReturn ? 'text-right' : ''">
                      {{ (data.unitCost * data.quantity) | withCurrency }}
                    </td>
                    <td v-if="allData.purchaseReturn" :class="allData.purchaseReturn ? 'text-right' : ''">
                      {{ (data.unitCost * data.returnQty) | withCurrency }}
                    </td>
                  </tr>
                  <tr>
                    <td class="text-right" :colspan="allData.purchaseReturn ? 8 : 7">
                      <strong>{{ $t("common.subtotal") }}</strong>
                    </td>
                    <td v-if="purchaseProducts" :class="!allData.purchaseReturn ? 'text-right' : ''">
                      <strong>{{ allData.subTotal | withCurrency }}</strong>
                    </td>
                    <td v-if="allData.purchaseReturn" :class="allData.purchaseReturn ? 'text-right' : ''">
                      <strong>{{
                        allData.purchaseReturn.totalReturn | withCurrency
                      }}</strong>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- /.row -->
        <div class="row mt-4" id="page-break">
          <div class="col-lg-12 col-xl-8">
            <strong class="mb-2 d-block">{{ $t("common.payment_history") }}:</strong>
            <div v-if="allData.payments && allData.payments.length > 0" class="table-custom table-responsive">
              <table class="table table-sm">
                <thead>
                  <tr>
                    <th>{{ $t("common.s_no") }}</th>
                    <th>{{ $t("common.payment_date") }}</th>
                    <th>{{ $t("common.paid_amount") }}</th>
                    <th>{{ $t("common.account") }}</th>
                    <th>{{ $t("common.cheque_no") }}</th>
                    <th>{{ $t("common.receipt_no") }}</th>
                    <th class="text-right">{{ $t("common.status") }}</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(data, i) in allData.payments" :key="i">
                    <td>{{ ++i }}</td>
                    <td>
                      <span v-if="data.date">{{ data.date }}</span>
                    </td>
                    <td>
                      <span v-if="data.amount">{{
                        data.amount | withCurrency
                      }}</span>
                    </td>
                    <td>
                      <span v-if="data.purchase_payment_transaction &&
                          data.purchase_payment_transaction.cashbook_account
                          ">{{ data.purchase_payment_transaction.cashbook_account.bank_name }}
                        ({{
                          data.purchase_payment_transaction.cashbook_account
                            .account_number
                        }})</span>
                    </td>
                    <td v-if="data.purchase_payment_transaction">
                      {{ data.purchase_payment_transaction.cheque_no }}
                    </td>
                    <td v-if="data.purchase_payment_transaction">
                      {{ data.purchase_payment_transaction.receipt_no }}
                    </td>
                    <td class="text-right">
                      <span v-if="data.status == 1" class="badge bg-success">{{
                        $t("common.active")
                      }}</span>
                      <span v-else class="badge bg-danger">{{
                        $t("common.in_active")
                      }}</span>
                    </td>
                  </tr>
                  <tr>
                    <td class="text-right" colspan="2">
                      <strong>{{ $t("common.total_paid") }}</strong>
                    </td>
                    <td colspan="5">
                      <strong>{{ allData.totalPaid | withCurrency }}</strong>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="no-print callout callout-danger mt-4 w-100" v-else>
              <h5>{{ $t("common.empty_payment") }}</h5>
              <p>{{ $t("common.empty_payment_msg") }}</p>
            </div>
          </div>
          <div class="col-lg-12 col-xl-4 text-lg-right mt-4 pt-2">
            <div class="table-responsive table-custom table-border-y-0" v-if="allData.supplier">
              <table class="table">
                <tbody>
                  <tr class="bg-sub-light text-bold">
                    <th>{{ $t("common.subtotal") }}:</th>
                    <td>{{ allData.subTotal | withCurrency }}</td>
                  </tr>
                  <tr v-if="allData.purchaseReturn">
                    <th>{{ $t("common.return_cost") }}:</th>
                    <td>
                      <span class="minus-sign">-</span>
                      {{ allData.purchaseReturn.totalReturn | withCurrency }}
                    </td>
                  </tr>
                  <tr>
                    <th>{{ $t("common.discount") }}:</th>
                    <td>
                      <span class="minus-sign">-</span>
                      {{ allData.totalDiscount | withCurrency }}
                    </td>
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
                      {{ $t("common.tax") }}
                      <span>({{ allData.taxType.rate }}%):</span>
                    </th>
                    <td>
                      <span class="plus-sign">+</span>
                      {{ allData.tax | withCurrency }}
                    </td>
                  </tr>
                  <tr class="bg-indigo-light">
                    <th>{{ $t("common.total") }}:</th>
                    <td>
                      <span class="equal-sign">=</span>
                      {{ allData.purchaseTotal | withCurrency }}
                    </td>
                  </tr>
                  <tr>
                    <th>{{ $t("common.total_paid") }}:</th>
                    <td>
                      <span class="minus-sign">-</span>
                      {{ allData.totalPaid | withCurrency }}
                    </td>
                  </tr>
                  <tr class="bg-red-light">
                    <th>{{ $t("common.due") }}:</th>
                    <td>{{ allData.due | withCurrency }}</td>
                  </tr>
                  <tr class="bg-green-light" v-if="allData.accountReceivable">
                    <th>{{ $t("common.account_receivable") }}:</th>
                    <td>{{ allData.accountReceivable | withCurrency }}</td>
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
    return { title: this.$t("purchases.list.view.page_title") };
  },
  data: () => ({
    breadcrumbsCurrent: "purchases.list.view.breadcrumbs_current",
    breadcrumbs: [
      {
        name: "purchases.list.view.breadcrumbs_first",
        url: "home",
      },
      {
        name: "purchases.list.view.breadcrumbs_second",
        url: "purchases.index",
      },
      {
        name: "purchases.list.view.breadcrumbs_active",
        url: "",
      },
    ],
    allData: "",
    purchaseSubTotal: 0,
    purchaseReturn: 0,
    purchaseProducts: [],
    productPrefix: "",
    purchasePrefix: "",
    loading: false,
    form: new Form({
      isSendEmail: false,
      isSendSMS: false,
    }),
    isDemoMode: window.config.isDemoMode,
  }),
  computed: mapGetters({
    appInfo: "operations/appInfo",
  }),
  created() {
    this.getPurchase();
    this.productPrefix = this.appInfo.productPrefix;
    this.purchasePrefix = this.appInfo.purchasePrefix;
    this.supplierPrefix = this.appInfo.supplierPrefix;
  },
  methods: {
    // get the purchase
    async getPurchase() {
      this.loading = true;
      const { data } = await axios.get(
        window.location.origin + "/api/purchases/" + this.$route.params.slug
      );
      this.allData = data.data;
      this.purchaseProducts = this.allData.products;
      this.purchaseProducts.sort(this.sortProducts);
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
        filename: "Purchase Invoice-" + this.$route.params.slug + ".pdf",
        image: { type: "jpeg", quality: 0.98 },
        pagebreak: { mode: "avoid-all", before: "#page-break" },
        html2canvas: { scale: 2 },
        jsPDF: { unit: "mm", format: "a4", orientation: "landscape" },
      };
      // Generate PDF from HTML content
      html2pdf().from(element).set(options).save();
    },

    // notify
    async notify() {
      if (!this.isDemoMode) {
        this.loading = true;
        await this.form
          .post(
            window.location.origin +
            "/api/purchase/notify/" +
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
