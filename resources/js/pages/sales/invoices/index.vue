<template>
  <div class="mb-50">
    <!-- breadcrumbs Start -->
    <breadcrumbs :items="breadcrumbs" :current="breadcrumbsCurrent" />
    <!-- breadcrumbs end -->
    <div class="row">
      <div class="col-lg-12">
        <div class="card custom-card w-100">
          <div class="card-header setings-header">
            <div class="col-xl-4 col-4">
              <h3 class="card-title">
                {{ $t("sales.invoices.index.page_title") }}
              </h3>
            </div>
            <div class="col-xl-8 col-8 float-right text-right">
              <div class="btn-group c-w-100">
                <a @click="refreshTable()" href="#" v-tooltip="'Refresh'" class="btn btn-success">
                  <i class="fas fa-sync"></i>
                </a>
                <a href="/invoices/pdf" v-tooltip="$t('common.export_table')" class="btn btn-secondary">
                  <i class="fas fa-file-export"></i>
                </a>
                <a @click="print" v-tooltip="$t('common.print_table')" class="btn btn-info">
                  <i class="fas fa-print"></i>
                </a>
                <router-link v-if="$can('invoice-create')" :to="{ name: 'invoices.create' }" class="btn btn-primary">
                  {{ $t("common.create") }}
                  <i class="fas fa-plus-circle d-none d-sm-inline-block" />
                </router-link>
              </div>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body position-relative">
            <div class="row">
              <div class="col-6 col-xl-4 mb-2">
                <search v-model="query" @reset-pagination="resetPagination()" @reload="reload" />
              </div>
              <div class="col-6 col-xl-8 mb-2 text-right">
                <date-range-picker ref="picker" opens="left" :locale-data="locale" :minDate="minDate" :maxDate="maxDate"
                  :singleDatePicker="false" :showWeekNumbers="false" :showDropdowns="true" :autoApply="true"
                  v-model="dateRange" @update="updateValues" :linkedCalendars="true" class="c-w-100">
                  <template v-slot:input="picker" style="min-width: 350px">
                    {{ picker.startDate | startDate }} -
                    {{ picker.endDate | endDate }}
                  </template>
                </date-range-picker>
              </div>
            </div>
            <table-loading v-show="loading" />
            <div class="table-responsive table-custom mt-3" id="printMe">
              <table class="table">
                <thead>
                  <tr>
                    <th>{{ $t("common.s_no") }}</th>
                    <th>{{ $t("common.invoice_no") }}</th>
                    <th>{{ $t("common.invoice_date") }}</th>
                    <th>{{ $t("common.client") }}</th>
                    <th>{{ $t("common.subtotal") }}</th>
                    <th>{{ $t("common.transport") }}</th>
                    <th>{{ $t("common.discount") }}</th>
                    <th>{{ $t("common.tax") }}</th>
                    <th>{{ $t("common.net_total") }}</th>
                    <th>{{ $t("common.total_paid") }}</th>
                    <th>{{ $t("common.total_due") }}</th>
                    <th>{{ $t("common.status") }}</th>
                    <th v-if="$can('invoice-view') ||
                      $can('invoice-edit') ||
                      $can('invoice-delete')
                      " class="text-right no-print">
                      {{ $t("common.action") }}
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-show="items.length" v-for="(data, i) in items" :key="i">
                    <td>
                      <span v-if="pagination && pagination.current_page > 1">
                        {{
                          pagination.per_page * (pagination.current_page - 1) +
                          (i + 1)
                        }}
                      </span>
                      <span v-else>{{ i + 1 }}</span>
                    </td>
                    <td>
                      <router-link v-if="$can('invoice-view')" :to="{
                        name: 'invoices.show',
                        params: { slug: data.slug },
                      }">
                        {{ data.invoiceNo | withPrefix(prefix) }}
                      </router-link>
                      <span v-else>{{
                        data.invoiceNo | withPrefix(prefix)
                      }}</span>
                    </td>
                    <td>
                      <span v-if="data.invoiceDate">{{
                        data.invoiceDate | moment("Do MMM, YYYY")
                      }}</span>
                    </td>
                    <td>{{ data.client }}</td>
                    <td>{{ data.subTotal | withCurrency }}</td>
                    <td>{{ data.transport | withCurrency }}</td>
                    <td>
                      {{ data.discount | withCurrency }}
                      <span v-if="data.discountType == 1">({{ data.discountPercentage }}%)</span>
                    </td>
                    <td>{{ data.tax | withCurrency }}</td>
                    <td>{{ data.invoiceTotal | withCurrency }}</td>
                    <td>{{ data.totalPaid | withCurrency }}</td>
                    <td>{{ data.due | withCurrency }}</td>
                    <td>
                      <span v-if="data.status === 1" class="badge bg-success">{{
                        $t("common.active")
                      }}</span>
                      <span v-else class="badge bg-danger">{{
                        $t("common.in_active")
                      }}</span>
                    </td>
                    <td v-if="$can('invoice-view') ||
                        $can('invoice-edit') ||
                        $can('invoice-delete')
                        " class="text-right no-print">
                      <div class="btn-group">
                        <a v-if="$can('invoice-view') && data.due > 0" v-tooltip="$t('common.add_payment')"
                          class="btn btn-secondary btn-sm" @click="handleModal(data)">
                          <i class="fas fa-money-check-alt" />
                        </a>
                        <router-link v-if="$can('invoice-view')" v-tooltip="$t('common.view')" :to="{
                          name: 'invoices.show',
                          params: { slug: data.slug },
                        }" class="btn btn-primary btn-sm">
                          <i class="fas fa-eye" />
                        </router-link>
                        <router-link v-if="$can('invoice-edit')" v-tooltip="$t('common.edit')" :to="{
                          name: 'invoices.edit',
                          params: { slug: data.slug },
                        }" class="btn btn-info btn-sm">
                          <i class="fas fa-edit" />
                        </router-link>
                        <a v-if="$can('invoice-delete')" v-tooltip="$t('common.delete')" href="#"
                          class="btn btn-danger btn-sm" @click="deleteData(data.slug)">
                          <i class="fas fa-trash" />
                        </a>
                      </div>
                    </td>
                  </tr>
                  <tr v-show="!loading && !items.length">
                    <td colspan="13">
                      <EmptyTable />
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="card-footer">
            <div class="dtable-footer">
              <div class="form-group row display-per-page">
                <label>{{ $t("per_page") }} </label>
                <div>
                  <select @change="updatePerPager" v-model="perPage" class="form-control form-control-sm ml-1">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                  </select>
                </div>
              </div>
              <!-- pagination-start -->
              <pagination v-if="pagination && pagination.last_page > 1" :pagination="pagination" :offset="5"
                class="justify-flex-end" @paginate="paginate" />
              <!-- pagination-end -->
            </div>
          </div>
        </div>
      </div>
    </div>
    <Modal v-if="showModal" @close="showModal = false">
      <h5 slot="header">
        {{ $t("payments.clients.invoice.create.form_title") }} :
        {{ form.selectedInvoice.invoiceNo | withPrefix(prefix) }}
      </h5>
      <div slot="body" class="row">
        <form role="form" @submit.prevent="savePayment" @keydown="form.onKeydown($event)" class="w-100">
          <div class="row">
            <div class="form-group col-md-6">
              <label for="clientInvoiceTotal">{{
                $t("common.invoice_total")
              }}</label>
              <input type="text" class="form-control" readonly v-model="form.selectedInvoice.invoiceTotal" />
            </div>
            <div class="form-group col-md-6">
              <label for="clientInvoiceDue">{{
                $t("common.invoice_due")
              }}</label>
              <input type="text" class="form-control" readonly v-model="form.selectedInvoice.due" />
            </div>
          </div>
          <div class="row">
            <div class="form-group col-md-4">
              <label for="paidAmount">{{ $t("common.paid_amount") }}</label>
              <input type="number" step="any" class="form-control" :placeholder="$t('common.paid_amount_placeholder')"
                required min="1" v-model="form.paidAmount" :max="form.selectedInvoice.due" />
            </div>
            <div class="form-group col-md-8">
              <label for="account">{{ $t("common.account") }}
                <span class="required">*</span></label>
              <v-select v-model="form.account" :options="accounts" label="label"
                :class="{ 'is-invalid': form.errors.has('account') }" name="account"
                :placeholder="$t('common.account_placeholder')" />
              <has-error :form="form" field="account" />
            </div>
          </div>

          <div class="row">
            <div class="form-group col-md-6">
              <label for="chequeNo">{{ $t("common.cheque_no") }}</label>
              <input type="text" v-model="form.chequeNo" class="form-control"
                :class="{ 'is-invalid': form.errors.has('chequeNo') }" id="chequeNo"
                :placeholder="$t('common.cheque_placeholder')" />
              <has-error :form="form" field="chequeNo" />
            </div>
            <div class="form-group col-md-6">
              <label for="receiptNo">{{ $t("common.receipt_no") }}</label>
              <input type="text" v-model="form.receiptNo" class="form-control"
                :class="{ 'is-invalid': form.errors.has('receiptNo') }" id="receiptNo"
                :placeholder="$t('common.receipt_no_placeholder')" />
              <has-error :form="form" field="receiptNo" />
            </div>
            <div class="form-group col-md-6">
              <label for="paymentDate">{{ $t("common.payment_date") }}</label>
              <input id="paymentDate" v-model="form.paymentDate" type="date" class="form-control"
                :class="{ 'is-invalid': form.errors.has('paymentDate') }" name="paymentDate" />
              <has-error :form="form" field="paymentDate" />
            </div>
            <div class="form-group col-md-6">
              <label for="status">{{ $t("common.status") }}</label>
              <select id="status" v-model="form.status" class="form-control"
                :class="{ 'is-invalid': form.errors.has('status') }">
                <option value="1">{{ $t("common.active") }}</option>
                <option value="0">{{ $t("common.in_active") }}</option>
              </select>
              <has-error :form="form" field="status" />
            </div>
          </div>
          <div class="form-group">
            <label for="note">{{ $t("common.note") }}</label>
            <textarea id="note" v-model="form.note" class="form-control"
              :class="{ 'is-invalid': form.errors.has('note') }" :placeholder="$t('common.note_placeholder')" />
            <has-error :form="form" field="note" />
          </div>
          <div class="form-group col-12 d-flex flex-wrap">
            <div class="pr-5">
              <toggle-button v-model="form.isSendEmail" :disabled="isDemoMode" />
              {{ $t("Send Email Notification") }}
            </div>
          </div>
          <div class="form-group col-12 d-flex flex-wrap">
            <div class="pr-5">
              <toggle-button v-model="form.isSendSMS" :disabled="isDemoMode" />
              {{ $t("Send SMS Notification") }}
            </div>
          </div>
          <v-button :loading="form.busy" class="btn btn-primary">
            <i class="fas fa-save" /> {{ $t("common.save") }}
          </v-button>
        </form>
      </div>
    </Modal>
  </div>
</template>

<script>
import Form from "vform";
import moment from "moment";
import i18n from "~/plugins/i18n";
import { mapGetters } from "vuex";
import DateRangePicker from "vue2-daterange-picker";
import { ToggleButton } from "vue-js-toggle-button";
import axios from "axios";

export default {
  middleware: ["auth", "check-permissions"],
  metaInfo() {
    return { title: this.$t("sales.invoices.index.page_title") };
  },
  components: {
    DateRangePicker,
    ToggleButton,
  },
  data: () => ({
    breadcrumbsCurrent: "sales.invoices.index.breadcrumbs_current",
    breadcrumbs: [
      {
        name: "sales.invoices.index.breadcrumbs_first",
        url: "home",
      },
      {
        name: "sales.invoices.index.breadcrumbs_second",
        url: "",
      },
      {
        name: "sales.invoices.index.breadcrumbs_active",
        url: "",
      },
    ],
    showModal: false,
    perPage: 10,
    query: "",
    prefix: "",
    minDate: moment(new Date("01-01-2021")).format("YYYY-MM-DD"),
    maxDate: moment().add(1, "days").format("YYYY-MM-DD"),
    dateRange: {
      startDate: "",
      endDate: "",
    },
    locale: {
      direction: "ltr",
      format: "YYYY-MM-DD",
      separator: " - ",
      applyLabel: "Apply",
      cancelLabel: "Cancel",
      weekLabel: "W",
      customRangeLabel: "Custom Range",
      daysOfWeek: moment.weekdaysMin(),
      monthNames: moment.monthsShort(),
      firstDay: 1,
    },
    accounts: [],
    form: new Form({
      selectedInvoice: "",
      paidAmount: 1,
      invoice_id: "",
      paymentDate: new Date().toISOString().slice(0, 10),
      date: new Date().toISOString().slice(0, 10),
      account: "",
      chequeNo: "",
      receiptNo: "",
      note: "",
      netTotal: 0,
      status: 1,
      isSendEmail: false,
      isSendSMS: false,
    }),
    isDemoMode: window.config.isDemoMode,
  }),
  filters: {
    startDate(val) {
      return val ? moment(val).format("YYYY-MM-DD") : i18n.t("common.from");
    },
    endDate(val) {
      return val ? moment(val).format("YYYY-MM-DD") : i18n.t("common.to");
    },
  },
  // Map Getters
  computed: {
    ...mapGetters("operations", ["items", "loading", "pagination", "appInfo"]),
  },
  watch: {
    // watch search data
    query: function (newQ) {
      if (newQ === "") {
        if (this.dateRange.startDate && this.dateRange.endDate) {
          this.searchData();
        } else {
          this.getData();
        }
      } else {
        this.searchData();
      }
    },
  },
  created() {
    this.getData();
    this.getAccounts();
    this.prefix = this.appInfo.invoicePrefix;
  },
  methods: {
    // get accounts
    async getAccounts() {
      const { data } = await axios.get(
        window.location.origin + "/api/all-accounts"
      );
      this.accounts = data.data;

      // assign default account
      if (this.accounts && this.accounts.length > 0) {
        let defaultAccountSlug = this.appInfo.defaultAccountSlug;
        this.form.account = this.accounts.find(
          (account) => account.slug == defaultAccountSlug
        );
      }
    },

    // filter data for selected date range
    async updateValues() {
      this.dateRange.startDate = moment(this.dateRange.startDate).format(
        "YYYY-MM-DD"
      );
      this.dateRange.endDate = moment(this.dateRange.endDate).format(
        "YYYY-MM-DD"
      );
      this.searchData();
    },

    // refresh table
    refreshTable() {
      this.query = "";
      this.dateRange.startDate = null;
      this.dateRange.endDate = null;
      this.query === "" ? this.getData() : this.searchData();
      setTimeout(
        function () {
          this.dateRange.startDate = "";
          this.dateRange.endDate = "";
        }.bind(this),
        500
      );
    },
    // update per page count
    updatePerPager() {
      this.pagination.current_page = 1;
      this.query === "" ? this.getData() : this.searchData();
    },
    // get data
    async getData() {
      this.$store.state.operations.loading = true;
      let currentPage = this.pagination ? this.pagination.current_page : 1;
      await this.$store.dispatch("operations/fetchData", {
        path: "/api/invoices?page=",
        currentPage: currentPage + "&perPage=" + this.perPage,
      });
    },

    // Pagination
    async paginate() {
      this.query === "" ? this.getData() : this.searchData();
    },

    // Reset pagination
    async resetPagination() {
      this.pagination.current_page = 1;
    },

    // search data
    async searchData() {
      this.$store.state.operations.loading = true;
      let currentPage = this.pagination ? this.pagination.current_page : 1;
      await this.$store.dispatch("operations/searchData", {
        path: "/api/invoices/search",
        term: this.query,
        currentPage: currentPage + "&perPage=" + this.perPage,
        startDate: this.dateRange.startDate,
        endDate: this.dateRange.endDate,
      });
    },

    // Reload after search
    async reload() {
      this.query = "";
      await this.searchData();
    },

    // print table
    async print() {
      await this.$htmlToPaper("printMe");
    },

    handleModal(item) {
      this.form.selectedInvoice = item;
      this.form.invoice_id = item.id;
      this.form.netTotal = item.due;
      this.showModal = true;
    },

    // save  payment
    async savePayment() {
      await this.form
        .post(window.location.origin + "/api/invoices-pay")
        .then(({ data }) => {
          toast.fire({
            type: "success",
            title: this.$t("sales.invoices.create.success_msg"),
          });
          this.$router.push({
            name: "invoices.show",
            params: { slug: this.form.selectedInvoice.slug },
          });
        })
        .catch(() => {
          toast.fire({ type: "error", title: this.$t("common.error_msg") });
        });
    },

    // delete data
    async deleteData(slug) {
      Swal.fire({
        title: this.$t("common.delete_title"),
        text: this.$t("sales.invoices.index.delete_warning"),
        type: "warning",
        showCancelButton: true,
        confirmButtonText: this.$t("common.delete_confirm_text"),
      }).then((result) => {
        // Send request to the server
        if (result.value) {
          this.$store
            .dispatch("operations/deleteData", {
              path: "/api/invoices/",
              slug: slug,
            })
            .then((response) => {
              if (response === true) {
                Swal.fire(
                  this.$t("common.deleted"),
                  this.$t("common.delete_success"),
                  "success"
                );
              } else {
                Swal.fire(
                  this.$t("common.failed"),
                  this.$t("sales.invoices.index.delete_failed"),
                  "warning"
                );
              }
            });
        }
      });
    },
  },
};
</script>
